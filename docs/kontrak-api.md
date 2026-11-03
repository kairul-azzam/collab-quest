# Kontrak API - To-Do List

Dokumen ini adalah kesepakatan antara **Backend (BE)** dan **Frontend (FE)**.
Kalau ada perubahan, wajib disepakati berdua, dicatat di bagian **Riwayat Perubahan**, lalu di-commit.

## 1. Informasi Umum

| Item | Isi |
|------|-----|
| Base URL | `http://localhost:8000/api` |
| Format data | JSON |
| Autentikasi | Bearer Token (Laravel Sanctum) |
| Header wajib | `Accept: application/json` dan `Content-Type: application/json` |
| Header untuk endpoint terlindungi | `Authorization: Bearer {token}` |

## 2. Format Response

**Berhasil**

```json
{
  "success": true,
  "message": "Pesan singkat",
  "data": {}
}
```

**Gagal**

```json
{
  "success": false,
  "message": "Pesan error",
  "errors": {
    "field": ["Pesan validasi"]
  }
}
```

## 3. Kode Status

| Kode | Arti |
|------|------|
| 200 | OK (berhasil) |
| 201 | Created (data berhasil dibuat) |
| 401 | Unauthenticated (token tidak ada / salah) |
| 403 | Forbidden (bukan tugas milik sendiri) |
| 404 | Data tidak ditemukan |
| 422 | Validasi gagal |

## 4. Struktur Data

### User

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | integer | ID user |
| name | string | Nama user |
| email | string | Email user (unik) |

### Task

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | integer | ID tugas |
| title | string | Judul tugas (wajib, maks 255 karakter) |
| deadline | string (date) | Format `YYYY-MM-DD`, boleh kosong (`null`) |
| status | string | Hanya `todo` atau `done` |

## 5. Daftar Endpoint

| No | Method | URL | Auth | Fungsi |
|----|--------|-----|------|--------|
| 1 | POST | `/register` | Tidak | Daftar akun |
| 2 | POST | `/login` | Tidak | Login |
| 3 | GET | `/profile` | Ya | Lihat profil |
| 4 | POST | `/logout` | Ya | Logout |
| 5 | GET | `/tasks` | Ya | Daftar tugas |
| 6 | POST | `/tasks` | Ya | Tambah tugas |
| 7 | GET | `/tasks/{id}` | Ya | Detail tugas |
| 8 | PUT | `/tasks/{id}` | Ya | Ubah judul / deadline |
| 9 | PATCH | `/tasks/{id}/status` | Ya | Ubah status todo / done |
| 10 | DELETE | `/tasks/{id}` | Ya | Hapus tugas |

---

## 6. Detail Endpoint

### 6.1 Register

`POST /register`

**Request body**

```json
{
  "name": "Budi",
  "email": "budi@example.com",
  "password": "rahasia123",
  "password_confirmation": "rahasia123"
}
```

| Field | Wajib | Aturan |
|-------|-------|--------|
| name | Ya | string, maks 255 |
| email | Ya | format email, unik |
| password | Ya | minimal 8 karakter |
| password_confirmation | Ya | sama dengan password |

**Response 201**

```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Budi",
      "email": "budi@example.com"
    }
  }
}
```

**Response 422**

```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "email": ["Email sudah terdaftar"]
  }
}
```

---

### 6.2 Login

`POST /login`

**Request body**

```json
{
  "email": "budi@example.com",
  "password": "rahasia123"
}
```

**Response 200**

```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "1|abcdefghijklmnopqrstuvwxyz",
    "user": {
      "id": 1,
      "name": "Budi",
      "email": "budi@example.com"
    }
  }
}
```

**Response 401** (email atau password salah)

```json
{
  "success": false,
  "message": "Email atau password salah"
}
```

---

### 6.3 Profil

`GET /profile`
Header: `Authorization: Bearer {token}`

**Response 200**

```json
{
  "success": true,
  "message": "Data profil",
  "data": {
    "id": 1,
    "name": "Budi",
    "email": "budi@example.com"
  }
}
```

**Response 401**

```json
{
  "message": "Unauthenticated."
}
```

---

### 6.4 Logout

`POST /logout`
Header: `Authorization: Bearer {token}`

