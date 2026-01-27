<x-user-layout>
    <div class="h-[calc(100vh-64px)] overflow-hidden flex flex-col items-center bg-slate-50/50 p-0 md:p-6">
        
        <div class="w-full max-w-4xl h-full flex flex-col bg-white rounded-none md:rounded-[25px] border-x-0 md:border border-slate-200 shadow-sm overflow-hidden relative">
            
            <div class="h-[65px] md:h-[70px] px-5 md:px-8 bg-white border-b border-slate-100 flex items-center justify-between z-10 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-9 h-9 md:w-10 md:h-10 bg-blue-600 rounded-xl md:rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-100">
                            <i class="fa-solid fa-headset text-xs md:text-sm"></i>
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full animate-pulse"></div>
                    </div>
                    <div>
                        <h4 class="font-black text-slate-800 tracking-tighter leading-none uppercase text-[12px] md:text-sm">Support System</h4>
                        <span class="text-[8px] md:text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">w3site official</span>
                    </div>
                </div>

                <button onclick="toggleModal(true)" class="group flex items-center gap-2 p-2.5 md:px-4 md:py-2 rounded-xl hover:bg-rose-50 transition-all text-slate-400 hover:text-rose-500 border border-transparent hover:border-rose-100">
                    <i class="fa-solid fa-trash-can text-sm md:text-xs"></i>
                    <span class="hidden md:block text-[10px] font-black uppercase tracking-widest">Clear Chat</span>
                </button>
            </div>

            <div id="chatBox" class="flex-1 overflow-y-auto p-4 md:p-10 space-y-5 md:space-y-6 flex flex-col bg-slate-50/30 custom-scrollbar">
                <div id="emptyState" class="m-auto text-center opacity-30 select-none">
                    <i class="fa-solid fa-comments text-5xl md:text-6xl block mb-4 text-slate-300"></i>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 text-center px-4">Loading conversation...</p>
                </div>
            </div>

            <div class="p-4 md:p-6 bg-white border-t border-slate-100 shrink-0">
                <form id="chatForm" class="flex items-center gap-2 md:gap-4 w-full max-w-4xl mx-auto">
                    <div class="flex-1">
                        <input type="text" id="chatInput" autocomplete="off"
                            class="w-full border-none bg-slate-100 rounded-xl md:rounded-2xl px-5 md:px-6 py-3.5 md:py-4 text-sm focus:ring-2 focus:ring-blue-500/20 text-slate-700 shadow-inner placeholder-slate-400"
                            placeholder="Tulis pesan...">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white h-[48px] md:h-[56px] w-[48px] md:w-auto md:px-8 rounded-xl md:rounded-2xl flex items-center justify-center gap-3 shadow-lg shadow-blue-100 transition-all active:scale-95 shrink-0">
                        <span class="hidden md:block text-xs font-black uppercase tracking-widest">Send</span>
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="clearModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[25px] md:rounded-[30px] p-6 md:p-8 max-w-sm w-full shadow-2xl animate-modal-in">
            <div class="w-14 h-14 md:w-16 md:h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mb-5 md:mb-6 mx-auto">
                <i class="fa-solid fa-trash-can text-xl md:text-2xl"></i>
            </div>
            <h3 class="text-lg md:text-xl font-black text-center text-slate-900 uppercase tracking-tighter">Hapus Chat?</h3>
            <p class="text-slate-500 text-center text-xs md:text-sm mt-2 font-medium">Riwayat pesan akan dihapus permanen.</p>
            <div class="flex gap-3 mt-6 md:mt-8">
                <button onclick="toggleModal(false)" class="flex-1 py-3 rounded-xl font-black text-slate-400 hover:bg-slate-50 transition-all text-[10px] uppercase tracking-widest">Batal</button>
                <button onclick="confirmClearChat()" class="flex-1 py-3 rounded-xl bg-rose-500 hover:bg-rose-600 font-black text-white shadow-lg shadow-rose-100 transition-all text-[10px] uppercase tracking-widest">Hapus</button>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/@supabase/supabase-js@2"></script>
    <script>
        // ... (Script Supabase tetap sama)
        let w3siteChat;
        const currentUserId = String("{{ Auth::user()->id }}");

        function toggleModal(show) {
            const modal = document.getElementById('clearModal');
            if(show) { modal.classList.replace('hidden', 'flex'); } 
            else { modal.classList.replace('flex', 'hidden'); }
        }

        async function confirmClearChat() {
            toggleModal(false);
            const { error } = await w3siteChat.from('discussions').delete().eq('project_id', currentUserId);
            if(!error) {
                document.getElementById('chatBox').innerHTML = `
                    <div id="emptyState" class="m-auto text-slate-300 text-[10px] font-black uppercase tracking-widest text-center">
                        <i class="fa-solid fa-check-double block text-3xl mb-4 opacity-20"></i>
                        Chat cleared
                    </div>`;
            }
        }

        function initW3Chat() {
            const lib = (typeof supabase !== 'undefined') ? supabase : (typeof supabasejs !== 'undefined' ? supabasejs : null);
            if (!lib) { setTimeout(initW3Chat, 500); return; }
            const SB_URL = "https://lymknuizgzhvufyvapwh.supabase.co";
            const SB_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imx5bWtudWl6Z3podnVmeXZhcHdoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3Njk0OTQ1NTgsImV4cCI6MjA4NTA3MDU1OH0.UtTrgmN-IiT0Yn4Dy6ftWk79uI0HO0hARzUVDKZsk4w";
            
            try {
                w3siteChat = lib.createClient(SB_URL, SB_KEY);
                const userName = "{{ Auth::user()->name }}";
                const chatBox = document.getElementById('chatBox');
    
                function appendBubble(msg) {
                    const empty = document.getElementById('emptyState');
                    if(empty) empty.remove();
                    const isMe = String(msg.is_admin) === 'false';
                    const bubble = `
                        <div class="flex ${isMe ? 'justify-end' : 'justify-start'} animate-bubble-in">
                            <div class="flex flex-col ${isMe ? 'items-end' : 'items-start'} max-w-[90%] md:max-w-[70%]">
                                <div class="px-4 md:px-5 py-2.5 md:py-3.5 ${isMe ? 'bg-blue-600 text-white rounded-[20px] rounded-tr-none shadow-lg shadow-blue-100' : 'bg-white text-slate-700 rounded-[20px] rounded-tl-none border border-slate-100 shadow-sm'}">
                                    <p class="text-[13px] md:text-[14px] leading-relaxed whitespace-pre-wrap font-medium">${msg.message}</p>
                                </div>
                                <span class="text-[8px] text-slate-400 mt-1.5 px-2 uppercase font-black tracking-widest italic opacity-50">
                                    ${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                </span>
                            </div>
                        </div>`;
                    chatBox.insertAdjacentHTML('beforeend', bubble);
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
    
                w3siteChat.from('discussions').select('*').eq('project_id', currentUserId).order('created_at', { ascending: true })
                    .then(({ data }) => {
                        chatBox.innerHTML = '';
                        if (data && data.length > 0) { data.forEach(msg => appendBubble(msg)); } 
                        else {
                            chatBox.innerHTML = `
                                <div id="emptyState" class="m-auto text-slate-300 text-[10px] font-black uppercase tracking-widest text-center">
                                    <i class="fa-solid fa-message block text-3xl mb-4 opacity-20"></i>
                                    No messages yet
                                </div>`;
                        }
                    });
    
                w3siteChat.channel('room-' + currentUserId).on('postgres_changes', { event: 'INSERT', schema: 'public', table: 'discussions', filter: `project_id=eq.${currentUserId}` }, 
                    payload => {
                        appendBubble(payload.new);
                        if(payload.new.is_admin) new Audio('https://assets.mixkit.co/sfx/preview/mixkit-software-interface-start-2574.mp3').play();
                    }).subscribe();
    
                document.getElementById('chatForm').onsubmit = async (e) => {
                    e.preventDefault();
                    const input = document.getElementById('chatInput');
                    const text = input.value.trim();
                    if(!text) return;
                    input.value = '';
                    await w3siteChat.from('discussions').insert([{ project_id: currentUserId, sender_name: userName, message: text, is_admin: false }]);
                };
            } catch (err) { console.error(err); }
        }
        initW3Chat();
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes modalScale { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .animate-bubble-in { animation: slideUp 0.3s ease-out forwards; }
        .animate-modal-in { animation: modalScale 0.2s ease-out forwards; }
    </style>
</x-user-layout>