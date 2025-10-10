
# Pilketos Web App

Project ini dibangun menggunakan Laravel 12 dengan struktur service-based (Controller, Service, Model) dan autentikasi menggunakan Laravel Sanctum.

---

## ⚙️ Requirements

Pastikan kamu sudah menginstal:
- PHP >= 8.2  
- Composer  
- MySQL  
- Git  

---

## 🚀 Cara Clone dan Setup Project

1. **Clone repository**
   ```bash
   git clone https://github.com/Altraaa/pilketos-voting-app.git
   cd pilketos-voting-app

2. **Install Depedency**
   ```bash
   composer install

3. **Copy file environment**
   ```bash
   cp .env.example .env

4. **Generate Application Key**
   ```bash
   php artisan key:generate

5. **Atur konfigurasi Database**
   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=root
   DB_PASSWORD=

6. **Jalankan migrasi Database**
   ```bash
   php artisan migrate

7. **Jalankan Seeder data (optional)**
   ```bash
   php artisan db:seed

9. **Jalankan server lokal**
   ```bash
   php artisan serve

8. **Server akan aktif di localhost:8000**