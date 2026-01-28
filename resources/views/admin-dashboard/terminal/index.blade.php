<x-admin-layout>
    <div class="w-full h-[calc(100vh-120px)] flex flex-col">
        {{-- Header: Statis di atas --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 flex-none">
            <div>
                <h1 class="text-2xl font-[1000] text-slate-900 tracking-tighter">Direct <span class="text-blue-600">Console</span></h1>
                <p class="text-sm text-slate-500 font-medium italic">Executing directly from /var/www</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="runQuick('./deploy.sh')" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                    <i class="fas fa-rocket text-xs"></i>
                    Deploy System
                </button>
                <button onclick="clearTerminal()" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-sm">
                    <i class="fas fa-eraser text-xs"></i>
                    Clear Screen
                </button>
            </div>
        </div>

        {{-- Main Terminal Box: Fixed Height --}}
        <div class="bg-slate-900 rounded-[2rem] border border-slate-800 shadow-2xl overflow-hidden flex flex-col flex-1 min-h-0">
            
            {{-- Area Log/Output: Scrollable --}}
            <div id="terminal-screen" class="flex-1 p-8 overflow-y-auto font-mono text-sm leading-relaxed custom-scrollbar bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-slate-800/40 via-transparent to-transparent">
                <div class="text-emerald-500 font-bold mb-1">Last login: {{ now()->format('D M d H:i:s') }}</div>
                <div class="text-emerald-400">W3Site Secure Console Connected.</div>
                <div class="text-slate-500 mb-6 italic opacity-50">Ready to accept commands...</div>
                {{-- Output perintah akan muncul di sini --}}
            </div>

            {{-- Form Input: Fixed di bawah (tidak ikut scroll) --}}
            <div class="flex-none p-5 bg-slate-950 border-t border-slate-800 flex items-center gap-3">
                <div class="flex items-center gap-2 text-emerald-500 font-mono font-bold select-none">
                    <span>admin@w3site</span>
                    <span class="text-slate-600">:</span>
                    <span class="text-blue-500">~</span>
                    <span class="animate-pulse text-white">#</span>
                </div>
                
                <input type="text" id="terminal-input" 
                       class="flex-1 bg-transparent border-none text-emerald-400 focus:ring-0 p-0 font-mono text-base caret-emerald-500"
                       placeholder="Enter command here..." 
                       autocomplete="off" 
                       autocorrect="off" 
                       autocapitalize="off" 
                       spellcheck="false" 
                       autofocus>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const input = document.getElementById('terminal-input');
        const screen = document.getElementById('terminal-screen');

        function appendOutput(text, type = 'normal') {
            const div = document.createElement('div');
            
            if (type === 'cmd') {
                div.className = 'text-white mt-5 mb-1 font-bold';
                div.innerHTML = `<span class="text-slate-600 font-normal">$</span> ${text}`;
            } else if (type === 'error') {
                div.className = 'text-red-400 mt-2 p-3 bg-red-500/5 border-l-2 border-red-500';
                div.innerText = text;
            } else {
                div.className = 'text-slate-300 whitespace-pre-wrap py-0.5';
                div.innerText = text;
            }
            
            screen.appendChild(div);
            // Otomatis scroll ke bawah setiap kali ada output baru
            screen.scrollTop = screen.scrollHeight;
        }

        async function executeCommand(cmd) {
            const command = cmd.trim();
            if (!command) return;
            
            appendOutput(command, 'cmd');
            
            try {
                const response = await axios.post('{{ route("admin.terminal.execute") }}', { command: command });
                appendOutput(response.data.output, response.data.status === 'error' ? 'error' : 'normal');
            } catch (error) {
                console.error(error); // Lihat detailnya di F12 Console
                let errorMsg = error.response ? error.response.data.output : 'Bridge unreachable';
                appendOutput('ERROR: ' + errorMsg, 'error');
            }
        }

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                executeCommand(input.value);
                input.value = '';
            }
        });

        function runQuick(cmd) {
            executeCommand(cmd);
        }

        function clearTerminal() {
            screen.innerHTML = `<div class="text-slate-600 italic opacity-50">Terminal cleared.</div>`;
        }

        // Memastikan input selalu fokus saat area terminal diklik
        screen.addEventListener('click', () => input.focus());
    </script>

    <style>
        /* Agar body utama tidak scroll */
        body { overflow: hidden; }

        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
        
        /* Hilangkan focus outline */
        #terminal-input:focus { outline: none; box-shadow: none; }
    </style>
</x-admin-layout>