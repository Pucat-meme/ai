<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SONE AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;700&family=Nunito+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #121212;
            --text-color: #ffffff;
            --input-bg: rgba(255, 255, 255, 0.1);
            --button-gradient: linear-gradient(45deg, #00ffff, #ff00ff);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }

        .logo {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            background: var(--button-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        nav a {
            color: var(--text-color);
            text-decoration: none;
            margin-left: 1rem;
            transition: text-shadow 0.3s ease;
        }

        nav a:hover {
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.7);
        }

        main {
            flex-grow: 1;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 4rem;
            height: calc(100vh - 4rem);
        }

        .chat-window {
            width: 100%;
            max-width: 800px;
            height: 100%;
            background: var(--input-bg);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-container {
            flex-grow: 1;
            padding: 1rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            scroll-behavior: smooth;
        }

        .message {
            max-width: 80%;
            padding: 0.8rem 1rem;
            margin-bottom: 0.8rem;
            border-radius: 15px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s ease forwards;
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word; /* Added for better word wrapping */
            width: fit-content; /* Added to ensure proper sizing */
            max-width: 80%; /* Maintain maximum width */
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-message {
            align-self: flex-end;
            background: rgba(0, 255, 255, 0.2);
            animation-delay: 0s;
        }

        .ai-message {
            align-self: flex-start;
            background: rgba(255, 0, 255, 0.2);
            animation-delay: 0.5s;
        }

        .input-container {
            padding: 1rem;
            background: rgba(0, 0, 0, 0.3);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .input-wrapper {
            display: flex;
            gap: 0.5rem;
            background: var(--input-bg);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 0.5rem;
        }

        .note-input {
            flex-grow: 1;
            background: none;
            border: none;
            padding: 0.5rem;
            color: var(--text-color);
            font-size: 1rem;
            resize: none;
            line-height: 1.5;
            max-height: 100px;
            min-height: 40px;
        }

        .note-input:focus {
            outline: none;
        }

        .generate-btn {
            background: var(--button-gradient);
            border: none;
            padding: 0.75rem 1.5rem;
            color: #ffffff;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1rem;
            border-radius: 20px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            flex-shrink: 0;
        }

        .generate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .typing-indicator {
            display: flex;
            gap: 3px; /* Reduced gap between dots */
            padding: 6px 10px; /* Reduced padding */
            background: rgba(255, 0, 255, 0.1);
            border-radius: 12px; /* Slightly reduced border radius */
            opacity: 0;
            animation: fadeIn 0.3s ease forwards;
            align-self: flex-start;
            margin-bottom: 0.8rem;
        }

        .typing-indicator span {
            width: 4px; /* Reduced dot size */
            height: 4px; /* Reduced dot size */
            background: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out;
        }

        .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
        .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-4px); } /* Reduced bounce height */
        }

        @media (max-width: 768px) {
            .header {
                padding: 0.5rem;
            }

            .logo {
                font-size: 1.5rem;
            }

            nav a {
                margin-left: 0.5rem;
                font-size: 0.9rem;
            }

            main {
                padding: 0.5rem;
            }

            .message {
                max-width: 85%;
                padding: 0.6rem 0.8rem;
                font-size: 0.95rem;
            }

            .generate-btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .chat-window {
                border-radius: 0;
                height: calc(100vh - 4.5rem);
            }

            .message {
                max-width: 90%;
                font-size: 0.9rem;
            }

            .input-wrapper {
                padding: 0.4rem;
            }

            .note-input {
                font-size: 0.9rem;
            }

            .generate-btn {
                padding: 0.5rem 0.8rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">SONE AI</div>
        <nav>
            <a href="index.php">HOME</a>
            <a href="notes.php">NOTES</a>
            <a href="pricing.php">PRICING</a>
            <a href="logout.php">LOGOUT</a>
        </nav>
    </header>
    <main>
        <div class="chat-window">
            <div class="chat-container" id="chatContainer"></div>
            <div class="input-container">
                <form id="chatForm" class="input-wrapper">
                    <textarea class="note-input" id="userInput" placeholder="Message Sone" required></textarea>
                    <button type="submit" class="generate-btn">Send</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        const chatForm = document.getElementById('chatForm');
        const chatContainer = document.getElementById('chatContainer');
        const userInput = document.getElementById('userInput');

        function createTypingIndicator() {
            return `
                <div class="typing-indicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            `;
        }

        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const message = userInput.value.trim();
            if (message) {
                addMessage('user', escapeHtml(message));
                userInput.value = '';
                userInput.style.height = 'auto';
                const loadingMessage = addMessage('ai', createTypingIndicator());

                try {
                    const response = await fetch('newchatbotai.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            user_input: message
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();
                    
                    // Remove loading message
                    loadingMessage.style.opacity = '0';
                    setTimeout(() => {
                        loadingMessage.remove();
                        
                        // Add the response message with proper handling of long content
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('message', 'ai-message');
                        messageElement.textContent = data.response; // Using textContent for automatic escaping
                        chatContainer.appendChild(messageElement);
                        
                        // Scroll to the new message
                        setTimeout(() => {
                            messageElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
                        }, 100);
                    }, 300);
                } catch (error) {
                    loadingMessage.textContent = 'Error: ' + error.message;
                }
            }
        });

        function addMessage(sender, message) {
            const messageElement = document.createElement('div');
            messageElement.classList.add('message', sender + '-message');
            
            // Use innerHTML for the typing indicator, but textContent for actual messages
            if (message.includes('typing-indicator')) {
                messageElement.innerHTML = message;
            } else {
                messageElement.textContent = message;
            }
            
            chatContainer.appendChild(messageElement);
            
            // Ensure smooth scrolling to the bottom
            setTimeout(() => {
                messageElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }, 100);
            
            return messageElement;
        }

        // Auto-resize textarea as user types
        userInput.addEventListener('input', function() {
            this.style.height = 'auto';
            const newHeight = Math.min(this.scrollHeight, 100);
            this.style.height = newHeight + 'px';
        });

        // Handle Enter key to submit form
        userInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>
</html>