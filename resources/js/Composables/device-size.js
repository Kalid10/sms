import { computed, onBeforeUnmount, onMounted, ref } from "vue";

export function useDeviceSize() {
    const windowWidth = ref(window.innerWidth);

    const resizeHandler = () => {
        windowWidth.value = window.innerWidth;
    };

    onMounted(() => {
        window.addEventListener("resize", resizeHandler);
    });

    onBeforeUnmount(() => {
        window.removeEventListener("resize", resizeHandler);
    });

    const deviceSize = computed(() => {
        if (windowWidth.value <= 640) return "xs";
        if (windowWidth.value <= 768) return "sm";
        if (windowWidth.value <= 1024) return "md";
        if (windowWidth.value <= 1280) return "lg";
        if (windowWidth.value <= 1536) return "xl";
        return "2xl";
    });

    return { deviceSize };
}
