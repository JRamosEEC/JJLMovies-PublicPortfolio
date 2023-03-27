<?php
    session_start();

    if($_SESSION["loggedIn"] == false) {
        header('Location: /Frontend/Login-Signup/loginPage.php');
    }

    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userMovieQueries.php");
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccountQueries.php");

    $action = $_GET['action'] ?? '';
    $movieID = $_GET['id'] ?? '';

    $movieTitle = '';
    $movieDescripton = '';
    $movieGenre = '';
    $movieIMG = '';

    $btnString = "Something went wrong";

    $statusMsg = '';
    $fixMsg = '';

    //UpdateDB function that will take the uploaded image and the movie data and insert it into the users movie if all the data is set (the type indicated if this is an add or an update)
    function updateDB($type) {
        $movieID = filter_input(INPUT_POST, 'movieID');

        global $fixMsg;

        $movieTitle = filter_input(INPUT_POST, 'movieTitle');
        $movieDescripton = filter_input(INPUT_POST, 'movieDescripton');
        $movieGenre = filter_input(INPUT_POST, 'movieGenre');

        $imageSource = filter_input(INPUT_POST, 'imgSource');

        $fileName = basename($_FILES["file"]["name"]);
        $fileType = pathinfo($fileName,PATHINFO_EXTENSION);

        if(strlen($movieTitle) >= 2) {            
            if(strlen($movieDescripton) > 15) {
                if($type == 'add') {
                    if(!empty($fileName) && !empty($imageSource)) {
                        // Allow certain file formats
                        $allowTypes = array('jpg','jpeg','jfif','pjpeg','pjp','svg','webp','webp','bmp','ico','tiff','tif','apng','avif','png','gif','pdf','apng','avif','gif','xbm','eps','raw','crw','nef','orf','sr2');        //all of this is for my uploading images
                        
                        if(in_array($fileType, $allowTypes)) {
                            $DatePosted = date('Y-m-d H:i:s');
                            $statusMsg = addMovie($_SESSION['user'], $movieTitle, $DatePosted, $movieGenre, $movieDescripton, $imageSource);
        
                            header('Location: ../UserProfile/profilePage.php');
                        }
                        else {
                            $statusMsg = 'Sorry, incorrect file type.';
                        }
                    }
                    else {
                        $fixMsg .= '<br>Please select a file to upload for your cover image!';
                    }
                }
                else {
                    if(!empty($fileName) && !empty($imageSource)) {
                        // Allow certain file formats
                        $allowTypes = array('jpg','jpeg','jfif','pjpeg','pjp','svg','webp','webp','bmp','ico','tiff','tif','apng','avif','png','gif','pdf','apng','avif','gif','xbm','eps','raw','crw','nef','orf','sr2');        //all of this is for my uploading images
                        
                        if(in_array($fileType, $allowTypes)) {
                            $statusMsg = editMovieByID($movieID, $_SESSION['user'], $movieTitle, $movieGenre, $movieDescripton, $imageSource);
                            header('Location: ../UserProfile/profilePage.php');
                        }
                        else {
                            $statusMsg = 'Sorry, incorrect file type.';
                        }
                    }
                    else {
                        //If file source is empty get and use the current image source
                        $movieDetails = getMovieByID($movieID);

                        foreach($movieDetails as $row) {
                            $movieIMG = $row['cover_image'];
                        }

                        $statusMsg = editMovieByID($movieID, $_SESSION['user'], $movieTitle, $movieGenre, $movieDescripton, $movieIMG);
                        header('Location: ../UserProfile/profilePage.php');
                    }
                }  
            }
            else {
                $fixMsg .= "<br>Please make the Description at least 15 characters!";
            }
        }
        else {
            $fixMsg .= "<br>Please make the title at least 5 characters!";
        }
    }

    // - - Check the query string from the url for a set action or movie, if there is none it's likely a post request so grab the hidden variable values in the form
    if($action == '') {
        $action = filter_input(INPUT_POST, 'actionType');
    }

    if($movieID == '') {
        $movieID = filter_input(INPUT_POST, 'movieID');
    }

    if ($action == "add") {
        $btnString = "Create";
    } 
    else if ($action == "edit" && $movieID != '') {
        $btnString = "Update";
        $movieDetails = getMovieByID($movieID);

        foreach($movieDetails as $row) {
            $movieTitle = $row['title'];
            $movieDescripton = $row['description'];
            $movieGenre = $row['genre'];
            $movieIMG = $row['cover_image'];
        }
    }
    else {
        $btnString = "Create";
    }
  

    if(isset($_POST['submitBtn'])) {
        updateDB('add');
    }
    else if(isset($_POST['editBtn'])){ 
        updateDB('update');
    }
    else if(isset($_POST['deleteBtn'])){
        deleteMovieByID($_SESSION['user'], $movieID);
        header('Location: ../UserProfile/profilePage.php');
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
            <div id="content" class="center">        
                <div id="formContainer" class="row center no-pad no-marginR no-marginL">
                    <form action='MoviePageCRUD.php' method='post' enctype="multipart/form-data">
                        <input name="actionType" type="hidden" class="form-control" value="<?php if($action != ''){echo $action;}?>">
                        <input name="movieID" type="hidden" class="form-control" value="<?php if($movieID != ''){echo $movieID;} ?>">
 
                        <div id="feedItem" class="row align-items-center justify-content-center">
                            <div class="col-sm-auto">
                                <div class="row justify-content-center">
                                    <div id="feedComponentMovieImg" class="col-auto">
                                        <div id="uploadImageContainer">
                                            <img id="previewImg" src='<?php echo $movieIMG; ?>' alt="" width=150px; height=225px;>
                                            <input id="movieFileUploadBtn" name='file' type="file" accept="image/*" value=""/>
                                            <input type="hidden" name="imgSource" id="imgSource" value=""/>
                                        </div>    
                                    </div>
                                </div>
                            </div>

                            <div id="feedDetail" class="col-sm-auto p-3">
                                <div id="feedComponentMovieDetailsContainer" class="row">
                                    <div id="feedComponentDetails" class="col-9">
                                        <div id="feedTitle"><input name="movieTitle" type="text" id="movieFormTxt" placeholder="Title" value="<?php if($btnString != 'Create' || isset($_POST['submitBtn'])){echo $movieTitle;} ?>"></div>    
                                    </div>

                                    <div id="feedRating" class='col-3 d-flex justify-content-end'>N/A</div>
                                
                                    <div id="feedComponentDetails" class="col-12">
                                        <div id="feedCreator">&nbsp;Creator : <?php echo $_SESSION['username']; ?></div>
                                    </div>

                                    <div id="feedDescription" class="col-12"><textarea id="movieFormTxtDesc" name='movieDescripton' rows="3"><?php if($btnString != 'Create' || isset($_POST['submitBtn'])){echo $movieDescripton;} ?></textarea></div>
                        
                                    <div id="feedComponentDetails" class="col-6 d-flex align-items-center">
                                        <div>Date Posted: N/A</div>
                                    </div>

                                    <div class="form-group col-6 d-flex no-margin justify-content-end">
                                        <div id="feedComponentDetails" class="d-flex align-items-center">Genre:</div>
                                        <select id="genreSelect" name='movieGenre' class="no-pad">
                                            <!--Movie options-->
                                            <option value="Action" <?php if($movieGenre == 'Action'){echo 'selected="selected"';} ?>>Action</option>
                                            <option value="Adventure" <?php if($movieGenre == 'Adventure'){echo 'selected="selected"';} ?>>Adventure</option>
                                            <option value="Horror" <?php if($movieGenre == 'Horror'){echo 'selected="selected"';} ?>>Horror</option>
                                            <option value="Comedy" <?php if($movieGenre == 'Comedy'){echo 'selected="selected"';} ?>>Comedy</option>
                                            <option value="Family" <?php if($movieGenre == 'Family'){echo 'selected="selected"';} ?>>Family</option>
                                            <option value="Thriller" <?php if($movieGenre == 'Thriller'){echo 'selected="selected"';} ?>>Thriller</option>
                                            <option value="Drama" <?php if($movieGenre == 'Drama'){echo 'selected="selected"';} ?>>Drama</option>
                                            <option value="Science Fiction" <?php if($movieGenre == 'Science Fiction'){echo 'selected="selected"';} ?>>Science Fiction</option>
                                            <option value="Romance" <?php if($movieGenre == 'Romance'){echo 'selected="selected"';} ?>>Romance</option>
                                            <option value="Western" <?php if($movieGenre == 'Western'){echo 'selected="selected"';} ?>>Western</option>
                                            <option value="Crime" <?php if($movieGenre == 'Crime'){echo 'selected="selected"';} ?>>Crime</option>
                                            <option value="Musical" <?php if($movieGenre == 'Musical'){echo 'selected="selected"';} ?>>Musical</option>
                                            <option value="Fantasy" <?php if($movieGenre == 'Fantasy'){echo 'selected="selected"';} ?>>Fantasy</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="submitContainer" class="row no-margin">
                            <div class="col-6 d-flex justify-content-center no-pad">
                                <button id="submitBtn" name='<?php if($btnString == "Update"){echo "editBtn";}else{echo "submitBtn";}?>' type="submit" class="btn btn-primary"><?php echo $btnString?></buton>
                            </div>

                            <?php 
                                if($btnString == "Update")
                                {
                                    echo "
                                    <div class='col-6 d-flex justify-content-center no-pad'>
                                        <button id='deleteBtn' name='deleteBtn' type='submit' class='btn btn-primary'>Delete</buton>
                                    </div>";
                                }
                            ?>
                        </div>
                    </form>  

                    <?php
                        // Display status message and fix errors
                        echo $fixMsg;
                        echo $statusMsg;
                    ?> 
                </div>
            </div>
        </div>
    </div>  
</body>
</html>

<script src="/Frontend/JavaScript/ImageUpload/imageUpload.js"></script>
<script src="/Frontend/JavaScript/ImageUpload/checkImage.js"></script>
<script src="/Frontend/JavaScript/ImageUpload/movieImageUploadInput.js"></script>