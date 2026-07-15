<div
    x-data="publicChat({{ $garansi->id }})"
    x-init="init()"
    class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl flex flex-col h-[520px]"
>

    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-4 text-white">

        <div class="flex items-center justify-between">

            <div class="flex items-center gap-3">

                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">

                    <i class="fas fa-user-headset text-lg"></i>

                </div>

                <div>

                    <h2 class="font-semibold">
                        Teknisi Garansi
                    </h2>

                    <p class="text-xs text-blue-100 flex items-center gap-1">

                        <span class="w-2 h-2 rounded-full bg-green-400"></span>

                        Online

                    </p>

                </div>

            </div>

            <div
                class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">

                <i class="fas fa-comments"></i>

            </div>

        </div>

    </div>

    {{-- Chat --}}
    <div
        x-ref="scrollBox"
        class="flex-1 overflow-y-auto bg-slate-50 px-4 py-4 space-y-3">

        <template
            x-for="chat in [...messages].reverse()"
            :key="chat.id">

            <div
                class="flex"
                :class="chat.sender_type=='customer'
                ? 'justify-end'
                : 'justify-start'">

                <div
                    class="max-w-[80%] rounded-2xl px-4 py-3 shadow-sm"
                    :class="chat.sender_type=='customer'
                    ? 'bg-blue-600 text-white rounded-br-md'
                    : 'bg-white border border-slate-200 text-slate-700 rounded-bl-md'">

                    <p
                        class="text-sm leading-relaxed whitespace-pre-line"
                        x-text="chat.message">
                    </p>

                    <p
                        class="mt-2 text-[10px] opacity-70 text-right"
                        x-text="formatTime(chat.created_at)">
                    </p>

                </div>

            </div>

        </template>

        <div
            x-show="messages.length==0"
            class="h-full flex flex-col items-center justify-center text-slate-400">

            <i class="fas fa-comments text-5xl mb-4"></i>

            <p class="font-medium">

                Belum ada percakapan

            </p>

            <p class="text-xs mt-1">

                Silakan kirim pesan pertama Anda.

            </p>

        </div>

    </div>

    {{-- Input --}}
    <form
        @submit.prevent="send"
        class="border-t border-slate-200 bg-white p-3">

        <div class="flex items-center gap-2">

            <input
                x-model="newMessage"
                type="text"
                placeholder="Ketik pesan..."

                class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none">

            <button
                type="submit"
                class="h-12 w-12 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">

                <i class="fas fa-paper-plane"></i>

            </button>

        </div>

    </form>

</div>

<script>
function publicChat(garansiId){

    return{

        messages:[],

        newMessage:'',

        loading:false,

        init(){

            this.fetchMessages();

            setInterval(()=>{

                this.fetchMessages(false);

            },3000);

        },

        async fetchMessages(scroll=true){

            try{

                const res=await fetch(`/tracking/${garansiId}/chat`);

                this.messages=await res.json();

                if(scroll){

                    this.$nextTick(()=>{

                        if(this.$refs.scrollBox){

                            this.$refs.scrollBox.scrollTo({

                                top:this.$refs.scrollBox.scrollHeight,

                                behavior:'smooth'

                            });

                        }

                    });

                }

            }catch(e){

                console.log(e);

            }

        },

        async send(){

            if(!this.newMessage.trim()) return;

            if(this.loading) return;

            this.loading=true;

            try{

                await fetch(`/tracking/${garansiId}/chat`,{

                    method:'POST',

                    headers:{

                        'Content-Type':'application/json',

                        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,

                    },

                    body:JSON.stringify({

                        message:this.newMessage

                    })

                });

                this.newMessage='';

                this.fetchMessages(true);

            }finally{

                this.loading=false;

            }

        },

        formatTime(timestamp){

            if(!timestamp) return '';

            const date=new Date(timestamp);

            const now=new Date();

            const isToday=date.toDateString()===now.toDateString();

            const time=date.toLocaleTimeString('id-ID',{

                hour:'2-digit',

                minute:'2-digit'

            });

            if(isToday){

                return time;

            }

            return date.toLocaleDateString('id-ID',{

                day:'2-digit',

                month:'short'

            })+' • '+time;

        }

    }

}
</script>
