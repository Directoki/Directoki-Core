#!/usr/bin/env bash

set -e

echo "en_GB.UTF-8 UTF-8" >> /etc/locale.gen

locale-gen

echo "tmpfs  /var/lib/postgresql  tmpfs  size=1536m,auto  0  0" >> /etc/fstab
mkdir /var/lib/postgresql
mount /var/lib/postgresql

sudo apt-get update
sudo apt-get install -y php postgresql php-pgsql zip  php-mbstring php-xml

sudo su --login -c "psql -c \"CREATE USER test WITH PASSWORD 'testpassword';\"" postgres
sudo su --login -c "psql -c \"CREATE DATABASE test WITH OWNER test ENCODING 'UTF8'  LC_COLLATE='en_GB.UTF-8' LC_CTYPE='en_GB.UTF-8'  TEMPLATE=template0 ;\"" postgres

mkdir -p /bin
wget -O /bin/composer.phar -q https://getcomposer.org/composer.phar
wget -O /bin/phpunit.phar -q https://phar.phpunit.de/phpunit-6.3.phar

cp /vagrant/vagrant/tests/parameters_test.yml /vagrant/app/config/parameters_test.yml

cd /vagrant
php /bin/composer.phar  install

cp /vagrant/vagrant/tests/test /home/ubuntu/test
chmod a+rx /home/ubuntu/test
