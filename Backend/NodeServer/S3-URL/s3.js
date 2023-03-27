const aws = require('aws-sdk');
const crypto = require('crypto');
const util =  require('util');

const randomBytes = util.promisify(crypto.randomBytes);

var bucketDetails = ini.parse(fs.readFileSync('./../../Config/s3config.ini', 'utf-8'));

const region = bucketDetails.region;
const bucketName = bucketDetails.bucketName;
const accessKeyId = bucketDetails.accessKeyId;
const secretAccessKey = bucketDetails.secretAccessKey;

const s3 = new aws.S3({
    region,
    accessKeyId,
    secretAccessKey,
    signatureVersion: 'v4'
});

async function generateUploadURL() {
    const rawBytes = await randomBytes(16)
    const imageName = rawBytes.toString('hex')
  
    const params = ({
      Bucket: bucketName,
      Key: imageName,
      Expires: 60
    })
    
    const uploadURL = await s3.getSignedUrlPromise('putObject', params)
    return uploadURL
}

exports.generateUploadURL = generateUploadURL;