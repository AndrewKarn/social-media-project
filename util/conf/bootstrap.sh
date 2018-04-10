#!/usr/bin/env bash

# initial commands
sudo apt-get update && apt-get upgrade
sudo apt-get install -y python-software-properties

# add php module repo
sudo add-apt-repository -y ppa:ondrej/php
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 2930ADAE8CAF5059EE73BB4B58712A2291FA4AD5
echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu xenial/mongodb-org/3.6 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.6.list
sudo apt-get update

# install dependencies
sudo apt-get install -y mongodb-org
sudo apt-get install -y composer
sudo apt-get install -y php7.2
sudo apt-get install -y php-pear php7.2-curl php7.2-dev php7.2-fpm zip unzip
sudo pecl install mongodb

# stop apache2 and install nginx
sudo service apache2 stop
sudo apt-get install -y nginx

# add mongodb library extensions and composer install
echo "Configuring php.ini files"
echo 'extension=mongodb.so' | sudo tee -a /etc/php/7.2/fpm/php.ini
echo 'cgi.fix_pathinfo=0' | sudo tee -a /etc/php/7.2/fpm/php.ini
echo 'extension=mongodb.so' | sudo tee -a /etc/php/7.2/cli/php.ini
cd /vagrant && sudo composer install

# nginx setup
sudo cp /vagrant/util/conf/zoe-nginx.conf /etc/nginx/sites-available/zoes-social-media-project.com
sudo chmod 744 /etc/nginx/sites-available/zoes-social-media-project.com
sudo ln -s /etc/nginx/sites-available/zoes-social-media-project.com /etc/nginx/sites-enabled/zoes-social-media-project.com

# link to vagrant home directory
sudo rm -rf /var/www
sudo ln -s /vagrant/src /var/www

# sync error logs
sudo ln -s /var/log/nginx/error.log /vagrant/util/logs/
sudo ln -s /var/log/php7.2-fpm.log /vagrant/util/logs/

# change log file to nginx owner so nginx can write log files
sudo chown www-data /var/log/nginx

# add bashrc scripts
sudo cat /vagrant/util/conf/write-to-vagrant-bashrc.txt >> ~/.bashrc

# restart nginx and php
sudo service nginx restart
sudo service php7.2-fpm restart