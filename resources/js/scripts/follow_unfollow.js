$('body').on('click', '.follow-user', function (event) {
    event.preventDefault();
    const self = $(this);
    let url = self.attr('href');
    self.addClass('loading');

    let user_name = self.attr('data-user-title');
    if(!user_name || user_name === '') {
        user_name = self.parents('.profile-card--meta').find('.profile-card--title').text().trim();
    }
    $.ajax({
        type: "POST",
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        complete: function (xhr, textStatus) {
            if (xhr.status === 200){
                self.removeClass('follow-user');

                // Generating unfollow link
                self.addClass('unfollow-user');
                url = url.replace("follow", "unfollow");
                self.attr('href', url);
                self.html('Unfollow');
                if(user_name && user_name !== ""){
                    toast('You started following <b>' + user_name + '</b>');
                }else{
                    toast('You now following this seller');
                }
            }
            self.removeClass('loading');
        }
    });

});


$('body').on('click', '.unfollow-user', function (event) {
    event.preventDefault();
    const self = $(this);
    let url = self.attr('href');
    self.addClass('loading');
    let user_name = self.attr('user-title');
    if(!user_name || user_name === '') {
        user_name = self.parents('.profile-card--meta').find('.profile-card--title').text().trim();
    }

    $.ajax({
        type: "POST",
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        complete: function (xhr, textStatus) {
            if (xhr.status === 200){
                self.removeClass('unfollow-user');

                // Generating unfollow link
                self.addClass('follow-user');
                url = url.replace("unfollow", "follow");
                self.attr('href', url);
                self.html('Follow');
                if(user_name && user_name !== ""){
                    toast('You unfollowed <b>' + user_name + '</b>');
                }else{
                    toast('You unfollowed this seller');
                }
            }
            self.removeClass('loading');
        }
    });

});
