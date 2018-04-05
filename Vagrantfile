# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  config.vm.box = "ubuntu/xenial64"
  config.vm.network "private_network", ip: "192.168.50.40"
  config.vm.network "forwarded_port", guest: 80, host: 4000
  config.vm.provider "virtualbox" do |vb|
    vb.gui = false
    vb.memory = "1024"
    vb.cpus = 1
    vb.customize [ "modifyvm", :id, "--uartmode1", "disconnected"]
  end
  config.vm.provision "shell", path: "util/conf/bootstrap.sh"
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