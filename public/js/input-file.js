
$(document).on('click', '#close-preview', function(){

    let imagePreview = $('.image-preview');

    $(imagePreview).popover('hide');

    $(imagePreview).bind('mouseover', function () {
        $('.image-preview').popover('show');
    });

    $(imagePreview).bind('mouseout', function () {
        $('.image-preview').popover('hide');
    });
});

$(function() {
    // keep button title
    let imagePreviewInputTitle = $(".image-preview-input-title").text();

    // Create the close button
    let closebtn = $('<button/>', {
        type:"button",
        text: 'x',
        id: 'close-preview',
        style: 'font-size: initial;',
    });

    closebtn.addClass("close float-right");

    // Set the popover default content
    $('.image-preview').popover({
        trigger:'manual',
        html:true,
        title: $(closebtn)[0].outerHTML,
        content: "",
        placement:'bottom'
    });

    // Clear event
    $('.image-preview-clear').click(function(){
        let imagePreview = $('.image-preview');

        $(imagePreview).attr("data-content","").popover('hide');
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.image-preview-input input:file').val("");
        $(".image-preview-input-title").text(imagePreviewInputTitle);

        // unbind hover
        $(imagePreview).unbind();
    });

    // Create the preview image
    $(".image-preview-input input:file").change(function (){
        let img = $('<img/>', {
            id: 'dynamic',
            width:250,
        });

        let file = this.files[0];
        let reader = new FileReader();

        let imagePreview = $('.image-preview');

        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);
            img.attr('src', e.target.result);
            $(imagePreview).attr("data-content",$(img)[0].outerHTML).popover("show");
            let popoverTimeout = setTimeout(function () {
                $(imagePreview).popover("hide");
            }, 4000);
        };
        reader.readAsDataURL(file);

        // unbind hover
        $(imagePreview).unbind();
    });
});