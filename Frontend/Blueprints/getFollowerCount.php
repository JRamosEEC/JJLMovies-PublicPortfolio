<?php
    session_start();
    
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccountQueries.php");

    $username = $_GET['username'] ?? '';
    $user_id = getUserID($username);

    $followerCount = getFollowerCount($user_id);

    echo $followerCount;
?>