(function ($) {
    let topBrands = $('.top-brands');
    let brandCount = 1;
    if (topBrands.length === 0) {
        return false;
    }

    topBrands.each(function () {
        loadBrands(this);
    });

    function loadBrands(elem) {
        let url = $(elem).attr('data-brand-url');
        $.ajax({
            url: url,
            method: 'GET',
            type: 'GET',
            success: function (response) {
                if (response && response.length > 0) {
                    $(elem).empty();
                    for (let i = 0; i < response.length; i++) {
                        appendBrandHTML(elem, response[i]);
                    }
                    $(elem).parents('.brands-wrapper').removeClass('d-none');
                } else {
                    $(elem).parents('.brands-wrapper').remove();
                }
            },
            error: function (response) {
                toast('Could not load top brands.', 'error');
                $(elem).parents('.brands-wrapper').remove();
            }
        })
    }

    function appendBrandHTML(elem, brand) {
        brandCount++;
        console.log(brand);
        let brandProducts = thousandsCurrencyFormat(brand.product_count);
        let brand_name = brand.name;
        let brand_url = brand.url;
        let brand_follow_url = brand.follow_url;
        let brand_image = brand.image;
        if (brand_image == null) {
            brand_image = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48uXrfwAJmgPd+J22fAAAAABJRU5ErkJggg==';
        }
        $(elem).append(` 
           <div class="brand-card mr-3" style="opacity: 0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="brand-image-wrapper">
                    <a class="text-decoration-none" href="${brand_url}">
                        <img class="img-fluid rounded-circle border p-1" src="${brand_image}" alt="${brand_name}">
                    </a>
                </div>
                <div class="brand-card--meta pl-3">
                    <h4 class="brand-card--title m-0 text-truncate"><a class="text-decoration-none" href="${brand_url}">${brand_name}</a></h4>
                    <div class="brand-card--products mb-2 text-muted text-truncate">${brandProducts} Products</div>
                    <a href="${brand_follow_url}" class="btn btn-light btn-sm border follow-brand">Follow</a>
                </div>
            </div>
        </div>
`)
        let brand_elem = $(elem).find('.brand-card');
        setTimeout(function () {
            brand_elem.css('opacity', 1);
        }, (brandCount * 100))

    }
})(jQuery);
