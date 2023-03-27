//Image Upload URL API Server ---------
const express = require('express');
const cors = require('cors');

const S3 = require('./S3-URL/s3.js');

const UrlAPI = express();
const UrlPort = 3000;

UrlAPI.use(cors({ origin: ['https://www.jramosportfolio.com'] }));
UrlAPI.use(express.static('front'));

UrlAPI.get('/s3Url', async (req, res) => {
  if (req.get('x-loc') == 'x-req') {
    const url = await S3.generateUploadURL();
    res.send({url});
  }
  else {
    res.writeHead(301, {
      Location: `https://jramosportfolio.com`
    }).end();
  }
});

UrlAPI.listen(UrlPort, () => console.log(`listening on port ${UrlPort}`));