include .env

up:
	@docker-compose up -d
	@php artisan db:seed
	@php artisan serve

down:
	@docker-compose down
