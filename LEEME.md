# Guía de Instalación y Ejecución - Proyecto Voluntariado

Este proyecto ha sido adaptado para ejecutarse de forma sencilla y rápida en cualquier computadora utilizando **Docker**, evitando tener que configurar manualmente PHP, Composer, Node o bases de datos de forma local.

---

## 🛠️ Requisitos Previos

Asegúrate de tener instalado:
1. **Docker Desktop** (con soporte para Docker Compose).

---

## 🚀 Pasos para Iniciar el Proyecto

1. **Descomprimir el archivo ZIP** en cualquier carpeta de tu computadora.
2. **Abrir una terminal** (consola, PowerShell o Git Bash) en la carpeta del proyecto.
3. **Ejecutar el siguiente comando** para construir e iniciar los contenedores:
   ```bash
   docker compose up --build -d
   ```
4. **Esperar a que finalice**. En la primera ejecución, Docker descargará las imágenes de PHP y MySQL, instalará las dependencias y compilará la interfaz del proyecto de manera automática.

---

## 🖥️ Enlaces del Proyecto

Una vez que termine la ejecución en la consola, puedes abrir en tu navegador:
* **Aplicación Web**: [http://localhost:8000](http://localhost:8000)
* **Gestor de Base de Datos (phpMyAdmin)**: [http://localhost:8080](http://localhost:8080)

---

## 🔑 Credenciales de Acceso (Admin)

Para ingresar al panel de administración:
* **Email / Usuario**: `admin@unifranz.edu.bo`
* **Contraseña**: `admin123`

---

## ⚠️ Solución de Problemas Comunes

### Error "419 | Page Expired" al iniciar sesión
Este error ocurre si envías el formulario con una sesión vieja de tu navegador. Simplemente **refresca la página (`F5` o `Ctrl + F5`)** en la pantalla de inicio de sesión e intenta de nuevo.

### Detener los servicios
Para apagar los contenedores y liberar recursos de tu computadora sin perder la base de datos:
```bash
docker compose down
```

### Reiniciar la Base de Datos desde cero
Si en algún momento deseas limpiar toda la base de datos y volver a crearla en limpio con los datos semilla:
```bash
docker compose down -v
docker compose up -d
```
