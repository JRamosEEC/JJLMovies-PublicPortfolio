<?php
    session_start();

    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccountQueries.php");
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userMovieQueries.php");

    $Username = $_GET['username'] ?? '';
    $userID = getUserID($Username);
    $profileType = 'Personal'; //Default is your own profile
    $rowCount = 0;

    if($_SESSION["loggedIn"] == false) {
        header('Location: /Frontend/Login-Signup/loginPage.php');
    }

    if ($userID != NULL && $userID != $_SESSION['user']){
        $userData = getUserByID($userID);
        $userFollowers = getFollowerCount($userID);
        $userFollowing = getFollowingCount($userID);

        $profileType = 'Other';
    }
    else {
        $userID = $_SESSION['user'];
        $profileType = 'Personal';
    }
    
    $userData = getUserByID($userID);
    $userFollowers = getFollowerCount($userID);
    $userFollowing = getFollowingCount($userID);

    foreach($userData as $user){
        //Get the user information from the table and storing into session variables to display on pages
        $Username = $user['username'];
        $fName = $user['first_name'];
        $lName = $user['last_name'];
        $profileImg = $user['profile_image']; 
    }

    //This saves the name for the purposes of following a user
    $_SESSION["profileName"] = $Username;
    //This saves the id for the purposes of following a user
    $_SESSION["profileID"] = $userID;

    $movies = getMoviesByUserID($userID);

    $userRole = getUserRole($userID); //Store the current profile user role
    $sessionRole = getUserRole($_SESSION['user']); //Check if this is an elevated user viewing the profile
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
                <div id="profileContainer" class="row no-margin">
                    
                    <!-- Admin Panel -->
                    <?php if(($sessionRole == "admin" || $sessionRole == "owner") && $profileType != "Personal") : ?>
                        <?php include($_SERVER['DOCUMENT_ROOT'] . "/Frontend/Blueprints/profileAdminPanel.php")?>
                    <?php endif; ?>

                    <div id="itemContainer" class="col-xl-3 center"> 
                        <div class="row"> 
                            <div id="profileIMG" class="col-12 d-flex justify-content-center profileItem">
                                <img src="<?php if($profileImg != NULL){ echo $profileImg; } else{echo "/images/profile-icon-logged-out.png";}?>" width="170px" height="170px"; >
                            </div>

                            <div class="col-12">
                                <div id="profileUsername" class="col-12 d-flex justify-content-center">
                                    <?php echo $Username ?>
                                </div>

                                <div id="profileName" class="col-12 d-flex justify-content-center">
                                    <?php echo $fName ?>
                                    <?php echo $lName ?>
                                </div>

                                <div id="profileFollowers" class="col-12 d-flex justify-content-center">
                                    <div id="followersText" class="row">
                                        <div class="col-6">
                                            <div class="row d-flex justify-content-center">Followers</div>

                                            <div id="userFollowers" class="row d-flex justify-content-center"><?php echo $userFollowers ?></div>
                                        </div>
                                    
                                        <div class="col-6">
                                            <div class="row d-flex justify-content-center">Following</div>

                                            <div id="userFollowing" class="row d-flex justify-content-center"><?php echo $userFollowing ?></div>
                                        </div>
                                    </div>
                                </div> 

                                <div id="profileAddMovie" class="col-12 d-flex justify-content-center align-items-end">
                                    <?php if($profileType == 'Personal'){ echo "<a href='/Frontend/UserProfile/MoviePageCRUD.php?action=add' class='btn btn-primary'>Create Movie</a>";} ?>
                                </div>
                                
                                <div id="profileLogout" class="col-12 d-flex justify-content-center align-items-center">
                                    <?php 
                                        if($profileType == 'Personal'){ 
                                            echo '<a href="/Frontend/Login-Signup/logoutPage.php" class="btn btn-primary">Log Out</a>';
                                        }
                                        else if (isFollowing($_SESSION["user"], $_SESSION["profileID"])){
                                            echo '<a id="unfollowBtn" class="btn btn-primary">Unfollow User</a>';
                                        }
                                        else{
                                            echo '<a id="followBtn" class="btn btn-primary">Follow User</a>';
                                        } 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="itemContainer" class="col-xl">
                        <div class="row profileMovieContainer">
                            
                            <?php foreach($movies as $row) :?>
                                <?php $rowCount += 1; ?>

                                <div id="movieItem" class="col-6 col-xl-4 no-pad movieItem<?php echo $rowCount; ?>">
                                    <div class="col-12 d-flex justify-content-center">
                                        <a href="../MoviePage/moviePage.php?id=<?php echo $row['movie_id'];?>"><img id="profileMovieImg" src='<?php echo $row['cover_image'];?>'></a>
                                    </div>
                                    
                                    <div class="col-12 d-flex justify-content-center">  
                                        <a href="../MoviePage/moviePage.php?id=<?php echo $row['movie_id'];?>">
                                            <div id="movieitemContainer" class="row">  
                                                <div class="col centerV">  
                                                    <div><?php echo $row['title'];?></div>
                                                </div>

                                                <div id="movieRating" class="col-auto justify-content-center">  
                                                    <div><?php if(getMovieRatingByID($row['movie_id']) != ""){ echo getMovieRatingByID($row['movie_id'])  . "/5";} else{ echo "N/A"; } ?></div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <?php if($profileType == "Personal") : ?>

                                        <div class="col-12 d-flex justify-content-center no-pad">
                                            <div id="userMovieBtns" class="row no-margin">
                                                <a id="editMovieBtnContainer" class="col-auto no-padL" href="MoviePageCRUD.php?action=edit&id=<?php echo $row['movie_id'];?>">
                                                    <div id="editMovieBtn" class="col-auto d-flex justify-content-center">  
                                                        <div class="centerV">  
                                                            <div>Edit</div>
                                                        </div>
                                                    </div>
                                                </a>

                                                <div id="shareMovieBtnContainer" class="col-auto d-flex justify-content-center no-pad shareBtn">
                                                    <div class="col-auto d-flex justify-content-center align-items-center no-pad">  
                                                        <a id="shareBtn" name="https://www.jramosportfolio.com/Frontend/MoviePage/moviePage.php?id=<?php echo $row['movie_id'];?>">
                                                            <img src='/images/share.png' id="shareImg";>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php else : ?>

                                        <div id="spacerDiv"></div>

                                    <?php endif; ?>
                                </div>   
                            <?php endforeach ?>

                            <a id="PrevPage" class="btn btn-primary"><</a>
                            <a id="NextPage" class="btn btn-primary">></a>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
</body>
</html>

<script src="/Frontend/JavaScript/Profile/followFunctions.js"></script>
<script src="/Frontend/JavaScript/Profile/shareFunctions.js"></script>
<script src="/Frontend/JavaScript/Profile/movieDisplayPageFunctions.js"></script>