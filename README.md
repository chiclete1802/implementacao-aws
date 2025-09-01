#  Implementação AWS -- Servidor LAMP com SSL

O projeto do repositório será hospedado em
`/var/www/html/implementacao-aws`.

------------------------------------------------------------------------

##  1. Criar Instância EC2 na AWS

1.  Acesse o [AWS Management Console](https://console.aws.amazon.com/).
2.  Vá em **EC2 \> Launch Instance**.
3.  Configure:
    -   **AMI**: Ubuntu Server
    -   **Security Group**: liberar portas:
        -   `22` (SSH)
        -   `80` (HTTP)
        -   `443` (HTTPS)
4.  Conecte-se à instância:

``` bash
chmod 400 labsuser.pem
```

------------------------------------------------------------------------

##  2. Atualizar pacotes

``` bash
sudo apt update && sudo apt upgrade -y
```

------------------------------------------------------------------------

##  3. Instalar Apache

``` bash
sudo apt install apache2 -y
sudo systemctl enable apache2
sudo systemctl start apache2
```

Teste:

``` bash
https://ec2-3-90-255-235.compute-1.amazonaws.com/
```

------------------------------------------------------------------------

##  4. Instalar PHP

``` bash
sudo apt install php libapache2-mod-php php-mysql -y
php -v
```

------------------------------------------------------------------------

##  5. Instalar MariaDB

``` bash
sudo apt install mariadb-server -y
sudo systemctl enable mariadb
sudo systemctl start mariadb
```

Configurar segurança:

``` bash
sudo mysql_secure_installation
```

Criar banco e usuário:

``` bash
sudo mysql -u root -p
```

``` sql
CREATE DATABASE aws_db;
CREATE USER 'phpuser'@'localhost' IDENTIFIED BY 'senha123';
GRANT ALL PRIVILEGES ON aws_db.* TO 'phpuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

------------------------------------------------------------------------

##  6. Instalar phpMyAdmin

``` bash
sudo apt install phpmyadmin -y
sudo phpenmod mysqli
sudo systemctl restart apache2
```

------------------------------------------------------------------------

##  7. Clonar Projeto

``` bash
cd /var/www/html
sudo rm index.html
sudo git clone https://github.com/chiclete1802/implementacao-aws.git implementacao-aws
sudo chown -R www-data:www-data /var/www/html/implementacao-aws
sudo chmod -R 755 /var/www/html/implementacao-aws
```

------------------------------------------------------------------------

##  8. Configurar SSL (HTTPS)

### Ativar módulo SSL

``` bash
sudo a2enmod ssl
sudo systemctl restart apache2
```

### Criar certificado autoassinado

``` bash
sudo mkdir -p /etc/ssl/private
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/apache-selfsigned.key -out /etc/ssl/certs/apache-selfsigned.crt
```

### Configurar VirtualHost SSL

Edite o arquivo:

``` bash
sudo nano /etc/apache2/sites-available/default-ssl.conf
```

Adicione/ajuste:

``` apache
<IfModule mod_ssl.c>
    <VirtualHost *:443>
        ServerAdmin admin@meuprojeto.com
        ServerName meuprojeto.com
        DocumentRoot /var/www/html/implementacao-aws

        SSLEngine on
        SSLCertificateFile /etc/ssl/certs/apache-selfsigned.crt
        SSLCertificateKeyFile /etc/ssl/private/apache-selfsigned.key

        <Directory /var/www/html/implementacao-aws>
            AllowOverride All
            Require all granted
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/ssl_error.log
        CustomLog ${APACHE_LOG_DIR}/ssl_access.log combined
    </VirtualHost>
</IfModule>
```

Ativar site SSL:

``` bash
sudo a2ensite default-ssl.conf
sudo systemctl reload apache2
```

Liberar porta 443 no firewall:

``` bash
sudo ufw allow 443/tcp
```

------------------------------------------------------------------------

##  9. Testar

-   **HTTP:** `http://ec2-3-90-255-235.compute-1.amazonaws.com/implementacao-aws/index.html`
-   **HTTPS:** `https://ec2-3-90-255-235.compute-1.amazonaws.com/implementacao-aws/index.html`

------------------------------------------------------------------------

