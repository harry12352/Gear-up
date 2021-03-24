function validateInput(element, isForm = false, forceRequired = false) {
    const elem = $(element);
    let elemParent = elem.parents('.form-item');

    elem.parent().find('.validation-error').remove();

    // checking if is-invalid class is from laravel or JS. if laravel, let it stay
    if (elem.next().hasClass('validation-error') || (!elem.next().hasClass('invalid-feedback')) || elem.next().length === 0) {
        elem.removeClass('is-invalid');
    }
    elem.removeClass('is-valid');
    elemParent.removeClass('input-error');

    if (elem.length < 0 || elem.attr('required') === undefined) {
        if (forceRequired === false) {
            return
        }
    }
    // sometimes input is not in .form-item
    if (elemParent.length === 0) {
        elemParent = elem.parent();
    }
    const isEmail = elem.attr('type') === 'email';
    const isFile = elem.attr('type') === 'file';
    const isText = (elem[0].tagName === "TEXTAREA" || elem.attr('type') === 'text' || elem.attr('type') === 'number' || elem.attr('type') === 'phone');
    const minVal = elem.attr('min');
    const maxVal = elem.attr('max');
    const regex = elem.attr('data-regex');
    const regexMsg = elem.attr('data-regex-message');
    const sameAsInput = elem.attr('data-sameInput');
    const label_txt = elem.parent().find('label').text().trim();
    const isRadioCheck = (elem.attr('type') === 'checkbox' || elem.attr('type') === 'radio');
    let emailValidate = true;
    if (isEmail) {
        emailValidate = elem.val().match(/^([\w-.]+@([\w-]+\.)+[\w-]{2,4})?$/);
    }
    let isNull = false; // some select element with empty option value comes as null
    if (elem.val() === null) {
        isNull = true;
    }
    let valid = true;

    // different for radio and checkbox
    let checkedInputName = elem.attr('name');
    if (isRadioCheck && $("input[name='" + checkedInputName + "']:checked").length === 0) {
        showError(elem);
        elemParent.addClass('input-error');
        valid = false;
    }
    // different for file type
    if (isFile && elem[0].files.length === 0) {
        showError(elem);
        elemParent.addClass('input-error');
        valid = false;
    }
    if ((!isFile && !isRadioCheck)) {
        console.log('')
        console.log('elem Name', label_txt)
        console.log('elem', elem)
        console.log('elem val', elem.val())
        console.log('Array.isArray(elem.val())', Array.isArray(elem.val()))
        console.log('emailValidate', emailValidate)
        if (
            // If element have array input ( select[multiple] OR name="input[]") and is empty
            (isForm && Array.isArray(elem.val()) && elem.val().length === 0)
            ||
            (
                // If elem don't have value or is empty or is not array but empty after trimmed
                (elem.val() === null || elem.val() === "" || (!Array.isArray(elem.val()) && elem.val().trim() === ""))
                // Also if is form or not because if it is, that means we checking multiples, not on keydown unlike email validation
                && isForm
            )
            // Input is email and pattern correct
            || (isEmail && !emailValidate)) {

            // Again check if email and pattern correct
            if (isEmail && !emailValidate) {
                showError(elem, "Email is not valid!");
            } else {
                // not email but no value either
                showError(elem);
            }
            elemParent.addClass('input-error');
            valid = false;
        }

        if (isText) {
            // check min value attribute
            if (valid && (elem.val().length > 0 && elem.val().length < minVal)) {
                showError(elem, label_txt + " must be at least " + minVal + " characters");
                valid = false;
            }
            // check max value attribute
            if (valid && (elem.val().length > 0 && elem.val().length > maxVal)) {
                showError(elem, label_txt + " cannot be more than " + maxVal + " characters");
                valid = false;
            }
        }

        // check regex if exists
        if (valid && (regex && elem.val().length > 0)) {
            let regexExp = new RegExp(regex, "gm");
            if (elem.val().search(regexExp)) {
                showError(elem, regexMsg);
                valid = false;
            }
        }

        // check if value needs to be same as other input
        if (valid && (sameAsInput && $("#" + sameAsInput).length > 0 && elem.val().length > 0)) {
            let sameAsInputElem = $("#" + sameAsInput);
            if (elem.val() !== sameAsInputElem.val()) {
                const sameAs_label_txt = sameAsInputElem.parent().find('label').text().trim();
                showError(elem, label_txt + " does not match with " + sameAs_label_txt);
                valid = false;
            }
        }
    }

    console.log('CheckValid00', valid, elem)
    if (valid) {
        elem.addClass('is-valid');
    }



    if (!isForm && !isText && !Array.isArray(elem.val()) && elem.val() === null) {
        elem.removeClass('is-valid');
    }else if (!isForm && !isText && Array.isArray(elem.val()) && elem.val().length == 0) {
        elem.removeClass('is-valid');
    }else if (!isForm && isText && !elem.val().trim()) {
        elem.removeClass('is-valid');
    }
    return valid;
}


function showError(elem, text) {
    let msg = "";
    if (text) {
        msg = text;
    } else {
        let label_txt = elem.parent().find('label').text().trim();
        msg = label_txt + " is required.";
    }

    // if element is using select2, then append after that
    if (elem.next().first().hasClass('select2')) {
        elem.next().first().after(`<span style="display:block" class="validation-error invalid-feedback" role="alert"> <strong>${msg}</strong> </span>`);
    } else {
        elem.after(`<span style="display:block" class="validation-error invalid-feedback" role="alert"> <strong>${msg}</strong> </span>`);
    }
    elem.addClass('is-invalid');
}

function validateForm(element) {
    let form = $(element);
    let formValid = true;
    // checking all inputs validation
    $(form).find('input, textarea, select').each(function () {
        const checkValid = validateInput(this, true);
        if (checkValid === false) {
            formValid = false;
        }
    });
    return formValid;


}

$(document).on('paste', '.validate-input, input[required], textarea[required]', function () {
    validateInput(this, false, true);
});
$(document).on('keydown', '.validate-input, input[required], textarea[required]', function () {
    let $self = this;
    setTimeout(function () {

        if(!$($self).val()) {
            $($self).removeClass('is-valid');
            $($self).removeClass('is-invalid');
            $($self).parent().find('.validation-error').remove();
            return;
        }
        let isMinMaxExists = ($($self).attr('min') !== undefined || $($self).attr('max') !== undefined);
        let isEmailInput = ($($self).attr('type') === 'email');

        if (isMinMaxExists || isEmailInput) {
            validateInput($self, false, true);
        } else if ($($self).val().length < 10) {
            validateInput($self, false, true);
        }
    }, 0);

});
$(document).on('change', 'select[required]', function () {
    validateInput(this, false, true);
});

// triggering on load because laravel fills value auto
$('.validate-input, input[required], textarea[required], select[required]').each(function () {
    if($(this).val()) {
        validateInput(this, false, true);
    }
});
