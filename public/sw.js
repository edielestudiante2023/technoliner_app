/* Service Worker — Technoliner SAS PWA */
const CACHE = 'technoliner-v2';

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

// Fetch: network-first en todo (HTML y recursos), con fallback a caché
// solo cuando no hay conexión. Así los cambios de contenido (fotos,
// estilos) se ven siempre en la siguiente carga, sin depender de que
// alguien suba el numero de version de este archivo.
self.addEventListener('fetch', (event) => {
  const req = event.request;
  if (req.method !== 'GET') return;

  event.respondWith(
    fetch(req)
      .then((res) => {
        if (res && res.status === 200 && res.type === 'basic') {
          const copy = res.clone();
          caches.open(CACHE).then((c) => c.put(req, copy));
        }
        return res;
      })
      .catch(() => caches.match(req).then((r) => r || (req.mode === 'navigate' ? caches.match('./') : undefined)))
  );
});
