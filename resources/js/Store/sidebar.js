import { defineStore } from "pinia";

export const useSidebarStore = defineStore({
    id: "sidebar",
    state: () => ({
        isOpen: true,
    }),
    actions: {
        open() {
            this.isOpen = true;
        },
        close() {
            this.isOpen = false;
        },
        toggle() {
            this.isOpen = !this.isOpen;
        },
    },
});
