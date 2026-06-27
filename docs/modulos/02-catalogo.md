# Entregable 2 · Catálogo (especialidades y especialistas)

**Objetivo:** que el paciente vea la pantalla de inicio con especialidades, entre a una
especialidad, vea la lista de especialistas y abra el perfil de cada uno (como en el mockup).

## Alcance

- Pantalla de **inicio del paciente** (`/inicio`): saludo "¡Hola! 👋", tarjetas de
  especialidades (Psiquiatría, Psicología), bloque "Próxima cita" (placeholder por ahora)
  y banner "Consultas on-line".
- Pantalla de **especialidad** (`/especialidades/{slug}`): descripción + lista de
  especialistas de esa especialidad (foto, nombre, título, rating, años de experiencia).
- Pantalla de **perfil del especialista** (`/especialistas/{id}`): foto, nombre, título,
  rating y reseñas, etiquetas (Adultos/Adolescentes), bio, precio y botón **Agendar cita**
  (el botón aún puede llevar a un placeholder hasta el entregable 4).
- Datos cargados por **seeders** (no hace falta panel admin todavía).

## Modelo de datos involucrado

- `specialties` (ver [02-modelo-de-datos](../02-modelo-de-datos.md))
- `doctors`

## Pasos

1. Crear migraciones `specialties` y `doctors`.
2. Crear modelos `Specialty` y `Doctor` con su relación (`Specialty hasMany Doctor`).
3. Crear seeders:
   - 2 especialidades (Psiquiatría, Psicología).
   - 3–4 doctores de ejemplo con foto (puede ser URL de placeholder), rating, experiencia,
     precio (`price_cop`) y tags.
4. Crear `CatalogController` con métodos: `home`, `specialty`, `doctor`.
5. Crear vistas Blade:
   - `inicio.blade.php` (home con tarjetas de especialidades).
   - `especialidades/show.blade.php` (lista de especialistas).
   - `especialistas/show.blade.php` (perfil + precio + botón Agendar).
6. Componente `x-doctor-card` reutilizable para la lista.
7. Formatear precio en COP (`$120.000 COP`) con un helper o accessor en el modelo.

## Reglas

- Solo se listan especialidades y doctores con `is_active = true`.
- `rating` y `reviews_count` se muestran tal cual están en BD (carga manual en MVP).
- El precio mostrado es `doctors.price_cop`.

## Criterios de aceptación

- [ ] La pantalla de inicio muestra las tarjetas de Psiquiatría y Psicología.
- [ ] Al tocar una especialidad veo su descripción y la lista de especialistas.
- [ ] Cada especialista muestra foto, nombre, título, rating y años de experiencia.
- [ ] El perfil del especialista muestra precio en COP y botón "Agendar cita".
- [ ] Todo responsive; en celular se ve igual al mockup.

## Entregable funcional

El paciente puede **explorar** toda la oferta de especialistas y ver precios. Es la
vitrina del producto; aún no se agenda.
