<link rel="stylesheet" href="/Frontend/CSS/pageHeader.css">
<script src="/Frontend/JavaScript/Nav/searchBarUpdate.js"></script>
<script src="/Frontend/JavaScript/Nav/sideBarCollapse.js"></script>


<div id="pageHeader" class="row header centerV no-marginL flex-nowrap">
    <nav id="logoContainer" class="col-auto transparent centerV no-padL navbar-expand-lg navbar-light bg-light">
        <div class="row flex-nowrap align-items-center">
            <div id="sidebarCollapseBtnContainer" class="col-auto no-padR">
                <a class="btn btn-primary" id="sidebarCollapseBtn"><i id="sidebarCollapseBtnIcon" class="fa-solid fa-bars"></i></a>
            </div>

            <a id="headerLogo" href="/Frontend/MoviePage/homePage.php" class="col-9"><img id='navLogo' class="text-center no-pad" src="/images/logo-icon.png"></a>
        </div>
    </nav>

    <div id="headerSearchContainer" class="center col-auto">
        <input id="headerSearch" name="headerSearch" type="text" placeholder="Search">

        <div id="headerSearchBox">
            
        </div>
    </div>

    <div id="loginContainer" class="centerV justify-content-center col-auto no-pad">
        <a href="<?php if(isset($_SESSION['user'])){echo "/Frontend/UserProfile/profilePage.php";}else{echo "/Frontend/Login-Signup/loginPage.php";} ?>"><img src="<?php if(isset($_SESSION['user'])){echo "/images/profile-icon-logged-in.png";}else{echo "/images/profile-icon-logged-out.png";} ?>" width="50px" height="50px"; ></a>
    </div>
</div>