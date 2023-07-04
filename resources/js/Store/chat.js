import { defineStore } from "pinia";
import axiosClient from "../https";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export const useMessageStore = defineStore({
    id: "message",
    state: () => ({
        isLoading: false,
        auth_id: computed(() => usePage().props.auth.user.id),
        messages: {},
        contacts: {},
        token: null,
        conversationStatus: false,
        records: {},
        activeChat: {
            id: null,
            name: null,
            avatar: null,
            active_status: null,
        },
        favorites: {},
    }),
    getters: {},
    actions: {
        async authenticate() {
            try {
                const response = await axiosClient.post("/chat/api/chat/auth", {
                    socket_id: "15886.48502",
                    channel_name: "private-chatify",
                });
                this.token = response.data;
            } catch (error) {
                console.error(error);
            }
        },
        async fetchMessages(from) {
            try {
                this.isLoading = true;
                const response = await axiosClient.post(
                    "/chat/api/fetchMessages",
                    {
                        id: from,
                    }
                );
                this.messages = response.data;
                this.isLoading = false;
                return response.data;
            } catch (error) {
                console.error(error);

                this.isLoading = false;
            }
        },

        async getContacts() {
            try {
                this.isLoading = true;
                const { data } = await axiosClient.get("/chat/api/getContacts");
                this.contacts = {};
                this.contacts = data.contacts;

                this.isLoading = false;
            } catch (error) {
                console.error(error);

                this.isLoading = false;
            }
        },

        async sendMessage(message) {
            try {
                const { data } = await axiosClient.post(
                    "/chat/api/sendMessage",
                    message
                );
                const updatedMessage = {
                    ...data.message,
                    body: data.message.message,
                };
                delete updatedMessage.message;
                this.messages.messages.unshift(updatedMessage);
            } catch (error) {
                console.error(error);
            }
        },

        async markAsRead(id) {
            this.isLoading = true;
            try {
                const { data } = await axiosClient.post("/chat/api/makeSeen", {
                    id: id,
                });

                this.isLoading = false;
            } catch (error) {
                console.error(error);

                this.isLoading = false;
            }
        },

        async searchContacts(query) {
            this.isLoading = true;
            try {
                const { data } = await axiosClient.get(
                    `/chat/api/search?input=${query}`
                );
                this.records = data.records;

                this.isLoading = false;
            } catch (error) {
                console.error(error);

                this.isLoading = false;
            }
        },
        async setActiveStatus(status) {
            try {
                const { data } = await axiosClient.post(
                    "/chat/api/setActiveStatus",
                    { status: status }
                );
                this.activeChat.active_status = data.status;
            } catch (error) {
                console.error(error);
            }
        },
        async toggleFavorite(userId) {
            try {
                const { data } = await axiosClient.post("/chat/api/star", {
                    user_id: userId ?? this.activeChat.id,
                });
                await this.getFavorites();
            } catch (error) {
                console.error(error);
            }
        },
        async getFavorites() {
            try {
                const { data } = await axiosClient.post(
                    "/chat/api/favorites",
                    {}
                );
                this.favorites = data.favorites;
            } catch (error) {
                console.error(error);
            }
        },
        async deleteConversation(userId) {
            this.isLoading = true;
            try {
                const { data } = await axiosClient
                    .post("/chat/api/deleteConversation", {
                        id: userId,
                    })
                    .then(() => {
                        this.getContacts();
                        this.fetchMessages(userId);
                        this.isLoading = false;
                    });
            } catch (error) {
                this.isLoading = false;
                console.error(error);
            }
        },
    },
});

export default useMessageStore;
