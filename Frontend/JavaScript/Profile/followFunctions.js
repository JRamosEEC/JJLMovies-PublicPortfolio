function updateFollowers() {
    var ajaxRequest;  // The variable that makes Ajax possible!
    
    try {        
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }
    
    // Create a function that will receive data
    // sent from the server and will update
    // div section in the same page.
    ajaxRequest.onreadystatechange = function() {
        if(ajaxRequest.readyState == 4) {
            var userFollowers = document.getElementById('userFollowers');
            userFollowers.innerText = ajaxRequest.responseText;
        }
    }

    ajaxRequest.open("GET", "/Frontend/Blueprints/getFollowerCount.php/?username=" + $.trim($('#profileUsername').text()), true);
    ajaxRequest.send(null);
}

function submitFollow() {
    var ajaxRequest;  // The variable that makes Ajax possible!

    try {        
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) { 
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    // Create a function that will receive data
    // sent from the server and will update
    // div section in the same page.
    ajaxRequest.onreadystatechange = function() {

        if(ajaxRequest.readyState == 4) {
            var unfollowBtn = document.getElementById('profileLogout');
            
            unfollowBtn.innerHTML = ajaxRequest.responseText;

            $('#unfollowBtn').on('click', function () {
                submitUnfollow();
            });
        }
    }

    ajaxRequest.open("GET", "/Frontend/Blueprints/followUser.php", true);
    ajaxRequest.send(null);

    updateFollowers();
}

function submitUnfollow() {
    var ajaxRequest;  // The variable that makes Ajax possible!

    try {        
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e) {
        
        // Internet Explorer Browsers
        try {
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            
            try {
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }

    // Create a function that will receive data
    // sent from the server and will update
    // div section in the same page.
    ajaxRequest.onreadystatechange = function() {

        if(ajaxRequest.readyState == 4) {
            var followBtn = document.getElementById('profileLogout');
            
            followBtn.innerHTML = ajaxRequest.responseText;

            $('#followBtn').on('click', function () {
                submitFollow();
            });
        }
    }

    ajaxRequest.open("GET", "/Frontend/Blueprints/unfollowUser.php", true);
    ajaxRequest.send(null); 

    updateFollowers();
}

$(document).ready(function () {
    $('#followBtn').on('click', function () {
        submitFollow();
    });
    $('#unfollowBtn').on('click', function () {
        submitUnfollow();
    });
});