<?php
    session_start(); 

    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccessQueries.php");

    $email = filter_input(INPUT_POST, 'email');
    $username = filter_input(INPUT_POST, 'username');
    $password = filter_input(INPUT_POST, 'password');
    $firstName = filter_input(INPUT_POST, 'firstName');
    $lastName = filter_input(INPUT_POST, 'lastName');

    $signupError = false;
    $responseStr = "";

    if(isset($_POST['submitBtn'])){
        $startsWithLetter = "/^[A-Za-z]+.+$/";
        $acceptedUsernameChars = "/^[A-Za-z0-9_]+$/";
        $usernamePattern = "/^[A-Za-z][A-Za-z0-9_]{6,25}$/";
        
        $oneUppercase = "/(?=.*[A-Z])/";
        $oneLowercase = "/(?=.*[a-z])/";
        $oneNumeric = "/.*[0-9].*/";
        $acceptedPassChars = "/^[A-Za-z0-9_]+$/";
        $passwordPattern = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/";
        $namePattern = "/^[A-Za-z ,.'-]+$/";

        if(!preg_match($usernamePattern, $username)) {
            $responseStr .= '<br>Invalid Username!';

            if(strlen($username) < 6 || strlen($username) > 25) {
                $responseStr .= '<br>Username must be between 6-25 characters long.';
            }

            if(!preg_match($startsWithLetter, $username)) {
                $responseStr .= '<br>Username must start with a letter.';
            }
            
            if(!preg_match($acceptedUsernameChars, $username)) {
                $responseStr .= '<br>Username must contain only letters, numbers, and underscores.';
            }

            $signupError = true;
        }

        if(!preg_match($passwordPattern, $password)){
            $responseStr .= '<br>Invalid Password!';

            if(strlen($password) < 6 || strlen($password) > 20) {
                $responseStr .= '<br>Password must be between 6-20 characters long.';
            }

            if(!preg_match($oneUppercase, $password)) {
                $responseStr .= '<br>Password must contain at least one upper case letter.';
            }

            if(!preg_match($oneLowercase, $password)) {
                $responseStr .= '<br>Password must contain at least one lower case letter.';
            }

            if(!preg_match($oneNumeric, $password)) {
                $responseStr .= '<br>Password must contain at least one number';
            }

            $signupError = true;
        }

        if(!preg_match($namePattern, $firstName)){
            $responseStr .= '<br>Invalid first name.';
            $signupError = true;
        }

        if(!preg_match($namePattern, $lastName)) {
            $responseStr .= '<br>Invalid last name.';
            $signupError = true;
        }

        if(validateUsername($username) == true){
            $responseStr .= '<br>This User Name is already taken please pick another one.';
            $signupError = true;
        }

        if(validateEmail($email) == true){
            $responseStr .= '<br>This email already exists on our site.';
            $signupError = true;
        }

        //make sure password is encrpyted using !sha256!
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            $responseStr .= "<br>Email address '$email' is considered invalid.\n";
            $signupError = true;
        }

        if(!$signupError) {
            $results = signUp($username, $password, $firstName, $lastName, $email);
            header('Location: ../Login-Signup/loginPage.php');
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
                    <h1 class="col-12 center">SIGN UP</h1>

                    <div id="spacer" class="col-3"></div>
                    
                    <form action="signupPage.php" method="post" class="col-6">
                        <div class="row">
                            <div id="formField" class="col-xl-6">
                                <div class="row">
                                    <div class="col-12 center">First Name</div>

                                    <div class="col-12 center">
                                        <input type="text" name="firstName" value="<?php echo $firstName; ?>" />
                                    </div>
                                </div>
                            </div>

                            <div id="formField" class="col-xl-6">
                                <div class="row">
                                    <div class="col-12 center">Last Name</div>

                                    <div class="col-12 center">
                                        <input type="text" name="lastName" value="<?php echo $lastName; ?>" />
                                    </div>
                                </div>
                            </div>

                            <div id="formField" class="col-xl-6">
                                <div class="row">
                                    <div class="col-12 center">Username</div>

                                    <div class="col-12 center">
                                        <input type="text" name="username" value="<?php echo $username; ?>" />
                                    </div>
                                </div>
                            </div>

                            <div id="formField" class="col-xl-6">
                                <div class="row">
                                    <div class="col-12 center">Password</div>

                                    <div class="col-12 center">
                                        <input type="password" name="password">
                                    </div>
                                </div>
                            </div>  

                            <div id="formField" class="col-md-12">
                                <div class="row">
                                    <div class="col-12 center">Email</div>

                                    <div class="col-12 center">
                                        <input type="text" name="email"  value="<?php echo $email; ?>" />
                                    </div>
                                </div>
                            </div>  
                            
                            <div id="formField" class="col-md-12 center">
                                <div id="createAcnt" class="center headerBtn col-6">
                                    <button name='submitBtn' type="submit" class="btn btn-primary">Create Account</buton>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="spacer" class="col-3"></div>

                    <?php
                        if($signupError){
                            echo $responseStr;
                        }
                    ?>         
                </div>
            </div>
        </div>
    </div>
</body>
</html>