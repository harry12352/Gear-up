function toast(message = '', type = 'success', dismissible = false, time = 3000) {
    if (type === "error") {
        type = 'danger';
    }
    if (type === "message") {
        type = 'info';
    }

    let toast_html = `<div class="toast toast-${type}">`;
    toast_html += `<div class="toast-message">${message}</div>`;
    if (dismissible) {
        toast_html += `<span class="close">×</span>`;
    }
    toast_html += `</div>`;

    $('body').append(toast_html);
    let toast_elem = $('.toast').last();

    setTimeout(function () {
        toast_elem.addClass('toast-show');

        setTimeout(function () {
            toast_elem.removeClass('toast-show');
            setTimeout(function () {
                toast_elem.remove();
            }, 600)
        }, time)
    }, 100)
}
