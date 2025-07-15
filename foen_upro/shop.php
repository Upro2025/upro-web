<?php include 'components/header.php'; ?>
<main class="p-8 max-w-6xl mx-auto font-sans">

  <!-- Hero Header -->
  <div class="flex flex-col md:flex-row gap-8">
    <img src="images/restaurant-hero.jpg" class="rounded-xl w-full md:w-1/2 object-cover" alt="Restaurant Image">
    
    <div class="flex-1">
      <h1 class="text-4xl font-bold text-gray-800 mb-2">Bangkok Garden</h1>
      <div class="flex items-center gap-2 text-yellow-500 text-sm mb-2">
        ★ ★ ★ ★ ☆ <span class="text-gray-500">(4.5)</span>
        <span class="bg-orange-500 text-white text-xs px-3 py-1 rounded-md ml-2">20% off lunch specials</span>
      </div>
      <p class="text-gray-600 leading-relaxed">
        Authentic Thai cuisine in a beautiful garden setting. Experience the rich flavors of Thailand with our traditional recipes passed down through generations.
      </p>
      <span class="inline-block bg-orange-100 text-orange-600 text-xs px-2 py-1 mt-3 rounded-full">Thai</span>
    </div>
  </div>

  <!-- Restaurant Info -->
  <div class="bg-white mt-10 rounded-lg shadow-md p-6 border border-gray-100">
    <h2 class="text-lg font-semibold mb-4">Restaurant Info</h2>
    <ul class="space-y-3 text-gray-600">
      <li class="flex items-center gap-2"><svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg> 123 Main Street, Downtown</li>
      <li class="flex items-center gap-2"><svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5h18M3 10h18M10 15h4"/></svg> +1 (555) 123-4567</li>
      <li class="flex items-center gap-2"><svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3"/></svg> 11:00 AM - 10:00 PM</li>
      <li class="flex items-center gap-2"><svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12c0 5.05 3.72 9.21 8.5 9.95v-7.06h-2.6V12h2.6v-2.05c0-2.58 1.54-4 3.9-4 1.12 0 2.3.2 2.3.2v2.52h-1.3c-1.28 0-1.67.79-1.67 1.6V12h2.8l-.45 2.89h-2.35v7.06C18.28 21.21 22 17.05 22 12c0-5.52-4.48-10-10-10z"/></svg> <a href="https://www.bangkokgarden.com" class="text-orange-600 hover:underline">www.bangkokgarden.com</a></li>
    </ul>
  </div>

  <!-- Menu Section -->
  <section class="mt-12">
    <h2 class="text-2xl font-bold mb-4">Menu</h2>

    <div class="flex gap-3 mb-6">
      <button class="bg-orange-500 text-white px-4 py-1 rounded-full text-sm">All</button>
      <button class="bg-gray-100 text-gray-700 px-4 py-1 rounded-full text-sm">Main Course</button>
      <button class="bg-gray-100 text-gray-700 px-4 py-1 rounded-full text-sm">Appetizer</button>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
      <!-- Menu Item -->
      <div class="border rounded-lg p-5 shadow-sm">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="text-lg font-bold">Pad Thai</h3>
            <p class="text-gray-600">Classic Thai stir-fried noodles with shrimp, tofu, and bean sprouts</p>
            <span class="text-green-600 text-sm mt-1 inline-block bg-green-100 px-2 py-0.5 rounded">Available</span>
          </div>
          <div class="text-right">
            <span class="text-orange-500 font-semibold text-lg">$12.99</span><br>
            <span class="text-xs text-orange-600 bg-orange-100 rounded px-2 py-0.5 inline-block mt-1">Main Course</span>
          </div>
        </div>
      </div>

      <div class="border rounded-lg p-5 shadow-sm">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="text-lg font-bold">Tom Yum Soup</h3>
            <p class="text-gray-600">Hot and sour soup with shrimp and lemongrass</p>
            <span class="text-red-600 text-sm mt-1 inline-block bg-red-100 px-2 py-0.5 rounded">Out of Stock</span>
          </div>
          <div class="text-right">
            <span class="text-orange-500 font-semibold text-lg">$8.99</span><br>
            <span class="text-xs text-orange-600 bg-orange-100 rounded px-2 py-0.5 inline-block mt-1">Appetizer</span>
          </div>
        </div>
      </div>

      <div class="border rounded-lg p-5 shadow-sm">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="text-lg font-bold">Green Curry</h3>
            <p class="text-gray-600">Spicy coconut curry with chicken and Thai basil</p>
            <span class="text-green-600 text-sm mt-1 inline-block bg-green-100 px-2 py-0.5 rounded">Available</span>
          </div>
          <div class="text-right">
            <span class="text-orange-500 font-semibold text-lg">$14.99</span><br>
            <span class="text-xs text-orange-600 bg-orange-100 rounded px-2 py-0.5 inline-block mt-1">Main Course</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php include 'components/footer.php'; ?>
