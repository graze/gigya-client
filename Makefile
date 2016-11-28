SHELL = /bin/sh

DOCKER ?= $(shell which docker)
DOCKER_REPOSITORY := graze/gigya-client
VOLUME := /srv
VOLUME_MAP := -v $$(pwd):${VOLUME}
DOCKER_RUN_BASE := ${DOCKER} run --rm -t ${VOLUME_MAP} -w ${VOLUME}
DOCKER_RUN := ${DOCKER_RUN_BASE} ${DOCKER_REPOSITORY}:latest

.PHONY: install composer clean help
.PHONY: test lint lint-fix test-unit test-integration test-matrix test-coverage test-coverage-html test-coverage-clover

.SILENT: help

# Building

install: ## Download the dependencies then build the image :rocket:.
	make 'composer-install --optimize-autoloader --ignore-platform-reqs'
	$(DOCKER) build --tag ${DOCKER_REPOSITORY}:latest .

composer-%: ## Run a composer command, `make "composer-<command> [...]"`.
	${DOCKER} run -t --rm \
        -v $$(pwd):/app \
        -v ~/.composer:/root/composer \
        -v ~/.ssh:/root/.ssh:ro \
        composer/composer:alpine --ansi --no-interaction $* $(filter-out $@,$(MAKECMDGOALS))

clean: ## Clean up any images.
	$(DOCKER) rmi ${DOCKER_REPOSITORY}:latest

# Testing

test: ## Run the unit and integration testsuites.
test: lint test-unit test-integration

lint: ## Run phpcs against the code.
	$(DOCKER_RUN) vendor/bin/phpcs -p --warning-severity=0 src/ tests/

lint-fix: ## Run phpcsf and fix lint errors.
	$(DOCKER_RUN) vendor/bin/phpcbf -p src/ tests/

test-unit: ## Run the unit testsuite.
	$(DOCKER_RUN) vendor/bin/phpunit --colors=always --testsuite unit

test-matrix: ## Run the unit tests against multiple targets.
	make DOCKER_RUN="${DOCKER_RUN_BASE} php:5.6-cli" test
	make DOCKER_RUN="${DOCKER_RUN_BASE} php:7.0-cli" test
	make DOCKER_RUN="${DOCKER_RUN_BASE} diegomarangoni/hhvm:cli" test

test-integration: ## Run the integration testsuite.
	$(DOCKER_RUN) vendor/bin/phpunit --colors=always --testsuite integration

test-performance: ## Run the performance testsuite.
	$(DOCKER_RUN) vendor/bin/phpunit --colors=always --testsuite performance

test-coverage: ## Run all tests and output coverage to the console.
	$(DOCKER_RUN) vendor/bin/phpunit --coverage-text --testsuite coverage

test-coverage-html: ## Run all tests and output html results
	$(DOCKER_RUN) vendor/bin/phpunit --coverage-html ./tests/report/html --testsuite coverage

test-coverage-clover: ## Run all tests and output clover coverage to file.
	$(DOCKER_RUN) vendor/bin/phpunit --coverage-clover=./tests/report/coverage.clover --testsuite coverage


# Help

help: ## Show this help message.
	echo "usage: make [target] ..."
	echo ""
	echo "targets:"
	egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'
