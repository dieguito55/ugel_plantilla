/**
 * Chatbot Inteligente UGEL El Collao - SOLUCIÓN FINAL
 * Corrección para doble-click y problemas de foco
 */

class UgelChatbot {
    
    constructor() {
        this.responses = [];
        this.settings = {};
        this.isOpen = false;
        this.isTyping = false;
        this.eventsBound = false;
        this.isToggling = false; // Prevenir múltiples toggles rápidos
        
        this.elements = {
            fab: document.getElementById('chatFab'),
            panel: document.getElementById('chatPanel'),
            body: document.getElementById('chatBody'),
            input: document.getElementById('chatTxt'),
            form: document.getElementById('chatForm'),
            close: document.getElementById('chatClose'),
            overlay: document.getElementById('chatOverlay')
        };
        
        this.init();
    }
    
    async init() {
        console.log('🤖 Inicializando chatbot...');
        
        // Verificar elementos requeridos
        if (!this.elements.fab || !this.elements.panel || !this.elements.body) {
            console.error('❌ Elementos del chatbot no encontrados');
            return;
        }
        
        await this.loadResponses();
        this.bindEvents();
        
        console.log('✅ Chatbot inicializado correctamente');
    }
    
    async loadResponses() {
        try {
            if (typeof ugel_chatbot_data !== 'undefined' && ugel_chatbot_data) {
                this.responses = ugel_chatbot_data.responses || [];
                this.settings = ugel_chatbot_data.settings || {};
                console.log('✅ Datos cargados desde WordPress:', this.responses.length, 'respuestas');
                return;
            }
            
            this.useDefaultResponses();
            
        } catch (error) {
            console.warn('⚠️ Error cargando respuestas:', error);
            this.useDefaultResponses();
        }
    }
    
    useDefaultResponses() {
        console.log('📦 Usando respuestas por defecto');
        
        this.responses = [
            {
                id: 1,
                category: 'Saludo',
                keywords: ['hola', 'ayuda', 'asistencia', 'buenos días', 'buenas tardes'],
                response: '👋 <strong>¡Hola! Bienvenido/a a la UGEL El Collao</strong><br><br>Soy tu asistente virtual y puedo ayudarte con:<br>• 📋 Información sobre trámites<br>• 📢 Convocatorias vigentes<br>• 🕒 Horarios y contacto<br>• 💻 Servicios online<br><br>¿En qué tema específico necesitas ayuda?',
                active: true
            },
            {
                id: 2,
                category: 'Contacto',
                keywords: ['contacto', 'teléfono', 'telefono', 'llamar', 'número', 'numero'],
                response: '📞 <strong>Contactos Oficiales:</strong><br>• <strong>Teléfono:</strong> <a href="tel:974202598">974 202 598</a><br>• <strong>Fijo:</strong> <a href="tel:051552506">051 552 506</a><br>• <strong>Email:</strong> info@ugelelcollao.edu.pe<br><br>🕒 <strong>Horario:</strong> Lun-Vie 8:30AM-4:30PM',
                active: true
            },
            {
                id: 3,
                category: 'Ubicación',
                keywords: ['dónde', 'donde', 'ubicación', 'dirección', 'direccion'],
                response: '📍 <strong>Nuestra ubicación:</strong><br>Jr. Sucre N° 215, Barrio Santa Bárbara<br>Ilave, El Collao, Puno<br><br>🗺️ <em>A una cuadra de la plaza de armas</em>',
                active: true
            }
        ];
        
        this.settings = {
            greeting: '👋 ¡Hola! Soy el asistente virtual de la UGEL El Collao.<br><br>¿En qué puedo ayudarte hoy?',
            fallback: '🤔 No encontré información específica sobre tu consulta.<br><br>📞 <strong>Contacta directamente:</strong> 974 202 598',
            typing_delay: 1500,
            match_threshold: 1
        };
    }
    
