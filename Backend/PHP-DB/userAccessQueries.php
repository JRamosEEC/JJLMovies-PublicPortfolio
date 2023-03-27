<?php
    include (__DIR__ . "/dbConnection.php");
    
    function getNextUserIDNum(){
        global $db;
        
        $results = 0;

        $ini = parse_ini_file( __DIR__ . '/../Config/hashkeyconfig.ini');

        $stmt = $db->prepare("SELECT CAST(AES_DECRYPT(UNHEX(user_id), '" . $ini['userKeyHash'] . "')+1 AS UNSIGNED) AS NUM FROM user_accounts ORDER BY NUM DESC limit 0,1"); 
        
        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchColumn();      
        }
        
        return ($results);
    }
    
    function signUp($Username, $Password, $FirstName, $LastName, $Email){
        global $db;

        $UserCnt = getNextUserIDNum();

        $results = "Not addded";

        $ini = parse_ini_file( __DIR__ . '/../Config/hashkeyconfig.ini');

        $stmt = $db->prepare("INSERT INTO user_accounts SET user_id = HEX(AES_ENCRYPT(:UserCnt, '" . $ini['userKeyHash'] . "')), username = :Username, password = :Password, first_name = :FirstName, last_name = :LastName, email = :Email");

        $binds = array(
            ":UserCnt" => $UserCnt,
            ":Username" => $Username,
            ":Password" => hash('sha256', $Password . $ini['userKeyHash']),
            ":FirstName" => $FirstName,
            ":LastName" => $LastName,
            ":Email" => $Email,
        );

        if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
            $results = "Person Added";
        }

        return ($results);
    }

    function validateLogin ($username, $password) {
        global $db; 

        $results = [];

        $ini = parse_ini_file( __DIR__ . '/../Config/hashkeyconfig.ini');

        $stmt = $db->prepare("SELECT * FROM user_accounts WHERE username = :Username AND password = :Password");

        $stmt->bindValue(':Username', $username);
        $stmt->bindValue(':Password', hash('sha256', $password . $ini['userKeyHash'])); 

        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);        
        }
         
        return ($results);
    }

    function validateUsername ($username) {
        global $db;

        $results = false;

        $stmt = $db->prepare("SELECT user_id FROM user_accounts WHERE username = :Username");

        $stmt->bindValue(':Username', $username);

        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
            $results = true;        
        }
         
        return ($results);
    }

    function validateEmail($email){

        global $db;

        $results = false;

        $stmt = $db->prepare("SELECT user_id FROM user_accounts WHERE email = :email");

        $binds = array(
            ":email" => $email,     
        );


        if ( $stmt->execute($binds) && $stmt->rowCount() > 0 ) {
            $results = true;      
        }
        
        return ($results);
    }
?>