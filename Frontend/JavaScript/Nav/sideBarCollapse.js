$(document).ready(function () {
    //Sideabar and fade layer view functionality on both header btn and dynamic nav btn for open and close
    jQuery("[id=sidebarCollapseBtn]").on('click', function () {
        $('#sidebar').toggleClass('active');
        $('#fadeLayer').toggleClass('active');
    });

    $('#fadeLayer').on('click', function () {
        $('#sidebar').removeClass('active');
        $('#fadeLayer').removeClass('active');
    });
});