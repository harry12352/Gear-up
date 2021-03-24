function expandFilters() {
    $('.product-filter--expand').on('click', function(e){
        e.preventDefault();
        let filtersWrap = $('.card.product-filter .card-body > form > .row');
        filtersWrap.toggleClass('active');
    });
}
expandFilters();
