#!/bin/bash -e

echo "# Installing dependencies ..."
composer install

echo "# Dumping env variables ..."
composer dump-env $APP_ENV

echo "# Stopping supervisor jobs ..."
supervisorctl stop all

echo "# Killing open connections to postgres ..."
bin/console dbal:run-sql "SELECT pg_terminate_backend(pg_stat_activity.pid) FROM pg_stat_activity WHERE datname = current_database() AND pid <> pg_backend_pid();"

echo "# Setting up development database ..."
bin/console doctrine:database:drop --force --if-exists
bin/console doctrine:database:create --no-interaction
bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration
bin/console doctrine:fixtures:load --no-interaction

echo "# Setting up test database ..."
mkdir -p ./var/data
bin/console doctrine:database:drop --force --env=test
bin/console doctrine:database:create --no-interaction --env=test
bin/console doctrine:schema:update --force --env=test

echo "# Clearing outdated cache ..."
bin/console cache:pool:prune --env=dev
bin/console cache:clear --no-warmup --env=dev

bin/console cache:pool:prune --env=test
bin/console cache:clear --no-warmup --env=test

echo "# Starting supervisor jobs ..."
supervisorctl start all
