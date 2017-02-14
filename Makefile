#/bin/bash

A_PHP?="7.0"

.PHONY: composer-update
composer-update:
	docker run --rm --tty --interactive -v $(shell pwd):/app composer/composer update

.PHONY: composer
composer:
	docker run --rm --tty --interactive -v $(shell pwd):/app composer/composer $(COMPOSER_CMD)

.PHONY: test-unit
test-unit:
	docker run --rm --tty --interactive --workdir /src -v $(shell pwd):/src php:$(A_PHP)-cli php vendor/bin/phpunit

.PHONY: phpunit
phpunit:
	docker run --rm --tty --interactive --workdir /src -v $(shell pwd):/src php:$(A_PHP)-cli php vendor/bin/phpunit $(PHPUNIT_FILE)

.PHONY: test-acceptance
test-acceptance:
	docker run --rm --tty --interactive --workdir /src -v $(shell pwd):/src php:$(A_PHP)-cli php vendor/bin/behat -c examples/json/behat.yml
	docker run --rm --tty --interactive --workdir /src -v $(shell pwd):/src php:$(A_PHP)-cli php vendor/bin/behat -c examples/xml/behat.yml
