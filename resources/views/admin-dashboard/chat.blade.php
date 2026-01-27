<x-admin-layout>
    <div id="clearChatModal" class="fixed inset-0 z-[999] hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
        
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-[2rem] shadow-2xl max-w-sm w-full p-8 relative animate-in">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                    <i class="fa-solid fa-trash-can text-2xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 text-center uppercase tracking-tighter">Hapus Chat?</h3>
                <p class="text-slate-500 text-sm text-center mt-2 font-medium">
                    Semua riwayat percakapan dengan <span id="modalUserName" class="text-slate-900 font-bold"></span> akan dihapus permanen dari server.
                </p>
                
                <div class="flex gap-3 mt-8">
                    <button onclick="closeClearModal()" class="flex-1 px-6 py-3.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Batal
                    </button>
                    <button onclick="confirmClearChat()" class="flex-1 px-6 py-3.5 rounded-xl bg-red-500 text-white text-xs font-black uppercase tracking-widest hover:bg-red-600 shadow-lg shadow-red-200 transition-all">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 tracking-tighter">
            Live <span class="text-blue-600">Support</span>
        </h2>
        <p class="text-slate-500 text-sm font-medium">Manajemen pesan dan bantuan pengguna real-time.</p>
    </div>

    <div class="bg-white rounded-[1.5rem] border border-slate-200 overflow-hidden flex flex-col relative" 
         style="height: calc(100vh - 250px); min-height: 500px;">
        
        <div class="flex flex-1 overflow-hidden">
            <div class="w-1/4 max-w-[320px] min-w-[260px] bg-[#1e293b] flex flex-col border-r border-slate-100">
                <div class="p-6 border-b border-white/5 shrink-0">
                    <div class="relative">
                        <input type="text" id="userSearchInput" oninput="filterUsers()" placeholder="Cari user..." 
                            class="w-full bg-slate-800 border-none rounded-2xl py-2.5 pl-10 text-xs text-white placeholder-slate-500 focus:ring-1 focus:ring-blue-500">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-slate-500 text-[10px]"></i>
                    </div>
                </div>

                <div id="userList" class="flex-1 overflow-y-auto p-3 space-y-2 custom-scrollbar-dark">
                    <div class="p-10 text-center text-[10px] text-slate-500 uppercase tracking-widest font-bold">
                        Menunggu...
                    </div>
                </div>
            </div>

            <div class="flex-1 flex flex-col bg-slate-50/30">
                <div id="chatHeader" class="h-20 px-8 bg-white border-b border-slate-100 flex items-center justify-between shrink-0 invisible">
                    <div class="flex items-center gap-4">
                        <div id="activeAvatar" class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-bold shadow-lg shadow-blue-100 uppercase">?</div>
                        <div>
                            <h4 id="activeUserName" class="font-black text-slate-900 uppercase tracking-tighter text-lg">Pilih User</h4>
                            <p class="text-[10px] text-emerald-500 font-black uppercase tracking-widest flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Active Now
                            </p>
                        </div>
                    </div>
                    <button onclick="openClearModal()" class="flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition-all active:scale-95 group">
                        <i class="fa-solid fa-trash-can text-xs group-hover:rotate-12 transition-transform"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">Clear Chat</span>
                    </button>
                </div>

                <div id="chatBox" class="flex-1 overflow-y-auto p-8 space-y-4 flex flex-col custom-scrollbar-light">
                    <div id="welcomeState" class="m-auto text-center opacity-20">
                        <i class="fa-solid fa-comments-bubble text-8xl text-slate-300"></i>
                        <p class="text-xs font-black tracking-[0.2em]">W3SITE ADMIN MESSENGER</p>
                    </div>
                </div>

                <div id="inputArea" class="p-6 bg-white border-t border-slate-100 hidden shrink-0">
                    <form id="chatForm" class="flex items-center gap-4">
                        <input type="text" id="chatInput" autocomplete="off"
                            class="flex-1 border-none bg-slate-100 rounded-2xl px-6 py-4 text-sm focus:ring-2 focus:ring-blue-500/20 text-slate-700 shadow-inner"
                            placeholder="Ketik balasan untuk pengguna..." autofocus>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white h-14 px-8 rounded-2xl flex items-center gap-3 shadow-lg shadow-blue-100 transition-all active:scale-95">
                            <span class="text-xs font-black uppercase tracking-widest">Reply</span>
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/@supabase/supabase-js@2"></script>
    <script>
        let w3siteChat;
        let selectedUserId = null;
        let knownUsers = [];

        function initW3Chat() {
            const lib = (typeof supabase !== 'undefined') ? supabase : (typeof supabasejs !== 'undefined' ? supabasejs : null);
            if (!lib) { setTimeout(initW3Chat, 500); return; }
            const SB_URL = "https://lymknuizgzhvufyvapwh.supabase.co";
            const SB_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imx5bWtudWl6Z3podnVmeXZhcHdoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3Njk0OTQ1NTgsImV4cCI6MjA4NTA3MDU1OH0.UtTrgmN-IiT0Yn4Dy6ftWk79uI0HO0hARzUVDKZsk4w";
            try {
                w3siteChat = lib.createClient(SB_URL, SB_KEY);
                loadUserList(); listenGlobal();
                document.getElementById('chatForm').onsubmit = async (e) => {
                    e.preventDefault();
                    const input = document.getElementById('chatInput');
                    const text = input.value.trim();
                    if(!text || !selectedUserId) return;
                    input.value = '';
                    await w3siteChat.from('discussions').insert([{ project_id: selectedUserId, sender_name: 'Admin w3site', message: text, is_admin: true }]);
                };
            } catch (err) { console.error(err); }
        }

        // --- MODAL LOGIC ---
        function openClearModal() {
            if(!selectedUserId) return;
            document.getElementById('modalUserName').innerText = document.getElementById('activeUserName').innerText;
            document.getElementById('clearChatModal').classList.remove('hidden');
        }

        function closeClearModal() {
            document.getElementById('clearChatModal').classList.add('hidden');
        }

        async function confirmClearChat() {
            const { error } = await w3siteChat.from('discussions').delete().eq('project_id', selectedUserId);
            if(!error) {
                document.getElementById('chatBox').innerHTML = '';
                closeClearModal();
                appendBubble({ message: "Percakapan telah dibersihkan oleh admin.", created_at: new Date(), is_admin: true, project_id: selectedUserId });
            }
        }

        // --- CHAT LOGIC ---
        async function loadUserList() {
            const { data } = await w3siteChat.from('discussions').select('project_id, sender_name, created_at').not('is_admin', 'eq', true).order('created_at', { ascending: false });
            if (data) {
                const seen = new Set();
                knownUsers = data.filter(el => { const dupe = seen.has(el.project_id); seen.add(el.project_id); return !dupe; });
                renderSidebar(knownUsers);
            }
        }

        function filterUsers() {
            const keyword = document.getElementById('userSearchInput').value.toLowerCase();
            const filtered = knownUsers.filter(u => u.sender_name.toLowerCase().includes(keyword) || u.project_id.toLowerCase().includes(keyword));
            renderSidebar(filtered);
        }

        function renderSidebar(usersToRender) {
            const listDiv = document.getElementById('userList');
            if(!usersToRender || usersToRender.length === 0) {
                listDiv.innerHTML = '<div class="p-10 text-center text-[10px] text-slate-500 uppercase tracking-widest font-bold">User tidak ditemukan</div>';
                return;
            }
            listDiv.innerHTML = usersToRender.map(u => `
                <div onclick="selectUser('${u.project_id}', '${u.sender_name}')" 
                    class="flex items-center gap-3 p-4 rounded-[1.5rem] cursor-pointer transition-all ${selectedUserId === u.project_id ? 'bg-blue-600 shadow-xl shadow-blue-900/40' : 'hover:bg-white/5 text-slate-400'}">
                    <div class="w-10 h-10 ${selectedUserId === u.project_id ? 'bg-white/20' : 'bg-slate-700'} rounded-xl flex items-center justify-center text-white font-black text-xs shrink-0">${u.sender_name[0]}</div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-black text-[13px] ${selectedUserId === u.project_id ? 'text-white' : 'text-slate-200'} truncate uppercase tracking-tighter">${u.sender_name}</h4>
                        <p class="text-[9px] ${selectedUserId === u.project_id ? 'text-blue-100 opacity-70' : 'text-slate-500'} truncate font-bold uppercase tracking-widest">ID: ${u.project_id}</p>
                    </div>
                </div>`).join('');
        }

        async function selectUser(id, name) {
            selectedUserId = id;
            document.getElementById('chatHeader').classList.remove('invisible');
            document.getElementById('inputArea').classList.replace('hidden', 'block');
            document.getElementById('activeUserName').innerText = name;
            document.getElementById('activeAvatar').innerText = name[0];
            filterUsers(); 
            const chatBox = document.getElementById('chatBox');
            chatBox.innerHTML = '<div class="m-auto text-[10px] font-black text-slate-400 animate-pulse tracking-widest uppercase italic">Loading...</div>';
            const { data } = await w3siteChat.from('discussions').select('*').eq('project_id', id).order('created_at', { ascending: true });
            chatBox.innerHTML = '';
            if (data) data.forEach(msg => appendBubble(msg));
        }
    
        function appendBubble(msg) {
            if (msg.project_id !== selectedUserId) return;
            const welcome = document.getElementById('welcomeState'); if(welcome) welcome.remove();
            const isMe = String(msg.is_admin) === 'true';
            const bubble = `
                <div class="flex ${isMe ? 'justify-end' : 'justify-start'} animate-in">
                    <div class="flex flex-col ${isMe ? 'items-end' : 'items-start'} max-w-[85%]">
                        <div class="px-5 py-3 ${isMe ? 'bg-blue-600 text-white rounded-[1.5rem] rounded-tr-none shadow-lg shadow-blue-100' : 'bg-white text-slate-700 rounded-[1.5rem] rounded-tl-none border border-slate-100 shadow-sm'}">
                            <p class="text-[13px] leading-relaxed whitespace-pre-wrap font-medium">${msg.message}</p>
                        </div>
                        <span class="text-[8px] text-slate-400 mt-1.5 px-2 font-black uppercase tracking-widest opacity-60">
                            ${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                        </span>
                    </div>
                </div>`;
            const chatBox = document.getElementById('chatBox');
            chatBox.insertAdjacentHTML('beforeend', bubble);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function listenGlobal() {
            w3siteChat.channel('global-admin').on('postgres_changes', { event: 'INSERT', schema: 'public', table: 'discussions' }, payload => {
                const newMsg = payload.new;
                if(!newMsg.is_admin) { loadUserList(); new Audio('https://assets.mixkit.co/sfx/preview/mixkit-positive-notification-951.mp3').play(); }
                if(newMsg.project_id === selectedUserId) appendBubble(newMsg);
            }).subscribe();
        }
        initW3Chat();
    </script>

    <style>
        .custom-scrollbar-dark::-webkit-scrollbar { width: 0px; }
        .custom-scrollbar-light::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar-light::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
    </style>
</x-admin-layout>