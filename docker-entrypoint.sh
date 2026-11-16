#!/bin/sh

echo "Esperando a que la base de datos esté lista..."
php -r "
for (\$i = 0; \$i < 30; \$i++) {
    try {
        \$pdo = new PDO('mysql:host=db;port=3306', 'root', 'secretpassword');
        exit(0);
    } catch (PDOException \$e) {
        echo 'Esperando por MySQL... ' . \$e->getMessage() . PHP_EOL;
        sleep(2);
    }
}
exit(1);
"

# Copiar configuración de entorno de Docker
cp .env.docker .env

# Instalar dependencias de Composer si es necesario
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "Instalando dependencias de Composer..."
    composer install --no-interaction --optimize-autoloader
fi

# Generar clave de aplicación si no existe
if ! grep -q "APP_KEY=base64:" .env || [ -z "$(grep "APP_KEY=" .env | cut -d= -f2)" ]; then
    echo "Generando clave de aplicación..."
    php artisan key:generate --force
fi

# Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force

# Compilar assets de frontend
echo "Instalando y verificando dependencias de Node.js..."
npm install
echo "Compilando assets con Vite..."
rm -f public/hot
npm run build

# Iniciar el servidor de Laravel
echo "Iniciando servidor de Laravel..."
exec php artisan serve --host=0.0.0.0 --port=8000
