<?php
    session_start();

    $_SESSION["loggedIn"] = false;

    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccessQueries.php");

    $loginFailed = "";

    //Checks if the user clicks log in and tests input (password storage and checks must be done in sha256 encryption)
    if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        $returnedAcnt = validateLogin($username, $password);
        
        if(count($returnedAcnt)){
            foreach($returnedAcnt as $user){
                //getting the user information from the table and storing into session variables to display on pages
                $user_ID = $user['user_id'];
            }

            setcookie("userID", $user_ID);
            setcookie("userName", $username);

            $_SESSION['user'] = $user_ID;
            $_SESSION['username'] = $username;

            $_SESSION["loggedIn"] = true;
        
            header('Location: ../MoviePage/homePage.php');   
        }
        else{
            $_SESSION["loggedIn"] = false;
            $loginFailed = 'Sorry, Login failed';
        }
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

            <!-- Notification Popup -->
            <?php include($_SERVER['DOCUMENT_ROOT'] . "/Frontend/Blueprints/notificationPopup.php")?>

            <!-- Page Content -->
            <div id="content" class="center">
                <div id="formContainer" class="row center no-pad no-marginR no-marginL">
                    <h1 class="col-xl-12 center">Login</h1>

                    <div id="spacer" class="col-3"></div>
                    
                    <form action="loginPage.php" method="post" class="col-6">
                        <div class="row d-flex flex-column align-items-center">
                            <div id="formInput" class="row">
                                <div class="col-12 center">Username</div>

                                <div class="col-12 center mb-3">
                                    <input type="text" name="username">
                                </div>
                            </div>

                            <div id="formInput" class="row">
                                <div class="col-12 center">Password</div>

                                <div class="col-12 center mb-3">
                                    <input type="password" name="password">
                                </div>
                            </div>
                            
                            <div id="loginBtns">
                                <div id="login" class="center headerBtn mb-3">
                                    <input type="submit" name="Login" value="Login" class="btn btn-primary"></a></input>
                                </div>
                                
                                <div id="createAcnt" class="center headerBtn mb-3">
                                    <a href="/Frontend/Login-Signup/signupPage.php" class="btn btn-primary">Create Account</a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="spacer" class="col-3"></div>
                </div>

                <?php 
                    if($loginFailed != ""){
                        echo "<div class='row center'><h2 id='loginResponse'>Please enter in a valid username and password.</h2></div>"; 
                    }
                ?> 
            </div>    
        </div>
    </div>
</body>
</html>
