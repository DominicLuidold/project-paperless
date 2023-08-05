.EXPORT_ALL_VARIABLES:

include .env

ifneq ("$(wildcard .env.local)","")
    include .env.local
endif

# Permissions
USER_UID=$(shell id -u)

# Makefile config
.DEFAULT_GOAL:=help
.PHONY: start debug stop enter-backend rebuild setup help

## Docker stack
start: ## Build and start the Docker stack.
	@docker compose -f ./docker/docker-compose.development.yml -p ${PROJECT_NAME} up -d --build

debug: ## Build and start the Docker stack with debugging enabled.
	@XDEBUG_MODE=debug docker compose -f ./docker/docker-compose.development.yml -p ${PROJECT_NAME} up -d --build

stop: ## Stop the Docker stack.
	@docker compose -p ${PROJECT_NAME} down

enter-backend: ## Enter the backend container.
	@docker exec -it ${PROJECT_NAME}-backend-1 /bin/bash || true

## Build
rebuild: ## Forces a rebuild of the custom Docker images.
	@docker compose -f ./docker/docker-compose.development.yml build --no-cache

## Setup
setup: ## Run the `/backend/bin/setup.sh` script in the backend container.
	@docker exec -it ${PROJECT_NAME}-backend-1 /var/www/app/bin/setup.sh || true

## Help
help: ## Show available commands.
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m##/' | sed -e 's/##//'
