# Eavily inspired by https://github.com/jorge07/symfony-5-es-cqrs-boilerplate/blob/symfony-5/makefile
# Thank you very much!

env=dev
docker-os=
compose=docker-compose -f docker-compose.yml
s=web-service

# I don't use Windows, feel free to make it work!
# ifeq ($(docker-os), windows)
# 	ifeq ($(env), dev)
# 		compose += -f etc/dev/docker-compose.windows.yml
# 	endif
# endif

export compose env docker-os

## UTILITIES

.PHONY: help
help: ## Display this help message
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: tests
tests: phpunit ## Runs all tests

.PHONY: start
start: docker_up ## Start application

.PHONY: stop
stop: docker_down ## Stops the application

.PHONY: reset
reset: ## !! Stops the application and deletes all its data
	$(compose) down --remove-orphans --volumes --rmi local

.PHONY: clean
clean: ## !! Stops the application and deletes all its data including all images
	$(compose) down --remove-orphans --volumes --rmi all

.PHONY: logs
logs: ## look for 's' service logs, make s=php logs
	$(compose) logs -f $(s)

.PHONY: sh
sh: ## Sh to the web-service container. Can be overriden like: make s=projections sh
	$(compose) exec -e SHELL=bash $(s)  bash -l
# SHELL=bash added for symfony-console-autocomplete

.PHONY: tests_coverage
tests_coverage: php_phpunit_coverage ## Runs all tests with coverage

## DOCKER

.PHONY: docker_build
docker_build: ## build environment and initialize composer and project dependencies
	$(compose) build --parallel --no-cache

.PHONY: docker_up
docker_up: ## spin up environment
	$(compose) up

.PHONY: docker_up_detached
docker_up_detached: ## spin up environment in detached mode
	$(compose) up -d

.PHONY: docker_down
docker_down: ## shutoff services
	$(compose) down --remove-orphans

.PHONY: docker_ps
docker_ps: ## list up services
	$(compose) ps

.PHONY: docker_stop
docker_stop: ## stop environment
	$(compose) stop

.PHONY: docker_remove
docker_remove: ## stop and delete containers, clean volumes.
	$(compose) stop
	docker-compose rm -v -f

## PHP

.PHONY: php_composer-update
php_composer-update: ## Update project dependencies
	$(compose) run --rm $(s) sh -lc 'COMPOSER_MEMORY_LIMIT=-1 composer update'

.PHONY: php_phpunit
php_phpunit: db_recreate ## execute project unit tests
	$(compose) exec $(s) sh -lc "XDEBUG_MODE=coverage ./vendor/bin/phpunit $(conf)"

.PHONY: php_phpunit_coverage
php_phpunit_coverage:
	$(compose) run --rm $(s) sh -lc "wget -q https://github.com/php-coveralls/php-coveralls/releases/download/v2.2.0/php-coveralls.phar; \
		chmod +x php-coveralls.phar; \
		export COVERALLS_RUN_LOCALLY=1; \
		export COVERALLS_EVENT_TYPE='manual'; \
		export CI_NAME='github-actions'; \
		php ./php-coveralls.phar -v; \
	"

# DATABASE

.PHONY: db_sql
db_sql: ## Login to the database shell
		$(compose) exec database sh -lc 'psql -d ecotone -U ecotone'

.PHONY: db_recreate
db_recreate: ## recreate database
	# Closing connections and forbidding new ones
	$(compose) exec database sh -lc 'psql -d ecotone -U ecotone -c "\
		REVOKE CONNECT ON DATABASE \"ecotone\" FROM public;\
	"'
	$(compose) exec database sh -lc 'psql -d ecotone -U ecotone -c "\
		SELECT pid, pg_terminate_backend(pid) \
		FROM pg_stat_activity \
		WHERE datname = current_database() AND pid <> pg_backend_pid();"'
	$(compose) exec $(s) sh -lc './bin/console doctrine:database:drop --force --if-exists'
	$(compose) exec $(s) sh -lc './bin/console doctrine:database:create --if-not-exists'
	$(compose) exec $(s) sh -lc './bin/console doctrine:migrations:migrate -n'
	# Allowing new connections
	$(compose) exec database sh -lc 'psql -d ecotone -U ecotone -c "\
		GRANT CONNECT ON DATABASE \"ecotone\" TO public;\
	"'

.PHONY: db_migrations-diff
db_migrations-diff: ## Generate migrations diff file
	$(compose) exec $(s) sh -lc './bin/console doctrine:migrations:diff'

.PHONY: db_schema-validate
db_schema-validate: ## validate database schema
	$(compose) exec $(s) sh -lc './bin/console doctrine:schema:validate'