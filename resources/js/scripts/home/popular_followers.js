(function ($) {
    let popularFollowers = $('.popular-followers');
    let peopleCount = 1;
    if (popularFollowers.length === 0) {
        return false;
    }

    popularFollowers.each(function () {
        loadPeople(this);
    });

    function loadPeople(elem) {
        let url = $(elem).attr('data-popular-url');
        $.ajax({
            url: url,
            method: 'GET',
            type: 'GET',
            success: function (response) {
                if (response && response.length > 0) {
                    $(elem).empty();
                    for (let i = 0; i < response.length; i++) {
                        appendPeopleHTML(elem, response[i].follower);
                    }
                    $(elem).parents('.popular-followers-wrapper').removeClass('d-none');
                } else {
                    $(elem).parents('.popular-followers-wrapper').remove();
                }
            },
            error: function (response) {
                console.log('response', response)
                if(!(response.responseJSON && response.responseJSON.error === true)){
                    toast('Could not load popular followers.', 'error');
                }
                $(elem).parents('.popular-followers-wrapper').remove();
            }
        })
    }

    function appendPeopleHTML(elem, people) {
        peopleCount++;
        let people_name = people.first_name + ' ' + people.last_name;
        let people_username = people.username;
        let people_image = people.profile_image_url;
        let people_url = people.profile_url;
        let people_follow_url = people.follow_url;
        let people_image_html = `<img width="200" height="200" class="border img-fluid rounded-circle" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P48fPXfwAJwwPruND9lQAAAABJRU5ErkJggg==" alt="${people_name}">`;

        if (people_image) {
            people_image_html = `<img class="img-fluid rounded-circle" src="${people_image}" alt="${people_name}">`
        }
        $(elem).append(`
            <div class="people-card user-select-none w-100" style="opacity: 0">
                <div class="profile-card profile-card--following">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="profile-image-wrapper">
                            <a class="text-decoration-none" href="${people_url}">
                            ${people_image_html}
                            </a>
                        </div>
                        <div class="profile-card--meta w-100 ml-3">
                            <h4 class="profile-card--title m-0 text-truncate"><a class="text-decoration-none" href="${people_url}">${people_name}</a></h4>
                            <div class="profile-card--username mb-2 text-muted text-truncate">@${people_username}</div>
                        </div>
                    </div>  
                </div>
            </div>
        `);

        let people_elem = $(elem).find('.people-card');
        people_elem.prev().addClass('mb-3');
        setTimeout(function () {
            people_elem.css('opacity', 1);
        }, (peopleCount * 100))

    }
})(jQuery);
