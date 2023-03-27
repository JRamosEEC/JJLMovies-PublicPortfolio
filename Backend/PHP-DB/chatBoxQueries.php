<?php    
    include (__DIR__ . "/dbConnection.php");

    //This statement will get every user that the account id follows or is following account id. It will also get every sent and received message ordered by most recent.
    //This needs to be changed in final version to be that it will get every message sent and received to user but also everyone the user follows only included on the list and people that don't follow will be included under messages as a "pending follow"
    function getDisplayMessages($userAccountID){
        global $db;
        
        $results = [];

        $stmt = $db->prepare(
            "SELECT pairID, sender, receiver, message, isRead FROM ( " .
                "SELECT pair_id AS pairID, sender_id AS sender, receiver_id AS receiver, message_content AS message, sent_date AS sentDate, message_read AS isRead " .
                    "FROM user_messages " .
                    "WHERE user_messages.sender_id = :userAccountID1 " .
                    "OR user_messages.receiver_id = :userAccountID2 " .
                "UNION ALL " .
                "SELECT SUBSTRING(HEX(AES_ENCRYPT(LEAST(follower_id, followee_id), GREATEST(follower_id, followee_id))), 1, 36) AS pairID, follower_id AS sender, followee_id AS receiver, ' ' AS message_content, null AS sentDat, 1 AS isRead " .
                    "FROM user_follow " .
                    "WHERE user_follow.follower_id = :userAccountID3 " .
                "ORDER BY sentDate DESC " .
                ") AS chatMessages " .
            "GROUP BY pairID"
        );
        
        $binds = array(
            ":userAccountID1" => $userAccountID,
            ":userAccountID2" => $userAccountID,  
            ":userAccountID3" => $userAccountID,  
        );

        if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);      
        }
        
        return ($results);
    }
?>