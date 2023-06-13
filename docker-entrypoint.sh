# docker-entrypoint.sh
# Install Laravel packages
composer install

if [ ! -e ./.env ]; then
  cp .env.example .env
fi

service cron start

php artisan serve --host=0.0.0.0
