FROM phpswoole/swoole

RUN docker-php-ext-install pcntl

ENV PHP_CLI_SERVER_WORKERS 10