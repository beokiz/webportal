# BeoKiz App

---
## Deploy on render.com

### 1. [Create](https://dashboard.render.com/new/database) a new PostgreSQL database on Render and copy the internal DB URL to use below.



### 2. [Create](https://dashboard.render.com/create?type=web) a new Web Service on Render, giving Render permission to access your repo from GitHub or GitLab connected account.


### 3. Select branch, select 'Docker' for the runtime, and add the following environment variables under the Advanced section:
| KEY           | VALUE                                                             |
| ------------- | ----------------------------------------------------------------- |
| DATABASE_URL  | The **internal database URL** for the database you created above. |
| DB_CONNECTION | `pgsql`                                                           |
| APP_KEY       | Copy the output of `php artisan key:generate --show`              |

Or you can copy the contents of the `.env.example` file, set the variables and add the contents through the "Add Secret File" button (the name of the added file is `.env`)



### 4. Wait for the deployment process to complete and enjoy the result!


---
## Deploy on production

### 1. Install git, php, MySQL (MariaDB), Apache, Nginx (and setup for using it as reverse proxy), Supervisor and Composer on the host
**Install main components**
```bash
apt install curl zip unzip apache2 net-tools git nginx mariadb-server mariadb-client supervisor
apt install php php-fpm php-common php-mysql php-bcmath php-curl php-gd php-cli php-mbstring php-xml php-simplexml php-zip php-json
```


**Complete MariaDB installation**
```bash
mysql_secure_installation
~ Switch to unix_socket authentication [Y/n]: n
~ Set root password / Change the root password? [Y/n]: y
~ Remove anonymous users? [Y/n]: y
~ Disallow root login remotely? [Y/n]: y
~ Remove test database and access to it? [Y/n]: Y
~ Reload privilege tables now? [Y/n]: Y
```


**Setup UFW**
```bash
ufw allow 22
ufw allow 80/tcp
ufw allow 443/tcp
ufw allow 8080/tcp
ufw enable
~ Command may disrupt existing ssh connections. Proceed with operation (y|n)? y
```


**Install NVM (Node version manager)**
```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash
source ~/.bashrc
nvm install v18.16.0
nvm use 18.16.0
```


**Install Composer**
```bash
mkdir /tmp/composer
cd /tmp/composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
ln -s /usr/local/bin/composer /usr/bin/composer
```



### 2. Create a MySQL DB and a user for the project
**Login to MySQL as a root**
```bash
mysql -u root -p
```

**We execute the specified commands to create a DB and a user for it**
```mysql
CREATE DATABASE `beokiz` CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER 'beokiz'@'localhost' IDENTIFIED BY '<password>';
GRANT ALL PRIVILEGES ON `beokiz`.* TO 'beokiz'@'localhost';
FLUSH PRIVILEGES;
```


### 3. Clone the project from the repository and go to the project folder
```bash
cd /var/www/
git clone git@github.com:beokiz/webportal.git html
cd html
```


### 4. Setting up Laravel environment
**Copying the env file from the example**
```bash
cp .env.example .env
```

**Generate app key**
```bash
php artisan key:generate
```

**Setting up common params**
```env
APP_URL=your-site.domain
APP_SUPPORT_EMAIL=noreply@your-site.domain
```

**Setting up data for working with the database**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=beokiz
DB_USERNAME=beokiz
DB_PASSWORD=<password>
```

**Setting up email params**
```env
MAIL_MAILER=<type>
MAIL_HOST=<host>
MAIL_PORT=<port>
MAIL_USERNAME=<username>
MAIL_PASSWORD="<password>"
MAIL_ENCRYPTION=<type>
```
**MAIL_MAILER=\<type\>** - This parameter indicates which mail driver will be used for sending emails. Here, smtp means that SMTP (Simple Mail Transfer Protocol) will be used for email delivery (example: MAIL_MAILER=smtp)\
**MAIL_HOST=\<host\>** - This requires specifying the SMTP server host that will be used for sending emails. It could be the address of an external email service, for example, smtp.gmail.com for Gmail (example: MAIL_HOST=smtp.gmail.com)\
**MAIL_PORT=\<port\>** - This parameter sets the port that will be used to connect to the SMTP server. Commonly used ports include 587 (for secure TLS connection) and 465 (for SSL connection) (example: MAIL_PORT=465)\
**MAIL_USERNAME=\<username\>** - Here you specify the username (or email address) used for authentication on the SMTP server. It should be a valid email provided by the email sending service (example: MAIL_USERNAME=your_email@gmail.com)\
**MAIL_PASSWORD="\<password\>"** - The password for the account used for authentication on the SMTP server. It's important to keep this parameter secure and not share it (example: MAIL_PASSWORD="y0ur_p@s5w0rd")\
**MAIL_ENCRYPTION=\<type\>** - This parameter defines the encryption method that will be used when sending emails. ssl and tls are the most commonly used options. The choice between ssl and tls depends on the requirements of the SMTP server (example: MAIL_ENCRYPTION=ssl
)




### 5. Install Composer & NPM dependencies
```bash
composer install
~ Do not run Composer as root/super user! See https://getcomposer.org/root for details Continue as root/super user [yes]? yes
npm install
```


### 6. Execute DB migrations and actions
```bash
php artisan migrate:refresh && php artisan actions && php artisan optimize:clear
```



### 7. Setting up cron
**We execute the specified command and, after selecting the editor, paste the contents of the ./etc/Crontab/beokiz-cron file into it**
```bash
crontab -u www-data -e
```



### 8. Setting up Nginx and Apache
**Copying configuration files**
```bash
cp ./etc/Apache/beokiz.conf /etc/apache2/sites-available
cp ./etc/Nginx/beokiz.conf /etc/nginx/sites-available
```

Customize if needed. For example, if several sites will be through the Nginx proxy, then we change the port from 8080 to some other one in the configs (for example, 8081). After that, add it to the /etc/apache2/ports.conf file.

**Create a links to configuration files**
```bash
ln -s /etc/apache2/sites-available/beokiz.conf /etc/apache2/sites-enabled/beokiz.conf
ln -s /etc/nginx/sites-available/beokiz.conf /etc/nginx/sites-enabled/beokiz.conf
```

**Restarting Nginx and Apache**
```bash
systemctl restart apache2
systemctl restart nginx
```


### 9. Finishing app setup
```bash
php artisan optimize:clear
chown -R www-data:www-data ./
```


### 10. Trying to access the app
```
https://beokiz.de
```
