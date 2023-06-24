import { defineStore } from 'pinia'
// import { createI18n } from 'vue-i18n'
// import en from '../locale/en.js'
// import am from '../locale/am.js'

export const useI18nStore = defineStore('lang', {
    state: () => ({
        locale: 'en', // Default language
    }),
    actions: {
        setLocale(state, locale) {
            state.locale = locale
        },
    },
})

