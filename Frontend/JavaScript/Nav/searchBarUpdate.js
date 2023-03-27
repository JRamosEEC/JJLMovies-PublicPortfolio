function searchRequest() {
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
            var ajaxDisplay = document.getElementById('headerSearchBox');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
        }
    }

    ajaxRequest.open("GET", "/Frontend/Blueprints/searchResults.php?searchTxt=" + $('#headerSearch').val(), true);
    ajaxRequest.send(null); 
}

$(document).ready(function () {
    //Headersearch box view functionality
    $('#headerSearch').on('input', function () {
        if($('#headerSearch').val().length > 0)
        {
            $('#headerSearchBox').addClass('active');
            searchRequest();

            //Change fade layer to under the header and fade on search bar results
            $("#fadeLayer").css("z-index", '75');
            $('#fadeLayer').addClass('active');
        }
        else{
            $('#headerSearchBox').removeClass('active');

            //Revert Fade Layer
            $("#fadeLayer").css("z-index", '');
            $('#fadeLayer').removeClass('active');
        }
    });

    $('#headerSearch').on('focusin', function () {
        $('#headerSearchContainer').addClass('active');
        $('#logoContainer').addClass('active');

        if($('#headerSearch').val().length > 0)
        {
            $('#headerSearchBox').addClass('active');
            searchRequest();

            //Change fade layer to under the header and fade on search bar results
            $("#fadeLayer").css("z-index", '75');
            $('#fadeLayer').addClass('active');
        }
    });

    $('#headerSearch').on('focusout', function () { 
        if ($('#headerSearch').val() <= 0) {
            $('#headerSearchBox').removeClass('active');
            $('#headerSearchContainer').removeClass('active');
            $('#logoContainer').removeClass('active');

            //Revert Fade Layer
            $("#fadeLayer").css("z-index", '');
            $('#fadeLayer').removeClass('active');
        }
    });

    $('#fadeLayer').on('click', function () {
        $('#headerSearchBox').removeClass('active');
        $('#headerSearchContainer').removeClass('active');
        $('#logoContainer').removeClass('active');

        //Revert Fade Layer
        $("#fadeLayer").css("z-index", '');
        $('#fadeLayer').removeClass('active');
    });
});