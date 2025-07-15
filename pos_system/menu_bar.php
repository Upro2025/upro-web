<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Upro Food Finder</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>

</head>

<aside class="w-64 bg-white border-r border-gray-200 h-screen fixed top-0 left-0 z-40 shadow-sm">
  <div class="p-6">
    <div class="text-2xl font-bold text-[#f37021]">
      <span class="text-black">U</span>pro POS
    </div>
  </div>
  <nav class="flex flex-col gap-1 px-4">
    <a href="dashboard.php" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700 <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-gray-100 font-semibold text-[#f37021]' : '' ?>">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h16V10" />
      </svg>
      Dashboard
    </a>
    
    <a href="menu_add.php" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700 <?= basename($_SERVER['PHP_SELF']) == 'menu_add.php' ? 'bg-gray-100 font-semibold text-[#f37021]' : '' ?>">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      จัดการเมนู
    </a>

    <a href="orders.php" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700 <?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'bg-gray-100 font-semibold text-[#f37021]' : '' ?>">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a1 1 0 011-1h3a1 1 0 011 1v6m-4 0h4" />
      </svg>
      ออเดอร์
    </a>

    <a href="sales.php" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700 <?= basename($_SERVER['PHP_SELF']) == 'sales.php' ? 'bg-gray-100 font-semibold text-[#f37021]' : '' ?>">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
      </svg>
      ยอดขาย
    </a>

    <a href="reviews.php" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700 <?= basename($_SERVER['PHP_SELF']) == 'reviews.php' ? 'bg-gray-100 font-semibold text-[#f37021]' : '' ?>">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.943a1 1 0 00.95.69h4.15c.969 0 1.371 1.24.588 1.81L17.6 11.81a1 1 0 00-.364 1.118l1.286 3.943c.3.921-.755 1.688-1.54 1.118L12 15.347l-3.36 2.444c-.784.57-1.838-.197-1.54-1.118l1.287-3.943a1 1 0 00-.364-1.118L4.663 9.37c-.783-.57-.38-1.81.588-1.81h4.15a1 1 0 00.95-.69l1.286-3.943z" />
      </svg>
      รีวิวลูกค้า
    </a>

    <a href="logout.php" class="flex items-center gap-3 px-4 py-2 mt-6 rounded-lg hover:bg-gray-100 text-red-500">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5m-5 14h1a2 2 0 002-2v-4" />
      </svg>
      ออกจากระบบ
    </a>
  </nav>
</aside>
