dev:
	docker compose --env-file .env.dev -f compose.dev.yml up -d

dev-build:
	composer install --prefer-dist --no-interaction --optimize-autoloader
	docker compose --env-file .env.dev -f compose.dev.yml up -d --build

dev-down:
	docker compose --env-file .env.dev -f compose.dev.yml down

dev-logs:
	docker compose --env-file .env.dev -f compose.dev.yml logs -f

prod:
	docker compose --env-file .env.prod -f compose.prod.yml up -d

prod-build:
	docker compose --env-file .env.prod -f compose.prod.yml up -d --build

prod-down:
	docker compose --env-file .env.prod -f compose.prod.yml down

prod-logs:
	docker compose --env-file .env.prod -f compose.prod.yml logs -f

help:
	@echo "make dev         - start development"
	@echo "make dev-build   - rebuild and start development"
	@echo "make dev-down    - stop development"
	@echo "make dev-logs    - show development logs"
	@echo "make prod        - start production"
	@echo "make prod-build  - rebuild and start production"
	@echo "make prod-down   - stop production"
	@echo "make prod-logs   - show production logs"