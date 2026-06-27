# Entregable 1 · Registro y acceso

**Objetivo:** que un paciente pueda crear una cuenta, iniciar sesión, cerrar sesión y
recuperar su contraseña.

## Alcance

- Registro de pacientes (nombre, correo, celular opcional, contraseña).
- Inicio de sesión / cierre de sesión.
- Recuperación de contraseña por correo.
- Verificación de correo (opcional para el MVP, recomendado).
- Campo `role` en `users` con default `patient`.
- Redirección post-login a la pantalla de inicio (home del paciente).

## Pasos

1. Instalar **Laravel Breeze** (stack Blade): `php artisan breeze:install blade`.
2. Ejecutar migraciones base de auth (`php artisan migrate`).
3. Añadir migración para los campos extra de `users`: `role` (enum) y `phone`.
4. Adaptar las vistas de Breeze (register/login/forgot) al diseño de Mind & Health
   (mismos componentes `x-button`, `x-card`, colores).
5. Ajustar el formulario de registro: nombre, correo, celular (opcional), contraseña + confirmación.
6. Configurar SMTP en `.env` (Mailtrap en desarrollo) para los correos de recuperación/verificación.
7. Configurar la redirección de `HOME` hacia `/inicio` (home del paciente).
8. Proteger rutas privadas con middleware `auth`.

## Reglas y validaciones

- Correo único y válido.
- Contraseña mínimo 8 caracteres, confirmada.
- Celular: opcional, formato numérico (para recordatorios/WhatsApp en fases futuras).
- Mensajes de error claros y en español.

## Seguridad

- Contraseñas con hash (bcrypt/argon, por defecto en Laravel).
- Protección CSRF (Laravel la trae).
- Rate limiting en login (Breeze lo incluye) para evitar fuerza bruta.

## Criterios de aceptación

- [ ] Puedo registrarme con nombre, correo y contraseña y quedo logueado.
- [ ] Puedo cerrar sesión y volver a iniciar.
- [ ] Si pongo correo repetido o contraseña corta, veo errores claros.
- [ ] Puedo solicitar recuperación de contraseña y me llega el correo (Mailtrap en dev).
- [ ] Las rutas privadas redirigen a login si no hay sesión.
- [ ] Todo se ve bien en celular.

## Entregable funcional

Sistema de cuentas completo. Un usuario real puede registrarse e iniciar sesión, base
indispensable para agendar.
