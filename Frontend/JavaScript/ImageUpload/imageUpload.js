//Takes a file and uploads it to an S3 Bucket with a secure url. Respond with a link to the server stored file.
async function fileUpload(file) {
    // get secure url from our server
    const { url } = await fetch("https://www.jramosportfolio.com/s3Url", { headers: {"x-loc" : "x-req"} }).then(res => res.json());

    // post the image direclty to the s3 bucket
    await fetch(url, {
        method: "PUT",
        headers: {
        "Content-Type": "multipart/form-data"
        },
        body: file
    });

    const imageUrl = url.split('?')[0];

    return imageUrl;
}