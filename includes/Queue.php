<?php
/**
 * Queue Class
 * Handles job dispatching to the background processing queue.
 */
class Queue {

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

        $jobTypeEsc   = mysqli_real_escape_string($conn, $jobType);
        $payloadJson  = mysqli_real_escape_string($conn, json_encode($payload));

        $sql = "INSERT INTO `jobs` (`job_type`, `payload`, `status`, `attempts`, `created_at`) 
                VALUES ('$jobTypeEsc', '$payloadJson', 'pending', 0, NOW())";

        if (mysqli_query($conn, $sql)) {
            $jobId = mysqli_insert_id($conn);
            
            // Trigger background execution without waiting
            self::triggerAsyncWorker();

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
        $uri = parse_url($reqUri, PHP_URL_PATH);
        $pathParts = explode('/', trim($uri, '/'));
        
        // If inside /dashboard, go up one level
        if (!empty($pathParts) && $pathParts[0] === 'dashboard') {
            array_shift($pathParts);
        }
        $workerUrl = $protocol . $host . '/worker.php?runner=async';

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $workerUrl);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            @curl_exec($ch);
            curl_close($ch);
        }
    }
}
?>
