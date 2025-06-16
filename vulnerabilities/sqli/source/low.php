<?php

if (isset($_REQUEST['Submit'])) {
    // Get input
    $id = $_REQUEST['id'];

    switch ($_DVWA['SQLI_DB']) {
        case MYSQL:
            // CHANGED: Use prepared statement instead of raw SQL
            $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], "SELECT first_name, last_name FROM users WHERE user_id = ?"); // ADDED
            mysqli_stmt_bind_param($stmt, "i", $id); // ADDED — "i" means integer

            mysqli_stmt_execute($stmt); // ADDED
            mysqli_stmt_bind_result($stmt, $first, $last); // ADDED

            while (mysqli_stmt_fetch($stmt)) { // ADDED
                // Feedback for end user
                $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
            }

            mysqli_stmt_close($stmt); // ADDED
            mysqli_close($GLOBALS["___mysqli_ston"]);
            break;

        case SQLITE:
            global $sqlite_db_connection;

            try {
                // CHANGED: Use prepared statement instead of raw SQL
                $stmt = $sqlite_db_connection->prepare("SELECT first_name, last_name FROM users WHERE user_id = :id"); // ADDED
                $stmt->bindValue(':id', $id, SQLITE3_INTEGER); // ADDED

                $results = $stmt->execute(); // ADDED

                while ($row = $results->fetchArray(SQLITE3_ASSOC)) { // ADDED
                    $first = $row["first_name"];
                    $last  = $row["last_name"];

                    // Feedback for end user
                    $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
                }
            } catch (Exception $e) {
                echo 'Caught exception: ' . $e->getMessage();
                exit();
            }
            break;
    }
}

?>
