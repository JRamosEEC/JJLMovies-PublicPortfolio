<?php
    session_start(); 
    
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccountQueries.php");
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userReviewQueries.php");
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userMovieQueries.php");

    //Get the parameter first then declare it as movieID to avoid getting a blank id on post request/submission
    $id=$_GET['id'];
    $movieID = $id;

    $movieDetails = getMovieByID($movieID);
    $getAvgReview = getMovieRatingByID($movieID);

    if (empty($movieID) || empty($movieDetails)){
        header('Location: /Frontend/MoviePage/homePage.php');
    }
    
    foreach($movieDetails as $r){
        $userID=$r['user_id'];
    }

    $userData = getUserByID($userID);

    foreach($userData as $user){
        $username = $user['username'];
    }

    $reviews = getReviews($movieID);

    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST'){
        if($_SESSION["loggedIn"] == true){
            if(filter_input(INPUT_POST, 'txtReview') != "" && filter_input(INPUT_POST,'rating') != ""){
                $userAccountID = $userID;
                $ReviewDescription = filter_input(INPUT_POST, 'txtReview');
                $Rating = filter_input(INPUT_POST, 'rating'); 

                addReview($userAccountID, $movieID, $ReviewDescription, $Rating);

                header("Location: moviePage.php?id=" . $movieID);  
            }
        }
        else{
            header('Location: /Frontend/Login-Signup/loginPage.php');
        }
    }
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
            <div id="content" method="POST">
                <div id="reviewPageContainer">
                    <?php foreach($movieDetails as $row) :?>
                        <div id="movieDetailsContainer" class="row no-margin justify-content-center">
                            <img id="reviewImg" class="col-sm no-padL no-padR no-marginR" id="reviewImg" src='<?php echo $row['cover_image'];?>'>
                            
                            <div id="reviewDescriptionContainer" class="col-sm no-marginL">  
                                <div id="reviewDescriptionHeader" class="row">  
                                    <div class="col d-flex justify-content-center">  
                                            Title: <?php echo $row['title'];?>
                                    </div>

                                    <div class="col d-flex justify-content-center">
                                        <a href="<?php echo "/Frontend/UserProfile/profilePage.php?username=" . $username; ?>"><div>Creator: <?php echo $username;?></div></a>
                                    </div>
                    <?php endforeach ?>
                    
                                    <div class="col d-flex justify-content-center">  
                                            Rating: <?php if($getAvgReview != ""){ echo $getAvgReview  . "/5"; } else{echo "N/A";} ?>
                                    </div>
                                </div>
                    
                    <?php foreach($movieDetails as $row) :?>
                                <div class="row">
                                        <h2 class="col-12 pt-3">Description</h2>

                                        <p class="col"><?php echo $row['description']; ?></p>
                                </div>
                            </div>               
                        </div>            
                    <?php endforeach ?>

                    <div id="userReviewContainer" class="row d-flex justify-content-center">
                        <div id="reviewContainer">
                            <div id="reviewGradient"></div>
                            
                            <form id="reviewForm" action="moviePage.php?id=<?php echo $id;?>" method="post" class="row d-flex justify-content-center no-marginL no-marginR">
                                <div class="col-12 d-flex justify-content-center no-pad">
                                        <textarea id="txtReview" type="text" rows="6" cols="60" name="txtReview"></textarea>
                                </div>

                                <fieldset id="txtRates" name="txtRates" class="col-auto rating no-pad">
                                    <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="Rocks!">5 stars</label>
                                    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Pretty good">4 stars</label>
                                    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Meh">3 stars</label>
                                    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Kinda bad">2 stars</label>
                                    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Sucks big time">1 star</label>
                                </fieldset>

                                <div id="reviewSubmitContainer" class="col-auto d-flex align-items-center justify-content-end no-pad" >
                                        <input id="btnReview" class="btn btn-primary" type="submit" value="Submit" name="btnReview">
                                </div>
                            </form>
                            
                            <div id="userReviews">
                                <?php foreach($reviews as $rev): ?>
                                    <div id="review" class="row d-flex align-items-center no-margin">
                                        <div id="reviewContent" class="col-10 no-padR">
                                            <?php echo $rev['review_content'];?>
                                        </div>

                                        <div id="reviewRating" class="col-2 d-flex justify-content-center no-padR">
                                            <?php echo $rev['review_rating'];?>/5
                                        </div>
                                    </div>
                                <?php endforeach; ?> 
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>  
</body>
</html>