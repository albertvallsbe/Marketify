#CREAR CERTIFICADO PARA CONEXIÓN HTTPS
# Habilitar el módulo SSL de Apache
sudo a2enmod ssl

# Reiniciar Apache para aplicar los cambios
sudo systemctl restart apache2

# Generar una clave privada de 2048 bits y guardarla en /etc/ssl/private/selfsigned.key
sudo openssl genrsa -out /etc/ssl/private/selfsigned.key 2048

# Generar una solicitud de firma de certificado (CSR) utilizando la clave privada generada anteriormente y guardarla en /etc/ssl/certs/selfsigned.csr
sudo openssl req -new -key /etc/ssl/private/selfsigned.key -out /etc/ssl/certs/selfsigned.csr

# Generar un certificado autofirmado válido por 365 días utilizando el archivo CSR y la clave privada
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

# Configurar el archivo 000-default.conf con el VirtualHost para el puerto 80
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

# Generar una nueva clave privada encriptada
sudo openssl genpkey -algorithm RSA -out /etc/ssl/private/selfsigned.key -aes128

# Generar un certificado autofirmado con la nueva clave privada
sudo openssl req -new -x509 -sha256 -key /etc/ssl/private/selfsigned.key -out /etc/ssl/certs/selfsigned.crt -days 3650

# Reiniciar Apache
sudo systemctl restart apache2

# Convertir la clave privada a formato sin encriptación
sudo openssl rsa -in /etc/ssl/private/selfsigned.key -out /etc/ssl/private/selfsigned.key

# Reiniciar Apache
sudo systemctl restart apache2