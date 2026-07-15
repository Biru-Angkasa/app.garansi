<div
    x-data="floatingChats()"
    x-init="init()"
    class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-4"
>

    {{-- ===================== CHAT WINDOW ===================== --}}
    <template x-if="activeChat">
        <div
            class="w-[360px] h-[580px] overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_20px_60px_rgba(0,0,0,.18)] flex flex-col">

            {{-- HEADER --}}
            <div
                class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-5 py-4 flex items-center justify-between text-white">

                <div class="flex items-center gap-3 min-w-0">

                    <button
                        @click="activeChat = null; messages=[]"
                        class="h-9 w-9 rounded-full hover:bg-white/10 transition flex items-center justify-center">
                        <i class="fas fa-arrow-left"></i>
                    </button>

                    <div
                        class="h-11 w-11 rounded-full bg-amber-500 flex items-center justify-center text-lg font-bold shadow">

                        <span x-text="activeChat.nama.charAt(0).toUpperCase()"></span>

                    </div>

                    <div class="min-w-0">

                        <p
                            class="truncate font-semibold text-sm"
                            x-text="activeChat.nama">
                        </p>

                        <p
                            class="text-xs text-slate-300">
                            Percakapan Garansi
                        </p>

                    </div>

                </div>

                <button
                    @click="closeAll"
                    class="h-9 w-9 rounded-full hover:bg-white/10 transition flex items-center justify-center">

                    <i class="fas fa-xmark text-lg"></i>

                </button>

            </div>

            {{-- CHAT --}}
            <div
                x-ref="scrollBox"
                class="flex-1 overflow-y-auto bg-slate-50 px-4 py-4 space-y-3">

                <template
                    x-for="chat in [...messages].reverse()"
                    :key="chat.id">

                    <div
                        class="flex"
                        :class="chat.sender_type === 'teknisi'
                            ? 'justify-end'
                            : 'justify-start'">

                        <div
                            class="max-w-[82%] rounded-2xl px-4 py-3 shadow-sm break-words"
                            :class="chat.sender_type === 'teknisi'
                                ? 'bg-amber-500 text-white rounded-br-md'
                                : 'bg-white border border-slate-200 text-slate-700 rounded-bl-md'">

                            <p
                                class="text-[13px] leading-relaxed"
                                x-text="chat.message">
                            </p>

                            <p
                                class="mt-2 text-[10px] opacity-70 text-right"
                                x-text="formatTime(chat.created_at)">
                            </p>

                        </div>

                    </div>

                </template>

            </div>

            {{-- INPUT --}}
            <form
                @submit.prevent="send"
                class="border-t bg-white p-3">

                <div
                    class="flex items-center gap-2">

                    <input
                        x-model="newMessage"
                        type="text"
                        placeholder="Tulis pesan..."

                        class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm focus:border-amber-500 focus:ring-4 focus:ring-amber-100 outline-none transition">

                    <button
                        class="h-11 w-11 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow transition">

                        <i class="fas fa-paper-plane"></i>

                    </button>

                </div>

            </form>

            {{-- FOOTER --}}
            <a
                :href="`/garansi/${activeChat.id}`"
                class="border-t bg-slate-50 py-3 text-center text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">

                <i class="fas fa-up-right-from-square mr-2"></i>

                Buka Detail Garansi

            </a>

        </div>
    </template>

    {{-- ===================== LIST CHAT ===================== --}}
    <template x-if="listOpen && !activeChat">

        <div
            class="w-[360px] max-h-[600px] overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_20px_60px_rgba(0,0,0,.18)] flex flex-col">

            <div
                class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-5 py-4 flex items-center justify-between text-white">

                <div>

                    <h3 class="font-semibold">
                        Chat Garansi
                    </h3>

                    <p class="text-xs text-slate-300">

                        <span x-text="visibleChats.length"></span>

                        Percakapan

                    </p>

                </div>

                <button
                    @click="listOpen=false"
                    class="h-9 w-9 rounded-full hover:bg-white/10 transition flex items-center justify-center">

                    <i class="fas fa-xmark"></i>

                </button>

            </div>

            <div
                class="flex-1 overflow-y-auto">

                <template
                    x-for="item in visibleChats"
                    :key="item.id">

                    <div
                        @click="openChat(item)"
                        class="group flex cursor-pointer items-center gap-3 border-b border-slate-100 px-4 py-4 transition hover:bg-slate-50">

                        <div
                            class="relative h-12 w-12 rounded-full bg-slate-900 text-white flex items-center justify-center font-semibold">

                            <span
                                x-text="item.nama.charAt(0).toUpperCase()">
                            </span>

                            <span
                                x-show="item.unread>0"
                                x-text="item.unread"
                                class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 text-[10px] flex items-center justify-center text-white ring-2 ring-white">
                            </span>

                        </div>

                        <div class="min-w-0 flex-1">

                            <p
                                class="truncate font-semibold text-slate-800"
                                x-text="item.nama">
                            </p>

                            <p
                                class="truncate text-xs text-slate-500 mt-1"
                                x-text="item.last_message || 'Belum ada pesan'">
                            </p>

                        </div>

                        <button
                            @click.stop="dismiss(item)"
                            class="opacity-0 group-hover:opacity-100 text-slate-400 hover:text-red-500 transition">

                            <i class="fas fa-xmark"></i>

                        </button>

                    </div>

                </template>

                <div
                    x-show="visibleChats.length==0"
                    class="py-20 text-center">

                    <i
                        class="fas fa-comments text-5xl text-slate-300 mb-4">
                    </i>

                    <p class="text-slate-500">

                        Tidak ada percakapan.

                    </p>

                </div>

            </div>

        </div>

    </template>
        {{-- ===================== FLOATING BUTTON ===================== --}}
    <button
        @click="listOpen = !listOpen; if (!listOpen) closeAll()"
        class="group relative flex h-16 w-16 items-center justify-center overflow-hidden rounded-full
               bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700
               text-white shadow-[0_12px_35px_rgba(15,23,42,.45)]
               transition-all duration-300
               hover:scale-110 hover:shadow-[0_20px_45px_rgba(15,23,42,.55)]
               active:scale-95">

        {{-- Glow --}}
        <div
            class="absolute inset-0 rounded-full bg-white/10 opacity-0 transition group-hover:opacity-100">
        </div>

        {{-- Ripple --}}
        <div
            class="absolute h-24 w-24 rounded-full bg-white/10 scale-0 group-hover:scale-100 transition duration-500">
        </div>

        {{-- Icon --}}
        <i
            class="fas fa-comments relative z-10 text-xl transition group-hover:rotate-6">
        </i>

        {{-- Badge --}}
        <span
            x-show="totalUnread > 0"
            x-transition.scale
            x-text="totalUnread"
            class="absolute -top-1 -right-1 z-20
                   flex h-6 min-w-[24px] px-1
                   items-center justify-center
                   rounded-full bg-red-500
                   text-[11px] font-bold text-white
                   ring-4 ring-white shadow-lg">
        </span>

        {{-- Ping Animation --}}
        <span
            x-show="totalUnread > 0"
            class="absolute -top-1 -right-1 h-6 w-6 rounded-full bg-red-400 animate-ping opacity-50">
        </span>

    </button>

