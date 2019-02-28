#!/bin/bash
set -e

has_changed() {
    echo $GIT_CHANGES | grep "$1" > /dev/null 2>&1
}

get_changed() {
    echo $GIT_CHANGES | xargs -d' ' -n 1 | grep "$1"
}

get_composer() {
    if [ ! -f "${DIR}/../composer.phar" ]; then
        curl -s https://getcomposer.org/installer | php
    fi

    if [ ! -d "${DIR}/../vendor" ]; then
        php composer.phar install
    fi

    exec 1>&2
}

composer() {
    COMPOSER_BIN=`which composer`

    if [ -f "$COMPOSER_BIN" ]; then
        $COMPOSER_BIN $@
    else
        get_composer
        php composer.phar $@
    fi;

    exec 1>&2
}

lint_php() {
    for PHPFILE in $@; do
        php -l $PHPFILE
    done

    exec 1>&2
}

fix_code_style() {
    composer run cs-fix $@

    exec 1>&2
}

phpunit() {
    PHPUNIT_BIN=`realpath "${DIR}/../vendor/bin/phpunit"`

    if [ ! -f "${PHPUNIT_BIN}" ]; then
        composer install
    fi

    php ${PHPUNIT_BIN} $@

    exec 1>&2
}

test_php() {
    for PHPFILE in $@; do
        TESTCASE=`basename $PHPFILE | cut -d'.' -f1`
        phpunit --filter="${TESTCASE}"
    done

    if [ -z "$@" ]; then
        phpunit
    fi

    exec 1>&2
}
