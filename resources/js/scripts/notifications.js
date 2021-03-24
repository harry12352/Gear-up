(function () {
    let isFetchingNewNotification = false; // so we don't send multiple requests when we doing infinite scroll on notifications

    function notificationHTML(data) {
        let unreadClass = "unread-notification";
        if (data['read']) {
            unreadClass = "";
        }

        $('.user-notification .dropdown-menu').append(`<a data-id="${data['id']}" class="dropdown-item notification ${unreadClass}" href="${data['url']}">
                                <div class="d-flex">
                                    <div class="notification--icon">
                                        <img src="${data['image_url']}">
                                    </div>
                                    <div class="notification--content ml-3">
                                        <div class="notification--title"><p>${data['message']}</p></div>
                                        <small class="text-muted notification--time">${data['time']}</small>
                                        <span data-url="${data['markAsReadURL']}" class="markSingleRead"></span>
                                    </div>
                                </div>
                            </a>`);
    }

    function showEmptyNotification() {
        $('.user-notification .dropdown-menu').prepend(`
            <div class="dropdown-item notification dropdown-item--simple">
                <div class="d-flex justify-content-center">
                    <p class="text-muted"><i>You don't have any notifications at the moment.</i></p>
                </div>
            </div>
            `);
    }

    function fetchUserNotifications() {
        if (isFetchingNewNotification) {
            return;
        }
        isFetchingNewNotification = true;
        let notificationPage = $('[data-notificationPage]').attr('data-notificationPage');
        let pageParam = '?page=';
        if (notificationPage > 0 && !isNaN(parseInt(notificationPage))) {
            pageParam += notificationPage;
        } else {
            pageParam += '1';
        }

        $.ajax({
            type: "GET",
            url: "/notifications" + pageParam,
            success: function (data) {
                let notifications = data.notifications;
                $('[data-notificationPage]').attr('data-notificationLastPage', data.lastPage);
                if (parseInt(notificationPage) === 1) {
                    $('.user-notification .dropdown-menu').empty();
                }

                if (notifications && notifications.length > 0) {
                    let haveUnreadNotifications = false;
                    for (let i = 0; i < notifications.length; i++) {
                        notificationHTML(notifications[i]);
                        if (notifications[i]['read'] === false) {
                            haveUnreadNotifications = true;
                        }
                    }
                    if (parseInt(notificationPage) === 1) {
                        $('.user-notification .dropdown-menu').prepend(`
                        <div class="dropdown-item notification dropdown-item--simple">
                            <div class="d-flex justify-content-between">
                                <p class="notification--header">Notifications</p>
                            </div>
                        </div>
                        `);
                    }
                    if (haveUnreadNotifications) {
                        $('.user-notification .dropdown-item--simple div').append(` <a href="javascript:void(0)" class="markAllRead">Mark all as read</a> `);
                        $('.user-notification').addClass('has-notifications');
                    } else {
                        $('.user-notification').removeClass('has-notifications');
                    }
                } else {
                    showEmptyNotification();
                }

                setTimeout(function () {
                    isFetchingNewNotification = false
                }, 3000);
            }
        });
    }

    if ($('.user-notification').length > 0) {
        fetchUserNotifications();
        setInterval(function () {
            let notificationPage = $('[data-notificationPage]');
            let pageNum = notificationPage.attr('data-notificationPage');
            if (pageNum > 0 && !isNaN(parseInt(pageNum)) && parseInt(pageNum) === 1) {
                fetchUserNotifications();
            }
        }, 5000);
    }

    function markUserNotificationsRead() {
        $.ajax({
            type: "GET",
            url: "/notifications/markReadAll",
            success: function () {
                fetchUserNotifications();
            }
        });
    }

    function markSingleUserNotificationsRead(url, elem) {
        $.ajax({
            type: "GET",
            url: url,
            success: function () {
                $(elem).removeClass('unread-notification');
                fetchUserNotifications();
            }
        });
    }

    function fetchMoreNotifications() {
        if (isFetchingNewNotification) {
            return;
        }
        let notificationPage = $('[data-notificationPage]');
        let pageNum = notificationPage.attr('data-notificationPage');
        let lastPageNum = notificationPage.attr('data-notificationLastPage');
        if (pageNum > 0 && !isNaN(parseInt(pageNum))) {
            pageNum = (parseInt(pageNum) + 1);
            if (pageNum > parseInt(lastPageNum)) {
                return;
            }
        } else {
            pageNum = 1;
        }
        notificationPage.attr('data-notificationPage', pageNum);
        fetchUserNotifications();
    }

    $(document).on('click', '.markAllRead', function (event) {
        event.preventDefault();
        event.stopPropagation();
        markUserNotificationsRead();
    });

    $(document).on('click', '.unread-notification', function () {
        let single_read_elem = $(this).find('.markSingleRead');
        markSingleUserNotificationsRead(single_read_elem.attr('data-url'), $(this));
    });
    $(document).on('click', '.markSingleRead', function (event) {
        event.preventDefault();
        event.stopPropagation();
        markSingleUserNotificationsRead($(this).attr('data-url'), $(this).parents('.notification'));
    });

    $('.user-notification .dropdown-menu').on('scroll', function () {
        if (($(this).scrollTop() + $(this).innerHeight()) >= ($(this)[0].scrollHeight)) {
            fetchMoreNotifications();
        }
    });

})();
