<div
    x-data="floatingChats()"
    x-init="init()"
    class="fixed bottom-24 md:bottom-6 right-4 md:right-6 z-[60]"
    :class="!isDragging ? 'transition-transform duration-300' : ''"
    :style="`transform: translate(${posX}px, ${posY}px); font-family: 'Segoe UI', Helvetica, Arial, sans-serif;`"
    @mousemove.window="onDrag($event)"
    @mouseup.window="stopDrag($event)"
    @touchmove.window="onDrag($event)"
    @touchend.window="stopDrag($event)"
    @resize.window="checkAlignment()"
>
    <div class="relative flex flex-col items-end">
        {{-- ===================== CHAT WINDOW ===================== --}}
    <template x-if="activeChat">
        <div
            class="absolute w-[360px] max-w-[calc(100vw-2rem)] h-[580px] max-h-[80vh] overflow-hidden rounded-xl shadow-[0_8px_30px_rgba(0,0,0,.22)] flex flex-col"
            :class="[isLeftAligned ? 'left-0' : 'right-0', isTopAligned ? 'top-full mt-3 origin-top' : 'bottom-full mb-3 origin-bottom']"
            style="background:#fff;"
            @click.outside="showEmoji = false"
        >
            {{-- HEADER --}}
            <div
                class="px-2 py-2 flex items-center justify-between text-white shrink-0"
                style="background:#075E54;"
            >
                <div class="flex items-center gap-2 min-w-0">
                    <button
                        @click="activeChat = null; messages=[]; showEmoji=false"
                        class="h-9 w-9 rounded-full hover:bg-white/10 transition flex items-center justify-center shrink-0"
                        aria-label="Kembali"
                    >
                        <i class="fas fa-arrow-left text-sm"></i>
                    </button>

                    <div
                        class="h-10 w-10 rounded-full flex items-center justify-center text-[15px] font-semibold shrink-0"
                        style="background:#25D366; color:#fff;"
                    >
                        <span x-text="activeChat.nama.charAt(0).toUpperCase()"></span>
                    </div>

                    <div class="min-w-0">
                        <p
                            class="truncate font-medium text-[15px] leading-tight"
                            x-text="activeChat.nama"
                        ></p>
                        <p class="text-[12px] leading-tight opacity-80">klik untuk info kontak</p>
                    </div>
                </div>

                <div class="flex items-center shrink-0">
                    @if(auth()->user()?->role === 'admin')
                    <button
                        type="button"
                        @click="deleteHistory(activeChat)"
                        :disabled="deleting"
                        class="h-9 w-9 rounded-full hover:bg-white/10 transition flex items-center justify-center disabled:opacity-50"
                        title="Hapus history chat"
                        aria-label="Hapus history chat"
                    >
                        <i class="fas fa-trash-can text-sm" x-show="!deleting"></i>
                        <i class="fas fa-spinner fa-spin text-sm" x-show="deleting"></i>
                    </button>
                    @endif
                    <a
                        :href="`/garansi/${activeChat.id}`"
                        class="h-9 w-9 rounded-full hover:bg-white/10 transition flex items-center justify-center"
                        title="Buka Detail Garansi"
                    >
                        <i class="fas fa-up-right-from-square text-sm"></i>
                    </a>
                    <button
                        @click="closeAll"
                        class="h-9 w-9 rounded-full hover:bg-white/10 transition flex items-center justify-center"
                        aria-label="Tutup"
                    >
                        <i class="fas fa-xmark text-base"></i>
                    </button>
                </div>
            </div>

            {{-- CHAT --}}
            <div
                x-ref="scrollBox"
                class="flex-1 overflow-y-auto px-3 py-3 space-y-1"
                style="background-color:#E5DDD5; background-image:url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23d4ccc4\' fill-opacity=\'0.35\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"
            >
                <template x-for="chat in [...messages].reverse()" :key="chat.id">
                    <div
                        class="flex"
                        :class="chat.sender_type === 'teknisi' ? 'justify-end' : 'justify-start'"
                    >
                        <div
                            class="relative max-w-[82%] px-2.5 pt-1.5 pb-1 shadow-sm break-words"
                            :class="chat.sender_type === 'teknisi'
                                ? 'rounded-lg rounded-tr-none'
                                : 'rounded-lg rounded-tl-none'"
                            :style="chat.sender_type === 'teknisi'
                                ? 'background:#DCF8C6; color:#111b21;'
                                : 'background:#FFFFFF; color:#111b21;'"
                        >
                            <p
                                class="text-[14.2px] leading-[19px] whitespace-pre-line pr-12"
                                x-text="chat.message"
                            ></p>
                            <span
                                class="absolute bottom-1 right-2 inline-flex items-center gap-1 text-[11px] leading-none whitespace-nowrap"
                                style="color:#667781;"
                            >
                                <span x-text="formatTime(chat.created_at)"></span>
                                <template x-if="chat.sender_type === 'teknisi'">
                                    <i class="fas fa-check-double text-[10px]" style="color:#53bdeb;"></i>
                                </template>
                            </span>
                        </div>
                    </div>
                </template>
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

            {{-- INPUT --}}
            <form
                @submit.prevent="send"
                class="px-2 py-1.5 flex items-end gap-1.5 shrink-0"
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

    {{-- ===================== LIST CHAT ===================== --}}
    <template x-if="listOpen && !activeChat">
        <div
            class="absolute w-[360px] max-w-[calc(100vw-2rem)] max-h-[600px] h-[80vh] overflow-hidden rounded-xl shadow-[0_8px_30px_rgba(0,0,0,.22)] flex flex-col"
            :class="[isLeftAligned ? 'left-0' : 'right-0', isTopAligned ? 'top-full mt-3 origin-top' : 'bottom-full mb-3 origin-bottom']"
            style="background:#fff;"
        >
            <div
                class="px-4 py-3 flex items-center justify-between text-white shrink-0"
                style="background:#075E54;"
            >
                <div>
                    <h3 class="font-medium text-[18px] leading-tight">Chats</h3>
                    <p class="text-[12px] opacity-80 mt-0.5">
                        <span x-text="visibleChats.length"></span> percakapan
                    </p>
                </div>
                <button
                    @click="listOpen=false"
                    class="h-9 w-9 rounded-full hover:bg-white/10 transition flex items-center justify-center"
                    aria-label="Tutup"
                >
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <div class="px-3 py-2 shrink-0" style="background:#fff;">
                <div
                    class="flex items-center gap-3 rounded-lg px-3 py-2"
                    style="background:#F0F2F5;"
                >
                    <i class="fas fa-magnifying-glass text-sm" style="color:#54656f;"></i>
                    <span class="text-[14px]" style="color:#667781;">Cari atau mulai chat baru</span>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto">
                <template x-for="item in visibleChats" :key="item.id">
                    <div
                        @click="openChat(item)"
                        class="group flex cursor-pointer items-center gap-3 px-3 py-3 transition hover:bg-[#f5f6f6] border-b"
                        style="border-color:#f0f2f5;"
                    >
                        <div
                            class="relative h-12 w-12 rounded-full flex items-center justify-center font-semibold text-[17px] shrink-0"
                            style="background:#dfe5e7; color:#54656f;"
                        >
                            <span x-text="item.nama.charAt(0).toUpperCase()"></span>
                        </div>

                        <div class="min-w-0 flex-1 border-b-0">
                            <div class="flex items-baseline justify-between gap-2">
                                <p
                                    class="truncate font-normal text-[16px] leading-tight"
                                    style="color:#111b21;"
                                    x-text="item.nama"
                                ></p>
                                <span
                                    class="text-[12px] shrink-0"
                                    :style="item.unread > 0 ? 'color:#25D366;' : 'color:#667781;'"
                                    x-text="item.last_message_at ? formatListTime(item.last_message_at) : ''"
                                ></span>
                            </div>
                            <div class="flex items-center justify-between gap-2 mt-0.5">
                                <p
                                    class="truncate text-[13px] leading-tight"
                                    style="color:#667781;"
                                    x-text="item.last_message || 'Belum ada pesan'"
                                ></p>
                                <span
                                    x-show="item.unread > 0"
                                    x-text="item.unread"
                                    class="shrink-0 flex h-5 min-w-[20px] px-1.5 items-center justify-center rounded-full text-[11px] font-semibold text-white"
                                    style="background:#25D366;"
                                ></span>
                            </div>
                        </div>

                        <div class="flex items-center shrink-0 gap-0.5">
                            @if(auth()->user()?->role === 'admin')
                            <button
                                @click.stop="deleteHistory(item)"
                                :disabled="deleting"
                                class="opacity-0 group-hover:opacity-100 transition shrink-0 h-8 w-8 flex items-center justify-center hover:text-red-500 disabled:opacity-50"
                                style="color:#8696a0;"
                                title="Hapus history chat"
                                aria-label="Hapus history chat"
                            >
                                <i class="fas fa-trash-can text-sm"></i>
                            </button>
                            @endif
                            <button
                                @click.stop="dismiss(item)"
                                class="opacity-0 group-hover:opacity-100 transition shrink-0 h-8 w-8 flex items-center justify-center"
                                style="color:#8696a0;"
                                aria-label="Sembunyikan"
                            >
                                <i class="fas fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                </template>

                <div
                    x-show="visibleChats.length==0"
                    class="py-16 px-6 text-center"
                >
                    <div
                        class="mx-auto mb-4 h-16 w-16 rounded-full flex items-center justify-center"
                        style="background:#F0F2F5; color:#8696a0;"
                    >
                        <i class="fas fa-comments text-2xl"></i>
                    </div>
                    <p class="text-[15px]" style="color:#667781;">Tidak ada percakapan</p>
                </div>
            </div>
        </div>
    </template>

    {{-- ===================== FLOATING BUTTON ===================== --}}
    <button
        x-ref="bubbleBtn"
        @mousedown="startDrag($event)"
        @touchstart="startDrag($event)"
        @click="if (!hasDragged) { listOpen = !listOpen; if (!listOpen) closeAll() }"
        class="relative flex h-14 w-14 items-center justify-center rounded-full text-white shadow-[0_4px_14px_rgba(0,168,132,.45)] hover:scale-105 hover:brightness-110 active:scale-95"
        :class="isDragging ? 'cursor-grabbing scale-105 brightness-110' : 'cursor-grab transition-all duration-200'"
        style="background:#25D366;"
        aria-label="Buka chat"
    >
        <i class="fas fa-comments text-xl"></i>

        <span
            x-show="totalUnread > 0"
            x-transition.scale
            x-text="totalUnread"
            class="absolute -top-1 -right-1 z-20 flex h-5 min-w-[20px] px-1 items-center justify-center rounded-full text-[11px] font-bold text-white ring-2 ring-white"
            style="background:#ea4335;"
        ></span>
    </button>
    </div>
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
        deleting: false,
        isAdmin: {{ auth()->user()?->role === 'admin' ? 'true' : 'false' }},
        showEmoji: false,
        emojis: [
            '😀','😁','😂','🤣','😊','😍','😘','😎',
            '😢','😭','😡','👍','👎','👏','🙏','🔥',
            '❤️','✨','🎉','✅','❌','⭐','💪','🤝',
            '📷','📱','💻','🛠️','📦','🚚','💬','👋'
        ],
        isDragging: false,
        hasDragged: false,
        isLeftAligned: false,
        isTopAligned: false,
        startX: 0,
        startY: 0,
        posX: 0,
        posY: 0,

        init() {
            this.posX = parseFloat(localStorage.getItem('bubblePosX')) || 0;
            this.posY = parseFloat(localStorage.getItem('bubblePosY')) || 0;
            this.$nextTick(() => this.checkAlignment());
            
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
        
        startDrag(e) {
            if (e.type === 'mousedown' && e.button !== 0) return;
            this.isDragging = true;
            this.hasDragged = false;
            let clientX = e.clientX || (e.touches && e.touches[0].clientX);
            let clientY = e.clientY || (e.touches && e.touches[0].clientY);
            this.startX = clientX - this.posX;
            this.startY = clientY - this.posY;
        },
        
        onDrag(e) {
            if (!this.isDragging) return;
            e.preventDefault();
            let clientX = e.clientX || (e.touches && e.touches[0].clientX);
            let clientY = e.clientY || (e.touches && e.touches[0].clientY);
            let newPosX = clientX - this.startX;
            let newPosY = clientY - this.startY;
            
            if (Math.abs(newPosX - this.posX) > 3 || Math.abs(newPosY - this.posY) > 3) {
                this.hasDragged = true;
            }
            this.posX = newPosX;
            this.posY = newPosY;
            this.checkAlignment();
        },
        
        checkAlignment() {
            if (this.$refs.bubbleBtn) {
                let rect = this.$refs.bubbleBtn.getBoundingClientRect();
                let centerX = rect.left + rect.width / 2;
                let centerY = rect.top + rect.height / 2;
                this.isLeftAligned = centerX < window.innerWidth / 2;
                this.isTopAligned = centerY < window.innerHeight / 2;
            }
        },
        
        stopDrag(e) {
            if (!this.isDragging) return;
            this.isDragging = false;
            if (this.hasDragged) {
                localStorage.setItem('bubblePosX', this.posX);
                localStorage.setItem('bubblePosY', this.posY);
            }
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

        insertEmoji(emoji) {
            this.newMessage = (this.newMessage || '') + emoji;
            this.$nextTick(() => {
                if (this.$refs.messageInput) this.$refs.messageInput.focus();
            });
        },

        async fetchActive() {
            try {
                const res = await fetch('{{ route('garansi.chat.active') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                if (res.status === 401) {
                    window.location.reload();
                    return;
                }
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

        async deleteHistory(item) {
            if (!this.isAdmin || !item) return;
            if (this.deleting) return;

            const ok = confirm(
                `Hapus seluruh history chat dengan "${item.nama}"?\n\nSemua pesan akan dihapus permanen.`
            );
            if (!ok) return;

            this.deleting = true;
            try {
                const res = await fetch(`/garansi/${item.id}/chat`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,
                        'Accept': 'application/json',
                    },
                });

                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    alert(data.message || 'Gagal menghapus history chat.');
                    return;
                }

                if (this.activeChat && this.activeChat.id === item.id) {
                    this.activeChat = null;
                    this.messages = [];
                    this.showEmoji = false;
                }

                this.chats = this.chats.filter(c => c.id !== item.id);
                this.fetchActive();
            } catch (e) {
                console.log(e);
                alert('Gagal menghapus history chat.');
            } finally {
                this.deleting = false;
            }
        },

        async openChat(item) {
            this.activeChat = item;
            this.showEmoji = false;
            item.unread = 0;
            await this.loadMessages(true);
        },

        async loadMessages(scroll = true) {
            if (!this.activeChat) return;
            try {
                const res = await fetch(
                    `/garansi/${this.activeChat.id}/chat`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                );
                if (res.status === 401) {
                    window.location.reload();
                    return;
                }
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
            this.showEmoji = false;
        },

        async send() {
            if (!this.newMessage.trim()) return;
            if (!this.activeChat) return;
            if (this.loading) return;
            this.loading = true;
            this.showEmoji = false;
            try {
                await fetch(`/garansi/${this.activeChat.id}/chat`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message: this.newMessage.trim() })
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
            return date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        formatListTime(timestamp) {
            if (!timestamp) return '';
            const date = new Date(timestamp);
            const now = new Date();
            const isToday = date.toDateString() === now.toDateString();
            if (isToday) {
                return date.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
            const yesterday = new Date(now);
            yesterday.setDate(yesterday.getDate() - 1);
            if (date.toDateString() === yesterday.toDateString()) {
                return 'Kemarin';
            }
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short'
            });
        }
    }
}
</script>
