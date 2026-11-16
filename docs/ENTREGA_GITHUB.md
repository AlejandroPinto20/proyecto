# Entrega en GitHub

## Separación del código fuente

La separación se realiza por responsabilidad, conforme a la estructura estándar de Laravel:

| Entregable | Directorios principales | Contenido |
| --- | --- | --- |
| Frontend | `resources/views`, `resources/js`, `resources/css`, `public` | Vistas Blade, estilos, JavaScript, imágenes y recursos compilados. |
| Backend | `app`, `routes`, `config`, `database`, `tests` | Controladores, servicios, modelos, middleware, rutas, configuración, migraciones, seeders y pruebas. |
| Datos | `database/migrations`, `database/seeders` | Esquema MySQL y datos iniciales. |
| Infraestructura | `Dockerfile`, `docker-compose.yml`, `docker-entrypoint.sh` | Ejecución con Docker, PHP, MySQL y phpMyAdmin. |

## Publicación

1. Crear un repositorio vacío en GitHub, sin README ni licencia inicial.
2. Desde la carpeta principal ejecutar:

```bash
git init
git add .
git commit -m "Entrega sistema de voluntariado UNIFRANZ"
git branch -M main
git remote add origin URL_DEL_REPOSITORIO
git push -u origin main
```

3. Verificar que el archivo `.env` no se publique. Compartir el enlace del repositorio con el docente.

## Ejecución del proyecto

```bash
docker compose up --build -d
```

La aplicación queda disponible en `http://localhost:8000` y phpMyAdmin en `http://localhost:8080`.
