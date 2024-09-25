/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

export const formatDate = (value, locales = 'en-US') => {
    const dateObject = new Date(value);

    if (isNaN(dateObject.getTime())) {
        return '-';
    }

    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    const formattedDate = new Date(value).toLocaleDateString(locales, options);
    return formattedDate.replace(/\//g, '.');
};

export const formatDateTime = (value, locales = 'en-US', withSeconds = false) => {
    const dateObject = new Date(value);

    if (isNaN(dateObject.getTime())) {
        return '-';
    }

    const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };

    if (withSeconds) {
        options.second = '2-digit';
    }

    const formattedDate = new Date(value).toLocaleDateString(locales, options);
    return formattedDate.replace(/\//g, '.');
};

export const ages = [
    {age_name: '2.5', age_number: 2.5},
    {age_name: '4.5', age_number: 4.5},
];

export const prepareInitialRatingData = (domains) => {
    let ratingsData = [];

    domains.forEach(function(item1, index1) {
        ratingsData[index1] = {
            domain: item1.id,
            milestones: [],
        };

        item1.subdomains.forEach(function(item2, index2) {
            item2.milestones.forEach(function(item3, index3) {
                ratingsData[index1].milestones.push({
                    id: item3.id,
                    abbreviation: item3.abbreviation,
                    value: null
                });
            });
        });
    });

    return ratingsData;
};

export const prepareDate = (inputDateString) => {
    const inputDate = new Date(inputDateString);

    const germanMonths = [
        'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
        'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'
    ];

    const germanDays = [
        'So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'
    ];

    const day = inputDate.getDate();
    const month = germanMonths[inputDate.getMonth()];
    const year = inputDate.getFullYear();
    const dayOfWeek = germanDays[inputDate.getDay()];

    return `${dayOfWeek}, ${day}. ${month} ${year}`;
};

export const getTrainingStatusIcon = (status) => {
    switch (status) {
        case 'planned':
            return 'mdi-calendar-month-outline';
        case 'confirmed':
            return 'mdi-check-circle-outline';
        case 'completed':
            return 'mdi-flag-checkered';
        case 'cancelled':
            return 'mdi-close-circle-outline';
        default:
            return 'mdi-help';
    }
};

export const getTrainingProposalStatusIcon = (status) => {
    switch (status) {
        case 'email_not_confirmed':
            return 'mdi-email-open-outline';
        case 'open':
            return 'mdi-clock-outline';
        case 'obsolete':
            return 'mdi-cancel';
        case 'reserved':
            return 'mdi-bookmark-outline';
        case 'confirmation_pending':
            return 'mdi-timer-sand';
        case 'confirmed':
            return 'mdi-check-circle-outline';
        default:
            return 'mdi-help';
    }
};
