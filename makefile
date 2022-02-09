# Eavily inspired by https://github.com/jorge07/symfony-5-es-cqrs-boilerplate/blob/symfony-5/makefile
# Thank you very much!

env=dev
docker-os=
compose=docker-compose -f docker-compose.yml
s=web-service
tty=

# I don't use Windows, feel free to make it work!
# ifeq ($(docker-os), windows)
# 	ifeq ($(env), dev)
# 		compose += -f etc/dev/docker-compose.windows.yml
# 	endif
# endif

ifeq ($(GITHUB_ACTIONS), true)
	tty = -T # docker-compose -T option required during CI in Github worklfow
	compose += -f docker-compose.ci.yml
endif

export compose env docker-os

# Arguments support https://stackoverflow.com/a/45003119/2714285
remaining_args := $(wordlist 2, $(words $(MAKECMDGOALS)), $(MAKECMDGOALS))
$(eval $(remaining_args):;@true)

## UTILITIES

.DEFAULT_GOAL := help
.PHONY: help
help: ## Display this help message
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: tests
tests: php_phpunit ## Runs all tests

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
	$(compose) build --parallel --no-cache  $(remaining_args)

.PHONY: docker_up
docker_up: ## spin up environment
	$(compose) up $(remaining_args)

.PHONY: docker_down
docker_down: ## shutoff services
	$(compose) down --remove-orphans  $(remaining_args)

.PHONY: docker_ps
docker_ps: ## list up services
	$(compose) ps $(remaining_args)

.PHONY: docker_stop
docker_stop: ## stop environment
	$(compose) stop  $(remaining_args)

.PHONY: docker_remove
docker_remove: ## stop and delete containers, clean volumes.
	$(compose) stop
	docker-compose rm -v -f  $(remaining_args)

.PHONY: docker_exec
docker_exec: ## Runs a command in the web-service container.
	$(compose) exec $(tty) $(s) $(remaining_args)

## PHP

.PHONY: php_composer-update
php_composer-update: ## Update project dependencies
	$(compose) run --rm $(s) sh -lc 'COMPOSER_MEMORY_LIMIT=-1 composer update'

.PHONY: php_phpunit
php_phpunit: db_recreate_test ## execute project unit tests
	$(compose) exec $(tty) --env XDEBUG_MODE=coverage $(s) ./vendor/bin/phpunit $(remaining_args)

.PHONY: php_coveralls
php_coveralls:
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
	$(compose) exec $(tty) database sh -lc 'psql -d ecotone -U ecotone -c "\
		REVOKE CONNECT ON DATABASE \"ecotone\" FROM public;\
	"'
	$(compose) exec $(tty) database sh -lc 'psql -d ecotone -U ecotone -c "\
		SELECT pid, pg_terminate_backend(pid) \
		FROM pg_stat_activity \
		WHERE datname = current_database() AND pid <> pg_backend_pid();"'
	$(compose) exec $(tty) $(s) sh -lc './bin/console doctrine:database:drop --force --if-exists'
	$(compose) exec $(tty) $(s) sh -lc './bin/console doctrine:database:create --if-not-exists'
	$(compose) exec $(tty) $(s) sh -lc './bin/console doctrine:migrations:migrate -n'
	# Allowing new connections
	$(compose) exec $(tty) database sh -lc 'psql -d ecotone -U ecotone -c "\
		GRANT CONNECT ON DATABASE \"ecotone\" TO public;\
	"'

.PHONY: db_recreate_test
db_recreate_test: ## recreate the test database
	$(compose) exec $(tty) --env APP_ENV=test $(s) /data/app/bin/console doctrine:database:drop --force --if-exists
	$(compose) exec $(tty) --env APP_ENV=test $(s) /data/app/bin/console doctrine:database:create --if-not-exists
	$(compose) exec $(tty) --env APP_ENV=test $(s) /data/app/bin/console doctrine:migrations:migrate -n
	# TODO: Is there a way to initialize projections tables without fake data?
	$(compose) exec $(tty) --env APP_ENV=test $(s) /data/app/bin/console app:register-user fake_user_to_generate_db_tables
	$(compose) exec $(tty) --env APP_ENV=test $(s) /data/app/bin/console app:prepare-ticket fake_ticket_to_generate_db_tables ticket_desc

.PHONY: db_migrations-diff
db_migrations-diff: ## Generate migrations diff file
	$(compose) exec $(tty) $(s) sh -lc './bin/console doctrine:migrations:diff'

.PHONY: db_schema-validate
db_schema-validate: ## validate database schema
	$(compose) exec $(tty) $(s) sh -lc './bin/console doctrine:schema:validate'