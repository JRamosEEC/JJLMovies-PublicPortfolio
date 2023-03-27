$(document).ready(function () {

    //Add an even listener for each share button which will use the name on the button to copy the link to clipboard
    jQuery("[id=shareBtn]").on('click', function () {
        var shareLink = $(this).attr('name');

        try {
            navigator.clipboard.writeText(shareLink);
            
            popupMessage("Copied to clipboard!")
        } catch (err) {
            popupMessage("Something went wrong!")
        }
    });
});