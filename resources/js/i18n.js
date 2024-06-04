import {createI18n} from 'vue-i18n';

import enAuth from './locales/en/auth';
import ruAuth from './locales/ru/auth';
import kzAuth from './locales/kz/auth';
import enLanguages from './locales/en/languages.js'
import ruLanguages from './locales/ru/languages.js'
import kzLanguages from './locales/kz/languages.js'
import enMenu from './locales/en/menu.js'
import ruMenu from './locales/ru/menu.js'
import kzMenu from './locales/kz/menu.js'

const messages = {
    en: {
        auth: enAuth,
        languages: enLanguages,
        menu: enMenu,
    },
    ru: {
        auth: ruAuth,
        languages: ruLanguages,
        menu: ruMenu,
    },
    kz: {
        auth: kzAuth,
        languages: kzLanguages,
        menu: kzMenu,
    }
};

const i18n = createI18n({
    locale: 'ru',
    fallbackLocale: 'ru',
    messages,
});

export default i18n;
