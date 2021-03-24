$('body').on('click', '.follow-category', function (event) {
    event.preventDefault();
    const self = $(this);
    let url = self.attr('href');
    self.addClass('loading');
    let category_name = self.parent().find('.category-title').text().trim();

    $.ajax({
        type: "GET",
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        complete: function (xhr, textStatus) {
            if (xhr.status === 200){
                self.removeClass('follow-category');

                // Generating unfollow link
                self.addClass('unfollow-category btn-outline-primary').removeClass('btn-primary');
                url = url.replace("follow", "unfollow");
                self.attr('href', url);
                self.html('Unfollow Category');
                if(category_name && category_name !== ""){
                    toast('You started following category <b>' + category_name + '</b>');
                }else{
                    toast('You now following this category');
                }
            }
            self.removeClass('loading');
        },
        error: function (response) {
            let response_msg = response.responseJSON.message;
            if (response_msg === "Unauthenticated.") {
                toast("You must be logged-in to follow this category", 'error');
            } else {
                toast(response.responseJSON.message, 'error');
            }
        }
    });

});


$('body').on('click', '.unfollow-category', function (event) {
    event.preventDefault();
    const self = $(this);
    let url = self.attr('href');
    self.addClass('loading');
    let category_name = self.parent().find('.category-title').text().trim();

    $.ajax({
        type: "GET",
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        complete: function (xhr, textStatus) {
            if (xhr.status === 200){
                self.removeClass('unfollow-category');

                // Generating unfollow link
                self.addClass('follow-category btn-primary').removeClass('btn-outline-primary');
                url = url.replace("unfollow", "follow");
                self.attr('href', url);
                self.html('Follow Category');
                if(category_name && category_name !== ""){
                    toast('You unfollowed category <b>' + category_name + '</b>');
                }else{
                    toast('You unfollowed this category');
                }
            }
            self.removeClass('loading');
        }
    });

});
