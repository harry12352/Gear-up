(function ($) {
    let singleProduct = $('.single-product');
    if (singleProduct.length === 0) {
        return false;
    }
    $.ajax({
        url: singleProduct.attr('data-recently'),
        method: 'GET',
        type: 'GET'
    });

})(jQuery);
