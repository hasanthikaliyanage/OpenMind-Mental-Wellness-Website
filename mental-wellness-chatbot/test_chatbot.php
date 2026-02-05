<?php
echo "<h2>🧘‍♀️ Testing Chatbot API</h2>";
echo "<hr>";

// Test different messages
$testMessages = [
    "Hello" => "greeting",
    "I'm feeling anxious" => "anxiety", 
    "I need help" => "general_support",
    "I want to talk to a counselor" => "counselor",
    "මට බයක් දැනෙනවා" => "sinhala_anxiety"
];

echo "<h3>📝 Testing Chatbot Responses:</h3>";

foreach ($testMessages as $message => $expected) {
    echo "<div style='border: 1px solid #ddd; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
    echo "<strong>Test Message:</strong> " . $message . "<br>";
    
    // Simulate API call
    $testData = [
        'message' => $message,
        'session_id' => 'test_session_' . time()
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost/mental-wellness-chatbot/chatbot.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        echo "<strong style='color: green;'>✅ Response:</strong> " . substr($data['message'], 0, 100) . "...<br>";
        echo "<strong>Action:</strong> " . ($data['action'] ?? 'none') . "<br>";
        if (isset($data['urgent']) && $data['urgent']) {
            echo "<strong style='color: red;'>🚨 URGENT:</strong> Crisis detected<br>";
        }
    } else {
        echo "<strong style='color: red;'>❌ Error:</strong> HTTP " . $httpCode . "<br>";
        echo "<strong>Response:</strong> " . $response . "<br>";
    }
    
    echo "</div>";
}

echo "<h3>✅ Chatbot API Test Complete!</h3>";
?>