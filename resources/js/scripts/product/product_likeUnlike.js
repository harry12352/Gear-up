$(document).on('click', '.like-product', function (e) {
    e.preventDefault();
    let $self = $(this);
    let url = $self.attr('href');
    $self.addClass('loading');

   $.ajax({
       url: url,
       method: 'GET',
       type: 'GET',
       success: function (response) {

           if (!response.error){
               $self.removeClass('like-product');

               // Generating unlike link
               $self.addClass('unlike-product');
               url = url.replace("like", "unlike");
               $self.attr('href', url);
               $self.html(`<svg width="32px" height="30px" viewBox="0 0 32 30" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set-Filled" sketch:type="MSLayerGroup" transform="translate(-102.000000, -882.000000)" fill="#e31b23"> <path d="M126,882 C122.667,882 119.982,883.842 117.969,886.235 C116.013,883.76 113.333,882 110,882 C105.306,882 102,886.036 102,890.438 C102,892.799 102.967,894.499 104.026,896.097 L116.459,911.003 C117.854,912.312 118.118,912.312 119.513,911.003 L131.974,896.097 C133.22,894.499 134,892.799 134,890.438 C134,886.036 130.694,882 126,882" id="heart-like" sketch:type="MSShapeGroup"></path> </g> </g> </svg> Liked`);
           }else{
               toast('Could not like this product. ' + response.message, 'error');
           }
           $self.removeClass('loading');
       },
       error: function (response) {
           let response_msg = response.responseJSON.message;
           if (response_msg === "Unauthenticated.") {
               toast("You must be logged-in to like this product", 'error');
           } else {
               toast(response.responseJSON.message, 'error');
           }
           $self.removeClass('loading');
       }
   })
});


$(document).on('click', '.unlike-product', function (e) {
    e.preventDefault();
    let $self = $(this);
    let url = $self.attr('href');
    $self.addClass('loading');

    $.ajax({
        url: url,
        method: 'GET',
        type: 'GET',
        success: function (response) {

            if (!response.error){
                $self.removeClass('unlike-product');

                // Generating unlike link
                $self.addClass('like-product');
                url = url.replace("unlike", "like");
                $self.attr('href', url);
                $self.html(`<svg width="32px" height="31px" viewBox="0 0 32 31" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-100.000000, -880.000000)" fill="#000000"> <path d="M128,893.682 L116,908 L104,893.623 C102.565,891.629 102,890.282 102,888.438 C102,884.999 104.455,881.904 108,881.875 C110.916,881.851 114.222,884.829 116,887.074 C117.731,884.908 121.084,881.875 124,881.875 C127.451,881.875 130,884.999 130,888.438 C130,890.282 129.553,891.729 128,893.682 L128,893.682 Z M124,880 C120.667,880 118.145,881.956 116,884 C113.957,881.831 111.333,880 108,880 C103.306,880 100,884.036 100,888.438 C100,890.799 100.967,892.499 102.026,894.097 L114.459,909.003 C115.854,910.48 116.118,910.48 117.513,909.003 L129.974,894.097 C131.22,892.499 132,890.799 132,888.438 C132,884.036 128.694,880 124,880 L124,880 Z" id="heart-like" sketch:type="MSShapeGroup"></path> </g> </g> </svg> Like`);
            }else{
                toast('Could not like this product. ' + response.message, 'error');
            }
            $self.removeClass('loading');
        },
        error: function (response) {
            toast(response.responseJSON.message, 'error');
            $self.removeClass('loading');
        }
    })
});
