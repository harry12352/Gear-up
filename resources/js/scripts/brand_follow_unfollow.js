$('body').on('click', '.follow-brand', function (event) {
    event.preventDefault();
    const self = $(this);
    let url = self.attr('href');
    self.addClass('loading');
    let brand_name = self.parent().find('.brand-title').text().trim();

    $.ajax({
        type: "GET",
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        complete: function (xhr, textStatus) {
            if (xhr.status === 200){
                self.removeClass('follow-brand');

                // Generating unfollow link
                self.addClass('unfollow-brand btn-outline-primary').removeClass('btn-primary');
                url = url.replace("follow", "unfollow");
                self.attr('href', url);
                self.html('Unfollow Brand');
                if(brand_name && brand_name !== ""){
                    toast('You started following brand <b>' + brand_name + '</b>');
                }else{
                    toast('You now following this brand');
                }
            }home
            self.removeClass('loading');
        },
        error: function (response) {
            let response_msg = response.responseJSON.message;
            if (response_msg === "Unauthenticated.") {
                toast("You must be logged-in to follow this brand", 'error');
            } else {
                toast(response.responseJSON.message, 'error');
            }
        }
    });

});


$('body').on('click', '.unfollow-brand', function (event) {
    event.preventDefault();
    const self = $(this);
    let url = self.attr('href');
    self.addClass('loading');
    let brand_name = self.parent().find('.brand-title').text().trim();

    $.ajax({
        type: "GET",
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        complete: function (xhr, textStatus) {
            if (xhr.status === 200){
                self.removeClass('unfollow-brand');

                // Generating unfollow link
                self.addClass('follow-brand btn-primary').removeClass('btn-outline-primary');
                url = url.replace("unfollow", "follow");
                self.attr('href', url);
                self.html('Follow Brand');
                if(brand_name && brand_name !== ""){
                    toast('You unfollowed brand <b>' + brand_name + '</b>');
                }else{
                    toast('You unfollowed this brand');
                }
            }
            self.removeClass('loading');
        }
    });

});
