<div x-data="{ 
    open: localStorage.getItem('chatOpen') === 'true', 
    unreadCount: 0 
}" 
x-init="$watch('open', value => {
    localStorage.setItem('chatOpen', value);
    if(value) unreadCount = 0; // Reset angka saat dibuka
})"
x-on:new-message.window="if(!open) { unreadCount++; new Audio('https://assets.mixkit.co/sfx/preview/mixkit-software-interface-start-2574.mp3').play().catch(()=>{}); }"
class="fixed bottom-6 right-6 z-[999]">

<button @click="open = !open" 
    class="w-14 h-14 bg-blue-600 rounded-full shadow-2xl flex items-center justify-center text-white transition-all active:scale-95 hover:rotate-12 relative">
    
    <i class="fa-solid text-xl" :class="open ? 'fa-xmark' : 'fa-comment-dots'"></i>
    
    <template x-if="unreadCount > 0 && !open">
        <span class="absolute -top-1 -right-1 flex items-center justify-center bg-red-500 text-white text-[10px] font-black w-6 h-6 rounded-full border-2 border-white animate-bounce shadow-lg">
            <span x-text="unreadCount"></span>
        </span>
    </template>
</button>

<div x-show="open" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-10 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200"
    class="absolute bottom-20 right-0 w-[350px] md:w-[400px] h-[500px] bg-white rounded-[2rem] shadow-2xl border border-slate-100 flex flex-col overflow-hidden" x-cloak>
    
    <div class="p-5 bg-blue-600 text-white flex items-center justify-between shadow-lg shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-headset"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm leading-none  tracking-tighter">Support w3site</h4>
                <span class="text-[9px] opacity-80 tracking-[0.2em] font-black">Live Chat</span>
            </div>
        </div>
        <button @click="open = false" class="hover:bg-white/10 p-2 rounded-lg transition-colors">
            <i class="fa-solid fa-minus"></i>
        </button>
    </div>

    <div id="widgetChatBox" class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50/50 custom-scrollbar text-sm flex flex-col">
    </div>

    <div class="p-4 bg-white border-t border-slate-100 shrink-0">
        <form id="widgetChatForm" class="flex items-end gap-2"> <textarea id="widgetInput" 
                rows="1" 
                class="flex-1 border-none bg-slate-100 rounded-xl px-4 py-3 text-xs focus:ring-2 focus:ring-blue-500/10 shadow-inner text-slate-700 resize-none custom-scrollbar"
                placeholder="Tulis pesan ke admin..."
                style="max-height: 80px; min-height: 44px; line-height: 1.5;"
                oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, 80) + 'px'"></textarea>
                
            <button type="submit" id="btnWidgetSend" class="bg-blue-600 text-white w-11 h-11 rounded-xl flex items-center justify-center shadow-lg active:scale-90 transition-all shrink-0">
                <i class="fa-solid fa-paper-plane text-xs"></i>
            </button>
        </form>
    </div>
</div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const SB_URL = "https://lymknuizgzhvufyvapwh.supabase.co";
        const SB_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imx5bWtudWl6Z3podnVmeXZhcHdoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3Njk0OTQ1NTgsImV4cCI6MjA4NTA3MDU1OH0.UtTrgmN-IiT0Yn4Dy6ftWk79uI0HO0hARzUVDKZsk4w";
        const sb = supabase.createClient(SB_URL, SB_KEY);
        const userId = String("{{ Auth::user()->id }}");
        const chatBox = document.getElementById('widgetChatBox');

        const widgetInput = document.getElementById('widgetInput');

        widgetInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault(); // Mencegah baris baru saat tekan Enter
                document.getElementById('widgetChatForm').dispatchEvent(new Event('submit'));
            }
        });

        document.getElementById('widgetChatForm').addEventListener('submit', function() {
            setTimeout(() => {
                widgetInput.style.height = '44px'; 
            }, 10);
        });

        function addBubble(msg) {
            const isMe = String(msg.is_admin) === 'false';
            const html = `
                <div class="flex ${isMe ? 'justify-end' : 'justify-start'} animate-bubble-in">
                    <div class="flex flex-col ${isMe ? 'items-end' : 'items-start'} max-w-[85%]">
                        <div class="${isMe ? 'bg-blue-600 text-white rounded-[1.2rem] rounded-tr-none shadow-md shadow-blue-50' : 'bg-white text-slate-700 border border-slate-100 rounded-[1.2rem] rounded-tl-none shadow-sm'} px-4 py-2.5">
                            <p class="leading-relaxed font-medium text-[13px]">${msg.message}</p>
                        </div>
                    </div>
                </div>`;
            chatBox.insertAdjacentHTML('beforeend', html);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Load History
        sb.from('discussions').select('*').eq('project_id', userId).order('created_at', { ascending: true })
            .then(({ data }) => { 
                if(data) data.forEach(m => addBubble(m)); 
            });

        // Listen for new messages
        sb.channel('widget-realtime').on('postgres_changes', { 
            event: 'INSERT', 
            schema: 'public', 
            table: 'discussions', 
            filter: `project_id=eq.${userId}` 
        }, payload => {
            const msg = payload.new;
            addBubble(msg);

            // Jika yang kirim Admin, kirim sinyal ke Alpine.js
            if(String(msg.is_admin) === 'true') {
                window.dispatchEvent(new CustomEvent('new-message'));
            }
        }).subscribe();

        // Send Message
        document.getElementById('widgetChatForm').onsubmit = async (e) => {
            e.preventDefault();
            const input = document.getElementById('widgetInput');
            const text = input.value.trim();
            if(!text) return;
            input.value = '';
            await sb.from('discussions').insert([{ 
                project_id: userId, 
                sender_name: "{{ Auth::user()->name }}", 
                message: text, 
                is_admin: false 
            }]);
        };
    });
</script>