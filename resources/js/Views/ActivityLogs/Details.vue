<template>
    <div class="flex justify-center p-2">
        <div class="mx-3 w-full max-w-2xl overflow-hidden rounded-lg bg-white shadow-md">
            <div class="p-6">
                <p class="text-center text-lg font-bold">Detailed view of the log</p>

                <div class="my-4">
                    <div class="flex items-center justify-between text-gray-700">
                        <div class="text-xs">ID:</div>
                        <div class="text-xs font-bold">{{ log.id }}</div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-gray-700">
                        <div class="text-xs">Batch UUID:</div>
                        <div class="text-xs font-bold">{{ log.batch_uuid }}</div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-gray-700">
                        <div class="text-xs">Causer ID:</div>
                        <div class="text-xs font-bold">{{ log.causer_id }}</div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-gray-700">
                        <div class="text-xs">Causer Type:</div>
                        <div class="text-xs font-bold">{{ log.causer_type }}</div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-gray-700">
                        <div class="text-xs">Causer Description:</div>
                        <div class="text-xs font-bold">{{ log.description }}</div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-gray-700">
                        <div class="text-xs">Event:</div>
                        <div class="text-xs font-bold">{{ log.event }}</div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-gray-700">
                        <div class="text-xs">Log name:</div>
                        <div class="text-xs font-bold">{{ log.log_name }}</div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-gray-700">
                        <div class="text-xs">Subject ID:</div>
                        <div class="text-xs font-bold">{{ log.subject_id }}</div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-gray-700">
                        <div class="text-xs">Subject Type:</div>
                        <div class="text-xs font-bold">{{ log.subject_type }}</div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-gray-700">
                        <div class="text-xs">Created At:</div>
                        <div class="text-xs font-bold">{{ log.created_at }}</div>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-gray-700">
                        <div class="text-xs">Updated At:</div>
                        <div class="text-xs font-bold">{{ log.updated_at }}</div>
                    </div>

                    <div class="mb-6 mt-4">
                        <div v-if="log.properties === null">
                            <p class="text-sm font-bold text-gray-600">Properties</p>
                            <p class="text-gray-700">No properties</p>
                        </div>
                        <div v-else>
                            <p class="text-md font-bold text-gray-600">Properties:</p>
                            <div
v-for="(value, key) in log.properties" :key="key"
                                 class="mt-2 rounded-md bg-gray-100 p-3">
                                <p class="text-sm font-medium text-gray-600">{{ key }}:</p>
                                <p class="text-xs text-gray-700">{{ value }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
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
.text-xs {
    font-size: 0.75rem;
}

.text-sm {
    font-size: 0.875rem;
}

.text-lg {
    font-size: 1.125rem;
}

.text-2xl {
    font-size: 1.5rem;
}

.font-bold {
    font-weight: 700;
}

.font-medium {
    font-weight: 500;
}

.rounded-md {
    border-radius: 0.375rem;
}

.p-6 {
    padding: 1.5rem;
}

.my-4 {
    margin-top: 1rem;
    margin-bottom: 1rem;
}

.mt-4 {
    margin-top: 1rem;
}

.mb-6 {
    margin-bottom: 1.5rem;
}

.bg-gray-100 {
    background-color: #f7fafc;
}

.text-gray-600 {
    color: #718096;
}

.text-gray-700 {
    color: #4a5568;
}
</style>
