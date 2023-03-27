//Payment Gateway API Server ---------
const express = require('express');
const cors = require('cors');

//Parser Functions
const bodyParser = require('body-parser');
var fs = require('fs');
var ini = require('ini');

//Parse the config for stripe keys
var serverBuild = ini.parse(fs.readFileSync('./../Config/serverbuild.ini', 'utf-8'));
var paymentConfig = ini.parse(fs.readFileSync('./../Config/stripeconfig.ini', 'utf-8'));

//Store the proper payment keys to variable (test keys or live keys depending on the server build)
const stripeSecretKey = serverBuild.serverbuild == 'production' ? 
    paymentConfig.keys.liveprivatekey : paymentConfig.keys.testprivatekey;

const stripe = require('stripe')(stripeSecretKey);

const paymentAPI = express();
const paymentPort = 3003;

paymentAPI.use(cors({ origin: ['https://www.jramosportfolio.com'] }));

paymentAPI.use(bodyParser.urlencoded({ extended: false }));
paymentAPI.use(bodyParser.json());

paymentAPI.post('/processPayment', async (req, res) => {
    stripe.charges.create({
        amount: req.body['chargeTotal'],
        source: req.body['stripeTokenID'],
        currency: 'usd'
    }).then(function() {
        res.json({ message: 'Success' });
    }).catch(function() {
        res.status(500).end()
    });
});
  
paymentAPI.listen(paymentPort, () => console.log(`listening on port ${paymentPort}`));