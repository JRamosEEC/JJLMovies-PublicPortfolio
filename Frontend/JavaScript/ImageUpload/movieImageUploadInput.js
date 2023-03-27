$(document).ready(function () {
    checkImage($('#previewImg').prop('src'), function(){ $('#previewImg').show(); }, function(){ $('#previewImg').hide(); } );
});

const imageInput = document.querySelector("#movieFileUploadBtn")
    imageInput.addEventListener("change", async event => {
        const file = imageInput.files[0];

        const imageUrl = await fileUpload(file);

        $("#previewImg").attr('src', imageUrl);
        $("#imgSource").val(imageUrl);

        checkImage(imageUrl, function(){ $('#previewImg').show(); }, function(){ $('#previewImg').hide(); } );
});