FROM dunglas/frankenphp:1.2-php8.3.8

RUN install-php-extensions \
    pcntl \
    mysqli \
    pdo_pgsql \
    pdo_mysql \
    intl \
    ctype \
    curl \
    dom \
    fileinfo \
    filter \
    hash \
    mbstring \
    pcre \
    tokenizer \
    xml

RUN apt-get update && apt-get install -y supervisor && apt-get clean

#COPY --from=node:20-slim /usr/local/bin /usr/local/bin

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY init-project.sh /usr/local/bin/start

#COPY . /app
VOLUME ["/app"]
WORKDIR /app

#RUN rm -f /app/.env

RUN chmod +x /usr/local/bin/start

ENTRYPOINT ["bash", "/usr/local/bin/start"]
