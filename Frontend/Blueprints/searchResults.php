<?php
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userAccountQueries.php");
    require ($_SERVER['DOCUMENT_ROOT'] . "/Backend/PHP-DB/userMovieQueries.php");

    $searchTxt = $_GET['searchTxt'];

    $searchResults = searchMovie($searchTxt);

    $resultHtmlStr = "";
?>

<?php

    foreach ($searchResults as $row)
    {
        
        $resultHtmlStr .= '<a id="searchItem" class="row no-margin align-items-center justify-content-center" href="https://www.jramosportfolio.com/Frontend/MoviePage/moviePage.php?id=' . $row['movie_id'] . '">';
        $resultHtmlStr .=    '<div id="searchImgContainer" class="col-md-auto">';
        $resultHtmlStr .=        '<div class="row justify-content-center">';
        $resultHtmlStr .=            '<div id="searchComponentMovieImg" class="col-auto no-pad">';
        $resultHtmlStr .=                '<img src="' . $row['cover_image'] . '" width=100px; height=150px;>'; //'<a href="moviePage.php?id="' . $row['movie_id'] . '"></a>';
        $resultHtmlStr .=            '</div>';
        $resultHtmlStr .=        '</div>';
        $resultHtmlStr .=    '</div>';

        $resultHtmlStr .=    '<div id="searchDetail" class="col-md-auto">';
        $resultHtmlStr .=        '<div id="searchComponenetHeader" class="row">';
        $resultHtmlStr .=            '<div id="searchMovieTitle" class="col-9 no-padR d-flex justify-content-start"><b>' . $row['title'] . '</b></div>';    

        $resultHtmlStr .=            '<div id="feedRating" class="col-3 d-flex justify-content-end">';
        
        $rating = getMovieRatingByID($row['movie_id']);
        
        if($rating != ""){ 
            $resultHtmlStr .= $rating  . "/5"; 
        } else
        { 
            $resultHtmlStr .= "N/A"; 
        }
        
        $resultHtmlStr .=            '</div>';
        $resultHtmlStr .=        '</div>';

        $resultHtmlStr .=        '<div id="searchMovieCreator" class="row">&nbsp;Creator : ' . getUserName($row["user_id"]) . '</div>';

        $resultHtmlStr .=        '<div id="searchMovieDescription" class="row">';
        $resultHtmlStr .=            '<textarea id="searchDescText" maxlength="5">';
                                        if(strlen($row['description']) <= 50){ $resultHtmlStr .= $row['description']; } else{ $resultHtmlStr .= substr($row['description'], 0, 50) . "..."; }               
        $resultHtmlStr .=            '</textarea>';
        $resultHtmlStr .=        '</div>';

        $resultHtmlStr .=        '<div id="feedDate" class="row d-flex justify-content-end">Date Posted: ' . substr($row['date_posted'], 0, 10) . '</div>';
        $resultHtmlStr .=    '</div>';
        $resultHtmlStr .= '</a>';
        
    }

    echo $resultHtmlStr;
?>