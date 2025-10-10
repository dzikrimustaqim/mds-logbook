SHELL := /bin/bash

.PHONY: up down build build-prod shell node-build prod-build assets

up:
	docker compose up --build -d

down:
	docker compose down

build:
	docker compose build

shell:
	docker compose exec app bash

node-build:
	docker compose run --rm node sh -lc "npm ci && npm run build"

assets: node-build

build-prod:
	docker compose -f docker-compose.yml -f docker-compose.prod.yml up --build -d

prod-shell:
	docker compose -f docker-compose.yml -f docker-compose.prod.yml exec app bash
