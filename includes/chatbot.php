<!-- AI Chatbot Widget -->
<div id="chatbot-container" class="fixed bottom-6 right-6 z-50">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-80 overflow-hidden transition-all duration-300 transform hidden" id="chatbot-window">
        <div class="bg-primary dark:bg-primary-dark text-white p-4 flex justify-between items-center">
            <h3 class="font-bold">MECMEC Assistant</h3>
            <button id="close-chat" class="text-white hover:text-slate-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="h-64 p-4 overflow-y-auto bg-slate-50 dark:bg-slate-700" id="chat-messages">
            <div class="chat-message bot-message mb-4">
                <div class="bg-slate-200 dark:bg-slate-600 text-slate-800 dark:text-slate-100 rounded-lg p-3">
                    Hello! I'm the MECMEC Boarding House assistant. How can I help you today?
                </div>
            </div>
            <div class="chat-message bot-message mb-4">
                <div class="bg-slate-200 dark:bg-slate-600 text-slate-800 dark:text-slate-100 rounded-lg p-3">
                    <p class="font-medium mb-2">Here are some common questions:</p>
                    <div class="flex flex-col space-y-2">
                        <button class="suggestion-btn" data-question="What are your room rates?">What are your room rates?</button>
                        <button class="suggestion-btn" data-question="Do you have available rooms?">Do you have available rooms?</button>
                        <button class="suggestion-btn" data-question="What amenities do you offer?">What amenities do you offer?</button>
                        <button class="suggestion-btn" data-question="What's your exact location?">What's your exact location?</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3 border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
            <div class="flex">
                <input type="text" id="user-input" placeholder="Type your question..." class="flex-1 px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-slate-700 text-slate-800 dark:text-white">
                <button id="send-btn" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-r-lg transition">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
    <button id="chatbot-toggle" class="bg-primary hover:bg-primary-dark text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg transition-all transform hover:scale-105">
        <i class="fas fa-robot text-xl"></i>
    </button>
</div>

