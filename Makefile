include .env

up:
	@docker-compose up -d
	@php artisan db:wipe
	@php artisan db:seed
	@php artisan serve

down:
	@docker-compose down
