/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

/**
 * Helpers functions
 * @link https://stackoverflow.com/a/50960293
 */
const Helpers = {
    /*
     * Link:
     */
    isEmail: function (value) {
        let pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;

        return pattern.test(value);
    },
    /*
     * Link:
     */
    isPhone: function (value) {
        let pattern = /^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/;

        return pattern.test(value);
    },
    /*
     * Link:
     */
    isLink: function (value) {
        let pattern = /https?:\/\/(?:w{1,3}\.)?[^\s.]+(?:\.[a-z]+)*(?::\d+)?(?![^<]*(?:<\/\w+>|\/?>))/;

        return pattern.test(value);
    },
    /*
     * Link: https://stackoverflow.com/a/50737567
     */
    downloadFile: function (response, filename) {
        // It is necessary to create a new blob object with mime-type explicitly set
        // otherwise only Chrome works like it should
        let newBlob = new Blob([response.data]);

        // IE doesn't allow using a blob object directly as link href
        // instead it is necessary to use msSaveOrOpenBlob
        if (window.navigator && window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(newBlob);
            return;
        }

        let fileURL = window.URL.createObjectURL(newBlob);
        let fileLink = document.createElement('a');

        fileLink.href = fileURL;
        fileLink.setAttribute('download', `${filename}.pdf`);
        fileLink.click();

        setTimeout(function () {
            // For Firefox it is necessary to delay revoking the ObjectURL
            window.URL.revokeObjectURL(fileLink);
        }, 100);
    },
    /*
     * Link: https://stackoverflow.com/a/1026087
     */
    capitalizeString: function (string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    },
    /*
     * Link: https://stackoverflow.com/a/32108184
     */
    isObjectEmpty: function (object) {
        if (Object.keys(object).length === 0) {
            return true;
        }

        // Because Object.keys(new Date()).length === 0;
        // We have to do some additional check
        for (let prop in object) {
            if (Object.prototype.hasOwnProperty.call(object, prop)) {
                return false;
            }
        }

        return JSON.stringify(object) === JSON.stringify({});
    },
    /*
     * Link: https://gist.github.com/eddieajau/5f3e289967de60cf7bf9?permalink_comment_id=2727196#gistcomment-2727196
     */
    extractColumn: function (arr, column) {
        return arr.map(x => x[column]);
    },
    /*
     * Link:
     */
    convertStrDateToFormat: function (str) {
        return new Date(str).toLocaleDateString('ru-RU', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });
    },
    /*
     * Link: https://stackoverflow.com/a/6078873
     */
    convertTimestampToDate: function (timestamp) {
        return new Date(timestamp * 1000).toLocaleDateString('ru-RU', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });
    },
    /*
     * Link: https://thewebdev.info/2021/04/17/how-to-convert-a-date-string-to-timestamp-in-javascript/
     */
    convertStrDateToTimestamp: function (strDate) {
        return Date.parse(strDate) / 1000;
    },
};

export default Helpers;
