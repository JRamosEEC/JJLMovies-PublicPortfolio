$(document).ready(function () {
    $("#donateBtn").on('click', function () {
        if($('#donationTxt').hasClass('active')) {
            const donationAmt = parseFloat($('#donationTxt').val()).toFixed(2) * 100;

            if(donationAmt >= 100 && donationAmt <= 999900) {
                processPayment(donationAmt).then(() => {
                    $('#donationTxt').val('');
                    $('#donationFeedback').text('Thank you for the generous donation! <3');
                }).catch((error) => {
                    $('#donationTxt').val('');
                    $('#donationFeedback').text('Sorry, something went wrong!');
                });

                $('#donationTxt').toggleClass('active');
            } else if (isNaN(donationAmt)) {
                $('#donationTxt').toggleClass('active');

                $('#donationTxt').val('');
                $('#donationFeedback').text('Help us out with a donation!');
            } else {
                $('#donationTxt').val('');
                $('#donationFeedback').text('Invalid amount! Please enter an amount within $1-$9999');
            }  
        } else {
            $('#donationTxt').toggleClass('active');
            $('#donationFeedback').text('Please enter an amount!');
        }
    });
});
