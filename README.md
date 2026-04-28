## App dev

# Instalar dependencias
`docker compose exec app composer install`

# 1. Copia el .env
cp .env.example .env

# 2. Genera APP_KEY y JWT_SECRET
`docker compose run --rm app php artisan key:generate`
`docker compose run --rm app php artisan jwt:secret`

# 3. Levanta los servicios
`docker compose up -d --build`

# 4. Corre las migraciones
`docker compose exec app php artisan migrate`
