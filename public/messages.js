/*!
 *  Lang.js for Laravel localization in JavaScript.
 *
 *  @version 1.1.10
 *  @license MIT https://github.com/rmariuzzo/Lang.js/blob/master/LICENSE
 *  @site    https://github.com/rmariuzzo/Lang.js
 *  @author  Rubens Mariuzzo <rubens@mariuzzo.com>
 */
(function(root, factory) {
    "use strict";
    if (typeof define === "function" && define.amd) {
        define([], factory)
    } else if (typeof exports === "object") {
        module.exports = factory()
    } else {
        root.Lang = factory()
    }
})(this, function() {
    "use strict";

    function inferLocale() {
        if (typeof document !== "undefined" && document.documentElement) {
            return document.documentElement.lang
        }
    }

    function convertNumber(str) {
        if (str === "-Inf") {
            return -Infinity
        } else if (str === "+Inf" || str === "Inf" || str === "*") {
            return Infinity
        }
        return parseInt(str, 10)
    }
    var intervalRegexp = /^({\s*(\-?\d+(\.\d+)?[\s*,\s*\-?\d+(\.\d+)?]*)\s*})|([\[\]])\s*(-Inf|\*|\-?\d+(\.\d+)?)\s*,\s*(\+?Inf|\*|\-?\d+(\.\d+)?)\s*([\[\]])$/;
    var anyIntervalRegexp = /({\s*(\-?\d+(\.\d+)?[\s*,\s*\-?\d+(\.\d+)?]*)\s*})|([\[\]])\s*(-Inf|\*|\-?\d+(\.\d+)?)\s*,\s*(\+?Inf|\*|\-?\d+(\.\d+)?)\s*([\[\]])/;
    var defaults = {
        locale: "en"
    };
    var Lang = function(options) {
        options = options || {};
        this.locale = options.locale || inferLocale() || defaults.locale;
        this.fallback = options.fallback;
        this.messages = options.messages
    };
    Lang.prototype.setMessages = function(messages) {
        this.messages = messages
    };
    Lang.prototype.getLocale = function() {
        return this.locale || this.fallback
    };
    Lang.prototype.setLocale = function(locale) {
        this.locale = locale
    };
    Lang.prototype.getFallback = function() {
        return this.fallback
    };
    Lang.prototype.setFallback = function(fallback) {
        this.fallback = fallback
    };
    Lang.prototype.has = function(key, locale) {
        if (typeof key !== "string" || !this.messages) {
            return false
        }
        return this._getMessage(key, locale) !== null
    };
    Lang.prototype.get = function(key, replacements, locale) {
        if (!this.has(key, locale)) {
            return key
        }
        var message = this._getMessage(key, locale);
        if (message === null) {
            return key
        }
        if (replacements) {
            message = this._applyReplacements(message, replacements)
        }
        return message
    };
    Lang.prototype.trans = function(key, replacements) {
        return this.get(key, replacements)
    };
    Lang.prototype.choice = function(key, number, replacements, locale) {
        replacements = typeof replacements !== "undefined" ? replacements : {};
        replacements.count = number;
        var message = this.get(key, replacements, locale);
        if (message === null || message === undefined) {
            return message
        }
        var messageParts = message.split("|");
        var explicitRules = [];
        for (var i = 0; i < messageParts.length; i++) {
            messageParts[i] = messageParts[i].trim();
            if (anyIntervalRegexp.test(messageParts[i])) {
                var messageSpaceSplit = messageParts[i].split(/\s/);
                explicitRules.push(messageSpaceSplit.shift());
                messageParts[i] = messageSpaceSplit.join(" ")
            }
        }
        if (messageParts.length === 1) {
            return message
        }
        for (var j = 0; j < explicitRules.length; j++) {
            if (this._testInterval(number, explicitRules[j])) {
                return messageParts[j]
            }
        }
        var pluralForm = this._getPluralForm(number);
        return messageParts[pluralForm]
    };
    Lang.prototype.transChoice = function(key, count, replacements) {
        return this.choice(key, count, replacements)
    };
    Lang.prototype._parseKey = function(key, locale) {
        if (typeof key !== "string" || typeof locale !== "string") {
            return null
        }
        var segments = key.split(".");
        var source = segments[0].replace(/\//g, ".");
        return {
            source: locale + "." + source,
            sourceFallback: this.getFallback() + "." + source,
            entries: segments.slice(1)
        }
    };
    Lang.prototype._getMessage = function(key, locale) {
        locale = locale || this.getLocale();
        key = this._parseKey(key, locale);
        if (this.messages[key.source] === undefined && this.messages[key.sourceFallback] === undefined) {
            return null
        }
        var message = this.messages[key.source];
        var entries = key.entries.slice();
        var subKey = "";
        while (entries.length && message !== undefined) {
            var subKey = !subKey ? entries.shift() : subKey.concat(".", entries.shift());
            if (message[subKey] !== undefined) {
                message = message[subKey];
                subKey = ""
            }
        }
        if (typeof message !== "string" && this.messages[key.sourceFallback]) {
            message = this.messages[key.sourceFallback];
            entries = key.entries.slice();
            subKey = "";
            while (entries.length && message !== undefined) {
                var subKey = !subKey ? entries.shift() : subKey.concat(".", entries.shift());
                if (message[subKey]) {
                    message = message[subKey];
                    subKey = ""
                }
            }
        }
        if (typeof message !== "string") {
            return null
        }
        return message
    };
    Lang.prototype._findMessageInTree = function(pathSegments, tree) {
        while (pathSegments.length && tree !== undefined) {
            var dottedKey = pathSegments.join(".");
            if (tree[dottedKey]) {
                tree = tree[dottedKey];
                break
            }
            tree = tree[pathSegments.shift()]
        }
        return tree
    };
    Lang.prototype._applyReplacements = function(message, replacements) {
        for (var replace in replacements) {
            message = message.replace(new RegExp(":" + replace, "gi"), function(match) {
                var value = replacements[replace];
                var allCaps = match === match.toUpperCase();
                if (allCaps) {
                    return value.toUpperCase()
                }
                var firstCap = match === match.replace(/\w/i, function(letter) {
                    return letter.toUpperCase()
                });
                if (firstCap) {
                    return value.charAt(0).toUpperCase() + value.slice(1)
                }
                return value
            })
        }
        return message
    };
    Lang.prototype._testInterval = function(count, interval) {
        if (typeof interval !== "string") {
            throw "Invalid interval: should be a string."
        }
        interval = interval.trim();
        var matches = interval.match(intervalRegexp);
        if (!matches) {
            throw "Invalid interval: " + interval
        }
        if (matches[2]) {
            var items = matches[2].split(",");
            for (var i = 0; i < items.length; i++) {
                if (parseInt(items[i], 10) === count) {
                    return true
                }
            }
        } else {
            matches = matches.filter(function(match) {
                return !!match
            });
            var leftDelimiter = matches[1];
            var leftNumber = convertNumber(matches[2]);
            if (leftNumber === Infinity) {
                leftNumber = -Infinity
            }
            var rightNumber = convertNumber(matches[3]);
            var rightDelimiter = matches[4];
            return (leftDelimiter === "[" ? count >= leftNumber : count > leftNumber) && (rightDelimiter === "]" ? count <= rightNumber : count < rightNumber)
        }
        return false
    };
    Lang.prototype._getPluralForm = function(count) {
        switch (this.locale) {
            case "az":
            case "bo":
            case "dz":
            case "id":
            case "ja":
            case "jv":
            case "ka":
            case "km":
            case "kn":
            case "ko":
            case "ms":
            case "th":
            case "tr":
            case "vi":
            case "zh":
                return 0;
            case "af":
            case "bn":
            case "bg":
            case "ca":
            case "da":
            case "de":
            case "el":
            case "en":
            case "eo":
            case "es":
            case "et":
            case "eu":
            case "fa":
            case "fi":
            case "fo":
            case "fur":
            case "fy":
            case "gl":
            case "gu":
            case "ha":
            case "he":
            case "hu":
            case "is":
            case "it":
            case "ku":
            case "lb":
            case "ml":
            case "mn":
            case "mr":
            case "nah":
            case "nb":
            case "ne":
            case "nl":
            case "nn":
            case "no":
            case "om":
            case "or":
            case "pa":
            case "pap":
            case "ps":
            case "pt":
            case "so":
            case "sq":
            case "sv":
            case "sw":
            case "ta":
            case "te":
            case "tk":
            case "ur":
            case "zu":
                return count == 1 ? 0 : 1;
            case "am":
            case "bh":
            case "fil":
            case "fr":
            case "gun":
            case "hi":
            case "hy":
            case "ln":
            case "mg":
            case "nso":
            case "xbr":
            case "ti":
            case "wa":
                return count === 0 || count === 1 ? 0 : 1;
            case "be":
            case "bs":
            case "hr":
            case "ru":
            case "sr":
            case "uk":
                return count % 10 == 1 && count % 100 != 11 ? 0 : count % 10 >= 2 && count % 10 <= 4 && (count % 100 < 10 || count % 100 >= 20) ? 1 : 2;
            case "cs":
            case "sk":
                return count == 1 ? 0 : count >= 2 && count <= 4 ? 1 : 2;
            case "ga":
                return count == 1 ? 0 : count == 2 ? 1 : 2;
            case "lt":
                return count % 10 == 1 && count % 100 != 11 ? 0 : count % 10 >= 2 && (count % 100 < 10 || count % 100 >= 20) ? 1 : 2;
            case "sl":
                return count % 100 == 1 ? 0 : count % 100 == 2 ? 1 : count % 100 == 3 || count % 100 == 4 ? 2 : 3;
            case "mk":
                return count % 10 == 1 ? 0 : 1;
            case "mt":
                return count == 1 ? 0 : count === 0 || count % 100 > 1 && count % 100 < 11 ? 1 : count % 100 > 10 && count % 100 < 20 ? 2 : 3;
            case "lv":
                return count === 0 ? 0 : count % 10 == 1 && count % 100 != 11 ? 1 : 2;
            case "pl":
                return count == 1 ? 0 : count % 10 >= 2 && count % 10 <= 4 && (count % 100 < 12 || count % 100 > 14) ? 1 : 2;
            case "cy":
                return count == 1 ? 0 : count == 2 ? 1 : count == 8 || count == 11 ? 2 : 3;
            case "ro":
                return count == 1 ? 0 : count === 0 || count % 100 > 0 && count % 100 < 20 ? 1 : 2;
            case "ar":
                return count === 0 ? 0 : count == 1 ? 1 : count == 2 ? 2 : count % 100 >= 3 && count % 100 <= 10 ? 3 : count % 100 >= 11 && count % 100 <= 99 ? 4 : 5;
            default:
                return 0
        }
    };
    return Lang
});

(function() {
    Lang = new Lang();
    Lang.setMessages({
        "ar.js_messages": {
            "file": {
                "choose_file": "\u0627\u062e\u062a\u0631 \u0645\u0644\u0641",
                "file_attached": "\u0645\u0644\u0641 \u0645\u0631\u0641\u0642",
                "upload": "\u0631\u0641\u0639"
            },
            "store": {
                "ASAP": "\u0647\u0643\u0630\u0627",
                "Schedule": "\u062c\u062f\u0648\u0644",
                "account_holder_name": "\u0627\u0633\u0645 \u0635\u0627\u062d\u0628 \u0627\u0644\u062d\u0633\u0627\u0628",
                "account_number": "\u0631\u0642\u0645 \u062d\u0633\u0627\u0628",
                "account_owner_name": "\u0627\u0633\u0645 \u0645\u0627\u0644\u0643 \u0627\u0644\u062d\u0633\u0627\u0628",
                "bank_code": "\u0631\u0645\u0632 \u0628\u0646\u0643\u064a",
                "bank_name": "\u0627\u0633\u0645 \u0627\u0644\u0628\u0646\u0643",
                "branch_code": "\u0631\u0645\u0632 \u0627\u0644\u0641\u0631\u0639",
                "branch_name": "\u0627\u0633\u0645 \u0627\u0644\u0641\u0631\u0639",
                "bsb": "BSB",
                "category": "\u0645\u0627 \u0646\u0648\u0639 \u0627\u0644\u0641\u0626\u0629 \u0627\u0644\u062a\u064a \u062a\u0642\u0648\u0645 \u0628\u0647\u0627\u061f",
                "city": "\u0645\u0627 \u0645\u062f\u064a\u0646\u0629 \u0645\u0637\u0639\u0645\u0643\u061f",
                "clearing_code": "\u0645\u0633\u062d \u0627\u0644\u0643\u0648\u062f",
                "conform_pasword": "\u0645\u0646 \u0641\u0636\u0644\u0643 \u0623\u062f\u062e\u0644 \u0646\u0641\u0633 \u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631",
                "cuisine": "\u0645\u0627 \u0646\u0648\u0639 \u0627\u0644\u0645\u0637\u0628\u062e \u0627\u0644\u0630\u064a \u062a\u0635\u0646\u0639\u0647\u061f",
                "description_field_required": "\u0627\u0644\u0648\u0635\u0641 \u0645\u0637\u0644\u0648\u0628",
                "document_file_may_not_greater": "\u0642\u062f \u0644\u0627 \u064a\u0632\u064a\u062f \u062d\u062c\u0645 \u0645\u0644\u0641 \u0627\u0644\u0645\u0633\u062a\u0646\u062f \u0639\u0646 10 \u0645\u064a\u063a\u0627\u0628\u0627\u064a\u062a",
                "email_address": "\u0645\u0627 \u0647\u0648 \u0639\u0646\u0648\u0627\u0646 \u0628\u0631\u064a\u062f\u0643 \u0627\u0644\u0627\u0644\u0643\u062a\u0631\u0648\u0646\u0649\u061f",
                "end_date_field_required": "\u062a\u0627\u0631\u064a\u062e \u0627\u0644\u0627\u0646\u062a\u0647\u0627\u0621 \u0645\u0637\u0644\u0648\u0628.",
                "field_required": "\u0647\u0630\u0647 \u0627\u0644\u062e\u0627\u0646\u0629 \u0645\u0637\u0644\u0648\u0628\u0647.",
                "first_name": "\u0645\u0627 \u0647\u0648 \u0625\u0633\u0645\u0643\u061f",
                "institution_number": "\u0631\u0642\u0645 \u0627\u0644\u0645\u0624\u0633\u0633\u0629",
                "invalid_code": "\u0627\u0644\u0631\u0645\u0632 \u063a\u064a\u0631 \u0635\u062d\u064a\u062d.",
                "last_name": "\u0645\u0627\u0647\u0648 \u0625\u0633\u0645 \u0639\u0627\u0626\u0644\u062a\u0643\u061f",
                "location_field_is_required": "\u062d\u0642\u0644 \u0627\u0644\u0645\u0648\u0642\u0639 \u0645\u0637\u0644\u0648\u0628",
                "message_send_successfully": "\u0625\u0631\u0633\u0627\u0644 \u0631\u0633\u0627\u0644\u0629 \u0628\u0646\u062c\u0627\u062d",
                "mobile_number_must_have_digits": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u062d\u062a\u0648\u064a \u0631\u0642\u0645 \u0627\u0644\u062c\u0648\u0627\u0644 \u0639\u0644\u0649 6 \u0623\u0631\u0642\u0627\u0645",
                "name_of_the_store": "\u0645\u0627 \u0627\u0633\u0645 \u0627\u0644\u0645\u0637\u0639\u0645\u061f",
                "password": "\u0627\u0644\u0631\u062c\u0627\u0621 \u0625\u062f\u062e\u0627\u0644 \u0643\u0644\u0645\u0629 \u0627\u0644\u0645\u0631\u0648\u0631",
                "percentage_field_required": "\u0627\u0644\u0646\u0633\u0628\u0629 \u0627\u0644\u0645\u0626\u0648\u064a\u0629 \u0645\u0637\u0644\u0648\u0628\u0629.",
                "phone_number": "\u0645\u0627 \u0647\u0648 \u0623\u0641\u0636\u0644 \u0631\u0642\u0645 \u0647\u0627\u062a\u0641 \u0644\u0644\u0648\u0635\u0648\u0644 \u0625\u0644\u064a\u0643\u061f",
                "please_enter_at_least_characters": "\u0627\u0644\u0631\u062c\u0627\u0621 \u0625\u062f\u062e\u0627\u0644 6 \u0623\u062d\u0631\u0641 \u0639\u0644\u0649 \u0627\u0644\u0623\u0642\u0644.",
                "please_enter_no_more_than_characters": "\u0627\u0644\u0631\u062c\u0627\u0621 \u0625\u062f\u062e\u0627\u0644 \u0645\u0627 \u0644\u0627 \u064a\u0632\u064a\u062f \u0639\u0646 7 \u0623\u062d\u0631\u0641.",
                "please_enter_valid_number": "\u0645\u0646 \u0641\u0636\u0644\u0643 \u0623\u062f\u062e\u0644 \u0631\u0642\u0645\u0627 \u0635\u0627\u0644\u062d\u0627.",
                "please_enter_value_less_than_or_equal": "\u064a\u0631\u062c\u0649 \u0625\u062f\u062e\u0627\u0644 \u0642\u064a\u0645\u0629 \u0623\u0642\u0644 \u0645\u0646 \u0623\u0648 \u062a\u0633\u0627\u0648\u064a 100.",
                "please_select_from_google_autocomplete": "\u064a\u0631\u062c\u0649 \u0627\u0644\u0627\u062e\u062a\u064a\u0627\u0631 \u0645\u0646 \u062c\u0648\u062c\u0644 \u0627\u0644\u0625\u0643\u0645\u0627\u0644 \u0627\u0644\u062a\u0644\u0642\u0627\u0626\u064a",
                "please_upload_file_like_format": "\u064a\u0631\u062c\u0649 \u062a\u062d\u0645\u064a\u0644 \u0627\u0644\u0645\u0644\u0641 \u0645\u062b\u0644 jpg \u060c png \u060c jpeg \u060c \u062a\u0646\u0633\u064a\u0642 pdf.",
                "please_upload_images_like_file_only": "\u064a\u0631\u062c\u0649 \u062a\u062d\u0645\u064a\u0644 \u0627\u0644\u0635\u0648\u0631 \u0645\u062b\u0644 JPG \u060c JPEG \u060c PNG \u060c \u0645\u0644\u0641 GIF \u0641\u0642\u0637.",
                "routing_number": "\u0631\u0642\u0645 \u0627\u0644\u062a\u0648\u0635\u064a\u0644",
                "search_for_store_category": "\u0627\u0644\u0628\u062d\u062b \u0639\u0646 \u0645\u062a\u062c\u0631 \u0623\u0648 \u0641\u0626\u0629",
                "search_for_store_cuisine": "\u0627\u0644\u0628\u062d\u062b \u0639\u0646 \u0645\u0637\u0639\u0645 \u0623\u0648 \u0627\u0644\u0645\u0637\u0628\u062e",
                "sort_code": "\u0627\u0644\u0643\u0648\u062f \u0627\u0644\u0628\u0646\u0643\u0649",
                "ssn_last_4": "SSN \u0622\u062e\u0631 4",
                "start_date_field_required": "\u062a\u0627\u0631\u064a\u062e \u0627\u0644\u0628\u062f\u0621 \u0645\u0637\u0644\u0648\u0628",
                "start_time_should_less_than_end_time": "\u064a\u062c\u0628 \u0623\u0646 \u064a\u0643\u0648\u0646 \u0648\u0642\u062a \u0627\u0644\u0628\u062f\u0621 \u0623\u0642\u0644 \u0645\u0646 \u0648\u0642\u062a \u0627\u0644\u0646\u0647\u0627\u064a\u0629",
                "store_address": "\u0645\u0627 \u0647\u0648 \u0639\u0646\u0648\u0627\u0646 \u0645\u0637\u0639\u0645\u0643\u061f",
                "title_field_required": "\u0627\u0644\u0639\u0646\u0648\u0627\u0646 \u0645\u0637\u0644\u0648\u0628",
                "transit_number": "\u0631\u0642\u0645 \u0627\u0644\u0639\u0628\u0648\u0631",
                "valid_email_address": "\u0645\u0646 \u0641\u0636\u0644\u0643 \u0623\u062f\u062e\u0644 \u0628\u0631\u064a\u062f \u0623\u0644\u064a\u0643\u062a\u0631\u0648\u0646\u0649 \u0635\u062d\u064a\u062d"
            }
        },
        "en.js_messages": {
            "file": {
                "choose_file": "Choose file",
                "file_attached": "File Attached",
                "upload": "upload"
            },
            "store": {
                "ASAP": "ASAP",
                "Schedule": "Schedule",
                "account_holder_name": "Account Holder Name",
                "account_number": "Account Number",
                "account_owner_name": "Account Owner Name",
                "bank_code": "Bank Code",
                "bank_name": "Bank Name",
                "branch_code": "Branch Code",
                "branch_name": "Branch Name",
                "bsb": "Bsb",
                "category": "What kind of category do you make?",
                "city": "What's your store city?",
                "clearing_code": "Clearing Code",
                "conform_pasword": "Please enter same password",
                "description_field_required": "Description is required.",
                "document_file_may_not_greater": "The document file may not be greater than 10 MB",
                "email_address": "What's your email address?",
                "end_date_field_required": "End Date is required.",
                "field_required": "This field is required",
                "first_name": "What's your first name?",
                "institution_number": "Institution Number",
                "invalid_code": "Invalid code.",
                "last_name": "What's your last name?",
                "location_field_is_required": "Location field is required",
                "message_send_successfully": "Message send successfully",
                "mobile_number_must_have_digits": "Mobile number must have 6 digits",
                "name_of_the_store": "What's the name of the store?",
                "password": "Please enter a password",
                "percentage_field_required": "Percentage is required.",
                "phone_number": "What's the best phone number to reach you at?",
                "please_enter_at_least_characters": "Please enter at least 6 characters.",
                "please_enter_no_more_than_characters": "Please enter no more than 7 characters.",
                "please_enter_valid_number": "Please enter a valid number.",
                "please_enter_value_less_than_or_equal": "Please enter a value less than or equal to 100.",
                "please_select_from_google_autocomplete": "Please select from google autocomplete",
                "please_upload_file_like_format": "Please upload the file like jpg,png,jpeg,pdf format.",
                "please_upload_images_like_file_only": "Please upload the images like JPG,JPEG,PNG,GIF File Only.",
                "routing_number": "Routing Number",
                "search_for_store_category": "Search for store or category",
                "sort_code": "Sort Code",
                "ssn_last_4": "Ssn Last 4",
                "start_date_field_required": "Start Date is required.",
                "start_time_should_less_than_end_time": "The start time should be less than the end time",
                "store_address": "What's your store address?",
                "title_field_required": "Title is required.",
                "transit_number": "Transit Number",
                "valid_email_address": "Please enter a valid email address"
            }
        },
         "es.js_messages": {
            "file": {
                "choose_file": "Elija el archivo",
                "file_attached": "Archivo adjunto",
                "upload": "subir"
            },
            "store": {
                "ASAP": "Lo antes posible",
                "Schedule": "Calendario",
                "account_holder_name": "nombre del titular de la cuenta",
                "account_owner_name": "Nombre del propietario de la cuenta",
                "bank_code": "Código bancario",
                "bank_name": "Nombre del banco",
                "branch_code": "Código de sucursal",
                "branch_name": "Nombre de la sucursal",
                "bsb": "Bsb",
                "category": "¿Qué tipo de categoría haces?",
                "city": "¿Cuál es la ciudad de tu tienda?",
                "clearing_code": "Código de compensación",
                "conform_pasword": "Ingrese la misma contraseña",
                "description_field_required": "Se requiere descripción",
                "document_file_may_not_greater": "El archivo del documento no puede superar los 10 MB",
                "email_address": "¿Cuál es su dirección de correo electrónico?",
                "end_date_field_required": "Se requiere fecha de finalización",
                "field_required": "Este campo es obligatorio",
                "first_name": "¿Cuál es su nombre de pila?",
                "organization_number": "Número de institución",
                "invalid_code": "Código inválido",
                "last_name": "¿Cuál es su apellido?",
                "location_field_is_required": "El campo de ubicación es obligatorio",
                "message_send_successfully": "Mensaje enviado correctamente",
                "mobile_number_must_have_digits": "El número de móvil debe tener 6 dígitos",
                "name_of_the_store": "¿Cuál es el nombre de la tienda?",
                "password": "Ingrese una contraseña",
                "percent_field_required": "Se requiere porcentaje",
                "phone_number": "¿Cuál es el mejor número de teléfono para contactarlo?",
                "please_enter_at_least_characters": "Ingrese al menos 6 caracteres",
                "please_enter_no_more_than_characters": "Ingrese no más de 7 caracteres",
                "please_enter_valid_number": "Ingrese un número válido",
                "please_enter_value_less_than_or_equal": "Ingrese un valor menor o igual a 100",
                "please_select_from_google_autocomplete": "Seleccione de Google autocompletar",
                "please_upload_file_like_format": "Cargue el archivo como formato jpg, png, jpeg, pdf",
                "please_upload_images_like_file_only": "Cargue las imágenes como JPG, JPEG, PNG, solo archivo GIF",
                "routing_number": "Número de ruta",
                "search_for_store_category": "Buscar tienda o categoría",
                "sort_code": "Código de clasificación",
                "ssn_last_4": "Ssn Last 4",
                "start_date_field_required": "Se requiere fecha de inicio",
                "start_time_should_less_than_end_time": "La hora de inicio debe ser inferior a la hora de finalización",
                "store_address": "¿Cuál es la dirección de su tienda?",
                "title_field_required": "Se requiere título",
                "transit_number": "Número de tránsito",
                "valid_email_address": "Ingrese una dirección de correo electrónico válida",

            }
        },
        "pt.js_messages": {
            "file": {
                "choose_file": "Escolher arquivo",
                "file_attached": "Arquivo anexado",
                "upload": "Envio"
            },
            "store": {
                "ASAP": "assim que poss\u00edvel",
                "Schedule": "Cronograma",
                "account_holder_name": "nome do titular da conta",
                "account_number": "N\u00famero da conta",
                "account_owner_name": "Nome da conta do propriet\u00e1rio",
                "bank_code": "C\u00f3digo banc\u00e1rio",
                "bank_name": "Nome do banco",
                "branch_code": "C\u00f3digo da Ag\u00eancia",
                "branch_name": "Nome ramo",
                "bsb": "Bsb",
                "category": "Que tipo de categoria voc\u00ea faz?",
                "city": "Qual \u00e9 a sua cidade loja?",
                "clearing_code": "C\u00f3digo Clearing",
                "conform_pasword": "Por favor, indique mesma senha",
                "cuisine": "Que tipo de cozinha que voc\u00ea faz?",
                "description_field_required": "Descri\u00e7\u00e3o \u00e9 necess\u00e1ria.",
                "document_file_may_not_greater": "O arquivo de documento n\u00e3o pode ser maior do que 10 MB",
                "email_address": "Qual \u00e9 o seu e-mail?",
                "end_date_field_required": "Data final \u00e9 necess\u00e1rio.",
                "field_required": "Este campo \u00e9 obrigat\u00f3rio.",
                "first_name": "Qual \u00e9 o seu primeiro nome?",
                "institution_number": "institui\u00e7\u00e3o N\u00famero",
                "invalid_code": "C\u00f3digo inv\u00e1lido.",
                "last_name": "Qual \u00e9 o seu sobrenome?",
                "location_field_is_required": "campo de localiza\u00e7\u00e3o \u00e9 necess\u00e1ria",
                "message_send_successfully": "Mensagem enviada com sucesso",
                "mobile_number_must_have_digits": "n\u00famero do celular deve ter 6 d\u00edgitos",
                "name_of_the_store": "Qual \u00e9 o nome do loja?",
                "password": "Por favor insira uma senha",
                "percentage_field_required": "Percentual \u00e9 necess\u00e1ria.",
                "phone_number": "Qual \u00e9 o melhor n\u00famero de telefone para chegar a voc\u00ea?",
                "please_enter_at_least_characters": "Por favor, insira pelo menos 6 caracteres.",
                "please_enter_no_more_than_characters": "Por favor, indique n\u00e3o mais do que 7 caracteres.",
                "please_enter_valid_number": "por favor insira um n\u00famero v\u00e1lido.",
                "please_enter_value_less_than_or_equal": "Por favor insira um valor menor ou igual a 100.",
                "please_select_from_google_autocomplete": "Selecione a partir do Google autocomplete",
                "please_upload_file_like_format": "Por favor envie o arquivo como jpg, png, jpeg, formato pdf.",
                "please_upload_images_like_file_only": "Por favor, enviar as imagens como JPG, JPEG, PNG, S\u00f3 arquivo GIF.",
                "routing_number": "N\u00famero de roteamento",
                "search_for_store_category": "Pesquisa por loja ou categoria",
                "search_for_store_cuisine": "Pesquisar loja ou cozinha",
                "sort_code": "C\u00f3digo de Ordena\u00e7\u00e3o",
                "ssn_last_4": "Ssn \u00daltima 4",
                "start_date_field_required": "Data de in\u00edcio \u00e9 necess\u00e1ria.",
                "start_time_should_less_than_end_time": "A hora de in\u00edcio deve ser menor do que o tempo do fim",
                "store_address": "Qual \u00e9 o seu endere\u00e7o de loja?",
                "title_field_required": "T\u00edtulo \u00e9 necess\u00e1ria.",
                "transit_number": "N\u00famero de tr\u00e2nsito",
                "valid_email_address": "Por favor insira um endere\u00e7o de e-mail v\u00e1lido"
            }
        }
    });
})();