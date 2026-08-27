# 🚀 AreaKerja Web

<p align="center">
  <strong>Platform Karier & Rekrutmen Berbasis Web</strong>
</p>

<p align="center">
  Menghubungkan pencari kerja, perusahaan, dan talent dalam satu ekosistem digital.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tailwind%20CSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Flowbite-UI-1C64F2?style=flat-square" alt="Flowbite">
  <img src="https://img.shields.io/badge/API-REST-00A86B?style=flat-square" alt="REST API">
  <img src="https://img.shields.io/badge/Authentication-Secure-16A34A?style=flat-square" alt="Authentication">
  <img src="https://img.shields.io/badge/Status-In%20Development-F39C12?style=flat-square" alt="Development">
</p>

---

# 📖 About

**AreaKerja Web** adalah platform berbasis web yang dirancang untuk mempertemukan **pencari kerja** dengan **perusahaan** dalam satu ekosistem karier.

Platform ini menyediakan fitur untuk mencari lowongan, membuat dan mengelola profil, mengirim lamaran, mengelola CV, serta membantu perusahaan dalam mengelola proses rekrutmen.

AreaKerja Web juga menjadi salah satu bagian dari ekosistem AreaKerja yang nantinya terintegrasi dengan **AreaKerja Mobile** melalui REST API.

---

# 🎯 Vision

> **Connecting Talent with Opportunity.**

AreaKerja bertujuan menciptakan platform yang membantu:

* 👤 Pencari kerja menemukan peluang yang sesuai.
* 🏢 Perusahaan menemukan kandidat yang tepat.
* 🎯 Talent mendapatkan peluang yang relevan.
* 🌐 Pengguna mengakses layanan AreaKerja melalui web maupun mobile.

---

# 👥 User Roles

AreaKerja memiliki beberapa role pengguna dengan tanggung jawab yang berbeda.

```text
                         AreaKerja
                             │
              ┌──────────────┼──────────────┐
              │              │              │
              ▼              ▼              ▼
           Job Seeker      Company        Admin
              │              │              │
              ▼              ▼              ▼
          Find Jobs       Post Jobs      Manage
              │              │              │
              ▼              ▼              ▼
            Apply        Candidates      Platform
```

---

## 👤 Job Seeker

Pencari kerja dapat:

* 🔐 Register & Login
* 👤 Mengelola profil
* 📄 Mengelola CV
* 🎓 Mengelola pendidikan
* 💼 Mengelola pengalaman
* 🛠️ Mengelola skill
* 🔍 Mencari lowongan
* 🎯 Filter lowongan
* 📋 Melihat detail pekerjaan
* ❤️ Menyimpan lowongan
* 📤 Mengirim lamaran
* 📊 Melihat status lamaran
* 📜 Melihat riwayat lamaran
* 💡 Membaca tips pekerjaan

---

## 🏢 Company

Perusahaan dapat:

* 🔐 Register & Login
* 🏢 Mengelola profil perusahaan
* 📢 Membuat lowongan
* ✏️ Mengedit lowongan
* 🗑️ Menghapus lowongan
* 📋 Mengelola lowongan
* 👥 Melihat kandidat
* 📄 Melihat CV kandidat
* 📊 Mengelola proses rekrutmen

---

## 👨‍💼 Admin

Admin bertanggung jawab terhadap pengelolaan platform.

Fitur utama:

* 📊 Dashboard Admin
* 👥 User Management
* 🔐 Role & Permission
* 🏢 Company Management
* 👤 Job Seeker Management
* 📢 Job Management
* 📂 Category Management
* 💡 Content / Tips Management
* ⚙️ Application Settings
* 📈 Monitoring platform

---

# ✨ Core Features

## 🔐 Authentication

* Login
* Register
* Logout
* Password management
* Role-based access
* Authorization
* Session management

---

## 🔍 Job Search

Pengguna dapat:

* Melihat daftar lowongan.
* Mencari berdasarkan keyword.
* Filter berdasarkan kategori.
* Filter berdasarkan lokasi.
* Filter berdasarkan tipe pekerjaan.
* Melihat detail pekerjaan.

---

## 📢 Job Management

Perusahaan dapat:

```text
Create
  ↓
Publish
  ↓
Manage
  ↓
Edit
  ↓
Close
```

Lowongan memiliki informasi seperti:

* Job title
* Company
* Description
* Requirements
* Location
* Employment type
* Salary
* Category
* Deadline

---

# 📤 Application System

Alur lamaran:

```text
Job Seeker
    │
    ▼
Search Job
    │
    ▼
Job Detail
    │
    ▼
Apply
    │
    ▼
Company Receives Application
    │
    ▼
Review Candidate
    │
    ▼
Update Status
```

Status lamaran dapat berkembang menjadi:

