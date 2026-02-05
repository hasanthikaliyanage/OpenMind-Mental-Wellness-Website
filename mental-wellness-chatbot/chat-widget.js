// chat-widget.js
(function() {
    // Create chat widget HTML
    const widgetHTML = `
        <div id="mental-wellness-widget" style="display: none;">
            <div class="widget-container">
                <div class="widget-header">
                    <span>🧘‍♀️ Mental Wellness Assistant</span>
                    <button class="widget-close" onclick="toggleChatWidget()">&times;</button>
                </div>
                <iframe id="chat-iframe" src="/mental-wellness-chatbot/index.html" 
                        width="100%" height="500" frameborder="0"></iframe>
            </div>
        </div>
        <div class="chat-launcher" onclick="toggleChatWidget()">
            <span class="launcher-icon">💬</span>
            <span class="launcher-text">Need Support?</span>
        </div>
    `;

    // Create CSS for widget
    const widgetCSS = `
        <style>
        #mental-wellness-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 400px;
            height: 600px;
            z-index: 9999;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border-radius: 15px;
            overflow: hidden;
            background: white;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .widget-container {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .widget-header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 15px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .widget-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .widget-close:hover {
            background: rgba(255,255,255,0.2);
        }

        #chat-iframe {
            flex: 1;
            border: none;
        }

        .chat-launcher {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            border-radius: 50px;
            padding: 15px 20px;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(76, 175, 80, 0.4);
            z-index: 9998;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        .chat-launcher:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.6);
        }

        .launcher-icon {
            font-size: 20px;
        }

        .launcher-text {
            font-size: 14px;
        }

        /* Mobile responsive */
        @media (max-width: 480px) {
            #mental-wellness-widget {
                width: 95%;
                height: 90%;
                bottom: 0;
                right: 2.5%;
                border-radius: 15px 15px 0 0;
            }
            
            .chat-launcher {
                bottom: 15px;
                right: 15px;
                padding: 12px 16px;
            }
            
            .launcher-text {
                display: none;
            }
        }
        </style>
    `;

    // Add CSS and HTML to page
    document.head.insertAdjacentHTML('beforeend', widgetCSS);
    document.body.insertAdjacentHTML('beforeend', widgetHTML);

    // Toggle function
    window.toggleChatWidget = function() {
        const widget = document.getElementById('mental-wellness-widget');
        const launcher = document.querySelector('.chat-launcher');
        
        if (widget.style.display === 'none' || widget.style.display === '') {
            widget.style.display = 'block';
            launcher.style.display = 'none';
        } else {
            widget.style.display = 'none';
            launcher.style.display = 'flex';
        }
    };
})();