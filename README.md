# kitsune-auto-setup
An Automated Setup (one file) for new CPPS owners. Idea from Dev's [script](https://aureus.pw/topic/1172-kitsune-auto-setup-script-as2/). Uses Kitsune AS2 or AS3 Protocol.


----------
Follow the steps on [this tutorial](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-14-04) to setup a LAMP environment on Ubuntu. Then, run this command:

    sudo apt-get install php5-curl
    
Next, download `setup.php`, and move it somewhere that is not in `/var/www/html/`, and run

    php setup.php
  
