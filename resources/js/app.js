import "./bootstrap";
import "../css/app.css";

import {createApp, h} from "vue";
import {createInertiaApp} from "@inertiajs/vue3";
import {ZiggyVue} from "../../vendor/tightenco/ziggy/dist/vue.m";
import Layout from "./Layout.vue";

import {createPinia} from "pinia";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText ||
    "SchoolManagement";

const pinia = createPinia();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", {eager: true});
        let page = pages[`./Pages/${name}.vue`]
        // console.log(page)
        page.default.layout = name.startsWith('GettingStarted/') ? undefined : page.default.layout || Layout
        // page.default.layout = page.default.layout || Layout;
        return page;
    },
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(pinia)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
