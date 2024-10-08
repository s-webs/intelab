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
import enSearch from './locales/en/search.js'
import ruSearch from './locales/ru/search.js'
import kzSearch from './locales/kz/search.js'
import enPages from './locales/en/pages.js'
import ruPages from './locales/ru/pages.js'
import kzPages from './locales/kz/pages.js'

const messages = {
    en: {
        auth: enAuth,
        languages: enLanguages,
        menu: enMenu,
        search: enSearch,
        pages: enPages,
    },
    ru: {
        auth: ruAuth,
        languages: ruLanguages,
        menu: ruMenu,
        search: ruSearch,
        pages: ruPages,
    },
    kz: {
        auth: kzAuth,
        languages: kzLanguages,
        menu: kzMenu,
        search: kzSearch,
        pages: kzPages,
    }
};

const i18n = createI18n({
    locale: 'ru',
    fallbackLocale: 'ru',
    messages,
});

export default i18n;
