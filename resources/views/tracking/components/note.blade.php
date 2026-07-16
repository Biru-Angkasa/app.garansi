<div
    x-data="publicChat({{ $garansi->id }})"
    x-init="init()"
    class="bg-white rounded-2xl border border-slate-200 shadow-lg overflow-hidden flex flex-col relative"
    style="font-family: 'Segoe UI', Helvetica, Arial, sans-serif; box-shadow: 0 10px 28px -6px rgba(15, 23, 42, 0.18), 0 4px 10px -4px rgba(15, 23, 42, 0.1);"
    :style="chatActive ? 'height: 560px;' : 'min-height: 280px;'"
    @click.outside="showEmoji = false"
>
    {{-- Card header --}}
    <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100 shrink-0 bg-white">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Chat Teknisi</h2>
            <p class="text-slate-500 text-xs mt-0.5">Hubungi teknisi terkait status garansi Anda.</p>
        </div>
        <div class="hidden sm:flex w-11 h-11 rounded-xl bg-emerald-100 items-center justify-center text-emerald-600 shrink-0">
            <i class="fa-solid fa-comments"></i>
        </div>
    </div>

    {{-- Prompt: belum memilih --}}
    <div
        x-show="!chatActive && !chatDeclined"
        x-cloak
        class="flex-1 flex flex-col items-center justify-center px-6 py-10 text-center"
    >
        <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 text-2xl mb-4">
            <i class="fa-solid fa-headset"></i>
        </div>
        <h3 class="text-base font-semibold text-slate-800 mb-2">
            Ingin menghubungi tim teknisi kami?
        </h3>
        <p class="text-sm text-slate-500 max-w-sm mb-6 leading-relaxed">
            Tanyakan status perbaikan atau konfirmasi pengiriman langsung ke teknisi.
        </p>
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full max-w-xs">
            <button
                type="button"
                @click="startChat()"
                class="w-full sm:flex-1 px-5 py-2.5 rounded-xl text-white text-sm font-semibold transition hover:brightness-110 active:scale-[0.98]"
                style="background:#00A884;"
            >
                <i class="fa-solid fa-comments mr-2"></i>Ya, hubungi teknisi
            </button>
            <button
                type="button"
                @click="declineChat()"
                class="w-full sm:flex-1 px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 text-sm font-medium transition hover:bg-slate-50 active:scale-[0.98]"
            >
                Tidak, nanti saja
            </button>
        </div>
    </div>

    {{-- Ditolak / ditunda --}}
    <div
        x-show="chatDeclined"
        x-cloak
        class="flex-1 flex flex-col items-center justify-center px-6 py-10 text-center"
    >
        <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl mb-4">
            <i class="fa-regular fa-comment-dots"></i>
        </div>
        <h3 class="text-base font-semibold text-slate-700 mb-2">Baik, tidak masalah</h3>
        <p class="text-sm text-slate-500 max-w-sm mb-5 leading-relaxed">
            Anda bisa menghubungi tim teknisi kapan saja jika membutuhkan bantuan.
        </p>
        <button
            type="button"
            @click="chatDeclined = false"
            class="px-5 py-2.5 rounded-xl text-sm font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition"
        >
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
        </button>
    </div>

    {{-- Chat aktif --}}
    <template x-if="chatActive">
        <div class="flex flex-col flex-1 min-h-0">
            {{-- Header chat (WhatsApp style) --}}
            <div class="px-4 py-2.5 flex items-center justify-between text-white shrink-0" style="background:#075E54;">
                <div class="flex items-center gap-3 min-w-0">
                    <div
                        class="h-10 w-10 rounded-full flex items-center justify-center shrink-0 overflow-hidden ring-1 ring-white/20"
                        style="background:#dfe5e7;"
                        aria-hidden="true"
                    >
                        <i class="fas fa-user text-[18px]" style="color:#aebac1;"></i>
                    </div>
                    <div class="min-w-0">
                        <h2 class="font-medium text-[15px] leading-tight truncate">Teknisi Garansi</h2>
                        <p class="text-[12px] leading-tight opacity-80">online</p>
                    </div>
                </div>
                <button
                    type="button"
                    @click="minimizeChat()"
                    class="h-8 w-8 rounded-full hover:bg-white/10 transition flex items-center justify-center shrink-0"
                    title="Tutup chat"
                    aria-label="Tutup chat"
                >
                    <i class="fas fa-chevron-down text-sm"></i>
                </button>
            </div>

            {{-- Chat area --}}
            <div
                x-ref="scrollBox"
                class="flex-1 overflow-y-auto px-3 py-3 space-y-1 min-h-0"
                style="background-color:#E5DDD5; background-image:url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23d4ccc4\' fill-opacity=\'0.35\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"
            >
                <template x-for="chat in [...messages].reverse()" :key="chat.id">
                    <div
                        class="flex items-end gap-1.5"
                        :class="chat.sender_type=='customer' ? 'justify-end' : 'justify-start'"
                    >
                        <div
                            x-show="chat.sender_type!='customer'"
                            class="h-7 w-7 rounded-full flex items-center justify-center shrink-0 overflow-hidden"
                            style="background:#dfe5e7;"
                            aria-hidden="true"
                        >
                            <i class="fas fa-user text-[12px]" style="color:#aebac1;"></i>
                        </div>

                        <div
                            class="relative max-w-[80%] px-2.5 pt-1.5 pb-1 shadow-sm break-words"
                            :class="chat.sender_type=='customer'
                                ? 'rounded-lg rounded-tr-none'
                                : 'rounded-lg rounded-tl-none'"
                            :style="chat.sender_type=='customer'
                                ? 'background:#DCF8C6; color:#111b21;'
                                : 'background:#FFFFFF; color:#111b21;'"
                        >
                            <p
                                class="text-[14.2px] leading-[19px] whitespace-pre-line pr-12"
                                x-text="chat.message"
                            ></p>
                            <span
                                class="absolute bottom-1 right-2 text-[11px] leading-none whitespace-nowrap"
                                style="color:#667781;"
                                x-text="formatTime(chat.created_at)"
                            ></span>
                        </div>

                        <div
                            x-show="chat.sender_type=='customer'"
                            class="h-7 w-7 rounded-full flex items-center justify-center shrink-0 overflow-hidden"
                            style="background:#cfe9ba;"
                            aria-hidden="true"
                        >
                            <i class="fas fa-user text-[12px]" style="color:#7a9e62;"></i>
                        </div>
                    </div>
                </template>

                <div
                    x-show="messages.length==0"
                    class="h-full flex flex-col items-center justify-center"
                >
                    <div
                        class="max-w-[280px] rounded-lg px-5 py-4 text-center shadow-sm"
                        style="background:#FFEAA7; color:#54656f;"
                    >
                        <i class="fas fa-lock text-xs mb-2 opacity-70"></i>
                        <p class="text-[13px] leading-relaxed">
                            Belum ada percakapan. Silakan kirim pesan pertama Anda.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Emoji picker --}}
            <div
                x-show="showEmoji"
                x-cloak
                x-transition
                class="absolute bottom-[56px] left-2 right-14 z-20 rounded-xl shadow-lg border p-2 grid grid-cols-8 gap-1 max-h-40 overflow-y-auto"
                style="background:#fff; border-color:#e9edef;"
                @click.stop
            >
                <template x-for="emoji in emojis" :key="emoji">
                    <button
                        type="button"
                        @click="insertEmoji(emoji)"
                        class="h-8 w-8 rounded hover:bg-slate-100 text-lg flex items-center justify-center"
                        x-text="emoji"
                    ></button>
                </template>
            </div>

            {{-- Input --}}
            <form
                @submit.prevent="send"
                class="px-2 py-1.5 flex items-end gap-1.5 relative shrink-0"
                style="background:#F0F2F5;"
            >
                <div class="flex-1 flex items-center rounded-full bg-white px-3 py-1.5 shadow-sm min-h-[42px]">
                    <button
                        type="button"
                        @click.stop="showEmoji = !showEmoji"
                        class="h-8 w-8 flex items-center justify-center shrink-0 transition"
                        :style="showEmoji ? 'color:#00A884;' : 'color:#54656f;'"
                        aria-label="Emoji"
                    >
                        <i class="far fa-face-smile text-lg"></i>
                    </button>
                    <input
                        x-ref="messageInput"
                        x-model="newMessage"
                        type="text"
                        placeholder="Ketik pesan"
                        class="flex-1 border-0 bg-transparent px-2 py-1.5 text-[15px] outline-none focus:ring-0"
                        style="color:#111b21;"
                        @focus="showEmoji = false"
                    >
                </div>
                <button
                    type="submit"
                    :disabled="loading || !newMessage.trim()"
                    class="h-[42px] w-[42px] rounded-full flex items-center justify-center text-white shrink-0 shadow transition hover:brightness-110 disabled:opacity-50"
                    style="background:#00A884;"
                    aria-label="Kirim"
                >
                    <i class="fas fa-paper-plane text-sm" x-show="!loading"></i>
                    <i class="fas fa-spinner fa-spin text-sm" x-show="loading"></i>
                </button>
            </form>
        </div>
    </template>
