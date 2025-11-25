<!-- Chatbox Widget -->
<div id="chatbox-widget" class="chatbox-widget">
    <div id="chatbox-toggle" class="chatbox-toggle">
        <i class="fa fa-comments"></i>
        <span class="chatbox-badge" id="chatbox-badge">0</span>
    </div>
    
    <div id="chatbox-container" class="chatbox-container">
        <div class="chatbox-header">
            <div class="chatbox-header-info">
                <i class="fa fa-robot"></i>
                <span>Trợ lý ảo</span>
            </div>
            <button id="chatbox-close" class="chatbox-close">
                <i class="fa fa-times"></i>
            </button>
        </div>
        
        <div id="chatbox-messages" class="chatbox-messages">
            <!-- Messages will be loaded here -->
        </div>
        
        <div class="chatbox-input-container">
            <input type="text" id="chatbox-input" class="chatbox-input" placeholder="Nhập tin nhắn của bạn...">
            <button id="chatbox-send" class="chatbox-send">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<style>
/* Chatbox Styles */
.chatbox-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9998;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.chatbox-toggle {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    position: relative;
}

.chatbox-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.chatbox-toggle i {
    color: white;
    font-size: 24px;
}

.chatbox-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff4444;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
}

.chatbox-container {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 380px;
    max-width: calc(100vw - 40px);
    height: 600px;
    max-height: calc(100vh - 120px);
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    overflow: hidden;
    z-index: 10000;
}

/* Responsive cho mobile */
@media (max-width: 767px) {
    .chatbox-container {
        width: calc(100vw - 20px);
        right: 10px;
        bottom: 80px;
        height: calc(100vh - 100px);
        max-height: calc(100vh - 100px);
    }
    
    .chatbox-widget {
        bottom: 15px;
        right: 15px;
    }
    
    .chatbox-toggle {
        width: 50px;
        height: 50px;
    }
    
    .chatbox-toggle i {
        font-size: 20px;
    }
}

.chatbox-container.active {
    display: flex;
}

.chatbox-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chatbox-header-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.chatbox-header-info i {
    font-size: 20px;
}

.chatbox-close {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 5px;
    transition: transform 0.2s;
}

.chatbox-close:hover {
    transform: rotate(90deg);
}

.chatbox-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f5f5f5;
}

.chatbox-message {
    margin-bottom: 15px;
    display: flex;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chatbox-message.user {
    justify-content: flex-end;
}

.chatbox-message-content {
    max-width: 75%;
    padding: 12px 16px;
    border-radius: 18px;
    word-wrap: break-word;
    line-height: 1.4;
}

.chatbox-message.user .chatbox-message-content {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 4px;
}

.chatbox-message.bot .chatbox-message-content {
    background: white;
    color: #333;
    border-bottom-left-radius: 4px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.chatbox-message.bot .chatbox-message-content strong {
    color: #667eea;
    font-weight: 600;
}

.chatbox-message.bot .chatbox-message-content br {
    line-height: 1.6;
}

.chatbox-message-time {
    font-size: 11px;
    color: #999;
    margin-top: 5px;
    text-align: right;
}

.chatbox-message.bot .chatbox-message-time {
    text-align: left;
}

/* Product Cards in Chat */
.chatbox-product-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 10px;
}

.chatbox-product-card {
    background: white;
    border-radius: 10px;
    padding: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0;
}

.chatbox-product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-color: #667eea;
}

.chatbox-product-card-content {
    display: flex;
    gap: 12px;
}

.chatbox-product-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.chatbox-product-info {
    flex: 1;
}

