/**
 * Created by Thieu-LM on 2/16/2017.
 */
// Phone number validation
// from: http://stackoverflow.com/questions/19840301/jquery-to-validate-phone-number
$.validator.addMethod(
    "isPhoneNumber",
    function (number, element) {
        number = number.replace(/\s+/g, "");
        return this.optional(element) || number.length > 9 && number.length <= 13 &&
            number.match(/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/);
    }
);
// Email format check
$.validator.addMethod(
    "isEmail",
    function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i.test(value);
    }
);
// Email format check
$.validator.addMethod(
    "isPassword",
    function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/i.test(value);
    }
);
// Postal code
$.validator.addMethod(
    "isPostalCode",
    function (value, element) {
        return this.optional(element) || /^([0-9]{3})-([0-9]{4})$/i.test(value) || /^([0-9]{7})$/i.test(value);
    }
);
/**
 * Max filesize
 * param = size (MB)
 * element = element to validate (<input>)
 * value = value of the element (file name)
 */
$.validator.addMethod('maxFileSize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= (param * 1024 * 1024));
});
/**
 * Ignore show error by title
 */
$.validator.setDefaults({
    ignoreTitle: true,
});