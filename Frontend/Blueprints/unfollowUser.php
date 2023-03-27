<?php
    session_start();

    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccountQueries.php");

    $searchResults = unfollowUser($_SESSION["user"], $_SESSION["profileID"]);

    if($searchResults == 'Success')
    {
        echo '<a id="followBtn" class="btn btn-primary">Follow User</a>';
    }
?>