**Response 200**

```json
{
  "success": true,
  "message": "Logout berhasil"
}
```

---

### 6.5 Daftar Tugas

`GET /tasks`
Header: `Authorization: Bearer {token}`

Hanya menampilkan tugas milik user yang sedang login.

**Response 200**

```json
{
  "success": true,
  "message": "Daftar tugas",
  "data": [
    {
      "id": 1,
      "title": "Kerjakan LKPD Laravel",
      "deadline": "2026-11-10",
      "status": "todo"
    },
    {
      "id": 2,
      "title": "Belajar Git",
      "deadline": null,
      "status": "done"
    }
  ]
}
```

Jika belum ada tugas, `data` berisi array kosong `[]`.

---

### 6.6 Tambah Tugas

`POST /tasks`
Header: `Authorization: Bearer {token}`

**Request body**

```json
{
  "title": "Kerjakan LKPD Laravel",
  "deadline": "2026-11-10"
}
```

| Field | Wajib | Aturan |
|-------|-------|--------|
| title | Ya | string, maks 255 |
| deadline | Tidak | tanggal `YYYY-MM-DD` |

Status awal otomatis `todo`.

**Response 201**

```json
{
  "success": true,
  "message": "Tugas berhasil ditambahkan",
  "data": {
    "id": 3,
    "title": "Kerjakan LKPD Laravel",
    "deadline": "2026-11-10",
    "status": "todo"
  }
}
```

**Response 422**

```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "title": ["Judul wajib diisi"]
  }
}
```

---

### 6.7 Detail Tugas

`GET /tasks/{id}`
Header: `Authorization: Bearer {token}`

**Response 200**

```json
{
  "success": true,
  "message": "Detail tugas",
  "data": {
    "id": 3,
    "title": "Kerjakan LKPD Laravel",
    "deadline": "2026-11-10",
    "status": "todo"
  }
}
```

**Response 403** (tugas milik user lain)

```json
{
  "success": false,
  "message": "Kamu tidak boleh mengakses tugas ini"
}
```

**Response 404**

```json
{
  "success": false,
  "message": "Tugas tidak ditemukan"
}
```

---

### 6.8 Ubah Tugas

`PUT /tasks/{id}`
Header: `Authorization: Bearer {token}`

**Request body**

```json
{
  "title": "Kerjakan LKPD Laravel (revisi)",
  "deadline": "2026-11-12"
}
```

**Response 200**

```json
{
  "success": true,
  "message": "Tugas berhasil diubah",
  "data": {
    "id": 3,
    "title": "Kerjakan LKPD Laravel (revisi)",
    "deadline": "2026-11-12",
    "status": "todo"
  }
}
```

Kode error yang mungkin: 401, 403, 404, 422.

---

### 6.9 Ubah Status Tugas

`PATCH /tasks/{id}/status`
Header: `Authorization: Bearer {token}`

**Request body**

```json
{
  "status": "done"
}
```

| Field | Wajib | Aturan |
|-------|-------|--------|
| status | Ya | hanya `todo` atau `done` |

**Response 200**

```json
{
  "success": true,
  "message": "Status tugas diperbarui",
  "data": {
    "id": 3,
    "title": "Kerjakan LKPD Laravel (revisi)",
    "deadline": "2026-11-12",
    "status": "done"
  }
}
```

Kode error yang mungkin: 401, 403, 404, 422.

---

### 6.10 Hapus Tugas

`DELETE /tasks/{id}`
Header: `Authorization: Bearer {token}`

**Response 200**

```json
{
  "success": true,
  "message": "Tugas berhasil dihapus"
}
```

Kode error yang mungkin: 401, 403, 404.

---

## 7. Aturan Penting

1. Semua endpoint selain `/register` dan `/login` wajib memakai Bearer token.
2. Setiap user hanya boleh melihat dan mengubah tugas miliknya sendiri.
3. Nama field memakai huruf kecil (`title`, `deadline`, `status`), jangan diganti diam-diam.
4. Nilai `status` hanya `todo` atau `done`.

## 8. Riwayat Perubahan

| Tanggal | Perubahan | Disepakati oleh |
|---------|-----------|-----------------|
| 2026-11-03 | Versi awal kontrak | BE & FE |