<x-admin-layout>
    <div class="w-full h-[calc(100vh-120px)] flex flex-col">
        {{-- Header --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 flex-none px-4">
            <div>
                <h1 class="text-2xl font-[1000] text-slate-900 tracking-tighter leading-none">DIRECT <span class="text-blue-600">CONSOLE</span></h1>
                <p class="text-[10px] md:text-sm text-slate-500 font-medium italic mt-2">
                    Default path: <span class="font-mono text-slate-800">/var/www/w3site</span>
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="runQuick('./deploy.sh')" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                    <i class="fas fa-rocket text-xs"></i>
                    Deploy System
                </button>
                <button onclick="clearTerminal()" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-sm">
                    <i class="fas fa-eraser text-xs"></i>
                    Clear
                </button>
            </div>
        </div>

        {{-- Main Terminal Box --}}
        <div class="bg-slate-900 rounded-[2rem] border border-slate-800 shadow-2xl overflow-hidden flex flex-col flex-1 min-h-0 mx-2 md:mx-4">
            
            {{-- Area Log/Output --}}
            <div id="terminal-screen" class="flex-1 p-6 md:p-8 overflow-y-auto font-mono text-xs md:text-sm leading-relaxed custom-scrollbar bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-slate-800/40 via-transparent to-transparent">
                <div class="text-emerald-500 font-bold mb-1">Session started: {{ now()->format('H:i:s') }}</div>
                <div class="text-emerald-400">Connected to W3Site Secure Bridge.</div>
                <div class="text-slate-500 mb-6 italic opacity-50 border-b border-slate-800 pb-2">Navigating to default workdir...</div>
            </div>

            {{-- Form Input (Fixed di bawah) --}}
            <div class="flex-none p-4 md:p-5 bg-slate-950 border-t border-slate-800 flex items-center gap-3">
                <div class="flex items-center gap-2 text-emerald-500 font-mono font-bold select-none text-xs md:text-sm">
                    <span class="hidden md:inline">admin@w3site</span>
                    <span class="text-slate-600">:</span>
                    <span id="current-path-display" class="text-blue-500">~</span>
                    <span class="animate-pulse text-white">#</span>
                </div>
                
                <input type="text" id="terminal-input" 
                       class="flex-1 bg-transparent border-none text-emerald-400 focus:ring-0 p-0 font-mono text-sm md:text-base caret-emerald-500"
                       placeholder="..." 
                       autocomplete="off" 
                       autocorrect="off" 
                       autocapitalize="off" 
                       spellcheck="false" 
                       autofocus>
            </div>
        </div>
    </div>

    {{-- Load Axios --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const input = document.getElementById('terminal-input');
        const screen = document.getElementById('terminal-screen');
        const pathDisplay = document.getElementById('current-path-display');

        // Setup CSRF secara global untuk Axios
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }

        function appendOutput(text, type = 'normal') {
            const div = document.createElement('div');
            
            if (type === 'cmd') {
                const currentPath = pathDisplay.innerText;
                div.className = 'text-white mt-5 mb-1 font-bold flex items-center gap-2 opacity-90';
                div.innerHTML = `<span class="text-slate-600 font-normal text-xs">[${currentPath}] $</span> ${text}`;
            } else if (type === 'error') {
                div.className = 'text-red-400 mt-2 p-3 bg-red-500/5 border-l-2 border-red-500 rounded-r-lg font-mono text-xs';
                div.innerText = text;
            } else {
                div.className = 'text-slate-300 whitespace-pre-wrap py-0.5 font-mono text-xs md:text-sm leading-loose opacity-90';
                div.innerText = text;
            }
            
            screen.appendChild(div);
            screen.scrollTop = screen.scrollHeight;
        }

        async function executeCommand(cmd) {
            const command = cmd.trim();
            if (!command) return;
            
            appendOutput(command, 'cmd');
            input.disabled = true; // Lock input saat proses
            
            try {
                const response = await axios.post('{{ route("admin.terminal.execute") }}', { 
                    command: command 
                });
                
                // Update tampilan path di prompt jika dikirim oleh server
                if (response.data.current_dir) {
                    // Ubah /var/www/w3site menjadi simbol ~ agar lebih cantik
                    let shortPath = response.data.current_dir.replace('/var/www/w3site', '~');
                    pathDisplay.innerText = shortPath;
                }

                appendOutput(response.data.output, response.data.status === 'error' ? 'error' : 'normal');
            } catch (error) {
                console.error(error);
                let errorMsg = 'Bridge unreachable';
                if (error.response && error.response.data.output) {
                    errorMsg = error.response.data.output;
                }
                appendOutput('SYSTEM ERROR: ' + errorMsg, 'error');
            } finally {
                input.disabled = false;
                input.focus();
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
            screen.innerHTML = `<div class="text-slate-600 italic opacity-50 text-[10px]">TERMINAL BUFFER CLEARED</div>`;
        }

        // Auto focus
        document.addEventListener('click', () => input.focus());
        screen.addEventListener('click', (e) => {
            e.stopPropagation();
            input.focus();
        });
    </script>

    <style>
        body { overflow: hidden; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #334155; }
        #terminal-input:focus { outline: none; box-shadow: none; }
        
        /* Mobile optimization */
        @media (max-width: 640px) {
            #terminal-screen { padding: 1rem; }
        }
    </style>
</x-admin-layout>