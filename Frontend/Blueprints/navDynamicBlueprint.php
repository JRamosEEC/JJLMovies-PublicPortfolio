<link rel="stylesheet" href="/Frontend/CSS/navDynamic.css">

<script defer src ="https://checkout.stripe.com/v2/checkout.js"></script>
<script defer src ="/Frontend/JavaScript/PaymentGateway/paymentHandler.js"></script>
<script defer src ="/Frontend/JavaScript/PaymentGateway/donationProcessor.js"></script>

<div id="fadeLayer"></div>

<nav id="sidebar">
    <div id="sidebarHeader" class="row no-margin">
        <div id="sidebarCollapseBtnContainer" class="col-auto no-margin no-pad">
            <a id="sidebarCollapseBtn" class="btn btn-primary"><i id="sidebarCollapseBtnIcon" class="fa-solid fa-bars"></i></a>
        </div>

        <a href="/Frontend/MoviePage/homePage.php" class="col-9 no-margin"><img id='navLogo' class="text-center no-pad col" src="/images/logo-icon.png"></a>
    </div>

    <ul id="sidebarNavList" class="list-unstyled">
        <li>
            <a href="/Frontend/MoviePage/homePage.php" class="row <?php if(basename($_SERVER['PHP_SELF']) == 'homePage.php'){echo 'current-page';} ?> no-marginR"><img id='navIcon' class="text-center no-pad col" src="/images/home-icon.png"><p class="col">Home</p><img id='navArrow' class="text-center col" src="/images/right-arrow.png"></a>
        </li>

        <li>
            <a href="/Frontend/MoviePage/highlightsPage.php" class="row <?php if(basename($_SERVER['PHP_SELF']) == 'highlightsPage.php'){echo 'current-page';} ?> no-marginR"><img id='navIcon' class="text-center no-pad col" src="/images/highlights-icon.png"><p class="col">Highlights</p><img id='navArrow' class="text-center col" src="/images/right-arrow.png"></a>
        </li>

        <li>
            <a href="/Frontend/UserProfile/MoviePageCRUD.php?action=add" class="row <?php if(basename($_SERVER['PHP_SELF']) == 'MoviePageCRUD.php'){echo 'current-page';} ?> no-marginR"><img id='navIcon' class="text-center no-pad col" src="/images/addMovie-icon.png"><p class="col">Add Movie</p><img id='navArrow' class="text-center col" src="/images/right-arrow.png"></a>
        </li>
    </ul>

    <div id="donateContainer" class="row no-margin d-flex justify-content-center">
        <p id="donationFeedback">Help us out with a donation!<br>Test Mode<br>(see readme for dummy card info)</p>
        <input id="donationTxt" type="number" min="1" max="9999" step=".01" placeholder="Amount">
        <a id="donateBtn" class="btn btn-primary">Donate</a>
    </div>
</nav>