#!/bin/bash
bin/console doctrine:fixtures:load -n
phpunit
