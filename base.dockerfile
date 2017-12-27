FROM ubuntu:xenial
WORKDIR /var/www/auth
ENV DOCKER_MICROSERVICE=1
RUN apt-get update
RUN apt-get install -y php php-curl php-xml php-mbstring php-mysql net-tools
ADD assets ./assets
ADD commands ./commands
ADD config/console.php ./config/
ADD config/params.php ./config/
ADD controllers/Health* ./controllers/
ADD runtime ./runtime/
ADD service ./service/
ADD vendor ./vendor/
COPY yii* ./
COPY wait* ./
ADD web ./web/
VOLUME /var/www/auth
EXPOSE 80