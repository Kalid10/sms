import { defineStore } from 'pinia'
// import { createI18n } from 'vue-i18n'
// import en from '../locale/en.json'
// import am from '../locale/am.json'

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

