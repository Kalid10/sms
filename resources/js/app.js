import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import Layout from "@/Layouts/Layout.vue";
import GettingStartedLayout from "@/Layouts/GettingStartedLayout.vue";

import { createPinia } from "pinia";
import TeacherLayout from "@/Layouts/TeacherLayout.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { useI18nStore } from "./Store/lang.js";

// Localization section
// import { lang } from './Store/lang.js'
import en from "./locale/en.js";
import am from "./locale/am.js";

import base from "./base";
import { createI18n } from "vue-i18n";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText ||
    "SchoolManagement";

const pinia = createPinia();

function getLayout(name, page) {
    switch (true) {
        case name.startsWith("Admin/GettingStarted/"):

        case name.startsWith("Auth/"):
            return GettingStartedLayout;

        case name.startsWith("Admin/"):
            return AdminLayout;

        case name.startsWith("Teacher/"):
            return TeacherLayout;
        default:
            return page.default.layout || Layout;
    }
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        let page = pages[`./Pages/${name}.vue`];
        page.default.layout = getLayout(name, page);
        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(pinia);

        const langStore = useI18nStore();

        const i18n = createI18n({
            legacy: false,
            locale: localStorage.getItem("selectedLanguage") || "en",
            globalInjection: true,
            messages: {
                en,
                am,
            },
        });

        app.use(i18n).mixin(base).mount(el);

        return app;
    },
    progress: {
        color: "brand-100",
    },
});
