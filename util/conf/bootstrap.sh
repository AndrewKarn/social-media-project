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


configureMongoDB() {
    sudo service mongod stop
    # setup mongod.conf
    sudo sed -i 's/bindIp: 127.0.0.1/bindIp: 192.168.50.40/' /etc/mongod.conf
#    sudo echo '# mongod.conf
#
#      # for documentation of all options, see:
#      #   http://docs.mongodb.org/manual/reference/configuration-options/
#
#      # Where and how to store data.
#      storage:
#        dbPath: /var/lib/mongodb
#        journal:
#          enabled: true
#      #  engine:
#      #  mmapv1:
#      #  wiredTiger:
#
#      # where to write logging data.
#      systemLog:
#        destination: file
#        logAppend: true
#        path: /var/log/mongodb/mongod.log
#
#      # network interfaces
#      net:
#        port: 27017
#        bindIp: 192.168.50.40
#
#
#      # how the process runs
#      processManagement:
#        timeZoneInfo: /usr/share/zoneinfo
#
#      #security:
#
#      #operationProfiling:
#
#      #replication:
#
#      #sharding:
#
#      ## Enterprise-Only Options:
#
#      #auditLog:
#
#      #snmp:' > /etc/mongod.conf

    sudo service mongod start
    sleep 5s
    sudo echo "
    var db = connect('192.168.50.40:27017/main');
    db.createCollection('users');
    db.users.createIndex({'email': 1}, { 'unique': true});
    db.createCollection('conversations');
    var users = db.getCollection('users');
    " > mongosetup.js
    # sudo echo "db.createUser({user:'adminz',pwd:'mongodbzpass', roles:['root']});" >> mongoInit.js
    # sudo echo "sb.grantRolesToUser('adminz',[{role: 'dbOwner', db: 'main'}]);" >> mongoInit.js
    sudo mongo 192.168.50.40:27017/main mongosetup.js
    rm mongosetup.js
}
configureMongoDB


# change log file to nginx owner so nginx can write log files
sudo chown www-data /var/log/nginx

# add bashrc scripts
sudo cat /vagrant/util/conf/write-to-vagrant-bashrc.sh ~/.bashrc

# restart nginx and php
sudo service nginx restart
sudo service php7.2-fpm restart

# configure autoload files
cd /vagrant && composer dump-autoload