$(document).ready(function () {
    var movieCount = jQuery("[id=movieItem]").length; 
    var movieHiddenTracker = 1;


    $('#PrevPage').on('click', function () {
        if((movieHiddenTracker - 6) >= 0) {
            $(".movieItem" + String(movieHiddenTracker - 1)).show();
            $(".movieItem" + String(movieHiddenTracker - 2)).show();
            $(".movieItem" + String(movieHiddenTracker - 3)).show();
            $(".movieItem" + String(movieHiddenTracker - 4)).show();

            //Large display shows 6 and small shows 4 so hide/show based on how many are being shown by box sizing
            if ($(window).innerWidth() >= 1183.33)
            {
                $(".movieItem" + String(movieHiddenTracker - 5)).show();
                $(".movieItem" + String(movieHiddenTracker - 6)).show();
            
                movieHiddenTracker -= 2;
            }
            
            movieHiddenTracker -= 4;
        } else if (movieHiddenTracker > 1) {
            while (movieHiddenTracker > 1) {
                $(".movieItem" + String(movieHiddenTracker - 1)).show();
                movieHiddenTracker--;
            }
        }
    });

    $('#NextPage').on('click', function () {
        if((movieHiddenTracker + 6) <= movieCount) {
            $(".movieItem" + String(movieHiddenTracker)).hide();
            $(".movieItem" + String(movieHiddenTracker + 1)).hide();
            $(".movieItem" + String(movieHiddenTracker + 2)).hide();
            $(".movieItem" + String(movieHiddenTracker + 3)).hide();

            if ($(window).innerWidth() >= 1183.33) {
                $(".movieItem" + String(movieHiddenTracker + 4)).hide();
                $(".movieItem" + String(movieHiddenTracker + 5)).hide();
            
                movieHiddenTracker += 2;
            }

            movieHiddenTracker += 4;
        } else if ((movieHiddenTracker + 4) <= movieCount && $(window).innerWidth() < 1183.33) {
            $(".movieItem" + String(movieHiddenTracker)).hide();
            $(".movieItem" + String(movieHiddenTracker + 1)).hide();
            $(".movieItem" + String(movieHiddenTracker + 2)).hide();
            $(".movieItem" + String(movieHiddenTracker + 3)).hide();

            movieHiddenTracker += 4;
        }
    });
});