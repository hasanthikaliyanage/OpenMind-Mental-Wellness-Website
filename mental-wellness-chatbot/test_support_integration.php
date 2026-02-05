<?php
echo "<h2>🧘‍♀️ Testing Support Page Integration</h2>";

// Test messages that should trigger support page buttons
$testMessages = [
    "I need a counselor",
    "I want professional help", 
    "Find support",
    "I'm having thoughts of suicide",
    "මට උපදේශකයෙක් ඕනි"
];

foreach ($testMessages as $message) {
    echo "<div style='border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 8px;'>";
    echo "<strong>Test Message:</strong> \"$message\"<br>";
    
    $testData = [
        'message' => $message,
        'session_id' => 'test_support_' . time()
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost/mental-wellness-chatbot/chatbot.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if (isset($data['show_support_page']) && $data['show_support_page']) {
        echo "<strong style='color: green;'>✅ Support Page Button:</strong> " . ($data['support_page_text'] ?? 'Find Support') . "<br>";
    }
    
    if (isset($data['urgent']) && $data['urgent']) {
        echo "<strong style='color: red;'>🚨 URGENT:</strong> Crisis support needed<br>";
    }
    
    echo "<strong>Response:</strong> " . substr($data['message'] ?? 'No response', 0, 100) . "...<br>";
    echo "</div>";
}

echo "<h3>✅ Support Page Integration Test Complete!</h3>";
?>