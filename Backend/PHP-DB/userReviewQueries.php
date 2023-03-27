<?php
    include (__DIR__ . "/dbConnection.php");

    function getNextReviewIDNum(){
        global $db;
        
        $results = 0;

        $ini = parse_ini_file( __DIR__ . '/../Config/hashkeyconfig.ini');

        $stmt = $db->prepare("SELECT CAST(AES_DECRYPT(UNHEX(review_id), '" . $ini['reviewKeyHash'] . "')+1 AS UNSIGNED) AS NUM FROM user_reviews ORDER BY NUM DESC limit 0,1"); 

        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchColumn();      
        }
        
        return ($results);
    }

    function addReview($userAccountID, $movieID, $ReviewDescription, $Rating){
        global $db;

        $ReviewCnt = getNextReviewIDNum();

        $results = "Not addded";

        $ini = parse_ini_file( __DIR__ . '/../Config/hashkeyconfig.ini');

        $stmt = $db->prepare("INSERT INTO user_reviews SET review_id = HEX(AES_ENCRYPT(:reviewCnt, '" . $ini['reviewKeyHash'] . "')), user_id = :userAccountID, movie_id = :movieID, review_content = :ReviewDescription, review_rating = :Rating");

        $binds = array(
            ":reviewCnt" => $ReviewCnt,
            ":userAccountID" => $userAccountID,
            ":movieID" => $movieID,
            ":ReviewDescription" => $ReviewDescription,
            ":Rating" => $Rating, 
        );

        if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
            $results = "Movie Added";
        }
    }

    function getReviews($id){
        global $db;
        
        $results = [];

        $stmt = $db->prepare("SELECT * FROM user_reviews WHERE movie_id = :movieID ORDER BY posted_date DESC");
        
        $binds = array(
            ":movieID" => $id,     
        );

        if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return ($results);
    }
?>