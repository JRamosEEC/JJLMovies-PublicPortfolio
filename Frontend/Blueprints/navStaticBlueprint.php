<link rel="stylesheet" href="/Frontend/CSS/navStatic.css">

<nav id="staticSidebar">
    <ul class="list-unstyled components">
        <li>
            <a href="/Frontend/MoviePage/homePage.php" class="row <?php if(basename($_SERVER['PHP_SELF']) == 'homePage.php'){echo 'current-page';} ?> no-margin justify-content-center"><img id='navStaticIcon' class="text-center no-pad col" src="/images/home-icon.png"></a>
        </li>

        <li>
            <a href="/Frontend/MoviePage/highlightsPage.php" class="row <?php if(basename($_SERVER['PHP_SELF']) == 'highlightsPage.php'){echo 'current-page';} ?> no-margin justify-content-center"><img id='navStaticIcon' class="text-center no-pad col" src="/images/highlights-icon.png"></a>
        </li>

        <li>
            <a href="/Frontend/UserProfile/MoviePageCRUD.php?action=add" class="row <?php if(basename($_SERVER['PHP_SELF']) == 'MoviePageCRUD.php'){echo 'current-page';} ?> no-margin justify-content-center"><img id='navStaticIcon' class="text-center no-pad col" src="/images/addMovie-icon.png"></a>
        </li>
    </ul>
</nav>