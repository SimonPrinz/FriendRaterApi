FROM ubuntu:latest

ENV DEBIAN_FRONTEND=noninteractive

RUN apt update && \
    apt upgrade -y
RUN apt install software-properties-common wget git zip -y && \
    apt-add-repository ppa:ondrej/php -y && \
    apt update && \
    apt upgrade -y
RUN apt install php7.4 php7.4-xml php7.4-zip php7.4-mysql php7.4-curl -y
RUN wget -O /tmp/composer-installer.php https://getcomposer.org/installer && \
    php /tmp/composer-installer.php --install-dir=/usr/bin --filename=composer && \
    composer selfupdate
RUN wget -O /tmp/symfony-installer https://get.symfony.com/cli/installer && \
    bash /tmp/symfony-installer && \
    mv /root/.symfony/bin/symfony /usr/bin/.

WORKDIR /app
COPY . /app

RUN composer install

EXPOSE 8000
CMD symfony server:start --no-tls
