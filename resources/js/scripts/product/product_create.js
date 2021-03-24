$('#category').select2();
$('#brand').select2();
$('#size').select2();
$('#color').select2();

$('#product-form [type="submit"]').click(function (event) {
    let form = $(this).parents('form');
    if(!validateForm(form)) {
        event.preventDefault();
        event.stopPropagation();
    }
});
