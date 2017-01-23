# Eslint config
ESLINT_SOURCE := assets/js/scripts.js

# Pot config
POT_NAME := wp-content-menu
POT_FILE := languages/wp-content-menu.pot

deps:
	npm install -g wp-pot-cli

lint:
	make lint:php

lint\:php:
	vendor/bin/phpcs -s --extensions=php --standard=phpcs.xml src/

pot:
	wp-pot --src 'src/**/*.php' --dest-file $(POT_FILE) --package $(POT_NAME)

test:
	vendor/bin/phpunit
