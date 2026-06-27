# Entregable 0 · Cimientos

**Objetivo:** dejar el proyecto Laravel corriendo con un layout responsive mobile-first
que reproduzca el estilo del mockup (paleta azul/lavanda, tipografía limpia, look de app).

## Alcance

- Proyecto Laravel 11 creado y conectado a la base de datos `health` (MySQL/MariaDB de XAMPP).
- Tailwind CSS + Alpine.js configurados.
- Layout base reutilizable: contenedor centrado tipo "pantalla de celular" en escritorio,
  y a pantalla completa en móvil.
- Barra de navegación inferior (Inicio, Citas, Mensajes, Perfil) — visible pero los
  enlaces aún pueden ser placeholders.
- Página de **bienvenida** (`/`) con logo "Mind & Health", subtítulo y botones
  **Comenzar** e **Iniciar sesión** (como la primera pantalla del mockup).
- Paleta y componentes base (botón primario, tarjeta, etc.).

## Pasos

1. `composer create-project laravel/laravel health` y configurar `.env`
   (`APP_NAME="Mind & Health"`, `APP_TIMEZONE=America/Bogota`, datos de BD).
2. Instalar y configurar Tailwind (vía Vite) y Alpine.js.
3. Crear `resources/views/layouts/app.blade.php` con:
   - `<meta name="viewport">` correcto.
   - Contenedor responsive (`max-w-md mx-auto` en desktop, full en móvil).
   - Slot para contenido + barra inferior de navegación como componente.
4. Definir tokens de diseño en `tailwind.config.js` (colores primario azul oscuro
   `#1e2a4a` aprox., acentos lavanda/menta, fondos suaves).
5. Crear componentes Blade base: `x-button`, `x-card`, `x-bottom-nav`.
6. Crear la página de bienvenida `welcome` con logo, eslogan y los dos botones.
7. Verificar en móvil real / DevTools modo responsive.

## Diseño / referencia visual

- **Colores:** azul profundo para botones/títulos, fondos lavanda y menta muy claros,
  blanco para tarjetas, bordes suaves redondeados (`rounded-2xl`), sombras leves.
- **Tipografía:** sans-serif legible (la de Tailwind por defecto, Inter/Figtree).
- **Botones:** primario azul lleno ("Comenzar"), secundario borde ("Iniciar sesión").

## Criterios de aceptación

- [ ] `php artisan serve` levanta la app sin errores.
- [ ] La página `/` muestra logo, eslogan y botones Comenzar/Iniciar sesión.
- [ ] El layout se ve como "app" centrada en escritorio y a pantalla completa en celular.
- [ ] La barra de navegación inferior se ve en móvil sin romper el diseño.
- [ ] Tailwind compila y los colores coinciden a grandes rasgos con el mockup.

## Entregable funcional

Una web que abre, se ve profesional y responsive, con la pantalla de bienvenida lista.
Aún no hace nada interactivo más allá de navegar.
