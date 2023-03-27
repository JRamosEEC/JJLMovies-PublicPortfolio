function changeUserPriviliges(change) {
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
            var userRole = document.getElementById('adminPanelRole');
            userRole.innerText = ajaxRequest.responseText;
        }
    }

    if (change == "admin") {
        ajaxRequest.open("GET", "/Frontend/Admin_Functions/elevateUser.php/?username=" + $.trim($('#profileUsername').text()), true);
    }
    else {
        ajaxRequest.open("GET", "/Frontend/Admin_Functions/downgradeUser.php/?username=" + $.trim($('#profileUsername').text()), true);
    }
    
    ajaxRequest.send(null);
}

$(document).ready(function () {
    $('#changeRoleBtn').on('click', function () {
        changeUserPriviliges($('#userRoleSelect').val());
    });
    
    $('#deleteUserBtn').on('click', function () {
        window.location.href = "http://www.jramosportfolio.com/Frontend/Admin_Functions/deleteUser.php?username=" + $.trim($('#profileUsername').text());
    });
});