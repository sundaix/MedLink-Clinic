<?php
class FirebasePHP {
    private $databaseUrl;
    private $apiKey;
    
    public function __construct() {
        $this->databaseUrl = "https://medlink-clinic-c9246-default-rtdb.asia-southeast1.firebasedatabase.app";
        $this->apiKey = "AIzaSyAJCit6L0ZXO0PpZ8UUZIulEB5ab4b42og";
    }
    
    public function create($path, $data) {
        $url = $this->databaseUrl . $path . '.json?auth=' . $this->apiKey;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            error_log("Firebase cURL Error: " . $curlError);
        }
        
        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'data' => json_decode($response, true),
            'error' => $curlError
        ];
    }
    
    // READ - Get records
    public function read($path) {
        $url = $this->databaseUrl . $path . '.json?auth=' . $this->apiKey;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            error_log("Firebase cURL Error: " . $curlError);
        }
        
        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'data' => json_decode($response, true),
            'error' => $curlError
        ];
    }
    
    // UPDATE - Update specific fields
    public function update($path, $data) {
        $url = $this->databaseUrl . $path . '.json?auth=' . $this->apiKey;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Important for free hosting
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            error_log("Firebase cURL Error: " . $curlError);
        }
        
        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'data' => json_decode($response, true),
            'error' => $curlError
        ];
    }
    
    // DELETE - Remove record
    public function delete($path) {
        $url = $this->databaseUrl . $path . '.json?auth=' . $this->apiKey;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Important for free hosting
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            error_log("Firebase cURL Error: " . $curlError);
        }
        
        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'data' => $response,
            'error' => $curlError
        ];
    }
    
    // SET - Completely replace data at path
    public function set($path, $data) {
        $url = $this->databaseUrl . $path . '.json?auth=' . $this->apiKey;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Important for free hosting
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            error_log("Firebase cURL Error: " . $curlError);
        }
        
        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'data' => json_decode($response, true),
            'error' => $curlError
        ];
    }
    
    // BATCH READ - Read multiple paths
    public function batchRead($paths) {
        $results = [];
        foreach ($paths as $path) {
            $results[$path] = $this->read($path);
        }
        return $results;
    }
    
    // TEST CONNECTION - Check if Firebase is accessible
    public function testConnection() {
        $url = $this->databaseUrl . '/.json?auth=' . $this->apiKey . '&shallow=true';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'http_code' => $httpCode,
            'error' => $curlError,
            'response' => $response
        ];
    }
}

// Initialize Firebase
$firebase = new FirebasePHP();

// Helper function to send JSON responses
function sendJsonResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Helper function to log errors
function logFirebaseError($message, $data = null) {
    error_log("Firebase Error: " . $message);
    if ($data) {
        error_log("Data: " . print_r($data, true));
    }
}

// Test endpoint (optional - remove in production)
if (isset($_GET['test'])) {
    $testResult = $firebase->testConnection();
    sendJsonResponse([
        'firebase_connection' => $testResult,
        'server_info' => [
            'php_version' => PHP_VERSION,
            'curl_support' => function_exists('curl_init'),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
        ]
    ]);
}
?>