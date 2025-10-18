<?php
/**
 * Simple API Route Tester
 * 
 * Run this from command line: php test-api.php
 * Or access via browser: http://your-domain/test-api.php
 */

echo "=== SalesPulse API Route Test ===\n\n";

// Test API endpoint
$apiUrl = 'https://salespulse.estudios.ug/api';

// Test routes
$routes = [
    'GET /api/login (should return 405)' => $apiUrl . '/login',
    'POST /api/login (simulated)' => $apiUrl . '/login',
    'GET /api/sales (unauthorized)' => $apiUrl . '/sales',
];

foreach ($routes as $name => $url) {
    echo "Testing: $name\n";
    echo "URL: $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json',
        'X-App-Identifier: SalesPulse-Mobile-App-2025-Secret-Key',
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Status Code: $httpCode\n";
    echo "Response: " . substr($response, 0, 200) . "...\n";
    echo "---\n\n";
}

echo "=== Test Complete ===\n";
