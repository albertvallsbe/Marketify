# Marketify
## Integrantes del grupo
* David Hernández
* Albert Valls 
* Oscar Ramírez

## Enlaces
* https://trello.com/invite/b/wj6DE1Gb/ATTI34f5e14159b56ec72c452723a2cb8b0353D256B8/planificacio-projecte-2
* https://www.figma.com/file/czdxEzwwIUWNcKAmMUDwJz/Gu%C3%ADa-de-estilos-Marketplace?node-id=0%3A1&t=bxxvJUFoXKdw84HO-1
* https://www.figma.com/file/xjsmV9M96W2X4kTGWDs0Jh/Mockups?node-id=0%3A1&t=LCFsb8FowIZtOYDZ-1

## Generar certificado HTTPS
```
# Generar una nueva clave privada encriptada
sudo openssl genpkey -algorithm RSA -out /etc/ssl/private/selfsigned.key -aes128
```
```
# Generar un certificado autofirmado con la nueva clave privada
sudo openssl req -new -x509 -sha256 -key /etc/ssl/private/selfsigned.key -out /etc/ssl/certs/selfsigned.crt -days 3650
```
```
# Reiniciar Apache
sudo systemctl restart apache2
```