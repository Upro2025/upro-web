const CACHE_NAME = 'upro-cache-v1';
const urlsToCache = [
  '/',
  '/index.html',
  '/assets/icon.png',
  '/assets/icon-512.png',
  // ใส่ไฟล์ที่ต้องการให้แคชไว้ในบริการ
];

// เมื่อ Service Worker ติดตั้ง
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        return cache.addAll(urlsToCache);
      })
  );
});

// เมื่อ Service Worker เริ่มใช้งาน
self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((cachedResponse) => {
        // ถ้ามีการแคช ให้ใช้แคช
        if (cachedResponse) {
          return cachedResponse;
        }

        // ถ้าไม่มีการแคช ให้ไปดึงจาก network
        return fetch(event.request);
      })
  );
});

// เมื่อ Service Worker อัปเดต
self.addEventListener('activate', (event) => {
  const cacheWhitelist = [CACHE_NAME];

  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (!cacheWhitelist.includes(cacheName)) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open('my-cache').then((cache) => {
      return cache.addAll([
        '/', // หน้าหลัก
        '/index.php', // หน้าอื่นๆที่ต้องการให้ทำงานออฟไลน์
        '/assets/logo.png', // ไฟล์ที่ต้องการให้แคช
        '/assets/banner1.jpg',
        '/assets/banner2.jpg',
        '/assets/banner3.png',
        '/assets/restaurant1.jpg',
        '/manifest.json' // เพิ่มไฟล์ manifest ที่ต้องการแคช
      ]);
    })
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      // ถ้าไฟล์อยู่ในแคชแล้วให้ดึงจากแคช
      return response || fetch(event.request); // ถ้าไม่มีก็ทำการดึงจากเซิร์ฟเวอร์
    })
  );
});

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open('v1').then((cache) => {
      return cache.addAll(['/', 'index.html']);
    })
  );
});
