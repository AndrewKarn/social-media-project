# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  config.vm.box = "ubuntu/xenial64"
  config.vm.network "private_network", ip: "192.168.50.40"
  config.vm.network "forwarded_port", guest: 80, host: 4000
  config.vm.provider "virtualbox" do |vb|
    vb.gui = false
    vb.memory = "1024"
  end
  config.vm.provision "shell", inline: <<-SHELL
    sudo su
    apt-get update && apt-get upgrade
    apt-get install -y python-software-properties
    add-apt-repository -y ppa:ondrej/php
    apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 2930ADAE8CAF5059EE73BB4B58712A2291FA4AD5
    echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu xenial/mongodb-org/3.6 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.6.list
    apt-get update
    apt-get install -y mongodb-org
    apt-get install -y composer
    apt-get install -y php7.2
    apt-get install -y php-pear php7.2-curl php7.2-dev php7.2-fpm zip unzip
    pecl install mongodb
    service apache2 stop
    apt-get install -y nginx
    echo 'extension=mongodb.so' | tee -a /etc/php/7.2/fpm/php.ini
    echo 'extension=mongodb.so' | tee -a /etc/php/7.2/cli/php.ini
    cd /vagrant && composer install
  SHELL
  config.vm.provision "shell", run: "always", inline: <<-SHELL
    service mongod start
    sudo service php7.2-fpm restart
    sudo service nginx restart
  SHELL
end
  # Share an additional folder to the guest VM. The first argument is
  # the path on the host to the actual folder. The second argument is
  # the path on the guest to mount the folder. And the optional third
  # argument is a set of non-required options.
  # config.vm.synced_folder "../data", "/vagrant_data"