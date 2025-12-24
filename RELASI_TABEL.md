# Dokumentasi Relasi Tabel

## Relasi yang Ada di Project

### 1. **Events ↔ Users**

#### Struktur Relasi:
- **Events** `belongsTo` **Users** (Many-to-One)
- **Users** `hasMany` **Events** (One-to-Many)

#### Detail:
- Tabel `events` memiliki foreign key `user_id` yang merujuk ke `users.id`
- Satu User dapat memiliki banyak Event
- Satu Event hanya dimiliki oleh satu User
- Jika User dihapus, semua Event miliknya akan ikut terhapus (CASCADE)

#### Kode di Model:

**app/Models/Event.php:**
```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

**app/Models/User.php:**
```php
public function events()
{
    return $this->hasMany(Event::class);
}
```

#### Migration:
```php
$table->foreignId('user_id')->constrained()->onDelete('cascade');
```

---

## Tabel yang Tidak Memiliki Relasi

### 1. **Karyawan**
- Tabel `karyawans` adalah tabel independen
- Tidak memiliki foreign key ke tabel lain
- Tidak memiliki relasi dengan `users` (meskipun menggunakan email yang sama)

---

## Diagram Relasi

```
┌─────────────┐
│    Users    │
│             │
│  - id       │
│  - name     │
│  - email    │
│  - password │
│  - role     │
└──────┬──────┘
       │
       │ hasMany (1:N)
       │
       ▼
┌─────────────┐
│   Events    │
│             │
│  - id       │
│  - title    │
│  - start_   │
│    date     │
│  - end_date │
│  - color    │
│  - user_id  │◄─── Foreign Key
└─────────────┘

┌─────────────┐
│  Karyawans  │
│             │
│  - id       │
│  - nama     │
│  - email    │
│  - jabatan  │
│  - gender   │
│  - telepon  │
│  - alamat   │
│  - foto     │
└─────────────┘
   (Independen)
```

---

## Catatan Penting

1. **Events ↔ Users**: Relasi sudah lengkap dan berfungsi
2. **Karyawans**: Tabel independen, tidak ada relasi database, hanya menggunakan email sebagai identifikasi (tidak ada foreign key)
3. Jika ingin menambahkan relasi antara Users dan Karyawans, perlu menambahkan foreign key `user_id` di tabel `karyawans`

