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

**Or you can copy the contents of the `.env.example` file, set the variables and add the contents through the "Add Secret File" button (the name of the added file is `.env`)**


### 4. Wait for the deployment process to complete and enjoy the result!


---
## Deploy on production

### 1. Install php, Apache, Nginx (and setup for using it as reverse proxy), Supervisor and Composer on the host


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


### 4. Setting up Laravel
**Copying the env file from the example**
```bash
cp .env.example .env
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


### 5. Install Composer & NPM dependencies
```bash
composer install
npm install
```


### 6. Execute DB migrations and actions
```bash
php artisan migrate:refresh && php artisan actions && php artisan optimize:clear
```


### 7. Setting up Supervisor
**Copying the configuration files for Supervisor**
```bash
cp ./etc/Supervisor/* /etc/supervisor/conf.d/
```

**Restart Supervisor to initialize new configs**
```bash
supervisorctl reread
supervisorctl update
supervisorctl status
```


### 8. Setting up cron
**We execute the specified command and, after selecting the editor, paste the contents of the ./etc/Crontab/beokiz-cron file into it**
```bash
crontab -u www-data -e
```


### 9. Building the app
```bash
npm run production
```


### 10. Setting up Nginx and Apache
**Copying configuration files**
```bash
cp ./etc/Apache/beokiz.conf /etc/apache2/sites-available
cp ./etc/Nginx/beokiz.conf /etc/nginx/sites-available
```

**Customize if needed. For example, if several sites will be through the Nginx proxy, then we change the port from 8080 to some other one in the configs (for example, 8081). After that, add it to the /etc/apache2/ports.conf file**

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


### 11. Finishing app setup
```bash
php artisan optimize:clear
php artisan gk:supervisor restart
chown -R www-data:www-data ./
```


### 12. Trying to access the app
```
https://beokiz-webportal.onrender.com/
```