```text
Applied
   ↓
Review
   ↓
Shortlisted
   ↓
Interview
   ↓
Accepted / Rejected
```

---

# 📄 CV & Profile

Job seeker dapat membangun profil profesional yang terdiri dari:

```text
Profile
│
├── Personal Information
├── About
├── Education
├── Experience
├── Skills
├── Portfolio
└── CV
```

Profil tersebut dapat digunakan sebagai informasi kandidat ketika melamar pekerjaan.

---

# 🏗️ System Architecture

AreaKerja Web menggunakan pendekatan **Layered Architecture** dengan pemisahan tanggung jawab antara:

```text
Browser
   │
   ▼
Routes
   │
   ▼
Controller
   │
   ▼
Service
   │
   ▼
Repository
   │
   ▼
Model / Eloquent
   │
   ▼
MySQL
```

Untuk API:

```text
Mobile
   │
   ▼
REST API
   │
   ▼
API Controller
   │
   ▼
Service
   │
   ▼
Repository
   │
   ▼
Eloquent
   │
   ▼
MySQL
```

---

# 🧱 Backend Architecture

Project menggunakan pola:

### Controller

Menangani HTTP request dan response.

```text
Controller
    ↓
Request Validation
    ↓
Service
```

### Service

Menangani business logic.

```text
Service
    ↓
Business Logic
    ↓
Repository
```

### Repository

Menangani akses data.

```text
Repository
    ↓
Eloquent
    ↓
Database
```

### Request

Digunakan untuk validasi input.

```text
Form Request
    ↓
Validation
    ↓
Controller
```

---

# 📂 Project Structure

```text
AreaKerja_web/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Requests/
│   │   └── Resources/
│   │
│   ├── Models/
│   │
│   ├── Repositories/
│   │   ├── Contracts/
│   │   └── Eloquent/
│   │
│   └── Services/
│
├── bootstrap/
│
├── config/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   ├── web.php
│   └── api.php
│
├── storage/
│
├── tests/
│
├── .env
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

---

# 🧩 Main Application Modules

```text
app/
│
├── Authentication
│
├── User Management
│
├── Role & Permission
│
├── Job Management
│
├── Company
│
├── Job Seeker
│
├── Application
│
├── Profile
│
├── CV
│
├── Category
│
├── Tips
│
├── Notification
│
└── API
```

---

# 🌐 REST API

AreaKerja Web juga menyediakan REST API yang digunakan oleh aplikasi mobile.

Base API:

```text
/api/v1
```

Contoh endpoint:

```text
/api/v1/auth/login
/api/v1/auth/register
/api/v1/auth/logout

/api/v1/profile
/api/v1/lowongan
/api/v1/lowongan/{id}

/api/v1/lamaran
/api/v1/lamaran/{id}

/api/v1/perusahaan
/api/v1/perusahaan/profile
```

Endpoint dapat berkembang mengikuti kebutuhan aplikasi.

---

# 🔗 Web & Mobile Integration

AreaKerja Web berperan sebagai salah satu sumber backend untuk AreaKerja Mobile.

```text
                  ┌──────────────────┐
                  │   AreaKerja Web  │
                  │                  │
                  │ Laravel Backend  │
                  └────────┬─────────┘
                           │
                    REST API / JSON
                           │
              ┌────────────┴────────────┐
              │                         │
              ▼                         ▼
       ┌──────────────┐         ┌──────────────┐
       │ AreaKerja    │         │ AreaKerja    │
       │ Web Client   │         │ Mobile       │
       └──────────────┘         └──────────────┘
```

Dengan pendekatan ini, business logic dan data dapat digunakan oleh berbagai client.

---

# 🛠️ Technology Stack

## Backend

| Technology                 | Purpose                        |
| -------------------------- | ------------------------------ |
| **Laravel**                | Web Framework & REST API       |
| **PHP**                    | Backend Programming            |
| **MySQL**                  | Database                       |
| **Eloquent ORM**           | Database abstraction           |
| **Laravel Validation**     | Request validation             |
| **Laravel Authentication** | Authentication & authorization |

## Frontend

| Technology       | Purpose                 |
| ---------------- | ----------------------- |
| **Blade**        | Server-side templating  |
| **Tailwind CSS** | Styling                 |
| **Flowbite**     | UI component library    |
| **JavaScript**   | Client-side interaction |
| **Vite**         | Frontend asset bundling |

---

# 🎨 UI & Design

AreaKerja Web menggunakan:

**Tailwind CSS + Flowbite**

Tujuannya:

* Responsive design
* Consistent UI
* Reusable components
* Modern dashboard
* Mobile-friendly layout

Struktur UI:

```text
Layout
 │
 ├── Navbar
 │
 ├── Sidebar
 │
 ├── Content
 │
 └── Footer
