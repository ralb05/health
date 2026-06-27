# 00 · Visión general

## El producto

**Mind & Health** — "Tu bienestar mental, nuestra prioridad". Una aplicación web
responsive que conecta pacientes con especialistas en salud mental (psiquiatría y
psicología) para agendar y pagar consultas en línea.

## Para quién

- **Paciente:** persona que busca atención en salud mental. Se registra, explora
  especialistas, agenda, paga y asiste a su consulta.
- **Especialista (doctor):** psiquiatra o psicólogo. Define su disponibilidad,
  ve sus citas y comparte el enlace de la videollamada.
- **Administrador:** gestiona especialistas, especialidades, precios y supervisa citas/pagos.

## Recorrido principal del paciente (happy path)

1. Entra a la web (en el celular se ve como una app).
2. Toca **Comenzar** → se registra (nombre, correo, contraseña).
3. En el inicio ve especialidades: **Psiquiatría** y **Psicología**.
4. Elige una especialidad → ve la lista de especialistas con foto, calificación y experiencia.
5. Abre el perfil de un especialista → ve detalle y precio (ej. *$120.000 COP*).
6. Elige **fecha** y **hora** entre los cupos disponibles.
7. Confirma los datos de la cita (tipo: *On-line*, precio, etc.).
8. **Paga** con Mercado Pago.
9. La cita queda **Confirmada**. Recibe correo de confirmación.
10. Antes de la cita, recibe el **enlace de la videollamada** que comparte el especialista.

> Estas pantallas corresponden 1:1 con las del mockup de referencia (inicio, listado de
> especialistas, detalle de la cita con "Confirmar cita" y "Pago 100% seguro y confidencial").

## Alcance del MVP (lo que SÍ entra)

- Registro e inicio de sesión de pacientes.
- Catálogo de especialidades y especialistas con perfil.
- Disponibilidad por especialista y cálculo de cupos libres.
- Agendamiento de cita con selección de fecha/hora.
- Pago en línea con **Mercado Pago** (COP).
- Estados de cita: `pendiente_pago` → `confirmada` → `completada` / `cancelada`.
- "Mis citas" para el paciente.
- Notificaciones por **correo** (confirmación, recordatorio simple).
- La consulta se hace por **enlace externo** (Meet/Zoom/WhatsApp) que el especialista pega en la cita.
- Panel básico para especialista y administrador.
- Diseño **responsive / mobile-first**, instalable como PWA.

## Fuera de alcance (fases posteriores)

Para mantener el MVP simple y estable, **NO** se incluye por ahora:

- Videollamada integrada dentro de la app (se usa enlace externo).
- Chat / mensajería en tiempo real (la pestaña "Mensajes" del mockup queda para fase 2).
- App móvil nativa (iOS/Android en tiendas).
- Historia clínica / notas clínicas / recetas digitales.
- Reprogramación automática y reembolsos automáticos (se hacen manual desde el panel).
- Facturación electrónica (DIAN), integración con EPS o seguros.
- Multi-idioma (solo español).
- Reseñas/calificaciones creadas por pacientes (las calificaciones se cargan manual al inicio).

## Criterio de "MVP estable y listo para lanzar"

- El paciente completa el recorrido principal de punta a punta sin errores.
- Los pagos se registran de forma confiable (con webhook de confirmación).
- Datos sensibles protegidos: HTTPS, contraseñas cifradas, validación de entradas.
- Funciona correctamente en celular (probado en Chrome Android y Safari iOS).
- Hay un panel donde el admin puede crear especialistas y ver citas/pagos.

## Reglas de negocio clave

- Una cita ocupa **un único cupo** de fecha/hora de un especialista; dos pacientes no
  pueden tomar el mismo cupo (control de concurrencia).
- Una cita sin pago confirmado en **15 minutos** vuelve a liberar el cupo (expira).
- El precio se toma del especialista/especialidad al momento de agendar y **se congela**
  en la cita (si luego cambia el precio, la cita ya creada no cambia).
- Moneda única: **COP**. Sin decimales en la UI (ej. `$120.000 COP`).
- Zona horaria: **America/Bogota**.