    bindEvents() {
        if (this.eventsBound) {
            console.warn('⚠️ Eventos ya vinculados');
            return;
        }
        
        console.log('🔗 Vinculando eventos...');
        
        // FAB Click - CON DEBOUNCE PARA EVITAR DOBLE CLICK
        if (this.elements.fab) {
            let lastClickTime = 0;
            
            this.elements.fab.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                const currentTime = Date.now();
                const timeDiff = currentTime - lastClickTime;
                
                // Ignorar clicks muy rápidos (debounce de 300ms)
                if (timeDiff < 300) {
                    console.log('⏭️ Click ignorado (muy rápido)');
                    return;
                }
                
                lastClickTime = currentTime;
                
                // Prevenir múltiples toggles simultáneos
                if (this.isToggling) {
                    console.log('🔒 Toggle ya en proceso, ignorando');
                    return;
                }
                
                console.log('🖱️ FAB clicked válido, isOpen:', this.isOpen);
                this.toggle();
                
            }, { passive: false });
        }
        
        // Cerrar button
        if (this.elements.close) {
            this.elements.close.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.close();
            });
        }
        
        // Form submit
        if (this.elements.form) {
            this.elements.form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.sendMessage();
            });
        }
        
        // Enter key
        if (this.elements.input) {
            this.elements.input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });
        }
        
        // Overlay click
        if (this.elements.overlay) {
            this.elements.overlay.addEventListener('click', () => {
                this.close();
            });
        }
        
        // ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.close();
            }
        });
        
        // Suggestions
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('suggestion-btn')) {
                e.preventDefault();
                const text = e.target.getAttribute('data-text');
                if (text && this.elements.input) {
                    this.elements.input.value = text;
                    if (!this.isOpen) {
                        this.open();
                    }
                    setTimeout(() => this.elements.input?.focus(), 300);
                }
            }
        });
        
        // Click outside - CON PROTECCIÓN MEJORADA
        let outsideClickTimeout;
        document.addEventListener('click', (e) => {
            if (!this.isOpen || this.isToggling) return;
            
            const isClickInsideChat = this.elements.panel?.contains(e.target) || 
                                    this.elements.fab?.contains(e.target);
            
            if (!isClickInsideChat) {
                // Clear timeout previo si existe
                if (outsideClickTimeout) {
                    clearTimeout(outsideClickTimeout);
                }
                
                // Delay para evitar conflictos con otros eventos
                outsideClickTimeout = setTimeout(() => {
                    if (this.isOpen && !this.isToggling) {
                        console.log('👆 Click fuera - cerrando');
                        this.close();
                    }
                }, 150);
            }
        });
        
        this.eventsBound = true;
        console.log('✅ Eventos vinculados');
    }
    
    toggle() {
        if (this.isToggling) {
            console.log('🔒 Toggle bloqueado - ya en proceso');
            return;
        }
        
        this.isToggling = true;
        console.log('🔄 Toggle iniciado, estado actual:', this.isOpen);
        
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
        
        // Desbloquear después de la animación
        setTimeout(() => {
            this.isToggling = false;
            console.log('🔓 Toggle desbloqueado');
        }, 250);
    }
    
    open() {
        if (!this.elements.panel || this.isOpen) return;
        
        console.log('📂 Abriendo chat...');
        
        this.isOpen = true;
        this.elements.panel.classList.add('open');
        this.elements.fab?.setAttribute('aria-expanded', 'true');
        
        // Overlay
        if (this.elements.overlay) {
            this.elements.overlay.classList.add('show');
        }
        
        // CORREGIR PROBLEMA DE ARIA-HIDDEN CON FOCO
        setTimeout(() => {
            // Primero remover aria-hidden, LUEGO hacer foco
            this.elements.panel.setAttribute('aria-hidden', 'false');
            this.elements.input?.focus();
        }, 200);
        
        // Saludo si está vacío
        if (this.elements.body && this.elements.body.children.length === 0) {
            setTimeout(() => this.showGreeting(), 300);
        }
        
        console.log('✅ Chat abierto');
    }
    
    close() {
        if (!this.elements.panel || !this.isOpen) return;
        
        console.log('📁 Cerrando chat...');
        
        // PRIMERO quitar foco, LUEGO poner aria-hidden
        if (this.elements.input) {
            this.elements.input.blur();
        }
        
        setTimeout(() => {
            this.isOpen = false;
            this.elements.panel.classList.remove('open');
            this.elements.panel.setAttribute('aria-hidden', 'true');
            this.elements.fab?.setAttribute('aria-expanded', 'false');
            
            if (this.elements.overlay) {
                this.elements.overlay.classList.remove('show');
            }
        }, 50);
        
        console.log('✅ Chat cerrado');
    }
    
    showGreeting() {
        const greeting = this.settings.greeting || '¡Hola! ¿Cómo puedo ayudarte?';
        this.addMessage(greeting, 'bot', true);
    }
    
    sendMessage() {
        if (!this.elements.input) return;
        
        const message = this.elements.input.value.trim();
        if (!message || this.isTyping) return;
        
        console.log('📤 Enviando mensaje:', message);
        
        this.addMessage(message, 'user', false);
        this.elements.input.value = '';
        this.processMessage(message);
    }
    
    addMessage(text, sender, isHtml = false) {
        if (!this.elements.body) return;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-msg ${sender === 'user' ? 'you' : 'bot'}`;
        
        if (isHtml && sender === 'bot') {
            messageDiv.innerHTML = this.sanitizeHtml(text);
        } else {
            messageDiv.textContent = text;
        }
        
        this.elements.body.appendChild(messageDiv);
        this.scrollToBottom();
        
        return messageDiv;
    }
    
    sanitizeHtml(html) {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const dangerousElements = tempDiv.querySelectorAll('script, iframe, object, embed');
        dangerousElements.forEach(element => element.remove());
        return tempDiv.innerHTML;
    }
    
    processMessage(userMessage) {
        this.showTypingIndicator();
        
        const delay = this.settings.typing_delay || 1500;
        const variation = Math.random() * 500;
        
        setTimeout(() => {
            this.hideTypingIndicator();
            const response = this.findResponse(userMessage);
            this.addMessage(response, 'bot', true);
            console.log('🤖 Respuesta enviada');
        }, delay + variation);
    }
    
    findResponse(userMessage) {
        const message = this.normalizeText(userMessage);
        const words = message.split(/\s+/);
        
        let bestMatch = null;
        let maxMatches = 0;
        const threshold = this.settings.match_threshold || 1;
        
        const activeResponses = this.responses.filter(r => r.active !== false);
        
        for (const response of activeResponses) {
            if (!response.keywords || response.keywords.length === 0) continue;
            
            let matches = 0;
            
            for (const keyword of response.keywords) {
                const normalizedKeyword = this.normalizeText(keyword.trim());
                
                if (message.includes(normalizedKeyword)) {
                    matches++;
                } else {
                    for (const word of words) {
                        if (word.includes(normalizedKeyword) || normalizedKeyword.includes(word)) {
                            matches += 0.5;
                        }
                    }
                }
            }
            
            if (matches >= threshold && matches > maxMatches) {
                maxMatches = matches;
                bestMatch = response;
            }
        }
        
        return bestMatch ? bestMatch.response : (this.settings.fallback || 'Lo siento, no tengo información sobre esa consulta.');
    }
    
    normalizeText(text) {
        return text.toLowerCase()
                  .normalize('NFD')
                  .replace(/[\u0300-\u036f]/g, '')
                  .replace(/[^\w\s]/g, ' ')
                  .replace(/\s+/g, ' ')
                  .trim();
    }
    
    showTypingIndicator() {
        if (this.isTyping || !this.elements.body) return;
        
        this.isTyping = true;
        
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chat-msg bot typing-indicator';
        typingDiv.innerHTML = '<span></span><span></span><span></span>';
        
        this.elements.body.appendChild(typingDiv);
        this.scrollToBottom();
    }
    
    hideTypingIndicator() {
        this.isTyping = false;
        const typingIndicator = this.elements.body?.querySelector('.typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
    
    scrollToBottom() {
        if (!this.elements.body) return;
        
        setTimeout(() => {
            this.elements.body.scrollTop = this.elements.body.scrollHeight;
        }, 100);
    }
    
    // Métodos públicos
    addResponse(keywords, response, category = 'Dinámico') {
        const newId = Math.max(...this.responses.map(r => r.id), 0) + 1;
        this.responses.push({
            id: newId,
            category: category,
            keywords: Array.isArray(keywords) ? keywords : keywords.split(',').map(k => k.trim()),
            response: response,
            active: true
        });
    }
    
    getStats() {
        return {
            totalResponses: this.responses.length,
            activeResponses: this.responses.filter(r => r.active !== false).length,
            isOpen: this.isOpen,
            isToggling: this.isToggling,
            eventsBound: this.eventsBound
        };
    }
}

// Estilos adicionales mejorados
if (!document.getElementById('ugel-chatbot-additional-styles')) {
    const styles = `
        .typing-indicator {
            opacity: 0.7;
            padding: 12px 16px !important;
        }
        
        .typing-indicator span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #666;
            display: inline-block;
            margin: 0 2px;
            animation: typing-bounce 1.4s infinite ease-in-out;
        }
        
        .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
        .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0s; }
        
        @keyframes typing-bounce {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }
        
        /* Evitar interferencias */
        .chat-fab { 
            z-index: 99999 !important; 
            pointer-events: auto !important;
        }
        
        .chat-panel { 
            z-index: 99998 !important; 
        }
        
        .chat-overlay { 
            z-index: 99997 !important; 
        }
        
        /* Mejorar accesibilidad */
        .chat-panel[aria-hidden="true"] .chat-input input {
            visibility: hidden;
        }
        
        .chat-panel[aria-hidden="false"] .chat-input input {
            visibility: visible;
        }
    `;
    
    const styleSheet = document.createElement('style');
    styleSheet.id = 'ugel-chatbot-additional-styles';
    styleSheet.textContent = styles;
    document.head.appendChild(styleSheet);
}

// Inicialización con protección mejorada
(function() {
    const initChatbot = () => {
        if (window.ugelChatbot) {
            console.log('⚠️ Chatbot ya existe');
            return;
        }
        
        console.log('🚀 Iniciando chatbot...');
        
        const requiredElements = ['chatFab', 'chatPanel', 'chatBody'];
        const missingElements = requiredElements.filter(id => !document.getElementById(id));
        
        if (missingElements.length > 0) {
            console.error('❌ Elementos faltantes:', missingElements);
            return;
        }
        
        window.ugelChatbot = new UgelChatbot();
        
        // Funciones globales
        window.toggleChat = () => window.ugelChatbot?.toggle();
        window.sendMsg = () => window.ugelChatbot?.sendMessage();
        
        console.log('✅ Chatbot listo completamente');
    };
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChatbot);
    } else {
        initChatbot();
    }
})();