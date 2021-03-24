(function () {

    // Upload files
    let $zone = $('.file-upload-zone');
    $zone.find('.btn').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('#product-form').find('[type="file"]').trigger('click')
    });
    $zone.find('[type="file"]').click(function (e) {
        e.stopPropagation();
    });
    $zone.click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('#product-form').find('[type="file"]').trigger('click')
    });
    let isAdvancedUpload = function () {
        let div = document.createElement('div');
        return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
    }();

    if (isAdvancedUpload) {

        let droppedFiles = false;
        $zone.on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
        }).on('dragover dragenter', function () {
            $zone.addClass('is-dragover');
        }).on('dragleave dragend drop', function () {
            $zone.removeClass('is-dragover');
        }).on('drop', function (e) {
            droppedFiles = e.originalEvent.dataTransfer.files;
            $zone.find('input[type="file"]').prop('files', droppedFiles);
            $zone.find('input[type="file"]').trigger('change')

        });
    }

    $('.choose-draft').click(function (e) {
        e.preventDefault();
        $('#status').val('drafted');
        $('#product-form').submit();
    });

    $('.choose-publish').click(function (e) {
        e.preventDefault();
        $('#status').val('published');
        $('#product-form').submit();
    });

})();
