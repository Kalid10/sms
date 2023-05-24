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

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText ||
    "SchoolManagement";

const pinia = createPinia();

function getLayout(name, page) {
    switch (true) {
        case name.startsWith("Teacher/"):
            return TeacherLayout;
        case name.startsWith("Admin/"):
            return AdminLayout;

        case name.startsWith("GettingStarted/"):
        case name.startsWith("Auth/"):
            return GettingStartedLayout;

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
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
}).then(() => {});
