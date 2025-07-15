<?php include 'components/header.php'; ?>

<header>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

</header>

<main class="max-w-4xl mx-auto px-4 py-10">
  <!-- Title Section -->
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">Restaurant Reviews</h1>
      <p class="text-gray-500">Share your dining experiences</p>
    </div>
    <button class="flex items-center gap-2 bg-[#f37021] text-white px-4 py-2 rounded-md hover:bg-orange-500 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      Write Review
    </button>
  </div>

  <!-- Review Card -->
  <div class="space-y-6">
    <!-- Review Item -->
    <div class="bg-white rounded-xl border px-6 py-4 flex justify-between items-start">
      <div>
        <h2 class="text-lg font-semibold text-gray-900">Bangkok Garden</h2>
        <p class="text-sm text-gray-500 mb-2">by John Doe</p>
        <p class="text-gray-800">Absolutely amazing Thai food! The Pad Thai was authentic and delicious.</p>
      </div>
      <div class="text-right">
        <div class="flex justify-end text-yellow-400 mb-1">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <p class="text-sm text-gray-400">2024-01-15</p>
      </div>
    </div>

    <div class="bg-white rounded-xl border px-6 py-4 flex justify-between items-start">
      <div>
        <h2 class="text-lg font-semibold text-gray-900">Coffee Central</h2>
        <p class="text-sm text-gray-500 mb-2">by Sarah Smith</p>
        <p class="text-gray-800">Great coffee and friendly staff. Perfect place for morning meetings.</p>
      </div>
      <div class="text-right">
        <div class="flex justify-end text-yellow-400 mb-1">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="far fa-star text-gray-300"></i>
        </div>
        <p class="text-sm text-gray-400">2024-01-12</p>
      </div>
    </div>

    <div class="bg-white rounded-xl border px-6 py-4 flex justify-between items-start">
      <div>
        <h2 class="text-lg font-semibold text-gray-900">Pizza Paradise</h2>
        <p class="text-sm text-gray-500 mb-2">by Mike Johnson</p>
        <p class="text-gray-800">Good pizza with fresh ingredients. A bit pricey but worth it.</p>
      </div>
      <div class="text-right">
        <div class="flex justify-end text-yellow-400 mb-1">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="far fa-star text-gray-300"></i>
        </div>
        <p class="text-sm text-gray-400">2024-01-10</p>
      </div>
    </div>
  </div>
</main>
<?php include 'components/footer.php'; ?>
