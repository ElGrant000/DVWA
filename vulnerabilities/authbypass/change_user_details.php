<?php
define('DVWA_WEB_PAGE_TO_ROOT', '../../');
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaDatabaseConnect();

// Access control for 'impossible' level
if (dvwaSecurityLevelGet() == "impossible" && dvwaCurrentUser() != "admin") {
    print json_encode(["result" => "fail", "error" => "Access denied"]);
    exit;
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] != "POST") {
    echo json_encode(["result" => "fail", "error" => "Only POST requests are accepted"]);
    exit;
}

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    // Validate JSON
    if (is_null($data) || !isset($data->id, $data->first_name, $data->surname)) {
        echo json_encode(["result" => "fail", "error" => 'Invalid format, expecting {"id":..., "first_name":"...", "surname":"..."}']);
        exit;
    }

    // Sanitize and validate input
    $id = filter_var($data->id, FILTER_VALIDATE_INT);
    $first_name = trim($data->first_name);
    $surname = trim($data->surname);

    if ($id === false || $first_name === '' || $surname === '') {
        echo json_encode(["result" => "fail", "error" => "Invalid or missing fields"]);
        exit;
    }

    // Use prepared statement to avoid SQL injection
    $stmt = mysqli_prepare(
        $GLOBALS["___mysqli_ston"],
        "UPDATE users SET first_name = ?, last_name = ? WHERE user_id = ?"
    );
    mysqli_stmt_bind_param($stmt, "ssi", $first_name, $surname, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo json_encode(["result" => "ok"]);
    exit;

} catch (Exception $e) {
    echo json_encode(["result" => "fail", "error" => "Invalid input"]);
    exit;
}
?>