```

---

# 🔐 Security

AreaKerja Web menerapkan beberapa prinsip keamanan:

* Authentication
* Authorization
* Role-based access control
* Form Request validation
* CSRF protection
* Password hashing
* API authentication
* Input validation
* Environment variables
* Database access melalui Eloquent

### Environment Security

Jangan commit:

```text
.env
```

Gunakan:

```text
.env.example
```

sebagai template.

---

# 💻 Requirements

Pastikan environment sudah memiliki:

* PHP
* Composer
* Laravel
* MySQL
* Node.js
* NPM
* Git

Cek PHP:

```bash
php -v
```

Cek Composer:

```bash
composer -V
```

Cek Node:

```bash
node -v
```

Cek NPM:

```bash
npm -v
```

---

# ⚡ Installation

## 1. Clone Repository

```bash
git clone <repository-url>
```

Masuk ke project:

```bash
cd AreaKerja_web
```

---

## 2. Install PHP Dependencies

```bash
composer install
```

---

## 3. Install Frontend Dependencies

```bash
npm install
```

---

## 4. Environment

Copy:

```text
.env.example
```

menjadi:

```text
.env
```

Kemudian konfigurasi database:

```env
APP_NAME=AreaKerja
APP_ENV=local
APP_KEY=
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=areakerja
DB_USERNAME=root
DB_PASSWORD=
```

---

## 5. Generate Application Key

```bash
php artisan key:generate
```

---

## 6. Database Migration

```bash
php artisan migrate
```

Jika project memiliki seeder:

```bash
php artisan db:seed
```

atau:

```bash
php artisan migrate --seed
```

---

## 7. Build Frontend

Development:

```bash
npm run dev
```

Production:

```bash
npm run build
```

---

## 8. Run Laravel

```bash
php artisan serve
```

Default:

```text
http://127.0.0.1:8000
```

---

# 🧪 Testing & Code Quality

Menjalankan test:

```bash
php artisan test
```

Membersihkan cache:

```bash
php artisan optimize:clear
```

Melihat route:

```bash
php artisan route:list
```

---

# 🌿 Git Workflow

Project menggunakan workflow:

```text
main
 │
 └── develop
      │
      ├── feature/auth
      ├── feature/api/mobile
      ├── feature/job
      ├── feature/company
      ├── feature/profile
      └── feature/admin
