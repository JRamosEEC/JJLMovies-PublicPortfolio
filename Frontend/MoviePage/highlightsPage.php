<?php
    session_start();

    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userMovieQueries.php");
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccountQueries.php");
    
    $trend = getTrendingMovies();
?>

<!DOCTYPE html>

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
            <div id="content" class="center">   
                <div id="trendingMoviesContainer" class="row d-flex justify-content-center">
                    <h1 class='col-12 d-flex justify-content-center'>Trending</h1>

                    <?php foreach($trend as $index=>$row) :?>
                        <?php if($index == 0 || $index == 3) :?>
                            <div id="trendRow" class="row align-items-end justify-content-center no-margin">
                        <?php endif; ?>
                        
                        <a id="trendingCard" class=<?php if ($index < 3){ $ratingNum = $index + 1; echo "'trendingTop" . $ratingNum . "'"; } else { echo "''"; } ?> href="moviePage.php?id=<?php echo $row['movie_id'];?>">
                            <div id="trendMovieImg" class="row no-margin d-flex justify-content-center">
                                <img id="trendImg" src='<?php echo $row['cover_image']; ?>'>
                            </div>

                            <div id=<?php if ($index < 3){ $ratingNum = $index + 1; echo "'trendMovieInfoTop" . $ratingNum . "'"; } else { echo "'trendMovieInfo'"; } ?> class="row d-flex justify-content-center centerV no-margin">
                                <div id="trendMovieTitle" class="col">  
                                    <?php echo $row['title'];?>
                                </div>
                                
                                <div id="movieRatingContainer" class="col-3 d-flex justify-content-center centerV no-pad">
                                    <div id="trendMovieRating">
                                        <?php if(getMovieRatingByID($row['movie_id']) != ""){ echo getMovieRatingByID($row['movie_id']); } else{ echo "N/A"; }?>
                                    </div>
                                </div>
                            </div>  
                        </a>

                        <?php if($index == 2) :?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach ?>
                </div>
            </div>
        </div> 
    </div>  
</body>
</html>