</div>

<script>
function floatingChats() {
    return {

        chats: [],
        dismissed: {},

        listOpen: false,
        activeChat: null,

        messages: [],

        newMessage: '',

        loading: false,

        init() {

            this.dismissed = JSON.parse(
                localStorage.getItem('dismissedChats') || '{}'
            );

            this.fetchActive();

            setInterval(() => {

                this.fetchActive();

                if (this.activeChat) {
                    this.loadMessages(false);
                }

            }, 3000);

        },

        get visibleChats() {

            return this.chats.filter(item => {

                const dismissedAt = this.dismissed[item.id];

                if (!dismissedAt) return true;

                return item.last_message_at &&
                    new Date(item.last_message_at) >
                    new Date(dismissedAt);

            });

        },

        get totalUnread() {

            return this.visibleChats.reduce((sum, item) => {

                return sum + item.unread;

            }, 0);

        },

        async fetchActive() {

            try {

                const res = await fetch('{{ route('garansi.chat.active') }}');

                this.chats = await res.json();

            } catch (e) {

                console.log(e);

            }

        },

        dismiss(item) {

            this.dismissed[item.id] = new Date().toISOString();

            localStorage.setItem(
                'dismissedChats',
                JSON.stringify(this.dismissed)
            );

        },

        async openChat(item) {

            this.activeChat = item;

            item.unread = 0;

            await this.loadMessages(true);

        },

        async loadMessages(scroll = true) {

            if (!this.activeChat) return;

            try {

                const res = await fetch(
                    `/garansi/${this.activeChat.id}/chat`
                );

                this.messages = await res.json();

                if (scroll) {

                    this.$nextTick(() => {

                        this.scrollBottom();

                    });

                }

            } catch (e) {

                console.log(e);

            }

        },

        closeAll() {

            this.activeChat = null;

            this.messages = [];

            this.listOpen = false;

        },

        async send() {

            if (!this.newMessage.trim()) return;

            if (!this.activeChat) return;

            if (this.loading) return;

            this.loading = true;

            try {

                await fetch(`/garansi/${this.activeChat.id}/chat`, {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,

                    },

                    body: JSON.stringify({

                        message: this.newMessage

                    })

                });

                this.newMessage = '';

                await this.loadMessages(true);

                this.fetchActive();

            } finally {

                this.loading = false;

            }

        },

        scrollBottom() {

            if (!this.$refs.scrollBox) return;

            this.$refs.scrollBox.scrollTo({

                top: this.$refs.scrollBox.scrollHeight,

                behavior: 'smooth'

            });

        },

        formatTime(timestamp) {

            if (!timestamp) return '';

            const date = new Date(timestamp);

            const now = new Date();

            const isToday =
                date.toDateString() === now.toDateString();

            const time = date.toLocaleTimeString('id-ID', {

                hour: '2-digit',

                minute: '2-digit'

            });

            if (isToday) {

                return time;

            }

            const dateStr = date.toLocaleDateString('id-ID', {

                day: '2-digit',

                month: 'short'

            });

            return `${dateStr}, ${time}`;

        }

    }
}
</script>