install:
	docker compose up -d --build
	sleep 10
	docker exec laravel_app php artisan key:generate
	docker exec laravel_app php artisan migrate
	docker exec laravel_app php artisan db:seed
	@echo "Listo! Abre http://localhost:8080"

nose te olvide correr este comadno https://humble-invention-v6j6jqpr5xw3p97p-8080.app.github.dev/index.html

up:
	docker compose up -d

down:
	docker compose down

reset:
	docker compose down -v
	docker compose up -d --build
