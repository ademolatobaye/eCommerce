<?php
/**
 * SimpleSMTP Class
 * Lightweight, zero-dependency SMTP email sender compatible with PHP 5.3 through PHP 8+
 */
class SimpleSMTP {
    public static function sendEmail($host, $port, $username, $password, $fromEmail, $fromName, $toEmail, $toName, $subject, $htmlBody) {
        $timeout = 15;
        $socketHost = ($port == 465) ? 'ssl://' . $host : $host;
        
        $socket = @fsockopen($socketHost, $port, $errno, $errstr, $timeout);
        if (!$socket) {
            throw new Exception("Could not connect to SMTP host $host:$port ($errstr)");
        }

        $read = function() use ($socket) {
            $response = '';
            while ($str = fgets($socket, 512)) {
                $response .= $str;
                if (substr($str, 3, 1) == ' ') break;
            }
            return $response;
        };

        $write = function($cmd) use ($socket) {
            fputs($socket, $cmd . "\r\n");
        };

        $response = call_user_func($read);
        if (substr($response, 0, 3) != '220') {
            fclose($socket);
            throw new Exception("SMTP Server Connection Error: " . trim($response));
        }

        call_user_func($write, "EHLO " . gethostname());
        call_user_func($read);

        call_user_func($write, "AUTH LOGIN");
        $response = call_user_func($read);
        if (substr($response, 0, 3) != '334') {
            fclose($socket);
            throw new Exception("SMTP AUTH LOGIN error: " . trim($response));
        }

        call_user_func($write, base64_encode($username));
        call_user_func($read);

        call_user_func($write, base64_encode($password));
        $response = call_user_func($read);
        if (substr($response, 0, 3) != '235') {
            fclose($socket);
            throw new Exception("SMTP Auth Failed: " . trim($response));
        }

        call_user_func($write, "MAIL FROM: <$fromEmail>");
        call_user_func($read);

        call_user_func($write, "RCPT TO: <$toEmail>");
        call_user_func($read);

        call_user_func($write, "DATA");
        call_user_func($read);

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <$fromEmail>\r\n";
        $headers .= "To: =?UTF-8?B?" . base64_encode($toName) . "?= <$toEmail>\r\n";
        $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
        $headers .= "Date: " . date("r") . "\r\n";

        $message = $headers . "\r\n" . $htmlBody . "\r\n.";
        call_user_func($write, $message);
        $response = call_user_func($read);

        call_user_func($write, "QUIT");
        fclose($socket);

        return (substr($response, 0, 3) == '250');
    }
}
?>
