(function ($) {
    let recentlyViewed = $('.recently-viewed');
    let productCount = 1;
    if (recentlyViewed.length === 0) {
        return false;
    }

    recentlyViewed.each(function () {
        loadPeople(this);
    });

    function loadPeople(elem) {
        let url = $(elem).attr('data-recentlyViewed-url');
        $.ajax({
            url: url,
            method: 'GET',
            type: 'GET',
            success: function (response) {
                if (response && response.length > 0) {
                    $(elem).empty();
                    for (let i = 0; i < response.length; i++) {
                        appendProductHTML(elem, response[i]);
                    }
                    $(elem).parents('.recently-viewed-wrapper ').removeClass('d-none');
                } else {
                    $(elem).parents('.recently-viewed-wrapper ').remove();
                }
            },
            error: function (response) {
                toast('Could not load your recently viewed products.', 'error');
                $(elem).parents('.recently-viewed-wrapper ').remove();
            }
        })
    }

    function appendProductHTML(elem, product) {
        productCount++;
        let product_name = product.title;
        let product_image = product.image;
        let product_url = product.url;
        let product_price = product.price;

        $(elem).append(`
           <div class="product_feed_card w-100 card border-0 mb-3">
                <div class="w-100 mb-2 bg-light rounded-sm">
                <a href="${product_url}" title="${product_name} image">
                    <img class="w-100 product_image rounded-sm" src="${product_image}" alt="${product_name}">
                </a>
            </div>
                <div class="mt-0 rounded-sm d-inline-block">
                <a href="${product_url}" class="text-decoration-none">
                    <h2 class="product-title text-dark m-0 h5">${product_name}</h2>
                </a>
                </div>
                <div class="text-dark mt-2 mb-3 h6 medium normal">${product_price}</div>
            </div>
        `);

        let people_elem = $(elem).find('.people-card');
        people_elem.prev().addClass('mb-3');
        setTimeout(function () {
            people_elem.css('opacity', 1);
        }, (productCount * 100))

    }
})(jQuery);
