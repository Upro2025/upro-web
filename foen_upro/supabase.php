<?php
require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

$SUPABASE_URL = 'https://pvojevkazwrkjdwqjgrw.supabase.co';
$SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU';

$client = new Client([
    'base_uri' => $SUPABASE_URL . '/rest/v1/',
    'headers' => [
        'apikey' => $SUPABASE_ANON_KEY,
        'Authorization' => 'Bearer ' . $SUPABASE_ANON_KEY,
        'Content-Type' => 'application/json',
    ]
]);

// ✅ ใช้เมธอด GET ตาม REST API
$response = $client->request('GET', 'shops', [
    'query' => ['select' => '*']
]);

$data = json_decode($response->getBody(), true);