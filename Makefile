
down:
	docker compose down

upd:
	docker compose pull
	docker compose up -d --build

up:
	docker compose up

login:
	docker compose exec ec-cube ash

build:
	docker compose build --no-cache