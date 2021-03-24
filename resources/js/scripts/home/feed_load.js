(function ($) {
    let home_feed = $('.home-feed');
    let isFetchingNewFeed = false;
    let productCount = 1;
    if (home_feed.length === 0) {
        return false;
    }

    loadFeed(home_feed);

    function fetchMoreProducts() {
        if (isFetchingNewFeed) {
            return;
        }
        let notificationPage = $('[data-feedPage]');
        let pageNum = notificationPage.attr('data-feedPage');
        if (pageNum > 0 && !isNaN(parseInt(pageNum))) {
            pageNum = (parseInt(pageNum) + 1);
        } else {
            pageNum = 1;
        }
        notificationPage.attr('data-feedPage', pageNum);
        loadFeed(home_feed);
    }

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= ($(document).height() - 100)) {
            fetchMoreProducts();
        }
    });

    function loadFeed(elem) {
        if (isFetchingNewFeed) {
            return;
        }
        isFetchingNewFeed = true;
        let notificationPage = $('[data-feedPage]').attr('data-feedPage');
        let pageParam = '?page=';
        if (notificationPage > 0 && !isNaN(parseInt(notificationPage))) {
            pageParam += notificationPage;
        } else {
            pageParam += '1';
        }


        let url = $(elem).attr('data-feed-url') + pageParam;
        $.ajax({
            url: url,
            method: 'GET',
            type: 'GET',
            success: function (response) {
                let feedPage = $('[data-feedPage]').attr('data-feedPage');
                if (response && response.length > 0) {


                    if (parseInt(feedPage) === 1) {
                        $(elem).empty();
                    }
                    for (let i = 0; i < response.length; i++) {
                        appendFeedHTML(elem, response[i]);
                    }
                } else {

                    $('[data-feedPage]').attr('data-feedPage', parseInt(feedPage)-1);
                    if (parseInt(feedPage) === 1) {
                        $(elem).find('.product_feed_card').remove();
                        $(elem).find('.empty_feed').removeClass('d-none');
                    }
                }

                setTimeout(function () {
                    isFetchingNewFeed = false
                }, 3000);
            },
            error: function (response) {
                toast('Could not load your feed. Please try again', 'error');
                $(elem).find('.product_feed_card').remove();
                $(elem).find('.empty_feed').removeClass('d-none');

                setTimeout(function () {
                    isFetchingNewFeed = false
                }, 3000);
            }
        })
    }

    function appendFeedHTML(elem, product) {
        productCount++;
        let product_name = product.title;
        let product_size = product.size.name;
        let product_colors = product.colors;
        let product_price = product.price;
        let product_image = product.image;
        let product_url = product.url;
        let product_has_liked = product.has_liked;
        let product_like_url = product.like_url;
        let product_share_url = product.share_url;
        let product_categories = product.categories;
        let productShareUser = product.productShareUser;
        let product_user = product.product_user;
        let product_categories_html = ``;
        let product_color = '';

        if(product_colors.length > 0){
            product_color = `<span class="small">Color:</span> <span class="badge badge-light mr-2">${product_colors[0].name}</span>`;
        }

        let shareUserHTML = '';
        if (productShareUser.username) {
            // if my product coming because someone shared, ignore it
            if ($('#headerusername').text() === product_user.username) {
                return;
            }
            if ($('#headerusername').text() === productShareUser.username) {
                shareUserHTML = `<p>You shared this product</p>`;
            } else {
                let followUrlBadge = '';
                if (product_user.follow_url !== '') {
                    followUrlBadge = `<a href="${product_user.follow_url}" data-user-title="${product_user.name}" class="badge badge-primary follow-user">Follow</a>`;
                }
                shareUserHTML = `<p><a href="${productShareUser.profile_url}">${productShareUser.first_name} ${productShareUser.last_name}</a> shared this product of <a href="${product_user.profile_url}">${product_user.name}</a> ${followUrlBadge}</p>`;
            }
        }

        if (product_categories.length > 0) {
            for (let i = 0; i < product_categories.length; i++) {
                if (i > 2) {
                    break;
                }
                let category_name = product_categories[i].name;
                let category_url = product_categories[i].url;
                product_categories_html += `<a href="${category_url}" class="ml-2 d-inline-block small">${category_name}</a>`;
            }
        }

        let like_html = `<a href="${product_like_url}" class="like-product btn btn-light btn-sm"> <svg width="32px" height="31px" viewBox="0 0 32 31" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-100.000000, -880.000000)" fill="#000000"> <path d="M128,893.682 L116,908 L104,893.623 C102.565,891.629 102,890.282 102,888.438 C102,884.999 104.455,881.904 108,881.875 C110.916,881.851 114.222,884.829 116,887.074 C117.731,884.908 121.084,881.875 124,881.875 C127.451,881.875 130,884.999 130,888.438 C130,890.282 129.553,891.729 128,893.682 L128,893.682 Z M124,880 C120.667,880 118.145,881.956 116,884 C113.957,881.831 111.333,880 108,880 C103.306,880 100,884.036 100,888.438 C100,890.799 100.967,892.499 102.026,894.097 L114.459,909.003 C115.854,910.48 116.118,910.48 117.513,909.003 L129.974,894.097 C131.22,892.499 132,890.799 132,888.438 C132,884.036 128.694,880 124,880 L124,880 Z" id="heart-like" sketch:type="MSShapeGroup"></path> </g> </g> </svg> Like</a>`;
        if (product_has_liked) {
            like_html = `<a href="${product_like_url}" class="unlike-product btn btn-light btn-sm"> <svg width="32px" height="30px" viewBox="0 0 32 30" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set-Filled" sketch:type="MSLayerGroup" transform="translate(-102.000000, -882.000000)" fill="#e31b23"> <path d="M126,882 C122.667,882 119.982,883.842 117.969,886.235 C116.013,883.76 113.333,882 110,882 C105.306,882 102,886.036 102,890.438 C102,892.799 102.967,894.499 104.026,896.097 L116.459,911.003 C117.854,912.312 118.118,912.312 119.513,911.003 L131.974,896.097 C133.22,894.499 134,892.799 134,890.438 C134,886.036 130.694,882 126,882" id="heart-like" sketch:type="MSShapeGroup"></path> </g> </g> </svg> Liked</a>`;
        }

        $(elem).append(`
        <div class="product_feed_card card shadow-sm mb-3" style="opacity: 0">
        <div class="card-body">
            ${shareUserHTML}
            <div class="w-100 mb-2 bg-light rounded-sm">
            <a href="${product_url}" title="${product_name} image">
                <img class="w-100 product_image rounded-sm" src="${product_image}" alt="${product_name}">
            </a>
            </div>
            <div class="row align-items-start">
                <div class="col-6">
                    <div class="mb-1">
                        <a href="${product_url}" class="text-decoration-none">
                            <h2 class="product-title text-dark m-0 h5">${product_name}</h2>
                        </a>
                    </div>
                    <div class="text-muted mb-2">${product_color} <span class="small">Size:</span> <span class="badge badge-light">${product_size}</span></div>
                    <div class="text-dark mt-2 mb-0 h5 medium normal">${product_price}</div>
                </div>
                <div class="col-6 text-right product-card--meta border-none">
                    ${like_html}
                    <a href="${product_share_url}" class="share-product btn btn-light btn-sm">
                        <svg width="24px" height="26px" viewBox="0 0 24 26" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-312.000000, -726.000000)" fill="#000000"> <path d="M331,750 C329.343,750 328,748.657 328,747 C328,745.343 329.343,744 331,744 C332.657,744 334,745.343 334,747 C334,748.657 332.657,750 331,750 L331,750 Z M317,742 C315.343,742 314,740.657 314,739 C314,737.344 315.343,736 317,736 C318.657,736 320,737.344 320,739 C320,740.657 318.657,742 317,742 L317,742 Z M331,728 C332.657,728 334,729.343 334,731 C334,732.657 332.657,734 331,734 C329.343,734 328,732.657 328,731 C328,729.343 329.343,728 331,728 L331,728 Z M331,742 C329.23,742 327.685,742.925 326.796,744.312 L321.441,741.252 C321.787,740.572 322,739.814 322,739 C322,738.497 321.903,738.021 321.765,737.563 L327.336,734.38 C328.249,735.37 329.547,736 331,736 C333.762,736 336,733.762 336,731 C336,728.238 333.762,726 331,726 C328.238,726 326,728.238 326,731 C326,731.503 326.097,731.979 326.235,732.438 L320.664,735.62 C319.751,734.631 318.453,734 317,734 C314.238,734 312,736.238 312,739 C312,741.762 314.238,744 317,744 C318.14,744 319.179,743.604 320.02,742.962 L320,743 L326.055,746.46 C326.035,746.64 326,746.814 326,747 C326,749.762 328.238,752 331,752 C333.762,752 336,749.762 336,747 C336,744.238 333.762,742 331,742 L331,742 Z" id="share" sketch:type="MSShapeGroup"></path> </g> </g> </svg>
                        Share
                    </a>
                    <div class="row mt-1"> 
                        <div class="col-12 ml-auto text-right">
                            <div class="text-muted mb-2">${product_categories_html}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        `);

        let product_feed_card = $(elem).find('.product_feed_card').last();
        setTimeout(function () {
            product_feed_card.css('opacity', 1);
        }, (productCount * 100))

    }


})(jQuery);
