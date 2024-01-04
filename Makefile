.EXPORT_ALL_VARIABLES:

include .env

ifneq ("$(wildcard .env.local)","")
    include .env.local
endif

# Permissions
USER_UID=$(shell id -u)

# Makefile config
.DEFAULT_GOAL:=help
.PHONY: start debug stop enter-node enter-backend rebuild setup install-git-hooks run-code-checks help

## Docker stack
start: ## Build and start the Docker stack.
	@docker compose -f ./docker/compose.yaml -p ${PROJECT_NAME} up -d --build

debug: ## Build and start the Docker stack with debugging enabled.
	@XDEBUG_MODE=debug docker compose -f ./docker/compose.yaml -p ${PROJECT_NAME} up -d --build

stop: ## Stop the Docker stack.
	@docker compose -p ${PROJECT_NAME} down

enter-node: ## Enter the frontend Node.js container.
	@./docker/frontend/node-ci.sh || true

enter-backend: ## Enter the backend PHP container.
	@docker exec -it ${PROJECT_NAME}-backend-1 /bin/bash || true

## Build
rebuild: ## Forces a rebuild of the custom Docker images.
	@docker compose -f ./docker/compose.yaml build --no-cache

## Setup
setup: ## Run the `/backend/bin/setup.sh` script in the backend container.
	@docker exec -it ${PROJECT_NAME}-backend-1 /var/www/app/bin/setup.sh || true

install-git-hooks: ## Install project-specific Git hooks.
	@ln -fs ./../../git-hooks/pre-commit ./.git/hooks/pre-commit

## Misc
run-code-checks: ## Run checks on code (style, types, dependencies).
	@if ! (git diff --quiet ./backend && git diff --quiet --staged ./backend); then \
		echo "# Running code checks ..."; \
		docker exec ${PROJECT_NAME}-backend-1 composer code-check || (echo "Running code checks failed."; exit 1); \
		echo "# Done!"; \
	fi

## Help
help: ## Show available commands.
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m##/' | sed -e 's/##//'
