$('.auth-wrap form [type="submit"]').click(function (event) {
    let form = $(this).parents('form');
    if(!validateForm(form)) {
        event.preventDefault();
        event.stopPropagation();
    }
});
