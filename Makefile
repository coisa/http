COMPOSER_BIN := php composer.phar
COMPOSER_FLAGS := --prefer-dist --optimize-autoload

.PHONY: install uninstall reinstall clean vendor

install: vendor
uninstall: clean
reinstall: clean vendor

clean:
	rm -rf vendor composer.phar

composer.phar:
	curl -s https://getcomposer.org/installer | php

vendor: composer.phar
	$(COMPOSER_BIN) install $(COMPOSER_FLAGS)
