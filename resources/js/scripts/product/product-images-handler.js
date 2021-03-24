function makeid(length) {
    let result = '';
    let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}


function previewImages($el) {
    let $preview = $('#previewImages');
    let $error = $('.file_error strong');
    $error.text('');
    if ($el.files) {
        // console.log('Please select at least 4 images for your property');
        $.each($el.files, function (i, file) {
            $error.text('');
            if (
                file.type === "image/jpeg" ||
                file.type === "image/jpg" ||
                file.type === "image/png"
            ) {
                if ($('.uploadedImage').length < 20) {
                    readAndPreview(i, file);
                } else {
                    $error.text('You cannot upload images more than 50');
                }
            } else {
                $error.text('We do not support this video or image file type.');
            }
        });

        // resetting file input
        $("#file").replaceWith($("#file").val('').clone(true));
    }

    function readAndPreview(i, file) {
        if (!/\.(jpe?g|png)$/i.test(file.name)) {
            $error.text(file.name + " is not an image");
            return;
        }
        let form_data = new FormData();
        form_data.append('file', file);
        // Adding property ID to upload file payload.
        form_data.append('product_id', $('#product_id').val());
        let image_random_id = makeid(10);
        $.ajax({
            url: $('#fileUrl').val(),
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
            },
            success: function (result) {
                console.log('result', result)
                console.log('result.error', result.error)
                console.log('!result.error', !result.error)
                if (!result.error) {
                    let imgElem = $('.' + image_random_id);
                    imgElem.find('.remove-image').attr('href', result.image_remove_path)
                    imgElem.find('.remove-image').show()
                    imgElem.find('.loader').remove();
                    imgElem.removeClass('loading');
                } else {
                    $error.text(result.message);
                }

            },
            error: function (result) {
                if (result.error) {
                    $error.text(result.message);
                }
            }
        });


        let reader = new FileReader();
        $(reader).on("load", function () {
            $preview.append(`
                <div class="uploadedImage loading ${image_random_id}">
                    <div class="loader"></div>
                    <img onerror="this.style.display = 'none'" src="${this.result}" alt="${file.name}" class="uploadedImage--img">
                    <a style="display:none" href="{{ route('images.delete', ['file' => $productImage['id']]) }}" class="remove-image close">&times</a>
                </div>
            `);
        });
        reader.readAsDataURL(file);
    }

}

$('#file').on('change', function (e) {
    if (this.files.length > 0) {
        previewImages(this);
    }
});

$(document).on('click', '.remove-image', function (e) {
    e.preventDefault();
    let self = $(this);
    $.ajax({
        url: self.attr('href'),
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        success: function (result) {
            self.parent().remove();
            console.log(result)
        },
        error: function (result) {
            if (result.error) {
                $error.text(result.message);
            }
        }
    });
});
