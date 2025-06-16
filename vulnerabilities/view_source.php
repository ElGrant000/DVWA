<?php

define('DVWA_WEB_PAGE_TO_ROOT', '../');
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup(array('authenticated'));

$page = dvwaPageNewGrab();
$page['title'] = 'Help' . $page['title_separator'] . $page['title'];

// Whitelisted valid IDs and locales
$allowed_ids = ['fi', 'brute', 'sqli', 'xss'];       // Define safe module IDs
$allowed_locales = ['en', 'fr', 'es'];               // Define supported locales

if (isset($_GET['id'], $_GET['security'], $_GET['locale'])) {
    $id = $_GET['id'];
    $security = $_GET['security'];
    $locale = $_GET['locale'];

    // Validate user input against allowed values
    if (in_array($id, $allowed_ids) && in_array($locale, $allowed_locales)) {

        // Safe file path construction
        $filename = $locale === 'en'
            ? DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/help/help.php"
            : DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/help/help.{$locale}.php";

        // Check if the file exists to avoid errors or exploits
        if (is_file($filename)) {
            ob_start();
            include $filename;  // Replaced eval() with include for safety
            $help = ob_get_clean();
        } else {
            $help = "<p>Help file not found.</p>";
        }
    } else {
        $help = "<p>Invalid parameters provided.</p>";
    }
} else {
    $help = "<p>Not Found</p>";
}

$page['body'] .= "
<script src='/vulnerabilities/help.js'></script>
<link rel='stylesheet' type='text/css' href='/vulnerabilities/help.css' />

<div class=\"body_padded\">
    {$help}
</div>\n";

dvwaHelpHtmlEcho($page);

?>
