#!/bin/sh
# Prepara la aplicacion la primera vez que arranca el contenedor.
set -e
cd /var/www/html

if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

if [ ! -f .env ]; then
    cp .env.example .env
fi

# "php artisan serve" no pasa las variables del contenedor al proceso hijo,
# asi que se copian a .env los valores definidos en docker-compose.yml.
for clave in APP_URL DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD MAIL_MAILER MAIL_HOST MAIL_PORT; do
    valor=$(printenv "$clave" || true)
    if [ -n "$valor" ]; then
        if grep -q "^$clave=" .env; then
            sed -i "s|^$clave=.*|$clave=$valor|" .env
        else
            echo "$clave=$valor" >> .env
        fi
    fi
done

if ! grep -q '^APP_KEY=base64' .env; then
    php artisan key:generate --force
fi

if [ "${KUROCUTE_SKIP_BOOTSTRAP:-0}" != "1" ]; then
    echo "Esperando a MySQL en ${DB_HOST:-mysql}..."
    until php -r 'try { new PDO("mysql:host=".getenv("DB_HOST").";port=".(getenv("DB_PORT") ?: 3306), getenv("DB_USERNAME"), getenv("DB_PASSWORD")); exit(0); } catch (Throwable $e) { exit(1); }'; do
        sleep 2
    done
    php artisan storage:link --force >/dev/null 2>&1 || true
    if [ "${KUROCUTE_SEED:-1}" = "1" ] && ! php artisan migrate:status >/dev/null 2>&1; then
        php artisan migrate --seed --force
    else
        php artisan migrate --force
    fi
fi

exec "$@"
