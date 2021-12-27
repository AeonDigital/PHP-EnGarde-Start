FROM debian:stable-20211011-slim
LABEL   "project"="web-server-apache-php" \
        "version"="1.0" \
        "description"="Debian based web server with Apache and PHP." \
        "vendor.name"="Aeon Digital" \
        "vendor.site"="aeondigital.com.br" \
        "author.name"="Rianna Cantarelli" \
        "author.email"="rianna@aeondigital.com.br"





#
# Define argumentos a serem passados para o momento de build
ARG DEBIAN_FRONTEND=noninteractive


# 
# Pacotes que serão instalados neste build
RUN buildPacks=" \
    apache2 \
    php7.4 \
    php7.4-dom php7.4-mbstring php7.4-bcmath php7.4-gd php7.4-zip \
    curl git vim \
    " \
    && apt-get -y update \
    && apt-get -y install $buildPacks \
    && apt-get -y upgrade \
    && rm -r /var/lib/apt/lists/* \
    && a2enmod rewrite \
    && service apache2 start
#    php7.4-cli php7.4-intl \
#    php7.4-opcache php7.4-mysql php7.4-pgsql php7.4-soap  \
#    php7.4-ldap \


#
# Instalação do Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer \
    && ln -s $(composer config --global home) /root/composer

#
# Instalação do PHPUnit
RUN curl -Lo /usr/local/bin/phpunit https://phar.phpunit.de/phpunit-9.phar \
    && chmod +x /usr/local/bin/phpunit


#
# Variáveis de ambiente
ENV PATH=$PATH:/root/composer/vendor/bin \
    COMPOSER_ALLOW_SUPERUSER=1 \
    TZ=America/Sao_Paulo


#
# Definindo o timezone padrão
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone


#
# Redefinindo o .bashrc
RUN > /root/.bashrc \
    && echo "alias ls='ls --color=auto'" >> /root/.bashrc \
    && echo "alias ll='ls -la --color=auto'" >> /root/.bashrc \
    && echo "PS1='\[\e[0;94;49m\]\$ \[\e[0;90;49m\]\u\[\e[0;94;49m\]@docker \[\e[0;90;49m\]\h\[\e[0;94;49m\] in \[\e[0;90;49m\]\w \n\076\[\e[0;37;49m\]\040'" >> /root/.bashrc \
    && echo "cd /var/www/html" >> /root/.bashrc