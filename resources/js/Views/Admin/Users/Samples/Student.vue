<template>
    <div
        class="scrollbar-hide mb-2 flex w-full flex-col space-y-3 overflow-y-scroll rounded-lg bg-white p-4 text-center text-sm"
    >
        Prepare a CSV file with the following columns, ensure all data is
        accurate and correctly spelled. <br />
        <span class="font-medium italic"
            >* Remember both student and guardian data are on the same
            file</span
        >

        <div class="mt-2 flex w-full flex-col">
            <div class="scrollbar-hide -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div
                    class="flex min-w-full flex-col space-y-5 py-2 align-middle sm:px-6 lg:px-8"
                >
                    <div
                        class="scrollbar-hide overflow-hidden border-b border-gray-200 shadow sm:rounded-lg"
                    >
                        <div class="p-2 font-semibold">Student Data</div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-brand-400 text-brand-text-500">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider"
                                    >
                                        name
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider"
                                    >
                                        grade
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider"
                                    >
                                        gender
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider"
                                    >
                                        date_of_birth
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                    >
                                        Lily Abebe
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                    >
                                        10
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                    >
                                        male/female
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                    >
                                        10/06/2023
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="scrollbar-hide w-full overflow-hidden border-b border-gray-200 shadow sm:rounded-lg"
                    >
                        <div class="p-2 font-semibold">Guardian Data</div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-brand-400 text-brand-text-500">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider"
                                    >
                                        guardian_name
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider"
                                    >
                                        guardian_email
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider"
                                    >
                                        guardian_phone_number
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider"
                                    >
                                        guardian_gender
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider"
                                    >
                                        guardian_relation
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                    >
                                        Abebe Kebede
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                    >
                                        example@example.com
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                    >
                                        0911223344
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                    >
                                        male/female
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                    >
                                        mother/father/aunt..
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
