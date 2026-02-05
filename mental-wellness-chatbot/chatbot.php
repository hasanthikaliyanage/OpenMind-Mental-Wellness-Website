<?php
// chatbot.php - Complete Website Integrated Mental Wellness Chatbot
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

class MentalWellnessChatbot {
    private $conversations = [];
    private $sessionFile = 'chat_sessions.json';
    private $websitePages = [];
    
    public function __construct() {
        $this->loadSessions();
        $this->initializeWebsitePages();
    }
    
    private function initializeWebsitePages() {
        $this->websitePages = [
            'home' => [
                'url' => 'index.php',
                'title' => 'Home Page',
                'description' => 'Main wellness dashboard and overview'
            ],
            'mood_tracker' => [
                'url' => 'mood-tracker.php',
                'title' => 'Mood Tracker',
                'description' => 'Track and monitor your daily mood patterns'
            ],
            'find_support' => [
                'url' => 'findSupport.php',
                'title' => 'Find Support',
                'description' => 'Connect with professional counselors and therapists'
            ],
            'relaxation' => [
                'url' => 'relaxation.php',
                'title' => 'Relaxation Center',
                'description' => 'Guided relaxation techniques and exercises'
            ],
            'about_us' => [
                'url' => 'about-us.php',
                'title' => 'About Us',
                'description' => 'Learn about our mission and team'
            ],
            'blog_stories' => [
                'url' => 'blog-stories.php',
                'title' => 'Blog & Stories',
                'description' => 'Recovery stories and mental health blog posts'
            ],
            'audio_video_therapy' => [
                'url' => 'audio-video-therapy.php',
                'title' => 'Audio/Video Therapy',
                'description' => 'Therapeutic audio and video content'
            ],
            'articles' => [
                'url' => 'articles.php',
                'title' => 'Articles',
                'description' => 'Educational articles on mental health topics'
            ],
            'digital_detox' => [
                'url' => 'digital-detox.php',
                'title' => 'Digital Detox',
                'description' => 'Tips and tools for healthy technology use'
            ],
            'podcast' => [
                'url' => 'podcast.php',
                'title' => 'Podcast',
                'description' => 'Mental wellness podcasts and audio content'
            ],
            'yoga' => [
                'url' => 'yoga.php',
                'title' => 'Yoga',
                'description' => 'Yoga routines for mental and physical wellness'
            ],
            'dream_analyzer' => [
                'url' => 'dream-analyzer.php',
                'title' => 'Dream Analyzer',
                'description' => 'Analyze and understand your dreams'
            ]
        ];
    }
    
    private function loadSessions() {
        if (file_exists($this->sessionFile)) {
            $data = file_get_contents($this->sessionFile);
            $this->conversations = json_decode($data, true) ?: [];
        }
    }
    
    private function saveSessions() {
        file_put_contents($this->sessionFile, json_encode($this->conversations, JSON_PRETTY_PRINT));
    }
    
