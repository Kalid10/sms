<template>
    <div class="my-5 w-10/12">
        <Title class="pb-2" title="Teachers" />
        <TeacherTableElement
            :columns="config"
            :selectable="false"
            title="Teachers"
            subtitle="List of all teachers in the school system"
            :data="formattedTeachersData"
        >
            <template #filter>
                <div class="flex w-full gap-4">
                    <TextInput
                        v-model="searchKey"
                        class="w-full"
                        placeholder="Search for a teacher by name"
                    />
                </div>
            </template>

            <template #name-column="{ data }">
                <div class="flex items-start gap-2">
                    <span class="font-medium">{{ data }}</span>
                </div>
            </template>

            <template #email-column="{ data }">
                <div class="flex items-center gap-1">
                    <span class="text-xs">
                        {{ data }}
                    </span>
                </div>
            </template>

            <template #subjects-column="{ data }">
                <div class="flex text-xs">
                    {{ data }}
                </div>
            </template>

            <template #footer>
                <Pagination :links="teachers.links" position="center" />
            </template>

            <template #empty-data>
                <div class="flex flex-col items-center justify-center">
                    <ExclamationTriangleIcon
                        class="mb-2 h-6 w-6 text-negative-50"
                    />
                    <p class="text-sm font-semibold">No data found</p>
                    <div v-if="searchKey.length">
                        <p
                            v-if="searchKey === null"
                            class="text-sm text-gray-500"
                        >
                            No teacher has been enrolled
                        </p>
                        <p v-else class="text-center text-sm text-gray-500">
                            Your search query "<span
                                class="font-medium text-black"
                                >{{ searchKey }}</span
                            >" did not match
                            <span class="block">any teacher's name</span>
                        </p>
                    </div>
                </div>
            </template>
        </TeacherTableElement>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import TeacherTableElement from "@/Components/TableElement.vue";
import { ExclamationTriangleIcon } from "@heroicons/vue/24/outline/index";
import TextInput from "@/Components/TextInput.vue";
import { debounce } from "lodash";
import Pagination from "@/Components/Pagination.vue";
import Title from "@/Views/Teacher/Views/Title.vue";

const teachers = computed(() => {
    return usePage().props.teachers;
});

const formattedTeachersData = computed(() => {
    return teachers.value.data.map((teacher) => {
        // Use Set to get distinct values
        const subjectsSet = new Set();
        teacher.batch_subjects.forEach((bs) =>
            subjectsSet.add(`${bs.subject.full_name}(${bs.batch.level.name})`)
        );
        const subjects = Array.from(subjectsSet).join(", ");

        return {
            id: teacher.id,
            name: teacher.user.name,
            email: teacher.user.email,
            gender: teacher.user.gender,
            homerooms: teacher.homeroom
                .map((hr) => `${hr.batch.level.name}${hr.batch.section}`)
                .join(", "),
            subjects: subjects,
        };
    });
});

const searchKey = ref("");
const perPage = ref(15);

const search = debounce(() => {
    router.get(
        "/admin/teachers/",
        {
            search: searchKey.value,
            perPage: perPage.value,
        },
        {
            only: ["teachers"],
            preserveState: true,
            replace: true,
        }
    );
}, 300);

watch([searchKey, perPage], () => {
    search();
});

const config = [
    {
        name: "Name",
        key: "name",
        link: "/admin/teachers/{id}",
        align: "left",
        type: "custom",
    },
    {
        name: "Email",
        key: "email",
        type: "custom",
        align: "left",
    },
    {
        name: "Homerooms",
        key: "homerooms",
    },
    {
        name: "Gender",
        key: "gender",
        type: "enum",
        options: ["female", "male"],
    },
    {
        name: "Subjects",
        key: "subjects",
        align: "left",
        type: "custom",
    },
];
</script>

<style scoped></style>
