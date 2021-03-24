(function ($) {
    if ($('.productImages').length === 0) {
        return false;
    }
    $(".productImages--inner").lightSlider({
        mode: "slide",
        item: 1,
        useCSS: true,
        autoWidth: false,
        adaptiveHeight: true,
        cssEasing: 'ease',

        // Thumbs
        thumbItem: 5,
        pager: true,
        gallery: true,
        galleryMargin: 5,
        thumbMargin: 5,
        currentPagerPosition: 'middle',
    });


    // Product Description
    let productDescription = $('.productInfo--description');
    if (productDescription.height() > 132) {
        productDescription.find('p').addClass('hide-overflow');
        productDescription.find('a').removeClass('d-none');
        productDescription.find('a').click(function () {
            $(this).hide();
            productDescription.find('p').removeClass('hide-overflow');
        })
    }


    // Taking scrollbar to bottom of comments list
    function commentScrollBottom() {
        let comments_list = $('.comments-list .list-unstyled');
        if (comments_list.length > 0 && comments_list.find('li').length > 5) {
            comments_list = comments_list[0];
            comments_list.scrollTop = comments_list.scrollHeight;
        }
    }

    commentScrollBottom();
    // Delete a comment
    $(document).on('click', '#delete-comment', function (e) {
        e.preventDefault();
        let $self = $(this);
        let url = $(this).attr('href');
        $.ajax({
            url: url,
            success: function (data) {
                if (!data.error) {
                    toast('Your comment has been removed', 'success');
                    $self.parents('.single-comment').remove()
                } else {
                    toast('Your comment could not be deleted. ' + data.message, 'error');
                }
            },
            error: function () {
                toast('Something went wrong while deleting your comment', 'error');
            }
        });
    });
    // Submit new comment
    $('#comment-form').submit(function (e) {
        e.preventDefault();
        $form = $(this);
        $form.addClass('loading');
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function (data) {
                let comment_text = $('#comment-text').val();
                $('#comment-text').val('');
                let logged_in_user_img = $('.user-name').parent().find('img').attr('src');
                let logged_in_user = $('.user-name').text();
                let logged_in_user_url = $('#userProfileUrl').attr('href');

                let headerusername = $('#headerusername').text();
                let productusername = $('#productusername').text();
                let commentOwnerHtml = ``;
                if (headerusername === productusername) {
                    commentOwnerHtml = `<div class="badge align-middle badge-primary">Owner</div>`;
                } else {
                    commentOwnerHtml = `<div class="badge align-middle badge-info">YOU</div>`;
                }
                if ($)
                    if (!data.error) {
                        $('.comments-list ul').append(`
                        <li class="single-comment d-flex flex-wrap"> <div class="comment-user w-5"> <img class="rounded-circle border img-fluid img-cover w-45px h-45px" src="${logged_in_user_img}" alt="${logged_in_user}"> </div> <div class="comment-content"> <div class="comment-meta"> <a class="font-weight-bold small" href="${logged_in_user_url}"> ${logged_in_user} </a> ${commentOwnerHtml} </div> <div class="comment-text mt-1"><p>${comment_text}</p></div> </div> </li>
                    `);
                        commentScrollBottom();
                    } else {
                        toast('You comment could not be added. ' + data.message, 'error');
                    }
                $form.removeClass('loading');
            },
            error: function () {
                toast('Something went wrong while adding your comment', 'error');
                $form.removeClass('loading');
            }
        })
    });


    // REVIEWS
    // Taking scrollbar to bottom of comments list
    function reviewScrollBottom() {
        let reviews_list = $('.reviews-list .list-unstyled');
        if (reviews_list.length > 0 && reviews_list.find('li').length > 5) {
            reviews_list = reviews_list[0];
            reviews_list.scrollTop = reviews_list.scrollHeight;
        }
    }

    reviewScrollBottom();
    // Submit new comment
    $('#review-form').submit(function (e) {
        e.preventDefault();
        $form = $(this);
        $form.addClass('loading');
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            success: function (data) {
                let review_text = $('#review-text').val();
                let review_stars = parseInt($('#review-form [name="rating"]:checked').val());
                $('#review-text').val('');
                let reviewOwnerHtml = `<div class="badge align-middle badge-info">YOU</div>`;
                let profile_img = $('#profileDropdown img').attr('src');
                let profile_url = $('#userProfileUrl').attr('href');
                let profile_name = $('#profileDropdown .user-name').text();
                let reviewStarsHtml = '';
                for (let i = 0; i < review_stars; i++){
                    reviewStarsHtml += `<div class="rating__label"><i class="rating__icon rating__icon--star"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M 30.335938 12.546875 L 20.164063 11.472656 L 16 2.132813 L 11.835938 11.472656 L 1.664063 12.546875 L 9.261719 19.394531 L 7.140625 29.398438 L 16 24.289063 L 24.859375 29.398438 L 22.738281 19.394531 Z"></path></svg> </i></div>`
                }
                if (!data.error) {
                    $('.reviews-list ul div').remove();
                    $('.reviews-list ul').append(`
                        <li class="single-review d-flex flex-wrap"> <div class="review-user w-5"> <img class="rounded-circle border border-dark img-fluid img-cover w-45px h-45px" src="${profile_img}"> </div> <div class="review-content"> <div class="review-meta"> <a class="font-weight-bold text-light small" href="${profile_url}"> ${profile_name} </a> <div class="rating-group">${reviewStarsHtml}</div> ${reviewOwnerHtml} </div> <div class="review-text mt-1 mb-3"><p class="mb-0">${review_text}</p> </div> </div> </li>
                    `);
                    reviewScrollBottom();
                } else {
                    toast('You review could not be added. ' + data.message, 'error');
                }
                $form.removeClass('loading');
            },
            error: function () {
                toast('Something went wrong while adding your review', 'error');
                $form.removeClass('loading');
            }
        })
    });
})(jQuery);
