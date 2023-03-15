<template>
<!--    List of subjects-->
<!--    Add Subject Section-->
    <div class="bg-white dark:bg-gray-900">
        <div class="mx-auto max-w-2xl py-8 px-4 lg:py-16">
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add a new Subject</h2>
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Subject Name</label>
                        <input id="name" type="text" name="name" class="focus:ring-primary-600 focus:border-primary-600 dark:focus:ring-primary-500 dark:focus:border-primary-500 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400" placeholder="Type subject name" required="">
                    </div>
                    <div>
                        <label for="category" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Grade</label>
                        <select id="category" class="focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-500 dark:focus:border-primary-500 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400">
                            <option selected="">1</option>
                            <option value="TV">2</option>
                            <option value="PC">3</option>
                            <option value="GA">4</option>
                            <option value="PH">4</option>
                        </select>
                    </div>
                    <div>
                        <label for="category" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">section</label>
                        <select id="category" class="focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-500 dark:focus:border-primary-500 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400">
                            <option selected="">A</option>
                            <option value="TV">B</option>
                            <option value="PC">C</option>
                        </select>
                    </div>
                </div>
                <button
                    class="text-dark-50 my-4 mt-6 h-12 w-full rounded-md bg-gray-300 font-light"
                >
                    Add
                </button>
        </div>
    </div>
</template>
<script setup></script>
