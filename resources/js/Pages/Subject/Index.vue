<template>
    <div
        class="w-full rounded-b-md bg-white"
    >
        <div class="body-font overflow-hidden">
            <div class="mx-auto px-5">
                <div class="-m-12 flex flex-wrap">
                    <div class="flex w-full flex-col p-12">
                        <div
                            class="flex flex-col rounded-2xl p-3 text-gray-100 md:flex-row lg:flex-row lg:justify-around"
                        >
                            <h2
                                class="flex w-5/12 justify-center font-medium tracking-tight text-black"
                            >
                                Subjects
                            </h2>
                            <div class="flex flex-row items-center">
                            <!--            Search input-->
                                <input
                                type="text"
                                placeholder="Search for subject"
                                class=" w-8/12 rounded-md pl-2 text-[0.6rem] font-light" />
                                <div class="flex w-8/12 justify-center">
                                    <button
                                        class="text-dark-50 my-4 mt-6 h-9 w-8/12 rounded-md bg-gray-300 font-light"
                                    >
                                        <a href="/subject-add">
                                            Add Subject
                                        </a>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <table
                                class="text-sm text-gray-100"
                        >
                            <thead
                                class="bg-dark-150 items-center text-xs dark:bg-zinc-800"
                            >
                            <tr>
                                <th
                                    scope="col"
                                    class="p-3 text-left font-light"
                                >
                                    Id
                                </th>
                                <th
                                    scope="col"
                                    class="p-3 text-left font-light"
                                >
                                    Subject
                                </th>
                                <th
                                    scope="col"
                                    class="p-3 text-left font-light"
                                >
                                    Edit
                                </th>
                                <th
                                    scope="col"
                                    class="p-3 text-left font-light"
                                >
                                    Delete
                                </th>
                            </tr>
                            </thead>
                            <tbody
                                class="text-dark-50 bg-gray-300 text-xs"
                            >
                            <tr
                            >
                                    <td class="p-2">
                                   1
                                </td>
                                <td>Bio</td>

                                <td>
                                 Edit
                                </td>
                                <td>
                                   Delete
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
