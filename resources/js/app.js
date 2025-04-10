/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

import './bootstrap';

import {createApp, h} from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { createVuetify } from 'vuetify';
import { createVueI18nAdapter } from 'vuetify/locale/adapters/vue-i18n';
import { VBtn } from 'vuetify/components/VBtn'
import * as labsComponents from 'vuetify/labs/components'
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import AOS from 'aos';
import { en, de } from 'vuetify/locale';
import { createI18n, useI18n } from 'vue-i18n';
import { vuetifyProTipTap } from './tiptap';


// CSS
import '../sass/app.scss';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import 'aos/dist/aos.css';
import 'vue3-timepicker/dist/VueTimepicker.css';

/*
 * Setup Vite
 */
import.meta.glob([
    '../images/**',
    '../fonts/**',
]);

/*
 * Setup AOS
 */
AOS.init();


/*
 * Setup Vuetify
 */
const i18n = createI18n({
    legacy: false,
    locale: 'de',
    fallbackLocale: 'en',
    messages: {
        de: {
            $vuetify: {
                ...de,
            },
        },
        en: {
            $vuetify: {
                ...en,
            },
        },
    },
});

const lightTheme = {
    dark: false,
    colors: {
        background: '#ececec',
        surface: '#ffffff',
        primary: '#00537f',
        secondary: '#0074b2',
        accent: '#ed6f25',
        'primary-darken-1': '#3700B3',
        'secondary-darken-1': '#018786',
        error: '#B00020',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FB8C00',
    },
}

const vuetify = createVuetify({
    aliases: {
        VBtnPrimary: VBtn,
        VBtnSecondary: VBtn,
        VBtnDropdown: VBtn,
    },
    defaults: {
        VBtnPrimary: {
            color: 'primary',
            variant: 'flat',
        },
        VBtnSecondary: {
            color: 'accent',
            variant: 'flat',
        },
        VBtnDropdown: {
            variant: 'plain',
        },
        VDataTableServer:{
            theme: 'accent',
            color: 'accent',
            class: ['basic-table'],
        },
        VNavigationDrawer:{
            color: 'secondary'
        },
        VToolbar:{
            color: 'secondary'
        },
        VBtn: {
            // style: { borderRadius: '45px' },
        },
        VTextField:{
            variant: 'underlined',
            color: 'primary',
        },
        VTextarea:{
            variant: 'underlined',
            color: 'primary',
        },
        VSelect:{
            variant: 'underlined',
        },
        VAutocomplete:{
            variant: 'underlined',
        },
        VCheckbox:{
            color: 'primary',
        },
    },
    theme: {
        defaultTheme: 'lightTheme',
        themes: {
            lightTheme,
        },
    },
    components: { ...components, ...labsComponents },
    directives,
    icons: {
        iconfont: 'mdiSvg',
    },
    locale: {
        adapter: createVueI18nAdapter({ i18n, useI18n }),
    },
})

/*
 * Setup App
 */
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel Vue Sample App';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vuetify)
            .use(i18n)
            .use(vuetifyProTipTap)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#ed6f25',
    },
});

