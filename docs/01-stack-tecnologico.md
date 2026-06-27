# 01 · Stack tecnológico

Elegido para que sea **rápido de construir, estable en producción y fácil de mantener
por un solo desarrollador** con base en PHP/MySQL (XAMPP).

## Resumen

| Capa | Tecnología | Por qué |
|------|-----------|---------|
| Lenguaje / framework | **PHP 8.5 con Laravel 13** | Trae auth, validación, migraciones, colas y correo listos. Madurez y comunidad enorme. *(El proyecto se creó con la versión más reciente; cualquier Laravel 11+ sirve.)* |
| Vistas (frontend) | **Blade + Tailwind CSS v4** | HTML del lado del servidor, una sola base de código. Tailwind v4 se configura por CSS (`@theme`), sin `tailwind.config.js`. Responsive rápido y consistente con el mockup. |
| Interactividad | **Alpine.js** (+ opcional **Livewire**) | JS ligero para menús, selector de fecha/hora y validaciones sin montar un SPA. |
| Base de datos | **MySQL / MariaDB** (la de XAMPP) | Ya la conoces y la tienes en XAMPP. |
| Autenticación | **Laravel Breeze** (Blade) | Registro, login, logout, recuperación de contraseña y verificación de correo, ya hechos. |
| Pagos | **Mercado Pago** (Checkout Pro) vía SDK PHP | Pasarela con buena documentación; soporta tarjetas y PSE en COP. |
| Correo | **Laravel Mail** + SMTP (Mailtrap en dev, p. ej. Brevo/Mailgun en prod) | Confirmaciones y recordatorios. |
| Tareas en segundo plano | **Laravel Queue** (driver `database`) + **Scheduler** | Enviar correos sin bloquear, expirar citas no pagadas, recordatorios. |
| Roles/permisos | **Gate/Policies nativos** (o `spatie/laravel-permission` si crece) | Paciente / especialista / admin. |
| PWA | Manifest + service worker simple | Instalable en el celular, ícono en pantalla. |
| Entorno local | **XAMPP** (Apache + MariaDB) o `php artisan serve` | Lo que ya usas. |

> **Por qué Blade y no React:** para este MVP toda la interacción cabe en páginas
> server-side con Tailwind + Alpine. Menos piezas, menos build, menos cosas que se
> rompen, y lo mantienes tú solo. Si más adelante necesitas app nativa, Laravel ya
> queda listo para exponer una API.

## Versiones objetivo

- PHP **8.2** o superior (Laravel 11 lo requiere).
- Composer 2.
- Node.js 18+ (solo para compilar Tailwind/Vite en desarrollo).
- MySQL 8 o MariaDB 10.4+ (la de XAMPP sirve).

## Paquetes principales (Composer)

```
laravel/framework: ^11
laravel/breeze: ^2      (dev, scaffolding de auth)
mercadopago/dx-php: ^3  (SDK oficial de Mercado Pago)
guzzlehttp/guzzle: ^7   (HTTP, ya viene con Laravel)
```

Opcionales según crezca:
```
spatie/laravel-permission  (roles y permisos)
laravel/horizon            (monitoreo de colas, si se usa Redis)
```

## Estructura del proyecto (Laravel estándar)

```
health/
├─ app/
│  ├─ Models/            Patient, Doctor, Specialty, Schedule, Appointment, Payment
│  ├─ Http/Controllers/  Auth, Catalog, Booking, Payment, Dashboard
│  ├─ Policies/          Quién puede ver/editar qué
│  └─ Services/          MercadoPagoService, AvailabilityService
├─ database/
│  ├─ migrations/        Esquema (ver 02-modelo-de-datos.md)
│  └─ seeders/           Especialidades y doctores de ejemplo
├─ resources/views/      Blade (layouts, componentes, páginas)
├─ routes/web.php        Rutas web
├─ docs/                 ESTA documentación
└─ public/               Punto de entrada + assets + manifest PWA
```

## Cómo levantar el entorno local (resumen)

```bash
# 1. Crear proyecto
composer create-project laravel/laravel health
cd health

# 2. Auth con Breeze (Blade)
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build

# 3. Base de datos: crear "health" en phpMyAdmin y configurar .env
#    DB_DATABASE=health  DB_USERNAME=root  DB_PASSWORD=

# 4. Migrar
php artisan migrate

# 5. Correr
php artisan serve     # http://127.0.0.1:8000
npm run dev           # compila assets en caliente (otra terminal)
```

> Configuración importante en `.env`: `APP_TIMEZONE=America/Bogota`,
> credenciales SMTP y las llaves de Mercado Pago (ver módulo 05).

### Estado real del entorno (ya configurado en el Entregable 0)

- PHP 8.5, Node y npm se instalaron con **Homebrew** (no se usa el PHP 7.4 de XAMPP, que
  es muy viejo para Laravel). Binarios en `/opt/homebrew/bin` → al abrir terminal usa
  `export PATH="/opt/homebrew/bin:$PATH"` o ejecuta `php`/`node` por ruta completa.
- **Base de datos en desarrollo: SQLite** (archivo `database/database.sqlite`), porque
  funciona sin levantar ningún servicio. Se cambiará a **MySQL/MariaDB** (XAMPP) en el
  Entregable 1 o, a más tardar, al desplegar.
- Correr en local:
  ```bash
  export PATH="/opt/homebrew/bin:$PATH"
  npm run build          # o: npm run dev (assets en caliente)
  php artisan serve       # http://127.0.0.1:8000
  ```

## Convenciones

- **Idioma de UI:** español. **Idioma del código:** inglés (modelos, variables).
- **Dinero:** se guarda en **enteros (centavos COP no aplican)** → se guarda el valor
  entero en pesos (ej. `120000`). Se formatea en la vista.
- **Fechas/horas:** se guardan en UTC, se muestran en `America/Bogota`.
- **Git:** un commit por paso entregable, con mensaje claro. Rama `main` siempre estable.