.chatbox-product-name {
    font-weight: 600;
    font-size: 14px;
    color: #333;
    margin-bottom: 5px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.chatbox-product-price {
    font-size: 16px;
    font-weight: bold;
    color: #667eea;
}

.chatbox-product-original-price {
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
    margin-left: 8px;
}

.chatbox-product-discount {
    display: inline-block;
    background: #ff4444;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: bold;
    margin-left: 8px;
}

.chatbox-product-button {
    margin-top: 8px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    width: 100%;
    transition: all 0.3s ease;
}

.chatbox-product-button:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.chatbox-input-container {
    display: flex;
    padding: 15px;
    background: white;
    border-top: 1px solid #e0e0e0;
    gap: 10px;
}

.chatbox-input {
    flex: 1;
    padding: 12px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 25px;
    outline: none;
    font-size: 14px;
    transition: border-color 0.3s;
}

.chatbox-input:focus {
    border-color: #667eea;
}

.chatbox-send {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.chatbox-send:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.chatbox-send:active {
    transform: scale(0.95);
}

/* Buttons */
.chatbox-buttons {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 10px;
}

.chatbox-button {
    padding: 10px 16px;
    background: white;
    border: 2px solid #667eea;
    border-radius: 20px;
    color: #667eea;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-align: left;
    width: 100%;
}

.chatbox-button:hover {
    background: #667eea;
    color: white;
    transform: translateX(5px);
}

.chatbox-button:active {
    transform: translateX(5px) scale(0.98);
}

/* Scrollbar */
.chatbox-messages::-webkit-scrollbar {
    width: 6px;
}

.chatbox-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chatbox-messages::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.chatbox-messages::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Responsive */
@media (max-width: 768px) {
    .chatbox-container {
        width: calc(100vw - 40px);
        height: calc(100vh - 120px);
        bottom: 80px;
        right: 20px;
        left: 20px;
    }
}
</style>

<script>
(function() {
    const chatboxWidget = document.getElementById('chatbox-widget');
    const chatboxToggle = document.getElementById('chatbox-toggle');
    const chatboxContainer = document.getElementById('chatbox-container');
    const chatboxClose = document.getElementById('chatbox-close');
    const chatboxMessages = document.getElementById('chatbox-messages');
    const chatboxInput = document.getElementById('chatbox-input');
    const chatboxSend = document.getElementById('chatbox-send');
    
    let isOpen = false;
    
    // Toggle chatbox
    chatboxToggle.addEventListener('click', function() {
        isOpen = !isOpen;
        if(isOpen) {
            chatboxContainer.classList.add('active');
            loadHistory();
            chatboxInput.focus();
        } else {
            chatboxContainer.classList.remove('active');
        }
    });
    
    chatboxClose.addEventListener('click', function() {
        isOpen = false;
        chatboxContainer.classList.remove('active');
    });
    
    // Send message
    function sendMessage() {
        const message = chatboxInput.value.trim();
        if(!message) return;
        
        // Add user message to chat
        addMessage(message, 'user');
        chatboxInput.value = '';
        
        // Send to server
        fetch('<?php echo base_url("chatbox/send_message"); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'message=' + encodeURIComponent(message)
        })
        .then(response => {
            // Check if response is ok
            if(!response.ok) {
                // Try to get error message from response
                return response.text().then(text => {
                    try {
                        const errorData = JSON.parse(text);
                        throw new Error(errorData.error || 'Network response was not ok');
                    } catch(e) {
                        throw new Error('Lỗi kết nối server. Vui lòng thử lại sau.');
                    }
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // Debug
            if(data && data.success) {
                // Check for redirect
                if(data.bot_response && data.bot_response.msg_type === 'redirect' && data.bot_response.url) {
                    addMessage(data.bot_response.message || 'Đang chuyển hướng...', 'bot');
                    setTimeout(function() {
                        window.location.href = data.bot_response.url;
                    }, 500);
                    return;
                }
                
                // Add bot response
                if(data.bot_response && data.bot_response.msg_type === 'product_list') {
                    addProductList(data.bot_response);
                } else if(data.bot_response && data.bot_response.message) {
                    // Check if has buttons
                    const buttons = data.bot_response.buttons || null;
                    addMessage(data.bot_response.message, 'bot', buttons);
                } else if(data.bot_response) {
                    // Try to parse if it's a string
                    try {
                        const parsed = typeof data.bot_response === 'string' ? JSON.parse(data.bot_response) : data.bot_response;
                        if(parsed.message) {
                            addMessage(parsed.message, 'bot', parsed.buttons || null);
                        } else {
                            addMessage('Xin lỗi, tôi không hiểu. Bạn có thể thử lại với câu hỏi khác.', 'bot');
                        }
                    } catch(e) {
                        addMessage('Xin lỗi, tôi không hiểu. Bạn có thể thử lại với câu hỏi khác.', 'bot');
                    }
                } else {
                    addMessage('Xin lỗi, tôi không hiểu. Bạn có thể thử lại với câu hỏi khác.', 'bot');
                }
            } else {
                addMessage(data && data.error ? data.error : 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            addMessage('❌ Xin lỗi, có lỗi xảy ra khi kết nối server. Vui lòng kiểm tra kết nối và thử lại sau.', 'bot');
        });
    }
    
    chatboxSend.addEventListener('click', sendMessage);
    chatboxInput.addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            sendMessage();
        }
    });
    
    // Add message to chat
    function addMessage(text, sender, buttons = null, saveToLocal = true) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'chatbox-message ' + sender;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'chatbox-message-content';
        // Replace \n with <br> for line breaks
        contentDiv.innerHTML = text.replace(/\n/g, '<br>');
        
        // Add buttons if available
        if(buttons && Array.isArray(buttons) && buttons.length > 0) {
            const buttonsDiv = document.createElement('div');
            buttonsDiv.className = 'chatbox-buttons';
            buttons.forEach(function(button) {
                const btn = document.createElement('button');
                btn.className = 'chatbox-button';
                btn.textContent = button.text || button.value;
                btn.onclick = function() {
                    const value = button.value || button.text || button.intent;
                    chatboxInput.value = value;
                    sendMessage();
                };
                buttonsDiv.appendChild(btn);
            });
            contentDiv.appendChild(buttonsDiv);
        }
        
        const timeDiv = document.createElement('div');
        timeDiv.className = 'chatbox-message-time';
        timeDiv.textContent = new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'});
        
        messageDiv.appendChild(contentDiv);
        messageDiv.appendChild(timeDiv);
        chatboxMessages.appendChild(messageDiv);
        
        // Save to localStorage
        if(saveToLocal) {
            saveMessageToLocal(text, sender, buttons);
        }
        
        scrollToBottom();
    }
    
    // Save message to localStorage
    function saveMessageToLocal(text, sender, buttons) {
        try {
            let history = JSON.parse(localStorage.getItem('chatbox_history') || '[]');
            history.push({
                text: text,
                sender: sender,
                buttons: buttons,
                time: new Date().toISOString()
            });
            // Keep only last 100 messages
            if(history.length > 100) {
                history = history.slice(-100);
            }
            localStorage.setItem('chatbox_history', JSON.stringify(history));
        } catch(e) {
            console.error('Error saving to localStorage:', e);
        }
    }
    
    // Load messages from localStorage
    function loadMessagesFromLocal() {
        try {
            const history = JSON.parse(localStorage.getItem('chatbox_history') || '[]');
            if(history.length > 0) {
                chatboxMessages.innerHTML = '';
                history.forEach(function(msg) {
                    addMessage(msg.text, msg.sender, msg.buttons, false);
                });
                scrollToBottom();
            }
        } catch(e) {
            console.error('Error loading from localStorage:', e);
        }
    }
    
    // Add product list
    function addProductList(response) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'chatbox-message bot';
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'chatbox-message-content';
        // Allow HTML in bot messages
        contentDiv.innerHTML = response.message.replace(/\n/g, '<br>');
        
        const productListDiv = document.createElement('div');
        productListDiv.className = 'chatbox-product-list';
        
        if(response.products && response.products.length > 0) {
            response.products.forEach(function(product) {
                const productCard = document.createElement('div');
                productCard.className = 'chatbox-product-card';
                
                const cardContent = document.createElement('div');
                cardContent.className = 'chatbox-product-card-content';
                
                const image = document.createElement('img');
                image.src = product.image;
                image.alt = product.name;
                image.className = 'chatbox-product-image';
                
                const info = document.createElement('div');
                info.className = 'chatbox-product-info';
                
                const name = document.createElement('div');
                name.className = 'chatbox-product-name';
                name.textContent = product.name;
                
                const priceDiv = document.createElement('div');
                priceDiv.className = 'chatbox-product-price';
                priceDiv.innerHTML = product.price;
                if(product.original_price) {
                    const originalPrice = document.createElement('span');
                    originalPrice.className = 'chatbox-product-original-price';
                    originalPrice.textContent = product.original_price;
                    priceDiv.appendChild(originalPrice);
                }
                if(product.discount) {
                    const discount = document.createElement('span');
                    discount.className = 'chatbox-product-discount';
                    discount.textContent = '-' + product.discount;
                    priceDiv.appendChild(discount);
                }
                
                const button = document.createElement('button');
                button.className = 'chatbox-product-button';
                button.textContent = 'Xem chi tiết';
                button.onclick = function() {
                    window.location.href = product.url;
                };
                
                info.appendChild(name);
                info.appendChild(priceDiv);
                cardContent.appendChild(image);
                cardContent.appendChild(info);
                productCard.appendChild(cardContent);
                productCard.appendChild(button);
                productListDiv.appendChild(productCard);
            });
        }
        
        const timeDiv = document.createElement('div');
        timeDiv.className = 'chatbox-message-time';
        timeDiv.textContent = new Date().toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'});
        
        messageDiv.appendChild(contentDiv);
        messageDiv.appendChild(productListDiv);
        messageDiv.appendChild(timeDiv);
        chatboxMessages.appendChild(messageDiv);
        
        scrollToBottom();
    }
    
    // Load chat history
    function loadHistory() {
        // First try to load from localStorage
        loadMessagesFromLocal();
        
        // Then try to load from server
        fetch('<?php echo base_url("chatbox/get_history"); ?>')
        .then(response => response.json())
        .then(data => {
            if(data.success && data.history.length > 0) {
                // Only load if localStorage is empty
                if(!localStorage.getItem('chatbox_history')) {
                    chatboxMessages.innerHTML = '';
                    data.history.forEach(function(msg) {
                        if(msg.sender === 'user') {
                            addMessage(msg.message, 'user');
                        } else if(msg.sender === 'bot') {
                            // Parse bot message - could be JSON or text
                            try {
                                const botData = JSON.parse(msg.message);
                                if(botData.msg_type === 'product_list') {
                                    addProductList(botData);
                                } else {
                                    addMessage(botData.message || msg.message, 'bot', botData.buttons || null);
                                }
                            } catch(e) {
                                addMessage(msg.message, 'bot');
                            }
                        }
                    });
                }
            } else if(!localStorage.getItem('chatbox_history')) {
                // Welcome message only if no history
                const welcomeMsg = '👋 <strong>Xin chào!</strong> Tôi là trợ lý ảo của cửa hàng. Tôi có thể giúp bạn:<br><br>✨ <strong>Tìm sản phẩm theo giá:</strong> "sản phẩm 20 triệu", "dưới 10 triệu"<br>🔥 <strong>Tìm sản phẩm đang giảm giá:</strong> "sale", "giảm giá"<br>🔍 <strong>Tìm sản phẩm theo tên:</strong> "iPhone", "Samsung", "Laptop"<br>📱 <strong>Tìm theo danh mục:</strong> "điện thoại", "laptop", "tablet"<br><br>💬 <strong>Bạn muốn tìm gì hôm nay?</strong>';
                const welcomeButtons = [
                    {text: '💰 Sản phẩm dưới 10 triệu', value: 'dưới 10 triệu'},
                    {text: '💵 Sản phẩm giá rẻ', value: 'giá rẻ'},
                    {text: '🔥 Sản phẩm đang sale', value: 'sale'},
                    {text: '📦 Xem tất cả sản phẩm', value: 'xem tất cả sản phẩm'}
                ];
                addMessage(welcomeMsg, 'bot', welcomeButtons);
            }
        })
        .catch(error => {
            console.error('Error loading history:', error);
            if(!localStorage.getItem('chatbox_history')) {
                const welcomeMsg = '👋 <strong>Xin chào!</strong> Tôi là trợ lý ảo của cửa hàng. Tôi có thể giúp bạn:<br><br>✨ <strong>Tìm sản phẩm theo giá:</strong> "sản phẩm 20 triệu", "dưới 10 triệu"<br>🔥 <strong>Tìm sản phẩm đang giảm giá:</strong> "sale", "giảm giá"<br>🔍 <strong>Tìm sản phẩm theo tên:</strong> "iPhone", "Samsung", "Laptop"<br>📱 <strong>Tìm theo danh mục:</strong> "điện thoại", "laptop", "tablet"<br><br>💬 <strong>Bạn muốn tìm gì hôm nay?</strong>';
                const welcomeButtons = [
                    {text: '💰 Sản phẩm dưới 10 triệu', value: 'dưới 10 triệu'},
                    {text: '💵 Sản phẩm giá rẻ', value: 'giá rẻ'},
                    {text: '🔥 Sản phẩm đang sale', value: 'sale'},
                    {text: '📦 Xem tất cả sản phẩm', value: 'xem tất cả sản phẩm'}
                ];
                addMessage(welcomeMsg, 'bot', welcomeButtons);
            }
        });
    }
    
    // Scroll to bottom
    function scrollToBottom() {
        chatboxMessages.scrollTop = chatboxMessages.scrollHeight;
    }
    
    // Auto open on first visit (optional)
    // if(!localStorage.getItem('chatbox_opened')) {
    //     setTimeout(function() {
    //         chatboxToggle.click();
    //         localStorage.setItem('chatbox_opened', 'true');
    //     }, 3000);
    // }
})();
</script>

