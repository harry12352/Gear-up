$(document).on('click', '.share-product', function (e) {
    e.preventDefault();
    let $self = $(this);
    let product_name = $self.parents('.card').find('.product-title').text();
    let url = $self.attr('href');
    $self.addClass('loading');

    $.ajax({
        url: url,
        method: 'GET',
        type: 'GET',
        success: function (response) {

            if (!response.error) {
                toast('<b>' + product_name + '</b> has been shared on your feed. ');
            } else {
                toast('Could not like this product. ' + response.message, 'error');
            }

            $self.removeClass('loading');
        },
        error: function (response) {
            let response_msg = response.responseJSON.message;
            if (response_msg === "Unauthenticated.") {
                toast("You must be logged-in to share this product", 'error');
            } else {
                toast(response.responseJSON.message, 'error');
            }
            $self.removeClass('loading');
        }
    })
});
