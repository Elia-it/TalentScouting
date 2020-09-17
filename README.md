Install composer:

<<<<<<< HEAD


php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" php composer-setup.php php -r "unlink('composer-setup.php');"
=======
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '8a6138e2a05a8c28539c9f0fb361159823655d7ad2deecb371b04a83966c61223adc522b0189079e3e9e277cd72b8897') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

>>>>>>> 3ed3cdeaaf12a77d897df80528ca2e60226a43f2

Move to globally: mv composer.phar /usr/local/bin/composer

Install laravel: composer global require laravel/installer

After clone it:

composer install

npm install

php artisan key:generate
