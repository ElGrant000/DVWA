<?php

if (isset($_GET['Submit'])) {
    $id = $_GET['id'];
    $exists = false;

    switch ($_DVWA['SQLI_DB']) {
        case MYSQL:
            // Prepared statement for MySQLi
            $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], "SELECT first_name, last_name FROM users WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, 's', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $exists = true;
            }

            mysqli_stmt_close($stmt);
            ((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
            break;

        case SQLITE:
            global $sqlite_db_connection;

            // Prepared statement for SQLite
            $stmt = $sqlite_db_connection->prepare("SELECT first_name, last_name FROM users WHERE user_id = :id");
            $stmt->bindValue(':id', $id, SQLITE3_TEXT);
            $results = $stmt->execute();
            $row = $results->fetchArray();

            if ($row !== false) {
                $exists = true;
            }

            break;
    }

    if ($exists) {
        $html .= '<pre>User ID exists in the database.</pre>';
    } else {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        $html .= '<pre>User ID is MISSING from the database.</pre>';
    }
}
?>
