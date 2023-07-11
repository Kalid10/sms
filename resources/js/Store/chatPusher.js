import { defineStore } from "pinia";
import { usePage } from "@inertiajs/vue3";
import axiosClient from "@/https";
import useMessageStore from "@/Store/chat";

export const useChatPusherStore = defineStore({
    id: "chatStore",
    state: () => ({
        isAuthenticated: false,
    }),
    actions: {
        getCsrfToken() {
            return document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
        },

        getPusherInstance(csrfToken) {
            return new Pusher(chatify.pusher.key, {
                encrypted: chatify.pusher.options.encrypted,
                cluster: chatify.pusher.options.cluster,
                authEndpoint: "/chat/api/chat/auth",
                auth: {
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                },
            });
        },

        subscribeToChannels(pusher, channelName, auth_id) {
            let channel = pusher.subscribe(channelName + "." + auth_id);
            let clientSendChannel;
            let clientListenChannel;

            if (useMessageStore().activeChat?.id) {
                clientSendChannel = pusher.subscribe(
                    `${channelName}.${useMessageStore().activeChat.id}`
                );
                clientListenChannel = pusher.subscribe(
                    `${channelName}.${auth_id}`
                );
            }

            return { channel, clientSendChannel, clientListenChannel };
        },

        async listenToMessagingEvent(channel) {
            channel.bind("messaging", async (data) => {
                if (
                    data.from_id === useMessageStore().activeChat.id &&
                    data.to_id === usePage().props.auth.user.id
                ) {
                    await useMessageStore().markAsRead(data.id);

                    // Check if the message is already in the message list
                    const messageExists =
                        useMessageStore().messages.messages.find(
                            (message) => message.id === data.id
                        );

                    if (!messageExists) {
                        const reshapedMessage = this.reshapeMessage(
                            data.message
                        );
                        useMessageStore().messages.messages.unshift(
                            reshapedMessage
                        );
                    }
                }
            });
        },

        reshapeMessage(message) {
            const {
                id,
                from_id,
                to_id,
                created_at,
                isSender,
                seen,
                timeAgo,
                message: body,
                attachment,
            } = message;

            return {
                id,
                from_id,
                to_id,
                created_at,
                isSender,
                seen,
                timeAgo,
                body, // Here we use the 'body' key to hold the actual message text
                attachment,
            };
        },

        async listen() {
            if (!this.isAuthenticated) await this.authenticate();

            const csrfToken = this.getCsrfToken();
            const pusher = this.getPusherInstance(csrfToken);

            const channelName = "private-chatify";
            const auth_id = usePage().props.auth.user.id;

            const { channel } = this.subscribeToChannels(
                pusher,
                channelName,
                auth_id
            );

            console.log("listening");
            await this.listenToMessagingEvent(channel);
        },

        async authenticate() {
            try {
                const response = await axiosClient.post("/chat/api/chat/auth", {
                    socket_id: "15886.48502",
                    channel_name: "private-chatify",
                });

                this.isAuthenticated = true;
            } catch (error) {
                console.error(error);
                this.isAuthenticated = false;
            }
        },
    },
});