```

### Branch

| Branch      | Purpose             |
| ----------- | ------------------- |
| `main`      | Production          |
| `develop`   | Development         |
| `feature/*` | Feature development |
| `fix/*`     | Bug fixing          |

---

# 🔀 Feature Development

Mulai dari `develop`:

```bash
git checkout develop
git pull origin develop
```

Buat feature branch:

```bash
git checkout -b feature/nama-feature
```

Contoh:

```bash
git checkout -b feature/api/mobile
```

---

# 📝 Commit Convention

Gunakan format:

```text
type: description
```

Contoh:

```bash
git commit -m "feat: add job listing API"
git commit -m "feat: add company profile"
git commit -m "fix: fix authentication middleware"
git commit -m "refactor: improve repository structure"
git commit -m "docs: update README"
```

### Commit Type

| Type       | Description        |
| ---------- | ------------------ |
| `feat`     | New feature        |
| `fix`      | Bug fix            |
| `refactor` | Code restructuring |
| `docs`     | Documentation      |
| `test`     | Testing            |
| `chore`    | Maintenance        |

---

# 🔄 Pull Request

Development flow:

```text
develop
   │
   ▼
Feature Branch
   │
   ▼
Development
   │
   ▼
Testing
   │
   ▼
Commit
   │
   ▼
Push
   │
   ▼
Pull Request
   │
   ▼
Code Review
   │
   ▼
Merge → develop
```

Jangan melakukan development langsung pada:

```text
main
```

---

# 👥 Development Team

AreaKerja dikembangkan menggunakan pembagian tanggung jawab berdasarkan module.

### Backend / API

Fokus:

* Laravel
* REST API
* Database
* Authentication
* Business Logic
* Repository
* Service
* API Integration

### Frontend / Web

Fokus:

* Blade
* Tailwind CSS
* Flowbite
* Dashboard
* UI/UX
* Form
* Responsive design

### Mobile

Fokus:

* Flutter
* API integration
* BLoC
* Mobile UI
* Authentication
* Job seeker features
* Company features

---

# 📊 Development Roadmap

## Phase 1 — Foundation

* [x] Laravel project
* [x] Database
* [x] Authentication foundation
* [x] UI framework
* [x] Role & permission foundation
* [ ] API standardization
* [ ] API documentation

## Phase 2 — Job Seeker

* [ ] Job seeker dashboard
* [ ] Profile
* [ ] CV
* [ ] Education
* [ ] Experience
* [ ] Skills
* [ ] Job search
* [ ] Job filter
* [ ] Saved jobs
* [ ] Apply job
* [ ] Application history

## Phase 3 — Company

* [ ] Company dashboard
* [ ] Company profile
* [ ] Create job
* [ ] Edit job
* [ ] Delete job
* [ ] Job management
* [ ] Applicant management
* [ ] Candidate detail

## Phase 4 — Admin

* [ ] Admin dashboard
* [ ] User management
* [ ] Company management
* [ ] Job management
* [ ] Category management
* [ ] Role & permission
* [ ] Platform settings
* [ ] Content management
* [ ] Statistics

## Phase 5 — Mobile Integration

* [ ] Authentication API
* [ ] Job API
* [ ] Application API
* [ ] Profile API
* [ ] Company API
* [ ] CV API
* [ ] Notification API

## Phase 6 — Advanced Features

* [ ] Talent Hunter
* [ ] Job recommendation
* [ ] Push notification
* [ ] Payment
* [ ] Subscription
* [ ] Coin system
* [ ] Analytics
* [ ] Advanced recruitment workflow

---

# 📸 Screenshots

Screenshot dapat diletakkan pada:

```text
docs/
├── homepage.png
├── login.png
├── register.png
├── job-list.png
├── job-detail.png
├── company.png
├── dashboard.png
└── admin.png
```

Contoh penggunaan:

```html
<p align="center">
  <img src="docs/homepage.png" width="800">
</p>
```

---

# 🗺️ System Overview

```text
                         AREA KERJA
                             │
          ┌──────────────────┼──────────────────┐
          │                  │                  │
          ▼                  ▼                  ▼
      Job Seeker          Company             Admin
          │                  │                  │
          ▼                  ▼                  ▼
     Find Jobs           Post Jobs          Manage
          │                  │                  │
          ▼                  ▼                  ▼
       Apply             Candidates          Users
          │                  │                  │
          └──────────┬───────┘                  │
                     │                          │
                     ▼                          ▼
               Recruitment                 Platform
                  Process                   Control
                     │
                     ▼
              Talent & Opportunity
```

---

# 🔮 Future Vision

AreaKerja tidak hanya ditargetkan sebagai website lowongan kerja.

Ekosistem AreaKerja dirancang untuk berkembang menjadi:

```text
                    AREA KERJA
                        │
        ┌───────────────┼───────────────┐
        │               │               │
        ▼               ▼               ▼
     Job Seeker      Company          Talent
        │               │               │
        └───────────────┼───────────────┘
                        │
                        ▼
                 Matching System
                        │
                        ▼
                 Job Opportunity
                        │
                        ▼
                   Recruitment
```

Dengan pengembangan berkelanjutan, AreaKerja dapat mencakup:

* Job Marketplace
* Talent Marketplace
* Recruitment Management
* Talent Hunter
* Career Development
* Job Recommendation
* Subscription
* Recruitment Analytics

---

# 🤝 Contribution

Kontribusi dilakukan melalui Pull Request.

Sebelum melakukan push:

```bash
composer install
npm install

php artisan optimize:clear

php artisan test

npm run build
```

Pastikan:

* Tidak ada error.
* Tidak ada credential yang ter-commit.
* Migration berjalan dengan baik.
* API tetap kompatibel dengan Mobile.
* UI responsive.
* Business logic berada pada layer yang sesuai.

---

# 🐛 Bug Report

Saat melaporkan bug, sertakan:

```text
1. Deskripsi masalah
2. Langkah reproduce
3. Expected result
4. Actual result
5. Screenshot
6. Browser
7. OS
8. PHP version
9. Laravel version
```

---

# 📌 Important Notes

### Backend API adalah Shared Resource

Karena AreaKerja Web juga menyediakan API untuk Mobile, perubahan pada:

```text
routes/api.php
```

atau response API harus diperhatikan agar tidak merusak aplikasi Mobile.

Sebelum mengubah API:

```text
Check Existing Endpoint
        ↓
Check Mobile Usage
        ↓
Update API
        ↓
Test API
        ↓
Update Mobile if Needed
```

### Database Changes

Perubahan database harus menggunakan migration.

Jangan mengubah database production secara manual tanpa migration.

Contoh:

```bash
php artisan make:migration add_column_to_users_table
```

Kemudian:

```bash
php artisan migrate
```

---

# 📄 License

Project **AreaKerja Web** dikembangkan untuk kebutuhan pengembangan platform AreaKerja.

---

<p align="center">

## 🚀 AreaKerja

### Connecting Talent with Opportunity

**Web • Mobile • API**

Built with ❤️ using **Laravel + Flutter**

</p>
