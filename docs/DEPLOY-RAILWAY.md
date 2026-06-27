# Deploy a Railway — Mind & Health

Despliegue gratuito para pruebas en internet usando el `Dockerfile` de este repo.
Railway detecta el Dockerfile y construye/sirve solo. No se duerme.

## 1. Subir el código a un repo

Railway despliega desde GitHub (recomendado) o desde la CLI.

```bash
git init
git add .
git commit -m "Preparar deploy en Railway"
# crea el repo en GitHub y luego:
git remote add origin git@github.com:TU_USUARIO/health.git
git push -u origin main
```

## 2. Crear el proyecto en Railway

1. Entra a https://railway.app e inicia sesión con GitHub.
2. **New Project → Deploy from GitHub repo** → elige este repositorio.
3. Railway detecta el `Dockerfile` y arranca el primer build.

## 3. Variables de entorno (Settings → Variables)

Pega estas variables. **APP_KEY ya viene generada** para ti:

```
APP_NAME=Mind & Health
APP_ENV=production
APP_KEY=base64:I3Yjlej1ZedrdS2A5qeox0isxrSNBHs0TlexZB3LmAE=
APP_DEBUG=false
APP_URL=https://TU-APP.up.railway.app

APP_LOCALE=es
APP_FALLBACK_LOCALE=es

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=sqlite
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=log

# MercadoPago (opcional).
# Si NO defines MP_ACCESS_TOKEN, la pasarela corre en MODO SIMULADO,
# ideal para pruebas sin cobrar de verdad. Para usar el sandbox real,
# pon tus credenciales de prueba:
# MP_PUBLIC_KEY=TEST-xxxxxxxx
# MP_ACCESS_TOKEN=TEST-xxxxxxxx
```

> Ajusta `APP_URL` con el dominio real una vez Railway lo genere
> (Settings → Networking → Generate Domain).

## 4. Exponer el dominio

Settings → **Networking → Generate Domain**. Railway usa la variable `PORT`
automáticamente; el `Dockerfile` ya sirve en `0.0.0.0:$PORT`.

## 5. Persistencia de la base SQLite (importante)

El sistema de archivos del contenedor es **efímero**: en cada deploy se reinicia
la base y pierdes los datos. Para pruebas rápidas puede bastar, pero si quieres
que los datos sobrevivan:

- **Opción A (fácil):** añade un **Volume** en Railway montado en `/app/database`.
  Así `database/database.sqlite` persiste entre despliegues.
- **Opción B (más sólida):** crea un **PostgreSQL** gratis en Railway y cambia:
  ```
  DB_CONNECTION=pgsql
  DB_HOST=...        # los da Railway
  DB_PORT=5432
  DB_DATABASE=railway
  DB_USERNAME=...
  DB_PASSWORD=...
  ```
  (Postgres ya viene soportado por Laravel; no hay que tocar el Dockerfile.)

## Notas

- Las migraciones corren solas en cada arranque (`docker/start.sh`).
- El mismo `Dockerfile` sirve para **Fly.io** (`fly launch`) o **Render**
  (Web Service → Docker) si algún día quieres migrar.
