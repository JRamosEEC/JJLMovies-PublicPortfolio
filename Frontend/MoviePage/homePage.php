<?php
    session_start();

    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccountQueries.php");
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userMovieQueries.php");
    
    $movies = getAllMovies();  

    if(isset($_SESSION['user'])) {
        setcookie("userID", $_SESSION['user']);
        setcookie("userName", $_SESSION['username']);
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php include($_SERVER['DOCUMENT_ROOT'] . "/Frontend/Blueprints/htmlHead.php")?>

<body>
    <div class="wrapper">
        <!-- Dynamic Sidebar -->
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/Frontend/Blueprints/navDynamicBlueprint.php")?>
        <!-- Header -->
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/Frontend/Blueprints/headerBlueprint.php")?>

        <div id="bodyContainer">
            <!-- Static Sidebar -->
            <?php include($_SERVER['DOCUMENT_ROOT'] . "/Frontend/Blueprints/navStaticBlueprint.php")?>

            <!-- Chat Box -->
            <?php include($_SERVER['DOCUMENT_ROOT'] . "/Frontend/Blueprints/messageBox.php")?>

            <!-- Notification Popup -->
            <?php include($_SERVER['DOCUMENT_ROOT'] . "/Frontend/Blueprints/notificationPopup.php")?>

            <!-- Page Content -->
            <div id="content">    

                <?php foreach ($movies as $row): ?>
                    <?php 
                        $userID = $row['user_id'];
                    
                        $userData = getUserByID($userID);
                    
                        foreach($userData as $user){
                            $username = $user['username'];
                        }
                    ?>

                    <a id="feedItem" class="row align-items-center justify-content-center" href="moviePage.php?id=<?php echo $row['movie_id'];?>">
                        <div id="feedImgContainer" class="col-sm-auto">
                            <div class="row justify-content-center">
                                <div id="feedComponentMovieImg" class="col-auto no-pad">
                                    <img src='<?php echo $row['cover_image']; ?>' width=150px; height=225px;>
                                </div>
                            </div>
                        </div>

                        <div id="feedDetail" class="col-sm-auto">
                            <div id="feedComponentHeader" class="row">
                                <div id="feedTitle" class="col-9"><b><?php echo $row['title'];?></b></div>    

                                <div id="feedRating" class='col d-flex justify-content-end'>
                                    <?php if(getMovieRatingByID($row['movie_id']) != ""){ 
                                        echo getMovieRatingByID($row['movie_id'])  . "/5";
                                    } else{echo "N/A";} ?>
                                </div>
                            </div>

                            <div id="feedCreator" class="row">&nbsp;Creator : <?php echo $username; ?></div>

                            <div id="feedDescription" class="row"><textarea id="feedDescText" maxlength="5"><?php if(strlen($row['description']) <= 295){ echo $row['description']; } else{ echo substr($row['description'], 0, 295) . "..."; }?></textarea></div>
                
                            <div id="feedDate" class="row d-flex justify-content-end">Date Posted: <?php echo substr($row['date_posted'], 0, 10)?></div>
                        </div>
                    </a>
                <?php endforeach; ?>    

            </div>
        </div>
    </div>
</body>
</html>