<?php
/**
 * Background Job Worker
 * Processes queued jobs such as emails and background tasks.
 * Compatible with PHP 5.3+ through PHP 8+
 */

@set_time_limit(0);
@ignore_user_abort(true);

require_once __DIR__ . "/db_conn.php";
require_once __DIR__ . "/includes/SimpleSMTP.php";

$sql = "SELECT * FROM system_setting LIMIT 1";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));

$setting_row = mysqli_fetch_assoc($result);
$phone = $setting_row['phone'];
$business_name = $setting_row['business_name'];
$address = $setting_row['address'];
$email = $setting_row['email'];

// Check if business_name is NULL or empty
if (empty($setting_row['business_name'])) {
    header("Location: ../management/");
    exit();
}

/**
 * Process a batch of pending jobs
 *
 * @param mysqli $conn
 * @param int $limit
 * @return int Number of processed jobs
 */
function processJobsBatch($conn, $limit) {
    if (!$conn) return 0;

    $limit = intval($limit);
    $sql = "SELECT * FROM `jobs` WHERE `status` = 'pending' AND `attempts` < 3 ORDER BY `id` ASC LIMIT $limit";
    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) === 0) {
        return 0;
    }

    $processedCount = 0;

    while ($job = mysqli_fetch_assoc($result)) {
        $jobId   = (int)$job['id'];
        $jobType = $job['job_type'];
        $payload = json_decode($job['payload'], true);

        // Mark as processing
        mysqli_query($conn, "UPDATE `jobs` SET `status` = 'processing', `attempts` = `attempts` + 1 WHERE `id` = $jobId");

        $success = false;
        $errorMsg = null;

        try {
            if ($jobType === 'send_order_status_email') {
                $success = handleSendOrderStatusEmail($payload);
            } else {
                $errorMsg = "Unknown job type: $jobType";
            }
        } catch (Exception $e) {
            $errorMsg = "Email Error: " . $e->getMessage();
        }

        if ($success) {
            $sqlDone = "UPDATE `jobs` SET `status` = 'completed', `error_log` = NULL WHERE `id` = $jobId";
            mysqli_query($conn, $sqlDone);
            $processedCount++;
        } else {
            $msgToUse = !empty($errorMsg) ? $errorMsg : 'Job execution failed';
            $errEsc = mysqli_real_escape_string($conn, $msgToUse);
            $sqlFail = "UPDATE `jobs` SET `status` = 'failed', `error_log` = '$errEsc' WHERE `id` = $jobId";
            mysqli_query($conn, $sqlFail);
        }
    }

    return $processedCount;
}

/**
 * Handler for sending order status emails using SimpleSMTP
 */
function handleSendOrderStatusEmail($payload) {
    global $business_name;
    $customerEmail  = isset($payload['customer_email']) ? $payload['customer_email'] : '';
    $customerName   = isset($payload['customer_name']) ? $payload['customer_name'] : 'Valued Customer';
    $orderIdDisp    = isset($payload['order_id_disp']) ? $payload['order_id_disp'] : '';
    $orderStatus    = isset($payload['order_status']) ? $payload['order_status'] : '';
    $courierName    = isset($payload['courier_name']) ? $payload['courier_name'] : '';
    $trackingNumber = isset($payload['tracking_number']) ? $payload['tracking_number'] : '';

    if (empty($customerEmail)) {
        throw new Exception("Recipient email is empty.");
    }

    $subject = "Order Status Update - Order #" . $orderIdDisp . " - $business_name";

    $courierHtml  = !empty($courierName) ? "<p style='margin: 5px 0;'><strong>Courier / Carrier:</strong> " . htmlspecialchars($courierName) . "</p>" : "";
    $trackingHtml = !empty($trackingNumber) ? "<p style='margin: 5px 0;'><strong>Tracking Code:</strong> " . htmlspecialchars($trackingNumber) . "</p>" : "";

    $htmlBody = "
    <div style='font-family: Arial, sans-serif; background-color: #f5f6fa; padding: 20px;'>
        <div style='max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
            <div style='background-color: #4B0082; padding: 20px; text-align: center; color: #ffffff;'>
                <h2 style='margin: 0;'>$business_name</h2>
                <p style='margin: 5px 0 0 0; font-size: 14px;'>Order Status Update</p>
            </div>
            <div style='padding: 30px; color: #333333;'>
                <p>Dear <strong>" . htmlspecialchars($customerName) . "</strong>,</p>
                <p>Your order <strong>#" . htmlspecialchars($orderIdDisp) . "</strong> has been updated:</p>
                <div style='background-color: #f0f3ff; border-left: 4px solid #4B0082; padding: 15px; margin: 20px 0; border-radius: 4px;'>
                    <p style='margin: 0; font-size: 16px;'><strong>Status:</strong> " . htmlspecialchars($orderStatus) . "</p>
                </div>
                {$courierHtml}
                {$trackingHtml}
                <p style='margin-top: 20px;'>Thank you for shopping with $business_name!</p>
            </div>
            <div style='background-color: #f9f9f9; padding: 15px; text-align: center; font-size: 12px; color: #777777;'>
                <p style='margin: 0;'>This email was automatically sent from $business_name.</p>
            </div>
        </div>
    </div>";

    return SimpleSMTP::sendEmail(
        'mail.pocketvest.com.ng',
        465,
        'noreply@pocketvest.com.ng',
        'ecommerce@2026',
        'noreply@pocketvest.com.ng',
        $business_name,
        $customerEmail,
        $customerName,
        $subject,
        $htmlBody
    );
}

// Execution block
$isCli = (php_sapi_name() === 'cli');

if ($isCli) {
    echo "Starting Queue Worker...\n";
    $count = processJobsBatch($conn, 10);
    echo "Processed $count jobs.\n";
} else {
    // Run batch silently for web triggers
    $count = processJobsBatch($conn, 5);
    if (isset($_GET['debug'])) {
        echo "Worker executed. Processed $count jobs.";
    }
}
?>