    public function processMessage($message, $sessionId) {
        $originalMessage = trim($message);
        $message = strtolower($originalMessage);
        
        // Initialize session if not exists
        if (!isset($this->conversations[$sessionId])) {
            $this->conversations[$sessionId] = [
                'messages' => [],
                'context' => [],
                'created_at' => date('Y-m-d H:i:s'),
                'last_activity' => date('Y-m-d H:i:s')
            ];
        }
        
        // Update last activity
        $this->conversations[$sessionId]['last_activity'] = date('Y-m-d H:i:s');
        
        // Add user message to session
        $this->conversations[$sessionId]['messages'][] = [
            'type' => 'user',
            'message' => $originalMessage,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Detect intent and generate response
        $intent = $this->detectIntent($message);
        $response = $this->generateResponse($intent, $message, $sessionId);
        
        // Add bot response to session
        $this->conversations[$sessionId]['messages'][] = [
            'type' => 'bot',
            'message' => $response['message'],
            'intent' => $intent,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Save sessions
        $this->saveSessions();
        
        return $response;
    }
    
    private function detectIntent($message) {
        // Crisis keywords (English & Sinhala)
        $crisisKeywords = [
            'suicide', 'kill myself', 'end my life', 'want to die', 'no point living',
            'harm myself', 'hurt myself', 'cutting', 'overdose', 'jump off',
            'මරන්න', 'මැරෙන්න', 'ජීවිතේ නවත්තන්න', 'සියදිවි නසා', 'මැරිලා'
        ];
        
        // Website navigation keywords
        $navigationKeywords = [
            // Home
            'home', 'main page', 'dashboard', 'මුල් පිටුව',
            
            // Mood Tracker
            'mood tracker', 'track mood', 'mood tracking', 'daily mood', 'mood diary',
            'මනෝභාවය', 'මනෝභාව ට්‍රැකර්',
            
            // Find Support
            'find support', 'counselor', 'therapist', 'professional help', 'therapy',
            'appointment', 'book counselor', 'mental health services',
            'උපදේශක', 'සහාය', 'ප්‍රතිකාර', 'වෛද්‍යවරයා',
            
            // Relaxation
            'relaxation', 'relax', 'calm', 'peaceful', 'meditation', 'breathing',
            'ප්‍රශාන්තකරණය', 'සන්සුන්', 'භාවනා',
            
            // Blog & Stories
            'blog', 'stories', 'recovery stories', 'success stories', 'experiences',
            'කතන්දර', 'සාර්ථක කතා', 'අත්දැකීම්',
            
            // Audio/Video Therapy
            'audio therapy', 'video therapy', 'therapeutic videos', 'guided sessions',
            'ශ්‍රව්‍ය ප්‍රතිකාර', 'වීඩියෝ ප්‍රතිකාර',
            
            // Articles
            'articles', 'educational content', 'mental health articles', 'learn',
            'ලිපි', 'අධ්‍යාපනික', 'ඉගෙනගන්න',
            
            // Digital Detox
            'digital detox', 'screen time', 'technology break', 'social media detox',
            'ඩිජිටල් ඩිටොක්ස්', 'තාක්ෂණික විවේකය',
            
            // Podcast
            'podcast', 'audio content', 'mental health podcast', 'wellness podcast',
            'පොඩ්කාස්ට්', 'ශ්‍රව්‍ය',
            
            // Yoga
            'yoga', 'yoga poses', 'yoga for mental health', 'physical wellness',
            'යෝග', 'ශාරීරික සුවතාව',
            
            // Dream Analyzer
            'dream analyzer', 'analyze dreams', 'dream interpretation', 'dreams',
            'සිහින විශ්ලේෂණය', 'සිහින'
        ];
        
        // Depression/Anxiety keywords
        $mentalHealthKeywords = [
            'depressed', 'depression', 'anxiety', 'anxious', 'panic', 'stress',
            'sad', 'lonely', 'overwhelmed', 'worried', 'scared',
            'උදාසීන', 'කනස්සල්ල', 'බය', 'ස්ට්‍රෙස්'
        ];
        
        // Check for crisis first (highest priority)
        foreach ($crisisKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return 'crisis';
            }
        }
        
        // Check for specific page navigation
        if (strpos($message, 'mood tracker') !== false || strpos($message, 'track mood') !== false) {
            return 'navigate_mood_tracker';
        }
        
        if (strpos($message, 'find support') !== false || strpos($message, 'counselor') !== false || strpos($message, 'therapist') !== false) {
            return 'navigate_find_support';
        }
        
        if (strpos($message, 'relaxation') !== false || strpos($message, 'meditation') !== false || strpos($message, 'breathing') !== false) {
            return 'navigate_relaxation';
        }
        
        if (strpos($message, 'blog') !== false || strpos($message, 'stories') !== false) {
            return 'navigate_blog_stories';
        }
        
        if (strpos($message, 'audio therapy') !== false || strpos($message, 'video therapy') !== false) {
            return 'navigate_audio_video';
        }
        
        if (strpos($message, 'articles') !== false) {
            return 'navigate_articles';
        }
        
        if (strpos($message, 'digital detox') !== false) {
            return 'navigate_digital_detox';
        }
        
        if (strpos($message, 'podcast') !== false) {
            return 'navigate_podcast';
        }
        
        if (strpos($message, 'yoga') !== false) {
            return 'navigate_yoga';
        }
        
        if (strpos($message, 'dream') !== false) {
            return 'navigate_dream_analyzer';
        }
        
        if (strpos($message, 'home') !== false || strpos($message, 'main') !== false) {
            return 'navigate_home';
        }
        
        if (strpos($message, 'about') !== false) {
            return 'navigate_about';
        }
        
        // Check for mental health issues
        foreach ($mentalHealthKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return $this->assessSeverity($message) === 'high' ? 'severe_mental_health' : 'mental_health_support';
            }
        }
        
        // Check for general help
        if (strpos($message, 'help') !== false || strpos($message, 'support') !== false) {
            return 'general_help';
        }
        
        // Check for navigation/menu request
        if (strpos($message, 'menu') !== false || strpos($message, 'options') !== false || strpos($message, 'what can you do') !== false) {
            return 'show_menu';
        }
        
        // Greetings
        $greetings = ['hello', 'hi', 'hey', 'ayubowan', 'කොහොමද'];
        foreach ($greetings as $greeting) {
            if (strpos($message, $greeting) !== false) {
                return 'greeting';
            }
        }
        
        return 'general';
    }
    
