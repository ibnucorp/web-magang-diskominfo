# Web Magang Diskominfo Palembang

A website designed to digitize the internship application process, featuring tools for submitting internship applications, monitoring application status, and printing response letters.
---

## Prerequisites

Before you begin, ensure you have the following installed on your local machine:
**Laravel** 12
* **PHP** 8.2
* **Filament** 3.3
* **SQLite**

---

## Installation & Setup Instructions

Follow these steps to get your development environment up and running:
```bash
git clone [https://github.com/ibnucorp/web-magang-diskominfo.git](https://github.com/ibnucorp/web-magang-diskominfo.git)

cd web-magang-diskominfo

composer install

cp .env.example .env

```
### Configure environtment variables
Only if using mysql, it is sqlite by default
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### Generate app key
```
php artisan key:generate
```

### Run migrations and seed dummy data
```
php artisan migrate:fresh --seed
```

### Start local development server
```
php artisan serve
```

## Dummy Login

### Admin
username : admin
password : admin

### Kepala Dinas
username : kadin
password : kadin

### Peserta
username : aditya
password : aditya