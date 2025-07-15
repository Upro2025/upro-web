<?php
require __DIR__ . '/vendor/autoload.php';

use Supabase\SupabaseClient;

echo "autoload worked\n";

$client = new SupabaseClient('https://dummy.supabase.co', 'dummykey');
echo "class loaded!";
