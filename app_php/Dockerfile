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
echo ----------------------------
echo "Installation des extensions PHP :"
echo ----------------------------
PHP_VERSION_MINEUR=${PHP_VERSION%.*}
echo "---- common"
apt install -y -q php${PHP_VERSION_MINEUR}-common
echo "---- CI4 base : intl, mbstring, json (fourni de base)"
apt install -y -q php${PHP_VERSION_MINEUR}-intl
sed -i 's/;extension=intl/extension=intl/g' /usr/local/etc/php/php.ini
apt install -y -q php${PHP_VERSION_MINEUR}-mbstring
sed -i 's/;extension=mbstring/extension=mbstring/g' /usr/local/etc/php/php.ini
echo "---- SQLite3"
apt install -y -q php${PHP_VERSION_MINEUR}-pdo-sqlite
sed -i 's/;extension=pdo_sqlite/extension=pdo_sqlite/g' /usr/local/etc/php/php.ini
apt install -y -q php${PHP_VERSION_MINEUR}-sqlite3
sed -i 's/;extension=sqlite3/extension=sqlite3/g' /usr/local/etc/php/php.ini
echo "---- CI4 Optionnelles : MySQLi,curl, gd"
apt install -y -q php${PHP_VERSION_MINEUR}-pdo-mysql
sed -i 's/;extension=pdo_mysql/extension=pdo_mysql/g' /usr/local/etc/php/php.ini
apt install -y -q php${PHP_VERSION_MINEUR}-mysql
sed -i 's/;extension=mysqli/extension=mysqli/g' /usr/local/etc/php/php.ini
apt install -y -q php${PHP_VERSION_MINEUR}-curl
sed -i 's/;extension=curl/extension=curl/g' /usr/local/etc/php/php.ini
apt install -y -q php${PHP_VERSION_MINEUR}-gd
sed -i 's/;extension=gd/extension=gd/g' /usr/local/etc/php/php.ini
#recopie des .so
cp /usr/lib/php/20240924/*.so /usr/local/lib/php/extensions/no-debug-non-zts-20240924
echo ----------------------------
echo "Suppression des virtuals hosts"
echo ----------------------------
a2dissite *
a2ensite ci.conf
a2ensite default.conf
a2enmod rewrite
EOF

CMD ["apache2-foreground"]


