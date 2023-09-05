<template>
    <FormElement
        :title="$t('allocateItem.allocateItem')"
        @submit="allocateItem"
    >
        <TextInput
            v-if="!allocateItemForm.recipient_user"
            v-model="searchText"
            :placeholder="$t('allocateItem.searchUser')"
            class="w-full"
        />

        <div v-if="allocateItemForm.recipient_user">
            <div
                class="flex w-fit space-x-1 rounded-lg bg-brand-400 px-3 py-1 text-sm font-light text-white shadow-sm"
            >
                <span>
                    {{ $t("allocateItem.selectedUser") }}
                    {{ allocateItemForm.recipient_user.name }}
                </span>
                <XMarkIcon
                    class="w-4 cursor-pointer text-white hover:scale-110 hover:text-red-500"
                    @click="allocateItemForm.recipient_user = null"
                />
            </div>
        </div>
        <div v-else>
            <div
                v-for="(item, index) in filteredUsers"
                :key="index"
                class="flex cursor-pointer justify-evenly py-3 text-sm text-black hover:bg-brand-350 hover:text-white"
                :class="index % 2 === 0 ? 'bg-brand-50' : 'bg-white'"
                @click="
                    allocateItemForm.recipient_user = item;
                    allocateItemForm.selectedItem = props.selectedItemId;
                "
            >
                <span class="w-5/12 text-center font-medium capitalize">{{
                    item.name
                }}</span>
                <span class="w-4/12 text-center">
                    {{ item.phone_number }}
                </span>
                <span class="w-3/12 text-center font-medium capitalize">{{
                    item.type
                }}</span>
            </div>
        </div>

        <TextInput
            v-model="allocateItemForm.quantity"
            :placeholder="$t('common.quantity')"
            :label="$t('common.quantity')"
            type="number"
            :error="usePage().props.errors.quantity"
        />
    </FormElement>

    <Loading v-if="isLoading" :is-full-screen="true" type="bounce" />
</template>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import FormElement from "@/Components/FormElement.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import debounce from "lodash/debounce";
import Loading from "@/Components/Loading.vue";
import { XMarkIcon } from "@heroicons/vue/20/solid";

const emit = defineEmits(["close"]);
const props = defineProps({
    selectedItemId: {
        type: Number,
        required: true,
    },
});

const searchText = ref("");
const users = computed(() => usePage().props.users ?? null);
const isLoading = ref(false);

const allocateItemForm = useForm({
    recipient_user: null,
    selectedItem: null,
    quantity: "",
});

const searchUser = () => {
    isLoading.value = true;
    router.get(
        "/admin/inventory",
        {
            search_query: searchText.value,
        },
        {
            only: ["users"],
            preserveState: true,
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );
};

const debouncedUpdate = debounce(searchUser, 300);

watch(searchText, () => {
    allocateItemForm.recipient_user = null;
    debouncedUpdate();
});

const filteredUsers = computed(() => {
    if (users.value === null) return [];
    return users.value.map((user) => ({
        name: user.name,
        type: user.type,
        id: user.id,
        phone_number: user.phone_number,
    }));
});

const allocateItem = () => {
    router.post(
        "/admin/inventory/allocate",
        {
            recipient_user_id: allocateItemForm.recipient_user.id,
            item_id: allocateItemForm.selectedItem,
            quantity: allocateItemForm.quantity,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                emit("close");
            },
        }
    );
};
</script>
<style scoped></style>