<style>
    .chat-message {
        display: flex;
        margin-bottom: 1rem;
    }
    .bot-message {
        justify-content: flex-start;
    }
    .user-message {
        justify-content: flex-end;
    }
    #chatbot-container {
        font-family: 'Inter', sans-serif;
    }
    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    #chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    #chat-messages::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    .dark #chat-messages::-webkit-scrollbar-track {
        background: #334155;
    }
    .dark #chat-messages::-webkit-scrollbar-thumb {
        background: #64748b;
    }
    .suggestion-btn {
        background: rgba(255,255,255,0.3);
        border: none;
        border-radius: 4px;
        padding: 6px 8px;
        text-align: left;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .suggestion-btn:hover {
        background: rgba(255,255,255,0.4);
    }
    .dark .suggestion-btn {
        background: rgba(0,0,0,0.2);
        color: white;
    }
    .dark .suggestion-btn:hover {
        background: rgba(0,0,0,0.3);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatbotContainer = document.getElementById('chatbot-container');
    const chatbotWindow = document.getElementById('chatbot-window');
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const closeChat = document.getElementById('close-chat');
    const chatMessages = document.getElementById('chat-messages');
    const userInput = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');
    
    let isChatOpen = false;
    
    // Boarding House Information
    const boardingHouseInfo = {
        name: "MECMEC Boarding House",
        owner: "Teresa Cordova",
        address: "123 Sanciangko Street, Cebu City, 6000 Philippines",
        contact: "+63 912 345 6789",
        email: "mechouse@gmail.com",
        amenities: [
            "Free WiFi",
            "Air-conditioned rooms",
            "Common kitchen",
            "Laundry service",
            "24/7 security",
            "Study area",
            "Common lounge with TV"
        ],
        roomTypes: [
            {
                type: "Single Room",
                price: "₱5,000/month",
                description: "Private room with single bed, cabinet, and study table"
            },
            {
                type: "Double Sharing",
                price: "₱3,500/month per person",
                description: "Shared room with two beds, ideal for friends"
            },
            {
                type: "Dormitory",
                price: "₱2,800/month per person",
                description: "Shared room with 4 beds, common bathroom"
            }
        ],
        rules: [
            "No visitors after 10 PM",
            "No smoking inside rooms",
            "Keep common areas clean",
            "Quiet hours from 10 PM to 6 AM",
            "Monthly payment due every 5th of the month"
        ],
        nearby: [
            "5-minute walk to University of Cebu",
            "Near 7-Eleven and Mini-Stop",
            "Walking distance to Ayala Mall",
            "Many affordable eateries nearby"
        ]
    };
    
    // CHATOPENAI AI configuration
    const API_KEY = "AIzaSyD3TuaSjaTz9pG6KV7JiiI4lzlvGigKlpM";
    const URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${API_KEY}`;
    const systemPrompt = `
    You are an assistant for MECMEC Boarding House. Here are the details you should know:

    Boarding House Name: ${boardingHouseInfo.name}
    Owner: ${boardingHouseInfo.owner}
    Address: ${boardingHouseInfo.address}
    Contact: ${boardingHouseInfo.contact}
    Email: ${boardingHouseInfo.email}

    Amenities:
    ${boardingHouseInfo.amenities.join('\n')}

    Room Types and Prices:
    ${boardingHouseInfo.roomTypes.map(room => `${room.type}: ${room.price} - ${room.description}`).join('\n')}

    House Rules:
    ${boardingHouseInfo.rules.join('\n')}

    Nearby Services:
    ${boardingHouseInfo.nearby.join('\n')}

    Answer questions about:
    - Room availability
    - Pricing and payments
    - House rules
    - Facilities and amenities
    - Nearby services
    - Any other boarding house related questions

    Be friendly, helpful, and provide accurate information based on the details above.
    If asked for contact information, provide both phone and email.
    For specific room availability, say they should call or visit to check current availability.
    `;
    
    // Show chat window when toggle button is clicked
    chatbotToggle.addEventListener('click', function() {
        if (!isChatOpen) {
            chatbotWindow.classList.remove('hidden');
            isChatOpen = true;
        } else {
            chatbotWindow.classList.add('hidden');
            isChatOpen = false;
        }
    });
    
    // Close chat window but keep the widget visible
    closeChat.addEventListener('click', function() {
        chatbotWindow.classList.add('hidden');
        isChatOpen = false;
    });
    
    // Handle suggestion button clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('suggestion-btn')) {
            const question = e.target.getAttribute('data-question');
            userInput.value = question;
            userInput.focus();
        }
    });
    
    // Send message function
    async function sendMessage() {
        const message = userInput.value.trim();
        if (message === '') return;
        
        // Add user message to chat
        addMessage(message, 'user');
        userInput.value = '';
        
        // Show typing indicator
        const typingIndicator = addTypingIndicator();
        
        try {
            // Get AI response from Gemini
            const payload = {
                contents: [{
                    parts: [{
                        text: `${systemPrompt}\n\nUser: ${message}`
                    }]
                }]
            };
            
            const response = await fetch(URL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            });
            
            const data = await response.json();
            const aiResponse = data?.candidates?.[0]?.content?.parts?.[0]?.text || 
                              "I'm sorry, I couldn't process your request. Please try again later.";
            
            // Remove typing indicator and add bot response
            chatMessages.removeChild(typingIndicator);
            addMessage(aiResponse, 'bot');
        } catch (error) {
            console.error('Error:', error);
            chatMessages.removeChild(typingIndicator);
            addMessage("I'm having trouble connecting right now. Please try again later or contact us directly at " + boardingHouseInfo.contact, 'bot');
        }
    }
    
    // Add typing indicator
    function addTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chat-message bot-message mb-4';
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'bg-slate-200 dark:bg-slate-600 text-slate-800 dark:text-slate-100 rounded-lg p-3 max-w-xs';
        
        // Add typing animation dots
        contentDiv.innerHTML = '<span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>';
        typingDiv.appendChild(contentDiv);
        chatMessages.appendChild(typingDiv);
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        return typingDiv;
    }
    
    // Add message to chat
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${sender}-message mb-4`;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = sender === 'bot' 
            ? 'bg-slate-200 dark:bg-slate-600 text-slate-800 dark:text-slate-100 rounded-lg p-3'
            : 'bg-primary dark:bg-primary-dark text-white rounded-lg p-3';
        
        contentDiv.textContent = text;
        messageDiv.appendChild(contentDiv);
        chatMessages.appendChild(messageDiv);
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Send message on button click
    sendBtn.addEventListener('click', sendMessage);
    
    // Send message on Enter key
    userInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
    
    // Add typing animation style
    const style = document.createElement('style');
    style.textContent = `
        .typing-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #64748b;
            margin: 0 2px;
            animation: typingAnimation 1.4s infinite ease-in-out;
        }
        .typing-dot:nth-child(1) {
            animation-delay: 0s;
        }
        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }
        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }
        @keyframes typingAnimation {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-5px); }
        }
    `;
    document.head.appendChild(style);
    
    // Show chatbot after page loads
    setTimeout(() => {
        chatbotContainer.classList.remove('hidden');
    }, 3000);
});
</script>