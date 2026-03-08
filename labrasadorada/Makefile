install:
	docker compose up -d --build
	sleep 10
	docker exec laravel_app php artisan key:generate
	docker exec laravel_app php artisan migrate
	docker exec laravel_app php artisan db:seed
	@echo "Listo! Abre http://localhost:8080"


ingresa al contenedor con el siguiente comando:

cd /workspaces/laBrasaDorada/labrasadorada
docker compose up -d --build

ingresa al a los puertos 8080 con el siguiente comando:
https://humble-invention-v6j6jqpr5xw3p97p-8080.app.github.dev/index.html

nose te olvide correr este comadno dentro del dashboard y mirar como admin o como cliente para ver las diferencias:

ADMIN
admin@brasadorada.com ---- password: admin123
willi@gmail.com ---- password: willi123

CLIENTE
marceling@gmail.com ---- password: 123456789

up:
	docker compose up -d

down:
	docker compose down

reset:
	docker compose down -v
	docker compose up -d --build
