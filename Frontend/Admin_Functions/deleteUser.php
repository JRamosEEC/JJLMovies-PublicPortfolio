<?php
    session_start();

    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccountQueries.php");
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccessQueries.php");
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/administrativeQueries.php");

    $affectedAccountName = $_GET['username'] ?? '';
    $affectedAccountID = getUserID($affectedAccountName);

    $loginFailed = "";

    //Checks if the user clicks log in and tests input (password storage and checks must be done in sha256 encryption)
    if(filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        $adminAcnt = validateLogin($username, $password);

        foreach($adminAcnt as $user){
            //getting the user information to check that these credentials match the signed in admin
            $admin_ID = $user['user_id'];
            $adminName = $user['username'];
        }

        if(count($adminAcnt) && $admin_ID == $_SESSION['user'] && $adminName == $_SESSION['username']){
            if(deleteUserByID($affectedAccountID) == 'Data Deleted') {
                header('Location: ../MoviePage/homePage.php');   
            }
            else {
                $loginFailed = "No user found with that username!";
            }
        }
        else{
            $loginFailed = 'Invalid Admin Credentials!';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php include($_SERVER['DOCUMENT_ROOT'] . "/Frontend/Blueprints/htmlHead.php")?>

<body> 
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include(__DIR__ . "/../Blueprints/navDynamicBlueprint.php")?>
        <?php include(__DIR__ . "/../Blueprints/headerBlueprint.php")?>

        <div id="bodyContainer">
            <!-- Static Sidebar -->
            <?php include(__DIR__ . "/../Blueprints/navStaticBlueprint.php")?>

            <!-- Page Content -->
            <div id="content">
                <div id="formContainer" class="row center no-margin no-padL">
                    <h1 class="col-xl-12 center">Confirm Delete of Account</h1>
                    <h2 class="col-xl-12 center">User: <?php echo $affectedAccountName; ?></h1>
                                        
                    <div id="spacer" class="col-3"></div>
                    
                    <form action="deleteUser.php?username=<?php echo $affectedAccountName; ?>" method="post" class="col-6">
                        <div class="row d-flex flex-column align-items-center">
            
                            <div>
                                <div class="row">
                                    <div class="col-12 center">Username</div>

                                    <div class="col-12 center mb-3">
                                        <input type="text" name="username">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="row">
                                    <div class="col-12 center">Password</div>

                                    <div class="col-12 center mb-3">
                                        <input type="password" name="password">
                                    </div>
                                </div>
                            </div>  
                            
                            <div>
                                <div id="login" class="center headerBtn mb-3">
                                    <input type="submit" name="Confirm" value="Confirm" class="btn btn-primary"></a></input>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="spacer" class="col-3"></div>
                </div>

                <?php 
                    if($loginFailed != ""){
                        echo "<div class='row center'><h2 style='color:red;font-size:20px; margin-top: 20px;'>{$loginFailed}</h2></div>"; 
                    }
                ?> 

            </div>    
        </div>
    </div>
</body>
</html>