</div>

<script>
function publicChat(garansiId) {
    return {
        messages: [],
        newMessage: '',
        loading: false,
        showEmoji: false,
        chatActive: false,
        chatDeclined: false,
        pollTimer: null,
        emojis: [
            '😀','😁','😂','🤣','😊','😍','😘','😎',
            '😢','😭','😡','👍','👎','👏','🙏','🔥',
            '❤️','✨','🎉','✅','❌','⭐','💪','🤝',
            '📷','📱','💻','🛠️','📦','🚚','💬','👋'
        ],

        async init() {
            await this.fetchMessages(false);
            if (this.messages.length > 0) {
                this.chatActive = true;
                this.startPolling();
            }
        },

        startChat() {
            this.chatActive = true;
            this.chatDeclined = false;
            this.startPolling();
            this.$nextTick(() => this.scrollToBottom());
        },

        declineChat() {
            this.chatDeclined = true;
            this.chatActive = false;
            this.stopPolling();
            this.showEmoji = false;
        },

        minimizeChat() {
            this.chatActive = false;
            this.chatDeclined = false;
            this.stopPolling();
            this.showEmoji = false;
        },

        startPolling() {
            if (this.pollTimer) return;
            this.pollTimer = setInterval(() => {
                if (this.chatActive) this.fetchMessages(false);
            }, 3000);
        },

        stopPolling() {
            if (!this.pollTimer) return;
            clearInterval(this.pollTimer);
            this.pollTimer = null;
        },

        insertEmoji(emoji) {
            this.newMessage = (this.newMessage || '') + emoji;
            this.$nextTick(() => {
                if (this.$refs.messageInput) this.$refs.messageInput.focus();
            });
        },

        async fetchMessages(scroll = true) {
            try {
                const res = await fetch(`/tracking/${garansiId}/chat`);
                this.messages = await res.json();
                if (scroll) this.scrollToBottom();
            } catch (e) {
                console.log(e);
            }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                if (this.$refs.scrollBox) {
                    this.$refs.scrollBox.scrollTo({
                        top: this.$refs.scrollBox.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            });
        },

        async send() {
            if (!this.newMessage.trim()) return;
            if (this.loading) return;
            this.loading = true;
            this.showEmoji = false;
            try {
                await fetch(`/tracking/${garansiId}/chat`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message: this.newMessage.trim() })
                });

                this.newMessage = '';
                this.fetchMessages(true);
            } finally {
                this.loading = false;
            }
        },

        formatTime(timestamp) {
            if (!timestamp) return '';
            const date = new Date(timestamp);
            return date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
}
</script>
