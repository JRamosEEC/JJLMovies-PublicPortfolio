<?php
    session_start();

    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccountQueries.php");
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/administrativeQueries.php");

    $affectedAcnt = $_GET['username'] ?? '';
    $user_id = getUserID($affectedAcnt);

    $searchResults = '';

    if(getUserRole($_SESSION["user"]) == 'owner') {
        $searchResults = elevateUserByID($user_id);
    }

    if ($searchResults == "Success") {
        echo '(' . getUserRole($user_id) . ')';
    }
?>