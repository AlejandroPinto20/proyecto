# Arquitectura de tres capas

## Sistema de Voluntariado UNIFRANZ

El proyecto emplea una arquitectura de tres capas. La separación mantiene la interfaz, las reglas de negocio y el almacenamiento de datos con responsabilidades independientes.

```text
Usuario
  │
  ▼
Capa de presentación
Blade, Tailwind CSS, JavaScript, Vite
resources/views · resources/js · resources/css · public
  │  HTTP / JSON
  ▼
Capa de lógica de negocio
Laravel 12, controladores, servicios, middleware, políticas, modelos Eloquent
app/Http · app/Services · app/Models · app/Policies · routes
  │  Eloquent / SQL
  ▼
Capa de datos
MySQL 8
database/migrations · database/seeders · tablas de usuarios, actividades,
participaciones, habilidades, rankings, certificados, donaciones y notificaciones
```

## Capa de presentación

Ofrece las vistas Blade para estudiantes y administradores. Incluye autenticación, perfil, actividades y calendario, participaciones, certificados, rankings, donaciones, notificaciones y asistente de ayuda. Vite compila los recursos CSS y JavaScript.

## Capa de lógica de negocio

Laravel recibe las solicitudes, valida datos y aplica autorización por rol. Los controladores gestionan usuarios, actividades, participaciones, certificados, donaciones, rankings y notificaciones. Los servicios implementan recomendaciones con cadenas de Markov y el asistente de voluntariado con Ollama.

## Capa de datos

MySQL almacena la información persistente. Laravel usa migraciones para definir el esquema, seeders para datos iniciales y Eloquent para consultar y actualizar los registros.

## Chatbot con Ollama

El asistente se ejecuta en Ollama local. La ruta autenticada `POST /api/assistant/ask` envía la pregunta al modelo configurado. El mensaje de sistema restringe las respuestas a la aplicación de voluntariado y proporciona contexto de las funciones y actividades actuales.

Configuración requerida:

```bash
ollama pull gemma3:1b
ollama serve
```

Copiar las variables de `.env.ollama.example` al archivo `.env`. El proyecto usa `gemma3:1b` por defecto y se puede cambiar `OLLAMA_MODEL` por otro modelo instalado en Ollama.

## Normas de referencia

- ISO 31000:2018 para el proceso de gestión de riesgos.
- ISO/IEC 27001:2022 e ISO/IEC 27005:2022 para seguridad y riesgos de información.
- ISO/IEC 25010:2023 para calidad de producto de software.
- ISO/IEC/IEEE 12207:2026 para ciclo de vida del software.
