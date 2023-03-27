//Write a function that can be called at anytime to activate the popup message with a given message
async function popupMessage(msg) {
    $("#notificationText").text(msg);

    //Adding the active class will show the popup on screen
    $("#notificationContainer").addClass('active')

    //Remove it after 3 seconds
    setTimeout(function (){
        $("#notificationContainer").removeClass('active')               
    }, 3000);
}