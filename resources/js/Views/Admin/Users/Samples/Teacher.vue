<template>
    <div
        class="scrollbar-hide mb-2 flex w-full flex-col space-y-5 overflow-y-scroll rounded-lg bg-white px-4 py-8 text-center text-sm"
    >
        Prepare a CSV file with the following columns, ensure all data is
        accurate and correctly spelled. <br />

        <div class="mt-4 flex flex-col">
            <div class="scrollbar-hide -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div
                    class="flex min-w-full flex-col space-y-5 py-2 align-middle sm:px-6 lg:px-8"
                >
                    <div
                        class="scrollbar-hide overflow-hidden border-b border-gray-200 shadow sm:rounded-lg"
                    >
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead
                                class="bg-brand-400 text-center text-xs font-medium uppercase tracking-wider text-brand-text-500"
                            >
                                <tr>
                                    <th scope="col" class="px-6 py-3">name</th>
                                    <th scope="col" class="px-6 py-3">email</th>
                                    <th scope="col" class="px-6 py-3">
                                        phone_number
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        gender
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500"
                                    >
                                        Abebe Kebede
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                    >
                                        abebe@gmail.com
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                    >
                                        0912345678
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                    >
                                        male/female
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup></script>
<style scoped></style>
