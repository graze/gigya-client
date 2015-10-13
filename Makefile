.PHONY: test test-coverage test-unit test-unit-coverage test-functional test-functional-coverage install

test:
	@./vendor/bin/phpunit --exclude-group performance

lint:
	@./vendor/bin/phpcs -p --standard=PSR2 --warning-severity=0 src/ tests/

lint-auto-fix:
	@./vendor/bin/phpcbf -p --standard=PSR2 src/ tests/

test-coverage:
	@./vendor/bin/phpunit --coverage-text --coverage-html ./tests/report

test-unit:
	@./vendor/bin/phpunit --testsuite unit

test-unit-coverage:
	@./vendor/bin/phpunit --testsuite unit --coverage-text --coverage-html ./tests/report

test-functional:
	@./vendor/bin/phpunit --testsuite functional

test-functional-coverage:
	@./vendor/bin/phpunit --testsuite functional --coverage-text --coverage-html ./tests/report

test-performance:
	@./vendor/bin/phpunit --testsuite performance

install:
	@composer install
