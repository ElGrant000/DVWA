<?php

if (isset($_POST['Submit'])) {
    // Get input
    $target = trim($_REQUEST['ip']);

    // Validate IP address format (whitelisting)
    if (filter_var($target, FILTER_VALIDATE_IP)) {

        // Escape argument to prevent command injection
        $safe_target = escapeshellarg($target);

        // Determine OS and execute the ping command safely
        if (stristr(php_uname('s'), 'Windows NT')) {
            // Windows
            $cmd = shell_exec('ping ' . $safe_target);
        } else {
            // *nix
            $cmd = shell_exec('ping -c 4 ' . $safe_target);
        }

        // Feedback for the end user
        $html .= "<pre>{$cmd}</pre>";
    } else {
        $html .= "<pre>Invalid IP address.</pre>";
    }
}
?>
