<?php
    include (__DIR__ . "/dbConnection.php");
    
    function getNextMovieIDNum(){
        global $db;
        
        $results = 0;

        $ini = parse_ini_file( __DIR__ . '/../Config/hashkeyconfig.ini');

        $stmt = $db->prepare("SELECT CAST(AES_DECRYPT(UNHEX(movie_id), '" . $ini['movieKeyHash'] . "')+1 AS UNSIGNED) AS NUM FROM user_movies ORDER BY NUM DESC limit 0,1"); 

        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchColumn();      
        }
        
        return ($results);
    }

    function addMovie ($UserAccountID, $MovieTitle, $DatePosted, $MovieGenre, $MovieDescription, $CoverIMG)  {    
        global $db;

        $MovieCnt = getNextMovieIDNum();
    
        $results = "Not addded";

        $ini = parse_ini_file( __DIR__ . '/../Config/hashkeyconfig.ini');
    
        $stmt = $db->prepare("INSERT INTO user_movies SET movie_id = HEX(AES_ENCRYPT(:movieCnt, '" . $ini['movieKeyHash'] . "')), user_id = :UserAccountID, title = :MovieTitle, date_posted = :DatePosted, genre = :MovieGenre, description = :MovieDescription, cover_image = :CoverIMG");
    
        $binds = array(
            ":movieCnt" => $MovieCnt,
            ":UserAccountID" => $UserAccountID,
            ":MovieTitle" => $MovieTitle,
            ":DatePosted" => $DatePosted,
            ":MovieGenre" => $MovieGenre,
            ":MovieDescription" => $MovieDescription,       
            ":CoverIMG" => $CoverIMG,
        );
    
    
        if ($stmt->execute($binds) && $stmt->rowCount() > 0) {
            $results = "Movie Added";
        }
    
        return $results;
    }

    function getAllMovies() {
        global $db;
        
        $results = [];

        $stmt = $db->prepare("SELECT movie_id, user_id, title, date_posted, genre, description, cover_image FROM user_movies ORDER BY date_posted DESC"); 


        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
         
        return ($results);
    }

    function editMovieByID($MovieID, $UserAccountID, $MovieTitle, $MovieGenre, $MovieDescription, $CoverIMG){
        global $db; 
        
        $results = [];

        $stmt = $db->prepare("UPDATE user_movies SET title = :MovieTitle, genre = :MovieGenre, description = :MovieDescription, cover_image = :CoverIMG WHERE user_id = :UserAccountID AND movie_id = :MovieID");
        $stmt->bindvalue(':MovieTitle', $MovieTitle);
        $stmt->bindvalue(':MovieGenre', $MovieGenre);
        $stmt->bindvalue(':MovieDescription', $MovieDescription); 
        $stmt->bindvalue(':CoverIMG', $CoverIMG);
        $stmt->bindvalue(':UserAccountID', $UserAccountID);
        $stmt->bindvalue(':MovieID', $MovieID); 


        if($stmt->execute() && $stmt->rowCount()> 0) {
            $results = "Your movie has been edited!"; 
        }

        return ($results); 
    }

    function getTrendingMovies() {
        global $db;
        
        $results = [];

        $stmt = $db->prepare("SELECT DISTINCT user_movies.movie_id, user_movies.user_id, user_movies.title, user_movies.date_posted, user_movies.genre, user_movies.description, user_movies.cover_image FROM user_movies, user_reviews WHERE user_movies.movie_id = user_reviews.movie_id ORDER BY (SELECT CAST(AVG(review_rating) AS DECIMAL(10,1)) FROM user_reviews WHERE user_movies.movie_id = user_reviews.movie_id ) DESC LIMIT 8");

        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
         
        return ($results);
    }

    function getMoviesByUserID($id){
        global $db;
        
        $results = [];

        $stmt = $db->prepare("SELECT * FROM user_movies WHERE user_id = :UserAccountID ORDER BY date_posted DESC"); 
        
        $stmt->bindvalue(':UserAccountID', $id);


        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
         
        return ($results);
    }

    function getMovieRatingByID($id){
        global $db;
        
        $results = 0;

        $stmt = $db->prepare("SELECT CAST(AVG(review_rating) AS DECIMAL(10,1)) FROM user_reviews WHERE movie_id = :movieID"); 
        
        $binds = array(
            ":movieID" => $id,     
        );


        if ( $stmt->execute($binds) && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchColumn();
        }
        
        return ($results);
    }

    function getMovieByID($id){
        global $db;
        
        $results = [];

        $stmt = $db->prepare("SELECT * FROM user_movies WHERE movie_id = :MovieID"); 
        
        $stmt->bindvalue(':MovieID', $id);


        if ( $stmt->execute() && $stmt->rowCount() > 0 ) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);     
        }
         
        return ($results);
    }

    function deleteMovieByID ($UserAccountID, $MovieID) {
        global $db;
        
        $results = "Data was not deleted";
    
        $stmt = $db->prepare("DELETE FROM user_movies WHERE user_id = :UserAccountID AND movie_id = :MovieID");
        
        $stmt->bindValue(':UserAccountID', $UserAccountID);
        $stmt->bindValue(':MovieID', $MovieID);
            

        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $results = 'Data Deleted';
        }
        
        return ($results);
    }

    function searchMovie($MovieTitle){
        global $db;

        $binds = array();

        $results = array();

        $sql = "SELECT * FROM user_movies WHERE 0=0";

        if($MovieTitle != " "){
            $sql .= " AND title LIKE :MovieTitle LIMIT 8";
            
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':MovieTitle', "%" . $MovieTitle . "%");
        }
        else{
            $sql .= " LIMIT 8";

            $stmt->bindValue(':MovieTitle', "ZZZZZZZZZZZ");
            $stmt = $db->prepare($sql);
        }
        

        if($stmt->execute() && $stmt-> rowCount() > 0){
            $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return ($results);
    }
?>