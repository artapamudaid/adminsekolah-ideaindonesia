# Docker

Project ini disiapkan menggunakan FrankenPHP PHP 8.4 Alpine (terbaru & hardened terhadap CVE). Database bisa memakai MySQL di network/container lain.

## Port

Ubah port host di `.env`:

```env
HTTP_PORT=8001
```

Container tetap memakai port internal `80`.

## Database Eksternal / Network Lain

Set koneksi database di `.env`:

```env
DB_HOST=mysql_container_or_host
DB_PORT=3306
DB_DATABASE=adminsekolah_demo
DB_USERNAME=adminsekolah
DB_PASSWORD=adminsekolah_secret
DB_NETWORK_NAME=mysql_network_name
```

Jika database ada di host/IP lain, cukup jalankan:

```bash
docker compose up -d --build
```

Jika database ada di container Docker lain, pastikan app join ke network yang sama:

```bash
docker network ls
```

Pakai nama network tersebut sebagai `DB_NETWORK_NAME`.

Lalu jalankan dengan file tambahan:

```bash
docker compose -f docker-compose.yml -f docker-compose.external-db-network.yml up -d --build
```

Aplikasi:

```text
http://127.0.0.1:8001
```

## Local MySQL Opsional

Jika ingin menjalankan MySQL lokal dari compose project ini:

```bash
docker compose -f docker-compose.yml -f docker-compose.local-db.yml up -d --build
```

MySQL lokal dari host:

```text
127.0.0.1:33084
database: adminsekolah_demo
user: adminsekolah
password: adminsekolah_secret
```

Dump awal `env/storage/db/origin.sql` otomatis di-import saat volume MySQL lokal pertama kali dibuat.
