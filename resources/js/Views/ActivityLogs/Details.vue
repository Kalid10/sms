<template>

    <div class="mx-2 flex w-full flex-row items-center gap-2 bg-gray-100 px-2">
        <div class="flex w-2/3">

            <div
v-if="log.properties && log.properties.old"
                 class="mt-2 gap-4 p-2">
                <p class="text-xs font-medium text-gray-600">Previous value:</p>
                <div>
                    <p class="text-xs text-gray-700">
                        {{ log.properties.old.name }},
                        who is {{ log.properties.old.type }}
                        with an email: {{ log.properties.old.email }}
                    </p>
                </div>
            </div>

            <div
v-if="log.properties && log.properties.attributes"
                 class="mt-2 gap-4">
                <p class="text-xs font-medium text-gray-600">New value:</p>
                <div>
                    <p class="text-xs text-gray-700">
                        {{ log.properties.attributes.name }},
                        who is {{ log.properties.attributes.type }}
                        with email: {{ log.properties.attributes.email }}
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-2 flex  w-1/3 items-center justify-start text-gray-700">
            <span class="text-xs">Created At: </span>
            <span class="text-xs "> {{ moment(log.created_at).fromNow() }}</span>
        </div>
    </div>


</template>

<script setup>
import moment from "moment";

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    log: {
        type: Array,
        required: true,
    },
});

</script>

<style scoped>
</style>
