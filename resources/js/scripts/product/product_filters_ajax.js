function ajax_filters() {
    let form  = $('.product-filter form');
    $('.filtered_products').addClass('loading');
    console.log(form.serialize());

    setTimeout(function(){
        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            method: 'GET',
            success: function(response){
                filtered_products_list(response);
                $('.filtered_products').removeClass('loading');
            },
            error: function(err){
                // console.log(err);
                $('.filtered_products').removeClass('loading');
            }
        });
    }, 1000);
}
$('.product-filter form select, .product-filter form input').on('change', function(){
    ajax_filters();
});

function filtered_products_list(response){
    let data = response.data;
    console.log(response.data);
    let current_user_id = $('input.userId').length !== 0 ? $('input.userId').val() : false;
    let like_text = 'Like';
    let like_class = 'unlike-product';
    let like_icon = '<svg width="32px" height="31px" viewBox="0 0 32 31" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-100.000000, -880.000000)" fill="#000000"> <path d="M128,893.682 L116,908 L104,893.623 C102.565,891.629 102,890.282 102,888.438 C102,884.999 104.455,881.904 108,881.875 C110.916,881.851 114.222,884.829 116,887.074 C117.731,884.908 121.084,881.875 124,881.875 C127.451,881.875 130,884.999 130,888.438 C130,890.282 129.553,891.729 128,893.682 L128,893.682 Z M124,880 C120.667,880 118.145,881.956 116,884 C113.957,881.831 111.333,880 108,880 C103.306,880 100,884.036 100,888.438 C100,890.799 100.967,892.499 102.026,894.097 L114.459,909.003 C115.854,910.48 116.118,910.48 117.513,909.003 L129.974,894.097 C131.22,892.499 132,890.799 132,888.438 C132,884.036 128.694,880 124,880 L124,880 Z" id="heart-like" sketch:type="MSShapeGroup"></path> </g> </g> </svg>';
    $('.filtered_products > div').not('.add_new_product_card').remove();
    for(let i=0; i < data.length; i++){
        if(current_user_id) {
            for(let num=0; num < data[i].likes.length; num++) {
                if (data[i].likes[num].user_id == current_user_id) {
                    like_text = 'Liked';
                    like_class = 'like-product';
                    like_icon = '<svg width="32px" height="30px" viewBox="0 0 32 30" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set-Filled" sketch:type="MSLayerGroup" transform="translate(-102.000000, -882.000000)" fill="#e31b23"> <path d="M126,882 C122.667,882 119.982,883.842 117.969,886.235 C116.013,883.76 113.333,882 110,882 C105.306,882 102,886.036 102,890.438 C102,892.799 102.967,894.499 104.026,896.097 L116.459,911.003 C117.854,912.312 118.118,912.312 119.513,911.003 L131.974,896.097 C133.22,894.499 134,892.799 134,890.438 C134,886.036 130.694,882 126,882" id="heart-like" sketch:type="MSShapeGroup"></path> </g> </g> </svg>';
                }
            }
        }
        $('.filtered_products').append(`
            <div class="col-12 mb-3 col-lg-3 col-md-4">
                <div class="card product-card h-100 position-relative">
                    <a href="${data[i].url}" class="text-decoration-none">
                        <div class="product-card--image">
                            <img src="${data[i].image}" class="card-img-top" alt="${data[i].title}">
                        </div>
                    </a>
                    <div class="card-body p-3">
                        <h5 class="card-title product-title product-card--title">
                            <a href="${data[i].url}" class="text-decoration-none">
                                ${data[i].title}
                            </a>
                        </h5>
                        <p class="product-card--price font-weight-bold">
                            $${data[i].price}
                        </p>
                        <p class="product-card--meta m-0 pt-3 border-top">
                            <a href="${data[i].like_url}" class="${like_class} btn btn-light btn-sm">
                                ${like_icon}
                                ${like_text}
                            </a>
                            <a href="${data[i].share_url}" class="share-product float-right btn btn-light btn-sm">
                                <svg width="24px" height="26px" viewBox="0 0 24 26" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
                                        <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-312.000000, -726.000000)" fill="#000000">
                                            <path d="M331,750 C329.343,750 328,748.657 328,747 C328,745.343 329.343,744 331,744 C332.657,744 334,745.343 334,747 C334,748.657 332.657,750 331,750 L331,750 Z M317,742 C315.343,742 314,740.657 314,739 C314,737.344 315.343,736 317,736 C318.657,736 320,737.344 320,739 C320,740.657 318.657,742 317,742 L317,742 Z M331,728 C332.657,728 334,729.343 334,731 C334,732.657 332.657,734 331,734 C329.343,734 328,732.657 328,731 C328,729.343 329.343,728 331,728 L331,728 Z M331,742 C329.23,742 327.685,742.925 326.796,744.312 L321.441,741.252 C321.787,740.572 322,739.814 322,739 C322,738.497 321.903,738.021 321.765,737.563 L327.336,734.38 C328.249,735.37 329.547,736 331,736 C333.762,736 336,733.762 336,731 C336,728.238 333.762,726 331,726 C328.238,726 326,728.238 326,731 C326,731.503 326.097,731.979 326.235,732.438 L320.664,735.62 C319.751,734.631 318.453,734 317,734 C314.238,734 312,736.238 312,739 C312,741.762 314.238,744 317,744 C318.14,744 319.179,743.604 320.02,742.962 L320,743 L326.055,746.46 C326.035,746.64 326,746.814 326,747 C326,749.762 328.238,752 331,752 C333.762,752 336,749.762 336,747 C336,744.238 333.762,742 331,742 L331,742 Z" id="share" sketch:type="MSShapeGroup"></path>
                                        </g>
                                    </g>
                                </svg>
                                Share
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        `);
    }
}