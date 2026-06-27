# Entregable 7 · Panel del especialista / administrador

**Objetivo:** que el equipo (admin) y los especialistas puedan gestionar la operación sin
tocar la base de datos a mano: crear doctores, definir disponibilidad, ver citas y cargar
el enlace de la videollamada.

## Alcance

### Panel del administrador
- CRUD de **especialidades** (crear/editar/activar/desactivar).
- CRUD de **especialistas** (datos del perfil, precio, especialidad, foto, tags, crear el
  acceso `user` con rol `doctor`).
- Listado de **citas** (filtrar por estado/doctor/fecha) y de **pagos**.
- Marcar citas como `completed` o `cancelled` manualmente; gestionar reembolsos manuales.

### Panel del especialista
- Ver **su agenda** y sus citas (próximas/pasadas).
- Definir/editar su **disponibilidad** (`schedules`).
- Pegar el **enlace de la videollamada** (`meeting_url`) en cada cita confirmada.
- Marcar una cita como `completed`.

## Pasos

1. Implementar autorización por **rol** (`patient` / `doctor` / `admin`) con middleware y
   **Policies** (un doctor solo ve sus citas; el admin ve todo).
2. Crear el grupo de rutas `/admin/*` (rol admin) y `/especialista/*` (rol doctor).
3. Vistas de panel (pueden ser sencillas, tablas + formularios, mismo Tailwind):
   - Admin: especialidades, especialistas, citas, pagos.
   - Especialista: agenda, disponibilidad, detalle de cita con campo `meeting_url`.
4. Reutilizar el `AvailabilityService` para que el especialista vea su agenda calculada.
5. Al guardar `meeting_url` en una cita confirmada, opcional: notificar al paciente por correo.
6. Seguridad: todas las acciones de gestión validan rol y propiedad del recurso.

## Reglas

- Solo `admin` crea/edita especialidades y especialistas.
- Un `doctor` solo ve y edita **su** disponibilidad y **sus** citas.
- Cambios de precio afectan **solo citas futuras**; las ya creadas conservan su `price_cop`.
- Cancelaciones/reembolsos desde el panel se registran (estado de cita y de pago).

## Criterios de aceptación

- [ ] El admin puede crear una especialidad y un especialista, y aparece en el catálogo público.
- [ ] El admin ve el listado de citas y pagos con filtros básicos.
- [ ] El especialista inicia sesión y ve solo sus citas y su agenda.
- [ ] El especialista define su disponibilidad y esos cupos se ofrecen al agendar.
- [ ] El especialista pega el enlace de la videollamada y el paciente lo ve en su cita.
- [ ] Un doctor no puede ver las citas de otro doctor (autorización probada).

## Entregable funcional

La operación se vuelve **autónoma**: ya no necesitas seeders ni tinker para administrar.
El negocio puede funcionar día a día desde la interfaz.
