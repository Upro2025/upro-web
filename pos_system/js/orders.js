async function createOrder(userId, shopId, items) {
  const total = items.reduce((sum, item) => sum + item.price * item.quantity, 0);

  const { data: order, error: orderError } = await supabase
    .from('orders')
    .insert([{ user_id: userId, shop_id: shopId, total_price: total }])
    .select()
    .single();

  if (orderError) {
    console.error('Create order failed:', orderError);
    return;
  }

  const orderItems = items.map(item => ({
    order_id: order.id,
    menu_id: item.id,
    quantity: item.quantity,
    price_each: item.price
  }));

  await supabase.from('order_items').insert(orderItems);
}
