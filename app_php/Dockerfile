# syntax=docker/dockerfile:1.4
ARG PHP_VERSION=8.4
FROM --platform=$BUILDPLATFORM docker.io/library/php:${PHP_VERSION}-apache as builder

ADD data/ci.conf data/default.conf /etc/apache2/sites-available
ADD data/ports.conf /etc/apache2


RUN <<EOF
export DEBIAN_FRONTEND=noninteractive
echo ----------------------------
echo "Mise à jour des dépôts"
echo ----------------------------
apt-get -q update
apt-get -y -q install lsb-release ca-certificates curl apt-transport-https
curl -sSLo /tmp/debsuryorg-archive-keyring.deb https://packages.sury.org/debsuryorg-archive-keyring.deb
dpkg -i /tmp/debsuryorg-archive-keyring.deb
sh -c 'echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
#redefinir les priorités des repos
sed -i "s/Pin-Priority:\ -1/Pin-Priority:\ 999/g" /etc/apt/preferences.d/no-debian-php
apt-get -q update

echo ----------------------------
echo "Installation de : vim"
echo ----------------------------
apt-get install -y -q vim

echo ----------------------------
echo "Installation de composer"
echo ----------------------------
curl -sS https://getcomposer.org/installer -o composer-setup.php
HASH=`curl -sS https://composer.github.io/installer.sig`
php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php

echo ----------------------------
echo "Installation de sqlite3"
echo ----------------------------
apt-get -y -q install sqlite3

echo "----------------------------"
echo "zip pour composer"
echo "----------------------------"
apt-get -y -q install unzip
echo ----------------------------
echo "Mise à jour du php.ini en dev"
echo ----------------------------
cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
# Activation de l'affichage des erreurs
sed -i 's/display_errors = Off/display_errors = On/g' /usr/local/etc/php/php.ini
sed -i 's/display_startup_errors = Off/display_startup_errors = On/g' /usr/local/etc/php/php.ini
sed -i 's/error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT/error_reporting = E_ALL/g' /usr/local/etc/php/php.ini
sed -i 's/log_errors = On/log_errors = On/g' /usr/local/etc/php/php.ini
echo ----------------------------
echo "Installation des extensions PHP :"
echo ----------------------------
echo "Activation de l'extension intl (Locale) via docker-php-ext-install"
apt-get -y -q install libicu-dev
docker-php-ext-install intl
docker-php-ext-enable intl
echo ----------------------------
echo "Suppression des virtuals hosts"
echo ----------------------------
a2dissite *
a2ensite ci.conf
a2ensite default.conf
a2enmod rewrite
EOF

CMD ["apache2-foreground"]


