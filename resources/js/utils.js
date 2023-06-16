import { useDeviceSize } from "@/Composables/device-size";
import { computed } from "vue";
import { useSidebarStore } from "@/Store/sidebar";
import { usePage } from "@inertiajs/vue3";

// Take a string and replace all the whitespaces with underscores
function toHashTag(string) {
    string = string
        .split(" ")
        .map((word) => capitalize(word))
        .join(" ");
    return `#${string.replace(/\s/g, "")}`;
}

// Capitalize a letter
function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function parseLevel(level) {
    return isNaN(level) ? level : `Grade ${level}`;
}

// take a number and add suffix to it
export function addSuffix(number) {
    if (number % 10 === 1 && number !== 11) {
        return number + "st";
    } else if (number % 10 === 2 && number !== 12) {
        return number + "nd";
    } else if (number % 10 === 3 && number !== 13) {
        return number + "rd";
    } else {
        return number + "th";
    }
}

function unshift(array, value) {
    array.unshift(value);
    return array;
}

const { deviceSize } = useDeviceSize();
const isSidebarOpenOnXlDevice = computed(() => {
    return deviceSize.value === "lg" || deviceSize.value === "xl"
        ? useSidebarStore().isOpen
        : false;
});
const subjectPriorityLabels = [
    "bg-red-100",
    "bg-red-300",
    "bg-red-500",
    "bg-red-700",
    "bg-red-900",
];

const genderLabels = {
    male: "bg-blue-200 text-blue-800",
    female: "bg-purple-200 text-purple-800",
};

const levelCategoryLabels = [
    "bg-green-100 text-green-600 border-green-600",
    "bg-yellow-100 text-yellow-600 border-yellow-600",
    "bg-blue-100 text-blue-600 border-blue-600",
];

const numberWithOrdinal = (n) => {
    const s = ["th", "st", "nd", "rd"],
        v = n % 100;
    return n + (s[(v - 20) % 10] || s[v] || s[0]);
};

// Check if the logged-in user is admin
function isAdmin() {
    return usePage().props.auth.user.type === "admin";
}

function isTeacher() {
    return usePage().props.auth.user.type === "teacher";
}

export {
    toHashTag,
    capitalize,
    parseLevel,
    unshift,
    subjectPriorityLabels,
    levelCategoryLabels,
    genderLabels,
    isSidebarOpenOnXlDevice,
    numberWithOrdinal,
    isAdmin,
    isTeacher,
};
