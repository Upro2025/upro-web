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
<body class="bg-white text-gray-800">

<header class="sticky top-0 z-50 backdrop-blur bg-white/40 border-b border-gray-200 shadow-sm px-4 py-4">
  <div class="max-w-screen-xl mx-auto flex items-center justify-between">
    <!-- โลโก้ซ้าย -->
    <a href="index.php" class="flex items-center gap-2 select-none text-2xl font-bold">
      <img src="assets/logo.png" alt="Upro Logo" class="w-10 h-10 object-contain" />
      <span class="text-black">U<span class="text-[#f37021]">pro</span></span>
    </a>

    <!-- ปุ่มเปิดเมนูมือถือ -->
    <button class="lg:hidden flex items-center px-2 text-gray-600 focus:outline-none" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>

    <!-- เมนูหลัก -->
    <nav class="hidden lg:flex gap-6 items-center text-sm font-medium">
      <a href="index.php" class="flex items-center gap-1 <?= $current == 'index.php' ? 'text-[#f37021]' : 'text-gray-500 hover:text-[#f37021]' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h16V10" />
        </svg>
        <span>Home</span>
      </a>

      <a href="nearby.php" class="flex items-center gap-1 <?= $current == 'nearby.php' ? 'text-[#f37021]' : 'text-gray-500 hover:text-[#f37021]' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.656 0 3-1.343 3-3S13.656 5 12 5 9 6.343 9 8s1.343 3 3 3zM12 14c-2.67 0-8 1.336-8 4v2h16v-2c0-2.664-5.33-4-8-4z"/>
        </svg>
        <span>Nearby</span>
      </a>

      <a href="recommend.php" class="flex items-center gap-1 <?= $current == 'recommend.php' ? 'text-[#f37021]' : 'text-gray-500 hover:text-[#f37021]' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-1.13a4 4 0 100-8 4 4 0 000 8z" />
        </svg>
        <span>For You</span>
      </a>

      <a href="about_contact_policy.php" class="flex items-center gap-1 <?= $current == 'about_contact_policy.php' ? 'text-[#f37021]' : 'text-gray-500 hover:text-[#f37021]' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.943a1 1 0 00.95.69h4.15c.969 0 1.371 1.24.588 1.81l-3.36 2.444a1 1 0 00-.364 1.118l1.286 3.943c.3.921-.755 1.688-1.54 1.118L12 15.347l-3.36 2.444c-.784.57-1.838-.197-1.54-1.118l1.287-3.943a1 1 0 00-.364-1.118L4.663 9.37c-.783-.57-.38-1.81.588-1.81h4.15a1 1 0 00.95-.69l1.286-3.943z" />
        </svg>
        <span>About Us</span>
      </a>
    </nav>

    <!-- Sign In -->
    <div class="hidden lg:block">
      <button class="border border-gray-300 text-gray-600 px-4 py-1 rounded-md text-sm hover:border-[#f37021] hover:text-[#f37021] transition">
        Sign In
      </button>
    </div>
  </div>

  <!-- เมนูมือถือ -->
  <div id="mobileMenu" class="lg:hidden hidden mt-4">
    <nav class="flex flex-col space-y-2 px-4 text-sm font-medium">
      <a href="index.php" class="<?= $current == 'index.php' ? 'text-[#f37021]' : 'text-gray-600 hover:text-[#f37021]' ?>">Home</a>
      <a href="nearby.php" class="<?= $current == 'nearby.php' ? 'text-[#f37021]' : 'text-gray-600 hover:text-[#f37021]' ?>">Nearby</a>
      <a href="recommend.php" class="<?= $current == 'recommend.php' ? 'text-[#f37021]' : 'text-gray-600 hover:text-[#f37021]' ?>">For You</a>
      <a href="reviews.php" class="<?= $current == 'reviews.php' ? 'text-[#f37021]' : 'text-gray-600 hover:text-[#f37021]' ?>">Reviews</a>
      <button class="border mt-2 border-gray-300 text-gray-600 px-4 py-1 rounded-md text-sm hover:border-[#f37021] hover:text-[#f37021] transition">
        Sign In
      </button>
    </nav>
  </div>
</header>

<!-- Your content goes here -->

<script>
  // Toggle mobile menu
  document.querySelector('.lg:hidden').addEventListener('click', function() {
    document.getElementById('mobileMenu').classList.toggle('hidden');
  });
</script>
</body>
</html>
