<?php

if (isset($_GET['Login'])) {
    // Get input
    $user = $_GET['username'];
    $pass = $_GET['password'];
    $pass = md5($pass);

    // Create a prepared statement
    $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE user = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, 'ss', $user, $pass); // 'ss' = two strings

    // Execute and fetch result
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $avatar = htmlspecialchars($row["avatar"]); // Prevent XSS

        $html .= "<p>Welcome to the password protected a
