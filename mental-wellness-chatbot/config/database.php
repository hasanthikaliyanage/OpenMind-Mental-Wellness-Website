<?php
// config/database.php
require_once dirname(__DIR__) . '/vendor/autoload.php';

class Database {
    private $client;
    private $database;
    private $connection_string;
    
    public function __construct() {
        // MongoDB connection configuration for localhost
        $this->connection_string = "mongodb://localhost:27017";
        $this->connect();
    }
    
    private function connect() {
        try {
            // Create MongoDB client
            $this->client = new MongoDB\Client($this->connection_string);
            
            // Select database
            $this->database = $this->client->mental_wellness_db;
            
            // Test connection by pinging
            $this->client->selectDatabase('admin')->command(['ping' => 1]);
            
            // Create indexes for better performance
            $this->createIndexes();
            
            echo "<!-- Database connected successfully -->\n";
            
        } catch (Exception $e) {
            die("❌ Database connection failed: " . $e->getMessage() . 
                "<br><br>🔧 Make sure MongoDB is running!<br>" .
                "Run this command: <code>net start MongoDB</code>");
        }
    }
    
    private function createIndexes() {
        try {
            // Create indexes for better query performance
            
            // Index for conversations collection
            $this->database->conversations->createIndex(['session_id' => 1]);
            $this->database->conversations->createIndex(['timestamp' => -1]);
            
            // Index for users collection  
            $this->database->users->createIndex(['session_id' => 1], ['unique' => true]);
            
            // Index for appointments collection
            $this->database->appointments->createIndex(['user_session' => 1]);
            $this->database->appointments->createIndex(['appointment_date' => 1]);
            $this->database->appointments->createIndex(['status' => 1]);
            
        } catch (Exception $e) {
            // Index creation is not critical, just log warning
            error_log("Index creation warning: " . $e->getMessage());
        }
    }
    
    public function getDatabase() {
        return $this->database;
    }
    
    public function getClient() {
        return $this->client;
    }
    
    // Test database connection
    public function testConnection() {
        try {
            $result = $this->database->test_collection->insertOne([
                'test' => 'Connection test',
                'timestamp' => new MongoDB\BSON\UTCDateTime(),
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            return [
                'success' => true,
                'message' => 'Database test successful!',
                'inserted_id' => (string)$result->getInsertedId()
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Database test failed: ' . $e->getMessage()
            ];
        }
    }
    
    // Get basic statistics
    public function getChatbotStats() {
        try {
            $stats = [
                'total_users' => $this->database->users->countDocuments(),
                'total_conversations' => $this->database->conversations->countDocuments(),
                'total_appointments' => $this->database->appointments->countDocuments(),
                'appointments_today' => $this->database->appointments->countDocuments([
                    'appointment_date' => date('Y-m-d')
                ]),
                'crisis_flags' => $this->database->users->countDocuments([
                    'crisis_flags' => ['$gt' => 0]
                ])
            ];
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error getting stats: " . $e->getMessage());
            return [
                'total_users' => 0,
                'total_conversations' => 0, 
                'total_appointments' => 0,
                'appointments_today' => 0,
                'crisis_flags' => 0
            ];
        }
    }
    
    // Create user session
    public function createUserSession($sessionId, $userData = []) {
        try {
            $userDoc = [
                'session_id' => $sessionId,
                'created_at' => new MongoDB\BSON\UTCDateTime(),
                'last_active' => new MongoDB\BSON\UTCDateTime(),
                'conversation_count' => 0,
                'crisis_flags' => 0,
                'user_data' => $userData
            ];
            
            $this->database->users->insertOne($userDoc);
            return true;
            
        } catch (MongoDB\Driver\Exception\BulkWriteException $e) {
            // User already exists, update last active
            $this->updateUserActivity($sessionId);
            return false;
        } catch (Exception $e) {
            error_log("Error creating user session: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateUserActivity($sessionId) {
        try {
            $this->database->users->updateOne(
                ['session_id' => $sessionId],
                [
                    '$set' => ['last_active' => new MongoDB\BSON\UTCDateTime()],
                    '$inc' => ['conversation_count' => 1]
                ],
                ['upsert' => true] // Create if doesn't exist
            );
        } catch (Exception $e) {
            error_log("Error updating user activity: " . $e->getMessage());
        }
    }
}
?>