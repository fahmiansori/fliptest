# Mini Project Flip


## Cara Menjalankan Program

Pertama edit file DB.php untuk konfigurasi basis data.

Gunakan PHP CLI untuk menjalankan program. Pertama dengan migrasi tabel yang akan digunakan untuk menyimpan hasil respon.

```bash
php migration.php
```
Kemudian untuk mengirim data ke server, gunakan perintah : 

```bash
php index.php --bank_code=bri --account_number=1234567890 --amount=9999 --remark='contoh remark'
```

Untuk mengecek status, gunakan perintah :
```bash
php index.php --req=check-disbursement --transaction_id=1400436297
```

## Terima kasih :)
