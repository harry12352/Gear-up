(function ($) {
    // Void if element doesn't exist
    if ($('.product-filter').length === 0) {
        return false;
    }

    function removeItem(arr, value) {
        let index = arr.indexOf(value);
        if (index > -1) {
            arr.splice(index, 1);
        } 
        return arr;
    }

    function fixDropdownPosition(elem) {
        let results = $('#select2-' + $(elem).attr('id') + '-results').parents('.select2-container');
        let topOffset = $(elem).offset().top + $(elem).next('.select2').height();
        results.css('top', topOffset);
    }

    // All dropdown as searchable
    let selectDropdown = $('.product-filter select');
    selectDropdown.select2({
        width: '100%',
        closeOnSelect: false
    });
    // clearing selected
    selectDropdown.each(function () {
        let name = $(this).attr('data-name');
        $(this).next().find('.select2-selection').html('<span class="filter_attr">Select ' + name + '</span>')
    });

    selectDropdown.on('select2:select', function (e) {
        let $self = $(this);
        let isMultiple = !!$self.attr('multiple');
        let selectedOption = e.params.data;
        let name = $self.attr('data-name');
        let singularName = name;
        // removing "s" if plural name
        if (name.charAt(name.length - 1) === 's') {
            singularName = name.substr(0, name.length - 1);
        }

        let elemSelect2 = $self.next('.select2');
        elemSelect2.find('.select2-selection').html('<span class="filter_attr">Select ' + name + '</span>');
        if (isMultiple && $self.val().length > 1 && $self.val().includes('all')) {
            let values = $self.val();
            removeItem(values, "all");
            $self.val(values).trigger("change");
            let results = $('#select2-' + $self.attr('id') + '-results');
            results.find('.select2-results__option').first().removeClass('select2-results__option--selected');
        }
        if (selectedOption.id === "all" && isMultiple) {
            $self.val(['all']).trigger("change");
            let results = $('#select2-' + $self.attr('id') + '-results');
            $('.active-filters [data-select="' + name + '"]').remove();
            results.find('.select2-results__option').removeClass('select2-results__option--selected').first().addClass('select2-results__option--selected');
        } else if (!isMultiple && selectedOption.id === "all") {
            $('.active-filters [data-select="' + name + '"]').remove();
        } else {
            if (!isMultiple) {
                $('.active-filters [data-select="' + name + '"]').remove();
            }
            $('.active-filters').append(`<span class="d-inline-block border rounded px-2 py-1 mr-2 mb-2" data-select="${name}" data-selected="${selectedOption.id}"><span class="font-weight-bold text-muted">${singularName}:</span> ${selectedOption.text} <span class="ml-2 close align-middle">&times;</span></span>`)
        }

        // animation of positioning
        if ($('.active-filters>span').length > 0) {
            $('.active-filters').css('max-height', $('.active-filters')[0].scrollHeight + 30);
        }else{
            $('.active-filters').css('max-height', 0);
        }
        if ($('.active-filters>span').length === 0 || $('.active-filters>span').length === 1) {
            let positionInterval = setInterval(function () {
                console.log("interval");
                fixDropdownPosition($self)
            }, 10);
            setTimeout(function () {
                clearInterval(positionInterval);
            }, 500)
        } else {
            fixDropdownPosition($self)
        }
    });

    selectDropdown.on('select2:unselect', function (e) {
        let isMultiple = !!$(this).attr('multiple');
        let selectedOption = e.params.data;
        let name = $(this).attr('data-name');
        let elemSelect2 = $(this).next('.select2');
        elemSelect2.find('.select2-selection').html('<span class="filter_attr">Select ' + name + '</span>');
        $('.active-filters [data-selected="' + selectedOption.id + '"]').remove();
        if (isMultiple && ((selectedOption.id === "all" && $(this).val().length === 0) || $(this).val().length === 0)) {
            $(this).val(['all']).trigger("change");
            let results = $('#select2-' + $(this).attr('id') + '-results');
            results.find('.select2-results__option').removeClass('select2-results__option--selected').first().addClass('select2-results__option--selected');
        }
        fixDropdownPosition(this);
    });


    // close active filter
    $(document).on('click', '.active-filters>span', function () {
        let selectElem = $('[data-name="' + $(this).attr('data-select') + '"]');
        let selectedID = $(this).attr('data-selected');
        let isMultiple = !!selectElem.attr('multiple');
        if (isMultiple) {
            let values = selectElem.val();
            removeItem(values, selectedID);
            selectElem.val(values).trigger("change");
        } else {
            selectElem.val('all')
        }
        $(this).remove();
    })

})(jQuery);