    private function assessSeverity($message) {
        $highSeverityWords = [
            'can\'t take it', 'extremely', 'severe', 'unbearable', 'constant',
            'every day', 'all the time', 'getting worse', 'very bad', 'hopeless'
        ];
        
        foreach ($highSeverityWords as $word) {
            if (strpos($message, $word) !== false) {
                return 'high';
            }
        }
        
        return 'moderate';
    }
    
    private function generateResponse($intent, $message, $sessionId) {
        switch ($intent) {
            case 'crisis':
                return [
                    'message' => "🚨 **CRISIS SUPPORT NEEDED**\n\nI'm very concerned about you. Your safety is the most important thing right now.\n\n**IMMEDIATE HELP:**\n🔴 **Crisis Hotline: 1333** (24/7 Free)\n🔴 **Emergency: 119**\n🔴 **Samaritans: 0717 639 639**\n\nPlease reach out to professional support immediately. You don't have to go through this alone.",
                    'action' => 'crisis_alert',
                    'urgent' => true,
                    'show_page_button' => true,
                    'page_url' => 'http://localhost/OMP/findSupport.phpfindSupport.php',
                    'page_text' => "🚨 Get Professional Help NOW",
                    'priority' => 'immediate'
                ];
                
            case 'navigate_mood_tracker':
                return [
                    'message' => "📊 **Mood Tracker** is a great tool for mental wellness!\n\n✨ **Features:**\n• Daily mood logging\n• Mood pattern analysis\n• Trigger identification\n• Progress tracking\n• Mood insights and reports\n\n📈 Regular mood tracking helps you understand patterns and improve your mental health over time.",
                    'action' => 'navigate',
                    'show_page_button' => true,
                    'page_url' => 'mood-tracker.php',
                    'page_text' => "📊 Open Mood Tracker",
                    'resources' => ['articles', 'blog_stories']
                ];
                
            case 'navigate_find_support':
                return [
                    'message' => "🏥 **Find Support** - Connect with Professional Help\n\n👥 **Available Services:**\n• Licensed Mental Health Counselors\n• Anxiety & Panic Specialists\n• Depression Treatment Experts\n• Crisis Intervention\n• Family & Relationship Counseling\n• Group Therapy Sessions\n\n✅ **What We Offer:**\n• Confidential sessions\n• Flexible scheduling\n• Both English & Sinhala support\n• Online & in-person options",
                    'action' => 'navigate',
                    'show_page_button' => true,
                    'page_url' => 'findSupport.php',
                    'page_text' => "🏥 Find Professional Support",
                    'urgent' => false
                ];
                
            case 'navigate_relaxation':
                return [
                    'message' => "🧘‍♀️ **Relaxation Center** - Find Your Inner Peace\n\n🌟 **Available Techniques:**\n• Guided meditation sessions\n• Progressive muscle relaxation\n• Deep breathing exercises\n• Mindfulness practices\n• Body scan relaxation\n• Visualization techniques\n\n💫 Take a moment to breathe and center yourself.",
                    'action' => 'navigate',
                    'show_page_button' => true,
                    'page_url' => 'relaxation.php',
                    'page_text' => "🧘‍♀️ Start Relaxing Now",
                    'resources' => ['breathing', 'meditation', 'yoga']
                ];
                
            case 'navigate_blog_stories':
                return [
                    'message' => "📖 **Blog & Stories** - Real Experiences, Real Hope\n\n💪 **What You'll Find:**\n• Recovery success stories\n• Personal mental health journeys\n• Expert blog posts\n• Community experiences\n• Tips from survivors\n• Inspirational content\n\n✨ These stories show that recovery is possible and you're not alone!",
                    'action' => 'navigate',
                    'show_page_button' => true,
                    'page_url' => 'blog-stories.php',
                    'page_text' => "📖 Read Stories & Blogs"
                ];
                
            case 'navigate_audio_video':
                return [
                    'message' => "🎥 **Audio/Video Therapy** - Healing Through Media\n\n🔊 **Available Content:**\n• Guided therapy sessions\n• Relaxation videos\n• Therapeutic audio tracks\n• Mindfulness videos\n• Sleep stories\n• Calming nature sounds\n\n🎧 Perfect for when you need immediate support or relaxation.",
                    'action' => 'navigate',
                    'show_page_button' => true,
                    'page_url' => 'audio-video-therapy.php',
                    'page_text' => "🎥 Access Audio/Video Therapy"
                ];
                
            case 'navigate_articles':
                return [
                    'message' => "📚 **Educational Articles** - Knowledge for Wellness\n\n📖 **Article Categories:**\n• Understanding mental health conditions\n• Coping strategies and techniques\n• Self-care and wellness tips\n• Relationship and family support\n• Treatment options explained\n• Latest research insights\n\n🧠 Knowledge is power in your mental health journey!",
                    'action' => 'navigate',
                    'show_page_button' => true,
                    'page_url' => 'articles.php',
                    'page_text' => "📚 Browse Articles"
                ];
                
            case 'navigate_digital_detox':
                return [
                    'message' => "📱 **Digital Detox** - Healthy Technology Balance\n\n⚖️ **What We Offer:**\n• Screen time management tips\n• Social media detox guides\n• Healthy tech boundaries\n• Offline activity suggestions\n• Mindful technology use\n• Digital wellness assessments\n\n🌿 Sometimes disconnecting helps us reconnect with ourselves.",
                    'action' => 'navigate',
                    'show_page_button' => true,
                    'page_url' => 'digital-detox.php',
                    'page_text' => "📱 Start Digital Detox"
                ];
                
            case 'navigate_podcast':
                return [
                    'message' => "🎙️ **Mental Wellness Podcasts** - Listen & Learn\n\n🔊 **Podcast Categories:**\n• Mental health discussions\n• Expert interviews\n• Recovery stories\n• Mindfulness sessions\n• Wellness tips\n• Guided meditations\n\n🎧 Perfect for learning on the go or during relaxation time!",
                    'action' => 'navigate',
                    'show_page_button' => true,
                    'page_url' => 'podcast.php',
                    'page_text' => "🎙️ Listen to Podcasts"
                ];
                
            case 'navigate_yoga':
                return [
                    'message' => "🧘‍♀️ **Yoga for Mental Wellness** - Mind-Body Connection\n\n🌟 **Yoga Sessions:**\n• Beginner-friendly routines\n• Anxiety relief yoga\n• Depression support poses\n• Morning energizing flows\n• Evening relaxation sequences\n• Breathing-focused practices\n\n💪 Yoga combines physical movement with mental wellness!",
                    'action' => 'navigate',
                    'show_page_button' => true,
                    'page_url' => 'yoga.php',
                    'page_text' => "🧘‍♀️ Start Yoga Practice"
                ];
                
            case 'navigate_dream_analyzer':
                return [
                    'message' => "🌙 **Dream Analyzer** - Understand Your Dreams\n\n✨ **Dream Analysis Features:**\n• Dream interpretation tools\n• Common dream meanings\n• Dream pattern tracking\n• Psychological insights\n• Dream journal features\n• Symbol meanings\n\n💭 Dreams can offer insights into our subconscious mind and emotional state.",
                    'action' => 'navigate',
                    'show_page_button' => true,
                    'page_url' => 'dream-analyzer.php',
                    'page_text' => "🌙 Analyze Your Dreams"
                ];
                
            case 'navigate_home':
                return [
                    'message' => "🏠 **Welcome Home** - Your Mental Wellness Dashboard\n\n🌟 **Home Features:**\n• Quick access to all services\n• Daily wellness tips\n• Mood check-ins\n• Progress overview\n• Personalized recommendations\n• Latest updates\n\nYour journey to better mental health starts here!",
                    'action' => 'navigate',
                    'show_page_button' => true,
                    'page_url' => 'index.php',
                    'page_text' => "🏠 Go to Home Page"
                ];
                
            case 'navigate_about':
                return [
                    'message' => "ℹ️ **About Us** - Our Mission & Team\n\n💙 **Learn About:**\n• Our mission and values\n• Mental health expertise\n• Team of professionals\n• Success stories\n• Contact information\n• Our approach to wellness\n\n🤝 We're here to support your mental health journey every step of the way.",
                    'action' => 'navigate',
                    'show_page_button' => true,
                    'page_url' => 'about-us.php',
                    'page_text' => "ℹ️ Learn About Us"
                ];
                
            case 'severe_mental_health':
                return [
                    'message' => "💙 I can sense you're going through a really difficult time right now. Your feelings are completely valid, and I want you to know that you're not alone.\n\n🆘 **Immediate Support:**\n📞 **Crisis Hotline: 1333** (Free, 24/7)\n🏥 **Professional Help Available**\n\n🌟 **Helpful Resources:**\n• Find Support page for professional counseling\n• Relaxation techniques for immediate relief\n• Recovery stories for hope and inspiration",
                    'action' => 'severe_support',
                    'urgent' => true,
                    'show_page_button' => true,
                    'page_url' => 'findSupport.php',
                    'page_text' => "🏥 Get Professional Help",
                    'resources' => ['relaxation', 'blog_stories', 'audio_video']
                ];
                
            case 'mental_health_support':
                return [
                    'message' => "💚 Thank you for reaching out. It takes courage to talk about mental health, and I'm here to support you.\n\n🌟 **Ways I Can Help:**\n• Connect you with professional support\n• Guide you to relaxation techniques\n• Share helpful articles and resources\n• Show you inspiring recovery stories\n\n🤝 Which type of support would be most helpful for you right now?",
                    'action' => 'general_support',
                    'show_page_button' => true,
                    'page_url' => 'findSupport.php',
                    'page_text' => "🏥 Explore Support Options",
                    'resources' => ['relaxation', 'articles', 'mood_tracker']
                ];
                
            case 'show_menu':
                return [
                    'message' => "🌟 **Mental Wellness Menu** - All Available Services\n\n🏠 **Main Pages:**\n• Home - Your wellness dashboard\n• Mood Tracker - Track daily moods\n• Find Support - Professional counseling\n• Relaxation - Meditation & breathing\n• About Us - Our mission & team\n\n📚 **Resources:**\n• Blog & Stories - Recovery experiences\n• Audio/Video Therapy - Healing media\n• Articles - Educational content\n• Digital Detox - Healthy tech balance\n• Podcast - Mental wellness audio\n• Yoga - Mind-body practices\n• Dream Analyzer - Understand dreams\n\n💬 Just tell me what interests you!",
                    'action' => 'show_menu',
                    'show_all_pages' => true
                ];
                
            case 'general_help':
                return [
                    'message' => "🤝 I'm here to help you with your mental wellness journey!\n\n💡 **I can help you:**\n• Navigate to different sections of our website\n• Find professional support and counseling\n• Access relaxation and meditation tools\n• Discover helpful articles and resources\n• Connect with recovery stories\n• Use our mood tracking tools\n\n📱 **Quick Navigation:**\nJust say things like:\n• \"Find support\" for counseling\n• \"Relaxation\" for calm activities\n• \"Mood tracker\" for mood logging\n• \"Articles\" for educational content\n\nWhat would you like help with?",
                    'action' => 'general_help',
                    'resources' => ['find_support', 'relaxation', 'mood_tracker', 'articles']
                ];
                
            case 'greeting':
                return [
                    'message' => "Hello! 😊 Welcome to your Mental Wellness Assistant!\n\n🌟 I'm here to help you navigate our comprehensive wellness platform. I can guide you to:\n\n🏥 **Professional Support** - Find counselors and therapists\n📊 **Mood Tracking** - Monitor your daily emotional wellness\n🧘‍♀️ **Relaxation Tools** - Meditation and breathing exercises\n📚 **Educational Resources** - Articles, stories, and podcasts\n🧘‍♂️ **Wellness Activities** - Yoga, digital detox, dream analysis\n\n💬 **How are you feeling today? What can I help you find?**\n\nYou can say things like \"find support\", \"relaxation\", \"mood tracker\", or just tell me how you're feeling!",
                    'action' => 'greeting',
                    'show_all_pages' => true
                ];
                
            default:
                return [
                    'message' => "I understand you're reaching out, and I'm here to help navigate your mental wellness journey.\n\n🌟 **Popular Options:**\n• **\"Find support\"** - Connect with professional counselors\n• **\"Relaxation\"** - Access meditation and breathing tools\n• **\"Mood tracker\"** - Monitor your daily emotional state\n• **\"Articles\"** - Read helpful mental health content\n• **\"Stories\"** - Read inspiring recovery experiences\n\n💬 You can also tell me how you're feeling, and I'll suggest the best resources for you.\n\n🤝 What would be most helpful for you today?",
                    'action' => 'general',
                    'resources' => ['find_support', 'relaxation', 'mood_tracker', 'articles']
                ];
        }
    }
    
    // Get page info by key
    public function getPageInfo($pageKey) {
        return isset($this->websitePages[$pageKey]) ? $this->websitePages[$pageKey] : null;
    }
    
    // Get all pages
    public function getAllPages() {
        return $this->websitePages;
    }
}

// Main processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['message']) && isset($input['session_id'])) {
        $chatbot = new MentalWellnessChatbot();
        $response = $chatbot->processMessage($input['message'], $input['session_id']);
        
        echo json_encode($response);
    } else {
        echo json_encode([
            'message' => 'Invalid request. Please provide message and session_id.',
            'error' => true
        ]);
    }
} else {
    // Handle GET requests (for testing)
    echo json_encode([
        'message' => 'Mental Wellness Chatbot API is running!',
        'status' => 'online',
        'pages_available' => [
            'Home', 'Mood Tracker', 'Find Support', 'Relaxation', 'About Us',
            'Blog & Stories', 'Audio/Video Therapy', 'Articles', 'Digital Detox',
            'Podcast', 'Yoga', 'Dream Analyzer'
        ],
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>