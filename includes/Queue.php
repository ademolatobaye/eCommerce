<?php
/**
 * Queue Class
 * Handles job dispatching to the background processing queue.
 */
class Queue {

    /**
     * Ensure jobs table exists
     */
    private static function ensureJobsTable($conn) {
        $createTableSql = "CREATE TABLE IF NOT EXISTS `jobs` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `job_type` varchar(100) NOT NULL,
          `payload` text NOT NULL,
          `status` varchar(50) NOT NULL DEFAULT 'pending',
          `attempts` int(11) NOT NULL DEFAULT 0,
          `error_log` text NULL,
          `created_at` datetime NULL,
          `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `status_idx` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        @mysqli_query($conn, $createTableSql);
    }

    /**
     * Dispatch a background job
     *
     * @param string $jobType E.g. 'send_order_status_email'
     * @param array $payload Data needed for the job
     * @param mysqli $conn Database connection handle
     * @return bool|int Job ID on success, false on failure
     */
    public static function dispatch($jobType, array $payload, $conn) {
        if (!$conn) {
            return false;
        }

        self::ensureJobsTable($conn);

        $jobTypeEsc   = mysqli_real_escape_string($conn, $jobType);
        $payloadJson  = mysqli_real_escape_string($conn, json_encode($payload));

        $sql = "INSERT INTO `jobs` (`job_type`, `payload`, `status`, `attempts`, `created_at`) 
                VALUES ('$jobTypeEsc', '$payloadJson', 'pending', 0, NOW())";

        if (mysqli_query($conn, $sql)) {
            $jobId = mysqli_insert_id($conn);
            
            // Process worker inline immediately for instant delivery
            $workerFile = __DIR__ . '/../worker.php';
            if (file_exists($workerFile)) {
                require_once $workerFile;
                if (function_exists('processJobsBatch')) {
                    processJobsBatch($conn, 5);
                }
            } else {
                self::triggerAsyncWorker();
            }

            return $jobId;
        }

        return false;
    }

    /**
     * Asynchronously trigger worker.php via HTTP cURL/socket without blocking the main request thread.
     */
    public static function triggerAsyncWorker() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        
        $reqUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $uriPath = parse_url($reqUri, PHP_URL_PATH);
        
        // Remove /dashboard or /vendor from path to get root app path
        $dir = dirname($uriPath);
        $dir = str_replace('\\', '/', $dir);
        if ($dir === '/' || $dir === '.') {
            $dir = '';
        } else {
            $dir = rtrim(str_replace(array('/dashboard', '/vendor'), '', $dir), '/');
        }
        
        $workerUrl = $protocol . $host . $dir . '/worker.php?runner=async';

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $workerUrl);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            @curl_exec($ch);
            curl_close($ch);
        }
    }
}
?>
