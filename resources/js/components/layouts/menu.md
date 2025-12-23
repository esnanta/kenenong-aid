# MENU DASHBOARD
## Crowdsourced Disaster Management System

---

## 1. Tujuan Dokumen

Dokumen ini menjelaskan struktur menu dashboard aplikasi
**Crowdsourced Disaster Management System** yang disusun
berdasarkan **Role-Based Access Control (RBAC)**.

Menu dirancang untuk:
- Mendukung pendekatan bottom-up
- Menghindari akses yang menyesatkan
- Menjaga pemisahan tanggung jawab per role

---

## 2. Prinsip Umum

- Menu ditampilkan berdasarkan permission (`can()`)
- Role tidak ditampilkan secara eksplisit ke user
- Menu operasional berada di bagian atas
- Menu governance berada di bagian bawah

---

## 3. Struktur Menu per Role

### 3.1 Guest (Publik)

**Tujuan:** Transparansi & akuntabilitas

Menu:
- Data Bencana (view & report)
- Distribusi Bantuan (view & report)

Permission:
- disaster-index
- disaster-view
- disaster-report
- aidDistribution-index
- aidDistribution-view
- aidDistribution-report

---

### 3.2 Regular (Relawan)

**Tujuan:** Input & update data lapangan

Menu:
- Data Bencana
- Tempat Evakuasi
- Logistik & Bantuan
- Rute Akses
- Media Lapangan

Permission:
- transaction-index
- transaction-create
- transaction-update
- transaction-view

Batasan:
- Tidak boleh delete
- Tidak mengelola master data

---

### 3.3 Coordinator

**Tujuan:** Kurasi & validasi data

Menu:
- Verifikasi
- Voting Validasi
- Moderasi Bantuan
- Laporan Operasional

Permission tambahan:
- transaction-report
- verification-update
- aidDistribution-update

---

### 3.4 Admin

**Tujuan:** Tata kelola sistem

Menu:
- Master Data
- Administrasi User
- Role, Permission, Rule

Permission:
- master-*
- transaction-delete
- user-*

---

## 4. Catatan Implementasi

- Jangan menampilkan menu berdasarkan role string
- Gunakan permission check (`can()`)
- Menu ≠ fitur
- RBAC adalah sumber kebenaran utama

---

## 5. Penutup

Struktur menu ini dirancang agar:
- Sistem tetap inklusif
- Data tetap akurat
- Tata kelola tetap kuat

Dokumen ini wajib diperbarui jika terjadi perubahan RBAC.
