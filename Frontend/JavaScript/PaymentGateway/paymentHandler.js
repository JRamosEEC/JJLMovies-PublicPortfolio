//Using the stripe API process a payment of a given amount (return true on successful payment)
function processPayment(price) {
    return new Promise((resolve, reject) => {
        var stripeHandler = StripeCheckout.configure({
            key: 'pk_test_51MhRKxCYuuuadTH066reYDFzVNCawpbr4gso8RnPjvSVYjUVt6SorrOFz9rdsY7jrGwLres30iDvOkf686D48BcX00NCTYaVc0',
            locale: 'en',
            name: 'Donation',
            token: function(token) {
                fetch('/processPayment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ stripeTokenID: token.id, chargeTotal: price })
                }).then(function(res) {
                    return res.json();
                }).then(function(message) {
                    resolve();
                }).catch(function(error) {
                    reject(new Error("Something went wrong"));
                });
            }
        });
    
        stripeHandler.open({
            amount: price
        });
    });
}