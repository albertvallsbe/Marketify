#!/bin/sh

# Actualizar repositorios
sudo apt-get update

# Instalar Apache
sudo apt-get install -y apache2

# Agregar repositorio de PHP 8.2
sudo apt-get install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt-get update

# Instalar PHP 8.2 y extensiones requeridas por Laravel
sudo sudo apt-get install -y apt-transport-https

sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg

sudo echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list

sudo apt-get update
sudo apt-get install -y php8.2 libapache2-mod-php8.2 php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip


# Instalar Composer
sudo apt-get install -y composer

# Crear un nuevo proyecto Laravel
sudo composer create-project --prefer-dist laravel/laravel /home/marketify/site

# Configurar Apache para Laravel
# sudo tee /etc/apache2/sites-available/000-default.conf > /dev/null <<EOF
# <VirtualHost *:80>
#     ServerAdmin webmaster@localhost
#     DocumentRoot /home/marketify/site/public

#     <Directory /home/marketify/site/public>
#         Options Indexes FollowSymLinks
#         AllowOverride All
#         Require all granted
#     </Directory>

#     ErrorLog ${APACHE_LOG_DIR}/error.log
#     CustomLog ${APACHE_LOG_DIR}/access.log combined
# </VirtualHost>
# EOF

sudo a2enmod rewrite
sudo service apache2 restart

# Cambiar el propietario de los archivos y directorios de Laravel al usuario de Apache
sudo chown -R www-data:www-data /home/marketify/site

# Establecer permisos adicionales en los directorios de Laravel
sudo chmod -R 775 /home/marketify/site/storage
sudo chmod -R 775 /home/marketify/site/bootstrap/cache
sudo chmod -R 775 /home/marketify/site/public

# Reiniciar Apache nuevamente
sudo service apache2 restart

# Instalar MariaDB
sudo apt-get install -y mariadb-server mariadb-client

# Crear la base de datos
sudo mysql << EOF
CREATE DATABASE marketifyBBDD charset='utf8mb4' collate='utf8mb4_unicode_ci';
EOF

# Configurar MariaDB
sudo cp /vagrant/50-server.cnf /etc/mysql/mariadb.conf.d/50-server.cnf

# Crear el usuario 'admin' con acceso remoto
sudo mysql << EOF
CREATE OR REPLACE USER 'admin'@'%' IDENTIFIED BY '1234';
GRANT ALL ON *.* TO 'admin'@'%';
FLUSH PRIVILEGES;
EOF

# Reiniciar MariaDB
sudo systemctl restart mariadb

#CREAR CERTIFICADO PARA CONEXIÓN HTTPS

# Habilitar el módulo SSL de Apache
sudo a2enmod ssl

# Reiniciar Apache para aplicar los cambios
sudo systemctl restart apache2

# Generar una clave privada de 2048 bits y guardarla en /etc/ssl/private/selfsigned.key
sudo openssl genrsa -out /etc/ssl/private/selfsigned.key 2048

#Generar una solicitud de firma de certificado (CSR) utilizando la clave privada generada anteriormente y guardarla en /etc/ssl/certs/selfsigned.csr
sudo openssl req -new -key /etc/ssl/private/selfsigned.key -out /etc/ssl/certs/selfsigned.csr \
  -subj "/C=ES/ST=Barcelona/L=Terrassa/O=Marketify/CN=Marketify_Nicolau/emailAddress=info@marketify.es"

# El certificado resultante se guardará en /etc/ssl/certs/selfsigned.crt
sudo openssl x509 -req -days 365 -in /etc/ssl/certs/selfsigned.csr -signkey /etc/ssl/private/selfsigned.key -out /etc/ssl/certs/selfsigned.crt

# Establecer los permisos adecuados para la clave privada
sudo chmod 600 /etc/ssl/private/selfsigned.key

# Establecer los permisos adecuados para el certificado
sudo chmod 644 /etc/ssl/certs/selfsigned.crt

# Reiniciar Apache para cargar el certificado y la clave privada
sudo systemctl restart apache2

# Modificar la configuración del archivo default-ssl.conf para cambiar la ubicación del DocumentRoot
sudo sed -i 's#DocumentRoot /var/www/html#DocumentRoot /home/marketify/site/public#' /etc/apache2/sites-available/default-ssl.conf

# Habilitar el sitio SSL predeterminado
sudo a2ensite default-ssl

# Reiniciar Apache nuevamente para aplicar los cambios de configuración
sudo systemctl restart apache2

# Configurar el archivo 000-default.conf con el VirtualHost para el puerto 80 y 443, además de forzar la redirección a HTTPS
sudo tee /etc/apache2/sites-available/000-default.conf > /dev/null <<EOF
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /home/marketify/site/public

    <Directory /home/marketify/site/public> 
        Options Indexes FollowSymLinks      
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog /var/log/apache2/error.log
    CustomLog /var/log/apache2/access.log combined

    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]
</VirtualHost>

<VirtualHost *:443>
    ServerAdmin webmaster@localhost
    DocumentRoot /home/marketify/site/public

    <Directory /home/marketify/site/public> 
        Options Indexes FollowSymLinks      
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog /var/log/apache2/error.log
    CustomLog /var/log/apache2/access.log combined

    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/selfsigned.crt
    SSLCertificateKeyFile /etc/ssl/private/selfsigned.key
</VirtualHost>
EOF
