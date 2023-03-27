<?php
    include (__DIR__ . "/dbConnection.php");

    function elevateUserByID($userAccountID) {
        global $db; 
        
        $results = [];

        $stmt = $db->prepare("UPDATE user_accounts SET role = 'admin' WHERE user_id = :UserAccountID");
        $stmt->bindvalue(':UserAccountID', $userAccountID);

        if($stmt->execute() && $stmt->rowCount()> 0) {
            $results = "Success"; 
        }

        return ($results); 
    }

    function downgradeUserByID($userAccountID) {
        global $db; 
        
        $results = [];

        $stmt = $db->prepare("UPDATE user_accounts SET role = 'user' WHERE user_id = :UserAccountID");
        $stmt->bindvalue(':UserAccountID', $userAccountID);

        if($stmt->execute() && $stmt->rowCount()> 0) {
            $results = "Success"; 
        }

        return ($results); 
    }

    //Deletes user account and all data associated with it
    function deleteUserByID($userAccountID) {
        global $db;
        
        $results = "Data was not deleted";
    
        $stmt = $db->prepare("DELETE t1, t2, t3, t4 FROM user_accounts AS t1 LEFT OUTER JOIN user_follow AS t2 ON t1.user_id = t2.follower_id OR t1.user_id = t2.followee_id LEFT OUTER JOIN user_movies AS t3 ON t1.user_id = t3.user_id LEFT OUTER JOIN user_reviews AS t4 ON t1.user_id = t4.user_id OR t3.movie_id = t4.movie_id WHERE t1.user_id = :userAccountID");
        
        $stmt->bindValue(':userAccountID', $userAccountID);
            

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $results = 'Data Deleted';
        }
        
        return ($results);
    }
?>