# Perintah Umum (Run Commands)

Berikut adalah daftar perintah yang sering digunakan dalam pengembangan proyek ini.

### 1. Menjalankan Aplikasi (Development)
Menjalankan server backend (Yii) dan frontend (Vite/NPM) secara bersamaan.
```bash
npx concurrently "php yii serve" "npm run dev"
```

### 2. Database
#### Membuat Database
```bash
php yii database/create
```

#### Impor Database
```bash
php yii database/import
```

#### Hapus Database
```bash
php yii database/drop
```

### 3. RBAC (Role Based Access Control)
Inisialisasi Role dan Permission.
```bash
php yii rbac/init
```