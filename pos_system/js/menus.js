import { supabase } from './supabaseClient.js';

async function loadMenus() {
  const { data, error } = await supabase
    .from('menus')
    .select('*')
    .eq('shop_id', 'your-shop-id');

  if (error) {
    console.error('Error loading menus:', error.message);
    return;
  }

  const menuContainer = document.getElementById('menu-list');
  menuContainer.innerHTML = data.map(item => `
    <div class="border p-4 rounded-md">
      <h3 class="font-bold">${item.name}</h3>
      <p>${item.description}</p>
      <p class="text-orange-500 font-semibold">${item.price}฿</p>
    </div>
  `).join('');
}

loadMenus();
