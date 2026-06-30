// Service worker mínimo de Mind & Health.
// Cachea solo el "app shell" estático. NO cachea respuestas con datos sensibles
// (citas, pagos, sesiones) para no exponer información de salud.

const CACHE = 'mh-shell-v1';
const SHELL = ['/icon.svg', '/manifest.webmanifest'];

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(CACHE).then((c) => c.addAll(SHELL)));
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE).map((k) => caches.delete(k)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const { request } = event;

  // Solo GET y mismo origen.
  if (request.method !== 'GET' || new URL(request.url).origin !== self.location.origin) {
    return;
  }

  // Nunca cachear navegaciones (páginas con datos del usuario): siempre a la red.
  if (request.mode === 'navigate') {
    return;
  }

  // Assets estáticos: cache-first con respaldo a la red.
  if (/\/(build|icon\.svg|manifest\.webmanifest)/.test(request.url)) {
    event.respondWith(
      caches.match(request).then((cached) => cached || fetch(request))
    );
  }
});
