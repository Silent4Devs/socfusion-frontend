<x-filament-panels::page>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
  
    <script>

        let socket;
        
        let chatId = null;

        const notyf = new Notyf();

        socket = new WebSocket("ws://192.168.40.1:8000/chat-soc");

        function renderMessages(messages) {
            messages.forEach(message => {
                if (message.sender_type === 'user') {
                    createUserMessage(message.content);
                } else if (message.sender_type === 'bot') {
                    createBotMessage(message.content);
                }
            });
        }

        window.getConversation = function(chat_id) {

            chatId = chat_id;
            fetch(`/api/messages/${chatId}`)
                .then(res => res.json())
                .then(messages => {
                    const chatMessages = document.getElementById("chat-messages");
                    chatMessages.innerHTML = "";
                    renderMessages(messages);
                });
        }

        

        function sendMessage(message, sender_type = 'user') {
            if (!chatId) {
                fetch('/api/chats', {
                    method: 'POST',
                    credentials: 'include', 
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        title: "Conversación con Chatbot",
                        user_id: {{ \Filament\Facades\Filament::auth()->id();}},
                        message: message
                    })
                }, )
                .then(res => res.json())
                .then(chat => {
                    chatId = chat.id;
                    console.log("Chat creado");
                    console.log(chat);
                    saveMessage(message, chatId, sender_type);
                });
            } else {
                saveMessage(message, chatId, sender_type);
            }

    
        }
        

        function saveMessage(message, chatId, sender_type) {
            fetch(`/api/chats/${chatId}/messages`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    content: message,
                    sender_type: sender_type
                })
            })
            .then(response => response.json())
            .then(data => {
            });
        }

        function createDocumentItem(filename, full_path, details) {
            const docItem = document.createElement("div");
            docItem.className = "flex items-center p-3 rounded-lg bg-gray-50/50 dark:bg-gray-800/50 hover:bg-gray-100/50 dark:hover:bg-gray-700/50 transition-colors";
            
            const iconContainer = document.createElement("div");
            iconContainer.className = "flex-shrink-0 mr-3 p-2 rounded-lg bg-white dark:bg-gray-700 shadow-sm";
            


            const docIcon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            docIcon.setAttribute("class", "w-5 h-5 text-primary-500 dark:text-primary-400");
            docIcon.setAttribute("fill", "none");
            docIcon.setAttribute("viewBox", "0 0 24 24");
            docIcon.setAttribute("stroke", "currentColor");
            docIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';
            
            iconContainer.appendChild(docIcon);
            
            const textContainer = document.createElement("div");
            textContainer.className = "flex-1 min-w-0";
            
            const fileName = document.createElement("p");
            fileName.className = "text-sm font-medium text-gray-800 dark:text-gray-200 truncate";
            fileName.textContent = filename;
            
            const fileDetails = document.createElement("p");
            fileDetails.className = "text-xs text-gray-500 dark:text-gray-400";
            fileDetails.textContent = details;
            
            textContainer.appendChild(fileName);
            textContainer.appendChild(fileDetails);
            
            const eyeButton = document.createElement("button");
            eyeButton.setAttribute("type", "button");
            eyeButton.setAttribute("title", "Ver PDF");
            eyeButton.className = "ml-2 p-1 rounded transition";

            const eyeIcon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            eyeIcon.setAttribute("class", "w-5 h-5 text-gray-500 hover:text-primary-500 dark:text-gray-400 dark:hover:text-primary-400");
            eyeIcon.setAttribute("fill", "none");
            eyeIcon.setAttribute("viewBox", "0 0 24 24");
            eyeIcon.setAttribute("stroke", "currentColor");
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;

            eyeButton.appendChild(eyeIcon);

            eyeButton.addEventListener("click", () => {
                Livewire.dispatch('show-pdf', { file: full_path });
            });

            const url = `http://192.168.40.1:8000/documents/file/?file_name=${encodeURIComponent(full_path)}`;

            const actionLink = document.createElement("a");
            actionLink.setAttribute("href", url);
            actionLink.setAttribute("target", "_blank");
            actionLink.setAttribute("rel", "noopener noreferrer"); 

            const actionIcon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            actionIcon.setAttribute("class", "w-5 h-5 text-gray-400 dark:text-gray-500 hover:text-primary-500 dark:hover:text-primary-400 ml-2 flex-shrink-0");
            actionIcon.setAttribute("fill", "none");
            actionIcon.setAttribute("viewBox", "0 0 24 24");
            actionIcon.setAttribute("stroke", "currentColor");
            actionIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            `;

            actionLink.appendChild(actionIcon);

            docItem.appendChild(iconContainer);
            docItem.appendChild(textContainer);
            docItem.appendChild(eyeButton);
            docItem.appendChild(actionLink); 
            
            return docItem;
        }

        socket.onmessage = function (event) {
            
            const data = JSON.parse(event.data);
            
            if (pendingBotMessage) {
                const botMessageContainer = pendingBotMessage.parentNode;
                

                pendingBotMessage.innerHTML = marked.parse(data.response);
                saveMessage(data.response, chatId, 'bot')

                pendingBotMessage.classList.remove("animate-pulse");
                
                const files = data.files;
                const validFiles = files.filter(file => file !== null);

                if (validFiles.length > 0 && data?.show_files === true) {
                    const docsContainer = document.createElement("div");
                    docsContainer.className = "mt-4 pt-4 border-t border-gray-200/30 dark:border-gray-700/30";
                    
                    const docsHeader = document.createElement("div");
                    docsHeader.className = "flex items-center mb-3";
                
                    const docsIcon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                    docsIcon.setAttribute("class", "w-5 h-5 text-primary-500 dark:text-primary-400 mr-2");
                    docsIcon.setAttribute("fill", "none");
                    docsIcon.setAttribute("viewBox", "0 0 24 24");
                    docsIcon.setAttribute("stroke", "currentColor");
                    docsIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';
                    
                    const docsTitle = document.createElement("span");
                    docsTitle.className = "text-sm font-medium text-primary-600 dark:text-primary-400";
                    docsTitle.textContent = "Documentos adjuntos";
                    
                    docsHeader.appendChild(docsIcon);
                    docsHeader.appendChild(docsTitle);
                    
                    const docsGrid = document.createElement("div");
                    docsGrid.className = "grid grid-cols-1 gap-2";

                    validFiles.forEach(file => {
                        const docItem = createDocumentItem(file.file, file.full_path, ""); 
                        docsGrid.appendChild(docItem);
                    });

                    docsContainer.appendChild(docsHeader);
                    docsContainer.appendChild(docsGrid);

                    botMessageContainer.appendChild(docsContainer);
                }
                pendingBotMessage = null;
            }
            disableInput(false);  
        };

        function currentTime() {
            const now = new Date();
            return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        };

        socket.onopen = function () {
            console.log("Conectado al WebSocket");
            const loadingSocket = document.getElementById("ws-loading");
            loadingSocket.style.display = 'none';
        };

        socket.onclose = function () {
            console.log("WebSocket cerrado. Reintentando en 5 segundos...");
        };

        socket.onerror = function (error) {
            console.error("Error en WebSocket:", error);   
            
        };


        let pendingBotMessage = null;
        let loadingBox = null;

        function disableInput(disabled = true) {
            document.querySelector('input[x-model="message"]').disabled = disabled;
            document.getElementById('send-button').disabled = disabled;
        }

        function currentTime() {
            const now = new Date();
            return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        function createBotMessage(content) {
            const chatMessages = document.getElementById("chat-messages");

            const botWrapper = document.createElement("div");
            botWrapper.className = "flex items-start space-x-3 mt-4";
            botWrapper.setAttribute("id", "bot-message-loading");
            
            const botIconWrapper = document.createElement("div");
            botIconWrapper.className = "flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-br from-primary-100 to-primary-50 dark:from-primary-900/30 dark:to-gray-800 flex items-center justify-center";
            
            const botIcon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            botIcon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
            botIcon.setAttribute("fill", "none");
            botIcon.setAttribute("viewBox", "0 0 24 24");
            botIcon.setAttribute("stroke-width", "1.5");
            botIcon.setAttribute("stroke", "currentColor");
            botIcon.classList.add("h-5", "w-5", "text-primary-600", "dark:text-primary-400");
            
            const pathEl = document.createElementNS("http://www.w3.org/2000/svg", "path");
            pathEl.setAttribute("stroke-linecap", "round");
            pathEl.setAttribute("stroke-linejoin", "round");
            pathEl.setAttribute("d", "M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z");
            
            botIcon.appendChild(pathEl);
            botIconWrapper.appendChild(botIcon);
            
            const loadingBox = document.createElement("div");
            loadingBox.className = "relative rounded-xl px-4 py-3 max-w-[85%] bg-white dark:bg-gray-800 shadow-sm shadow-primary-100/50 dark:shadow-primary-900/10 border border-gray-100 dark:border-gray-700";
            
            const loadingText = document.createElement("p");
            loadingText.className = "text-sm text-gray-800 dark:text-gray-200 leading-relaxed";
            loadingText.textContent = content;
            
            const time = document.createElement("p");
            time.className = "text-xs text-gray-500 dark:text-gray-400 mt-1";
            time.innerText = currentTime();
            
            const botTriangle = document.createElement("div");
            botTriangle.className = "absolute -left-1.5 top-3.5 w-3 h-3 rotate-45 bg-white dark:bg-gray-800 border-l border-b border-gray-100 dark:border-gray-700";
            
            loadingBox.appendChild(loadingText);
            loadingBox.appendChild(time);
            loadingBox.appendChild(botTriangle);
            
            botWrapper.appendChild(botIconWrapper);
            botWrapper.appendChild(loadingBox);
            
            chatMessages.appendChild(botWrapper);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }


        function createUserMessage(content) {
            const text = typeof content === 'string' ? content : content.content;

            const chatMessages = document.getElementById("chat-messages");

            const userWrapper = document.createElement("div");
            userWrapper.className = "flex items-start justify-end space-x-3 group";
            
            const messageDiv = document.createElement("div");
            messageDiv.className = "relative rounded-xl px-4 py-3 max-w-[85%] bg-gradient-to-r from-primary-600 to-blue-600 dark:from-primary-500 dark:to-blue-500 shadow-md shadow-primary-500/20 dark:shadow-primary-900/20";

            const messageText = document.createElement("p");
            messageText.className = "text-sm text-white leading-relaxed";
            messageText.textContent = text;

            const timestamp = document.createElement("p");
            timestamp.className = "text-xs text-primary-200/80 dark:text-primary-300/80 mt-3 text-right";
            timestamp.textContent = currentTime();

            const triangle = document.createElement("div");
            triangle.className = "absolute -right-1.5 top-3.5 w-3 h-3 rotate-45 bg-gradient-to-br from-primary-600 to-blue-600 dark:from-primary-500 dark:to-blue-500";

            messageDiv.appendChild(messageText);
            messageDiv.appendChild(timestamp);
            messageDiv.appendChild(triangle);

            const avatarDiv = document.createElement("div");
            avatarDiv.className = "flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-br from-primary-600 to-blue-600 dark:from-primary-500 dark:to-blue-500 flex items-center justify-center shadow-sm";

            const icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            icon.setAttribute("fill", "none");
            icon.setAttribute("viewBox", "0 0 24 24");
            icon.setAttribute("stroke-width", "1.5");
            icon.setAttribute("stroke", "currentColor");
            icon.classList.add("h-5", "w-5", "text-white");

            const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
            path.setAttribute("stroke-linecap", "round");
            path.setAttribute("stroke-linejoin", "round");
            path.setAttribute("d", "M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0");

            icon.appendChild(path);
            avatarDiv.appendChild(icon);

            userWrapper.appendChild(messageDiv);
            userWrapper.appendChild(avatarDiv);

            chatMessages.appendChild(userWrapper);
        }

        function appendUserMessage(text) {

            if (socket.readyState === WebSocket.OPEN) {
                const chatMessages = document.getElementById("chat-messages");

                const userWrapper = document.createElement("div");
                userWrapper.className = "flex items-start justify-end space-x-3 group";
                
                const messageDiv = document.createElement("div");
                messageDiv.className = "relative rounded-xl px-4 py-3 max-w-[85%] bg-gradient-to-r from-primary-600 to-blue-600 dark:from-primary-500 dark:to-blue-500 shadow-md shadow-primary-500/20 dark:shadow-primary-900/20";

                const messageText = document.createElement("p");
                messageText.className = "text-sm text-white leading-relaxed";
                messageText.textContent = text;
                
                const timestamp = document.createElement("p");
                timestamp.className = "text-xs text-primary-200/80 dark:text-primary-300/80 mt-3 text-right";
                timestamp.textContent = currentTime();

                const triangle = document.createElement("div");
                triangle.className = "absolute -right-1.5 top-3.5 w-3 h-3 rotate-45 bg-gradient-to-br from-primary-600 to-blue-600 dark:from-primary-500 dark:to-blue-500";
                
                const docsContainer = document.createElement("div");
                docsContainer.className = "mt-3 pt-3 border-t border-primary-500/30 dark:border-primary-400/20";

                messageDiv.appendChild(messageText);
                messageDiv.appendChild(timestamp);
                messageDiv.appendChild(triangle);
                
                const avatarDiv = document.createElement("div");
                avatarDiv.className = "flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-br from-primary-600 to-blue-600 dark:from-primary-500 dark:to-blue-500 flex items-center justify-center shadow-sm";
                
                const icon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                icon.setAttribute("fill", "none");
                icon.setAttribute("viewBox", "0 0 24 24");
                icon.setAttribute("stroke-width", "1.5");
                icon.setAttribute("stroke", "currentColor");
                icon.classList.add("h-5", "w-5", "text-white");
                
                const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                path.setAttribute("stroke-linecap", "round");
                path.setAttribute("stroke-linejoin", "round");
                path.setAttribute("d", "M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0");
                
                icon.appendChild(path);
                avatarDiv.appendChild(icon);
                userWrapper.appendChild(messageDiv);
                userWrapper.appendChild(avatarDiv);
                chatMessages.appendChild(userWrapper);
    
                const botWrapper = document.createElement("div");
                botWrapper.className = "flex items-start space-x-3 mt-4";
                botWrapper.setAttribute("id", "bot-message-loading");
                
                const botIconWrapper = document.createElement("div");
                botIconWrapper.className = "flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-br from-primary-100 to-primary-50 dark:from-primary-900/30 dark:to-gray-800 flex items-center justify-center";
                
                const botIcon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                botIcon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
                botIcon.setAttribute("fill", "none");
                botIcon.setAttribute("viewBox", "0 0 24 24");
                botIcon.setAttribute("stroke-width", "1.5");
                botIcon.setAttribute("stroke", "currentColor");
                botIcon.classList.add("h-5", "w-5", "text-primary-600", "dark:text-primary-400");
                
                const pathEl = document.createElementNS("http://www.w3.org/2000/svg", "path");
                pathEl.setAttribute("stroke-linecap", "round");
                pathEl.setAttribute("stroke-linejoin", "round");
                pathEl.setAttribute("d", "M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z");
                
                botIcon.appendChild(pathEl);
                botIconWrapper.appendChild(botIcon);
                
                const loadingBox = document.createElement("div");
                loadingBox.className = "relative rounded-xl px-4 py-3 max-w-[85%] bg-white dark:bg-gray-800 shadow-sm shadow-primary-100/50 dark:shadow-primary-900/10 border border-gray-100 dark:border-gray-700";
                
                const loadingText = document.createElement("p");
                loadingText.className = "text-sm text-gray-800 dark:text-gray-200 leading-relaxed";
                loadingText.innerHTML = `<span class="flex items-center space-x-1.5"><span class="inline-flex space-x-1"><span class="h-2 w-2 rounded-full bg-gray-400 dark:bg-gray-500 animate-pulse"></span><span class="h-2 w-2 rounded-full bg-gray-400 dark:bg-gray-500 animate-pulse delay-75"></span><span class="h-2 w-2 rounded-full bg-gray-400 dark:bg-gray-500 animate-pulse delay-150"></span></span><span>Escribiendo...</span></span>`;
                
                const time = document.createElement("p");
                time.className = "text-xs text-gray-500 dark:text-gray-400 mt-1";
                time.innerText = currentTime();
                
                const botTriangle = document.createElement("div");
                botTriangle.className = "absolute -left-1.5 top-3.5 w-3 h-3 rotate-45 bg-white dark:bg-gray-800 border-l border-b border-gray-100 dark:border-gray-700";
                
                loadingBox.appendChild(loadingText);
                loadingBox.appendChild(time);
                loadingBox.appendChild(botTriangle);
                
                botWrapper.appendChild(botIconWrapper);
                botWrapper.appendChild(loadingBox);
                
                chatMessages.appendChild(botWrapper);
                chatMessages.scrollTop = chatMessages.scrollHeight;
                
                pendingBotMessage = loadingText;
                disableInput(true);
            } else {
                notyf.error("Error de conexión");
            }
        };
        
        window.addEventListener('pdf-error', () => {
            window.showToastError("Hubo un error al ver el documento.");    
        });
    </script>
    
    @livewire('pdf-modal')

    <div id="ws-loading" class="fixed top-4 right-4 z-50">
        <div class="flex items-center space-x-2 bg-white p-2 rounded shadow" id="loading-indicator">
            <svg class="animate-spin h-5 w-5 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span class="text-sm">Conectando...</span>
        </div>
    </div>

    <x-filament::section class="!p-0 overflow-hidden" compact>

        <div class="flex flex-col h-[70vh] bg-white dark:bg-gray-900 dark:border-gray-800 rounded-xl">
          
        <div class="sticky top-0 z-10 flex items-center justify-between p-4 border-b border-gray-200/70 dark:border-gray-700 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm backdrop-saturate-150">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <x-filament::icon
                        icon="heroicon-o-sparkles"
                        class="h-7 w-7 text-primary-500 dark:text-primary-400"
                    />
                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                    </span>
                </div>
                <h2 class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-purple-600 dark:from-primary-400 dark:to-purple-400">
                    Asistente AI
                </h2>
            </div>
            <div class="flex space-x-2">
                @livewire('chat-historial')
            </div>
        </div>

            <div class="flex-1 p-4 space-y-6 overflow-y-auto scroll-smooth" id="chat-messages">
                <div class="flex items-start space-x-3 group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
                    <div class="flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-br from-primary-100 to-primary-50 dark:from-primary-900/30 dark:to-gray-800 flex items-center justify-center transition-all duration-300"
                        :class="{ 'ring-2 ring-primary-300 dark:ring-primary-500/50': hovered }">
                        <x-filament::icon
                            icon="heroicon-o-sparkles"
                            class="h-5 w-5 text-primary-600 dark:text-primary-400 transition-transform duration-300"
                        />
                    </div>
   
                    <div class="relative">
                        <div class="rounded-xl px-4 py-3 max-w-[85%] bg-white dark:bg-gray-800 shadow-sm shadow-primary-100/50 dark:shadow-primary-900/10 border border-gray-100 dark:border-gray-700 transition-all duration-300"
                            :class="{ 'scale-[1.02]': hovered }">
                            <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                                ¡Hola! Soy tu asistente virtual. ¿En qué puedo ayudarte hoy?
                            </p>
                        </div>
                        <div class="absolute -left-1.5 top-3.5 w-3 h-3 rotate-45 bg-white dark:bg-gray-800 border-l border-b border-gray-100 dark:border-gray-700"></div>
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 p-4 border-t border-gray-200/70 dark:border-gray-700 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm backdrop-saturate-150">
                <div x-data="{
                    message: '',
                    send() {
                        if (this.message.trim() === '') return;

                        appendUserMessage(this.message); 
                        sendMessage(this.message);
                        if (socket && socket.readyState === WebSocket.OPEN) {
                            socket.send(this.message); 
                        } else {
                            alert('WebSocket no conectado.');
                        }

                        this.message = ''; // Limpiar input
                    }
                }" @keydown.enter.prevent="send">
                    <div class="flex space-x-3">
                        <div class="relative flex-1">
                            <input
                                type="text"
                                x-model="message"
                                placeholder="Escribe tu mensaje..."
                                class="w-full px-4 py-3 rounded-xl border-0 bg-gray-100/70 dark:bg-gray-700/70 focus:bg-white dark:focus:bg-gray-700 focus:ring-2 focus:ring-primary-500/50 dark:focus:ring-primary-400/50 text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm transition-all duration-200 outline-none"
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" x-show="message.length > 0">
                                <button @click="message = ''" class="p-1 rounded-full bg-gray-300/50 dark:bg-gray-600/50 text-gray-600 dark:text-gray-300 hover:bg-gray-400/50 dark:hover:bg-gray-500/50 transition-colors pointer-events-auto">
                                    <x-filament::icon icon="heroicon-o-x-mark" class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                        <button
                            @click="send"
                            :disabled="message.trim() === ''"
                            class="px-5 py-3 rounded-xl bg-gradient-to-r from-primary-600 to-blue-600 dark:from-primary-500 dark:to-blue-500 text-white font-medium shadow-md hover:shadow-lg hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-[1.02] active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary-400/50"
                            id="send-button"
                        >
                            <span class="flex items-center space-x-1.5">
                                <x-filament::icon icon="heroicon-o-paper-airplane" class="h-5 w-5" />
                                <span>Enviar</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>


        </div>
    </x-filament::section>


</x-filament-panels::page>