# Atajos para el entorno Docker de zBooks. `make help` lista los comandos.
DC = docker compose
EXEC = $(DC) exec app

.DEFAULT_GOAL := help
.PHONY: help setup assets up down restart build logs shell artisan composer test lint format analyse insights check migrate fresh front psql analisis

help: ## Muestra esta ayuda
	@grep -E '^[a-zA-Z_-]+:.*?## ' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-10s\033[0m %s\n", $$1, $$2}'

setup: ## Primera instalación: .env, imagen, dependencias, base de datos y front
	@test -f .env || cp .env.example .env
	$(DC) build app
	$(DC) up -d
	$(EXEC) composer install
	@grep -q '^APP_KEY=base64:' .env || $(EXEC) php artisan key:generate
	$(EXEC) php artisan migrate
	$(MAKE) assets
	@echo "Listo: http://localhost"

assets: ## Compila el CSS y el JS (sin Vite en marcha)
	$(DC) run --rm --no-deps node sh -c "npm ci && npm run build"

up: ## Arranca el entorno
	$(DC) up -d

down: ## Para el entorno
	$(DC) down

restart: down up ## Reinicia el entorno

build: ## Reconstruye la imagen de la app
	$(DC) build app

logs: ## Muestra los logs (make logs s=app)
	$(DC) logs -f --tail=200 $(s)

shell: ## Abre una terminal dentro de la app
	$(EXEC) bash

artisan: ## Ejecuta artisan (make artisan c="migrate")
	$(EXEC) php artisan $(c)

composer: ## Ejecuta composer (make composer c="require foo/bar")
	$(EXEC) composer $(c)

test: ## Ejecuta los tests (make test c="--filter=portada")
	$(EXEC) composer test -- $(c)

lint: ## Comprueba el estilo con Pint
	$(EXEC) composer lint

format: ## Corrige el estilo con Pint
	$(EXEC) composer format

analyse: ## Análisis estático con Larastan
	$(EXEC) composer analyse

insights: ## Puntúa el código con PHP Insights
	$(EXEC) composer insights

check: ## Estilo, análisis, PHP Insights y tests (lo mismo que la CI)
	$(EXEC) composer check

migrate: ## Ejecuta las migraciones
	$(EXEC) php artisan migrate

fresh: ## Recrea la base de datos con datos de prueba
	$(EXEC) php artisan migrate:fresh --seed

front: ## Arranca Vite en modo desarrollo
	$(DC) --profile front up -d node

psql: ## Abre psql en la base de datos
	$(DC) exec db psql -U $${DB_USERNAME:-zbooks} -d $${DB_DATABASE:-zbooks}

analisis: ## Genera el PDF del análisis funcional (necesita pandoc y Playwright)
	NODE_PATH=$$(npm root -g) node docs/analisis/herramientas/generar-pdf.mjs
