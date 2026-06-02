/* Service Worker — Technoliner SAS PWA */
const CACHE = 'technoliner-v1';

// Recursos base a precachear (rutas relativas al scope del SW).
const PRECACHE = [
  './',
  './assets/css/style.css',
  './assets/js/main.js',
  './assets/icons/icon-192.png',
  './assets/icons/icon-512.png',
  './manifest.webmanifest'
];

// Instalación: precachea los recursos base.
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE)
      .then((cache) => cache.addAll(PRECACHE))
      .then(() => self.skipWaiting())
  );
});

// Activación: limpia versiones de caché antiguas.
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(
        keys.filter((k) => k !== CACHE).map((k) => caches.delete(k))
      ))
      .then(() => self.clients.claim())
  );
});

// Fetch:
//  - Navegación (HTML): network-first con fallback a caché (offline).
//  - Otros recursos: cache-first con actualización en segundo plano.
self.addEventListener('fetch', (event) => {
  const req = event.request;
  if (req.method !== 'GET') return;

  if (req.mode === 'navigate') {
    event.respondWith(
      fetch(req)
        .then((res) => {
          const copy = res.clone();
          caches.open(CACHE).then((c) => c.put(req, copy));
          return res;
        })
        .catch(() => caches.match(req).then((r) => r || caches.match('./')))
    );
    return;
  }

  event.respondWith(
    caches.match(req).then((cached) => {
      const network = fetch(req)
        .then((res) => {
          if (res && res.status === 200 && res.type === 'basic') {
            const copy = res.clone();
            caches.open(CACHE).then((c) => c.put(req, copy));
          }
          return res;
        })
        .catch(() => cached);
      return cached || network;
    })
  );
});
