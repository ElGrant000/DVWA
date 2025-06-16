<?php

if (isset($_POST['Submit'])) {
    // Get input
    $target = trim($_REQUEST['ip']);

    // Validate input: only allow valid IPv4 or IPv6 addresses
    if (filter_var($target, FILTER_VALIDATE_IP)) {

        // Escape the IP address argument to safely pass to shell
        $safe_target = escapeshellarg($target);

        // Determine OS and execute the ping command
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
