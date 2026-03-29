# SIMRS API

Backend API untuk integrasi **Sistem Informasi Manajemen Rumah Sakit (SIMRS)** dengan layanan **BPJS VClaim**, **SatuSehat**, **Antrol RS**, dan **AI Clinical Assistant** berbasis **Laravel 12**.

## ✨ Fitur & Modul

### 1) Modul BPJS VClaim (`/api/v1/bpjs`)
- Pencarian peserta
- Kelola SEP (cari, insert, update, hapus, riwayat)
- Monitoring kunjungan dan klaim
- Referensi master BPJS:
  - poli
  - diagnosa
  - faskes
  - dokter DPJP
  - provinsi
  - kabupaten
  - kecamatan
  - prosedur
- Surat Kontrol & SPRI

### 2) Modul AI Clinical Assistant (`/api/v1/ai`)
Tersedia **35 endpoint AI** untuk membantu triase, prediksi risiko klinis, deteksi anomali, hingga dukungan keputusan operasional rumah sakit.

### 3) Modul Antrol RS (`/api/v1/antrol`)
- Ringkasan antrean RS
- Master poli
- Master dokter
- Jadwal dokter

### 4) Modul SatuSehat (`/api/v1/satu-sehat`)
- Generate token OAuth2
- Kirim data encounter FHIR

### 5) Authentication & User Management (`/api/v1/auth`, `/api/v1/users`)
- Register, login, profil user, logout
- Daftar user dan role
- Ubah role user

### 6) Standarisasi Response
Semua endpoint menggunakan format JSON konsisten:
- `metaData.code`
- `metaData.message`
- `response`

---

## 🏗️ Tech Stack

- PHP 8.2+
- Laravel 12
- Laravel Sanctum
- Spatie Permission
- Vite
- SQLite / MySQL

---

## 🚀 Instalasi

```bash
git clone https://github.com/ahmadfauzirahman99/simrs-api.git
cd simrs-api
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

Jalankan aplikasi:

```bash
php artisan serve
npm run dev
```

Atau mode cepat:

```bash
composer run dev
```

---

## ⚙️ Konfigurasi Environment

Contoh `.env` minimum:

```env
APP_NAME="SIMRS API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
```

Konfigurasi BPJS VClaim:

```env
BPJS_V3_API_VER=2
BPJS_V3_BASE_URL=URL_BPJS_V3
BPJS_V3_CONS_ID=CONS_ID_BPJS_V3
BPJS_V3_SECRET_KEY=SECRET_KEY_BPJS_V3
BPJS_V3_USER_KEY=USER_KEY_BPJS_V3
```

Konfigurasi SatuSehat (opsional sesuai integrasi):

```env
SATUSEHAT_CLIENT_ID=your_client_id
SATUSEHAT_CLIENT_SECRET=your_client_secret
SATUSEHAT_BASE_URL=https://api-satusehat.kemkes.go.id
```

---

## 📡 Base API

Base URL lokal:

```text
http://127.0.0.1:8000/api/v1
```

Health check:

```http
GET /api/v1
```

---

## 📚 Ringkasan Endpoint

### BPJS (`/api/v1/bpjs`)

- `GET /peserta`
- `GET /sep`
- `GET /sep-riwayat`
- `POST /sep`
- `PUT /sep`
- `DELETE /sep`
- `GET /monitoring-kunjungan`
- `GET /monitoring-klaim`
- `GET /referensi/poli`
- `GET /referensi/diagnosa`
- `GET /referensi/faskes`
- `GET /referensi/dokter-dpjp`
- `GET /referensi/provinsi`
- `GET /referensi/kabupaten`
- `GET /referensi/kecamatan`
- `GET /referensi/prosedur`
- `POST /surat-kontrol/insert`
- `POST /surat-kontrol/update`
- `POST /spri/insert`
- `POST /spri/update`

### AI Clinical Assistant (`/api/v1/ai`)

- `POST /triage-suggestion`
- `POST /patient-risk-score`
- `POST /readmission-prediction`
- `POST /bed-demand-forecast`
- `POST /medication-interaction-check`
- `POST /duplicate-record-detection`
- `POST /referral-recommendation`
- `POST /queue-estimate`
- `POST /claim-anomaly-detection`
- `POST /clinical-summary`
- `POST /mortality-risk-estimate`
- `POST /sepsis-early-warning`
- `POST /stroke-risk-estimate`
- `POST /nutrition-risk-screening`
- `POST /fall-risk-assessment`
- `POST /infection-control-risk`
- `POST /surgery-readiness-check`
- `POST /icu-transfer-recommendation`
- `POST /ventilator-need-prediction`
- `POST /discharge-planning-score`
- `POST /length-of-stay-estimate`
- `POST /emergency-load-prediction`
- `POST /lab-critical-value-detection`
- `POST /antibiotic-suggestion`
- `POST /dehydration-risk-score`
- `POST /pressure-ulcer-risk`
- `POST /pediatric-dosage-check`
- `POST /dialysis-need-prediction`
- `POST /blood-transfusion-need`
- `POST /mental-health-screening`
- `POST /maternal-risk-assessment`
- `POST /neonatal-risk-assessment`
- `POST /outpatient-no-show-prediction`
- `POST /vaccine-eligibility-check`
- `POST /telemedicine-suitability`

### Antrol (`/api/v1/antrol`)

- `GET /antrean`
- `GET /poli`
- `GET /dokter`
- `GET /jadwal-dokter`

### Authentication & User Management

- `POST /auth/register`
- `POST /auth/login`
- `GET /auth/me` *(auth:sanctum)*
- `POST /auth/logout` *(auth:sanctum)*
- `GET /users` *(auth:sanctum)*
- `POST /users` *(auth:sanctum)*
- `PUT /users/{user}/role` *(auth:sanctum)*
- `GET /roles` *(auth:sanctum)*

### Utility Catalog

- `GET /dashboard`
- `GET /apotek`
- `GET /vclaim`
- `GET /system-info`

### SatuSehat (`/api/v1/satu-sehat`)

- `GET /token`
- `POST /encounter/send`

---

## 🧪 Pengujian

```bash
composer test
```

> Jika dependency belum terpasang, jalankan `composer install` terlebih dahulu.

---

## 🧾 Format Response

Success:

```json
{
  "metaData": {
    "code": "200",
    "message": "Sukses"
  },
  "response": {}
}
```

Error:

```json
{
  "metaData": {
    "code": "400",
    "message": "Pesan error"
  },
  "response": {}
}
```

---

## 👨‍💻 Author

**Sobri**

- GitHub: [sobri3195](https://github.com/sobri3195)
- Email: [muhammadsobrimaulana31@gmail.com](mailto:muhammadsobrimaulana31@gmail.com)
- Website: [muhammadsobrimaulana.netlify.app](https://muhammadsobrimaulana.netlify.app)

---

## 📜 License

This project is licensed under the **MIT License**.
