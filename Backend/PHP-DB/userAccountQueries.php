<?php
    include (__DIR__ . "/dbConnection.php");

    function getUserByID($userAccountID){
        global $db;
        
        $results = [];

        $stmt = $db->prepare("SELECT * FROM user_accounts WHERE user_id = :userAccountID"); 
        
        $binds = array(
            ":userAccountID" => $userAccountID,     
        );
        

        if ( $stmt->execute($binds) && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
         
        return ($results);
    }

    function getUserName($userAccountID) {
        global $db;
        
        $results = '';

        $stmt = $db->prepare("SELECT username FROM user_accounts WHERE user_id = :userAccountID"); 
        
        $binds = array(
            ":userAccountID" => $userAccountID,     
        );
        

        if ( $stmt->execute($binds) && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchColumn();
        }
         
        return ($results);
    }

    function getUserID($userName){

        global $db;

        $stmt = $db->prepare("SELECT user_id FROM user_accounts WHERE username =:UserName");

        $binds = array(
            ":UserName" => $userName,     
        );


        if ( $stmt->execute($binds) && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchColumn();      
        }
        else {$results = NULL;}
        
        return ($results);
    }

    function getUserRole($userAccountID) {
        global $db;
        
        $results = '';

        $stmt = $db->prepare("SELECT role FROM user_accounts WHERE user_id = :userAccountID"); 
        
        $binds = array(
            ":userAccountID" => $userAccountID,     
        );
        

        if ( $stmt->execute($binds) && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchColumn();
        }
         
        return ($results);
    }

    function getFollowerCount($userAccountID){
        global $db;
        
        $results = 0;

        $stmt = $db->prepare("SELECT COUNT(*) AS 'total' FROM user_follow WHERE followee_id = :userAccountID"); 
        
        $binds = array(
            ":userAccountID" => $userAccountID,     
        );


        if ( $stmt->execute($binds) && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchColumn();      
        }
        
        return ($results);
    }

    function getFollowingCount($userAccountID){
        global $db;
        
        //Declare as default as statement to set result only runs if row count is greater than 0 this avoids the need for an else statement
        $results = 0;

        $stmt = $db->prepare("SELECT COUNT(*) AS 'total' FROM user_follow WHERE follower_id = :userAccountID"); 
        
        $binds = array(
            ":userAccountID" => $userAccountID,     
        );

        if ( $stmt->execute($binds) && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchColumn();
                 
        }
        
        return ($results);
    }

    function isFollowing($userAccountID, $profileAccountID){
        global $db;
        
        //Declare as default as statement to set result only runs if row count is greater than 0 this avoids the need for an else statement
        $results = false;

        $stmt = $db->prepare("SELECT * FROM user_follow WHERE followee_id = :profileAccountID AND follower_id = :userAccountID"); 
        
        $binds = array(
            ":profileAccountID" => $profileAccountID,
            ":userAccountID" => $userAccountID,
        );


        if ( $stmt->execute($binds) && $stmt->rowCount() > 0 ) {
            $results = true;      
        }
        
        return ($results);
    }

    function getFollowers($userAccountID){
        global $db;
        
        $results = [];

        $stmt = $db->prepare("SELECT follower_id FROM user_follow WHERE followee_id = :userAccountID"); 
        
        $binds = array(
            ":userAccountID" => $userAccountID,     
        );


        if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);      
        }
        
        return ($results);
    }

    function followUser($followingAccountID, $followingUsername, $followerAccountID, $followerUsername){
        global $db;
        
        $results = '';

        $stmt = $db->prepare("INSERT INTO user_follow SET followee_id = :followingAccountID, follower_id = :followerAccountID"); 
    
        $binds = array(
            ":followingAccountID" => $followingAccountID,
            ":followerAccountID" => $followerAccountID,
        );


        if ( $stmt->execute($binds) && $stmt->rowCount() > 0 ) {
            $results = 'Success';
        }
        
        return ($results);
    }

    function unfollowUser($followingAccountID, $followerAccountID){
        global $db;
        
        $results = '';

        $stmt = $db->prepare("DELETE FROM user_follow WHERE follower_id = :followingAccountID AND followee_id = :followerAccountID"); 
    
        $binds = array(
            ":followingAccountID" => $followingAccountID,
            ":followerAccountID" => $followerAccountID,
        );

        if ( $stmt->execute($binds) && $stmt->rowCount() > 0 ) {
            $results = 'Success';
        }
        
        return ($results);
    }
?>