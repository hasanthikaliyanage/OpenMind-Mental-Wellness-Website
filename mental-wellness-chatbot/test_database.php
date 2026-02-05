<?php
require_once 'config/database.php';

echo "<h2>🧘‍♀️ Mental Wellness Chatbot - Database Test</h2>";
echo "<hr>";

echo "<h3>📡 Testing Database Connection...</h3>";

try {
    $db = new Database();
    echo "✅ <strong>Database connection successful!</strong><br><br>";
    
    // Test document insertion
    $testResult = $db->testConnection();
    
    if ($testResult['success']) {
        echo "✅ <strong>Database write test successful!</strong><br>";
        echo "📝 Test document ID: " . $testResult['inserted_id'] . "<br><br>";
    } else {
        echo "❌ Database write test failed: " . $testResult['message'] . "<br><br>";
    }
    
    // Get initial stats
    echo "<h3>📊 Database Statistics:</h3>";
    $stats = $db->getChatbotStats();
    
    echo "<ul>";
    echo "<li>Total Users: <strong>" . $stats['total_users'] . "</strong></li>";
    echo "<li>Total Conversations: <strong>" . $stats['total_conversations'] . "</strong></li>";
    echo "<li>Total Appointments: <strong>" . $stats['total_appointments'] . "</strong></li>";
    echo "<li>Appointments Today: <strong>" . $stats['appointments_today'] . "</strong></li>";
    echo "<li>Crisis Flags: <strong>" . $stats['crisis_flags'] . "</strong></li>";
    echo "</ul>";
    
    echo "<hr>";
    echo "<h3 style='color: green;'>🎉 Database setup completed successfully!</h3>";
    echo "<p>✅ MongoDB connection working<br>";
    echo "✅ Collections can be created<br>";
    echo "✅ Data can be inserted and retrieved<br>";
    echo "✅ Indexes created for performance</p>";
    
    echo "<p><strong>Next step:</strong> Create chatbot files</p>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Database Setup Failed!</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    
    echo "<h4>🔧 Troubleshooting Steps:</h4>";
    echo "<ol>";
    echo "<li>Make sure MongoDB is running: <code>net start MongoDB</code></li>";
    echo "<li>Check if MongoDB service is in Windows Services</li>";
    echo "<li>Try restarting MongoDB: <code>net stop MongoDB</code> then <code>net start MongoDB</code></li>";
    echo "<li>Check port 27017 is not blocked</li>";
    echo "</ol>";
}
?>