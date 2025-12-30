# Quick Start Guide - Sistem Tiket dan Transaksi

## 🚀 Cara Menjalankan

### 1. Migrasi Database
```bash
php artisan migrate
```

### 2. Seed Data Tiket (Opsional)
```bash
php artisan db:seed --class=TicketSeeder
```

### 3. Jalankan Server
```bash
php artisan serve
```

## 👤 User Roles

### Customer (Pembeli Tiket)
**Dapat melakukan:**
- Browse dan melihat event
- Memesan tiket event
- Melihat daftar transaksi
- Upload bukti pembayaran
- Membatalkan transaksi

**Routes:**
- `/events` - Browse events
- `/events/{event}/book` - Pesan tiket
- `/transactions` - Daftar transaksi saya
- `/transactions/{transaction}` - Detail transaksi
- `/transactions/{transaction}/payment` - Upload bukti pembayaran

### Organizer (Penyelenggara Event)
**Dapat melakukan:**
- Membuat dan mengelola event
- Mengelola jenis tiket untuk event
- Melihat transaksi event
- Verifikasi pembayaran
- Monitor statistik penjualan

**Routes:**
- `/events/create` - Buat event baru
- `/events/{event}/tickets` - Kelola tiket event
- `/events/{event}/tickets/create` - Tambah tiket baru
- `/events/{event}/transactions` - Lihat transaksi event

## 📋 Flow Pemesanan Tiket

### 1. Customer Memesan Tiket
1. Login sebagai customer
2. Browse events di `/events`
3. Klik event untuk melihat detail
4. Klik "Beli Tiket" atau navigasi ke `/events/{event}/book`
5. Pilih jenis tiket dan jumlah
6. Klik "Pesan Tiket"
7. Sistem akan:
   - Membuat transaksi dengan status `pending`
   - Kurangi kuota tiket (reserved)
   - Set batas waktu pembayaran 24 jam
   - Generate kode transaksi unik (TRX-XXXXXXXXXX)

### 2. Upload Bukti Pembayaran
1. Dari halaman transaksi, klik "Bayar Sekarang"
2. Lihat instruksi pembayaran:
   - Bank BCA: 1234567890
   - E-Wallet: 081234567890
3. Lakukan transfer sesuai total pembayaran
4. Pilih metode pembayaran
5. Upload screenshot/foto bukti transfer
6. Klik "Upload Bukti"

### 3. Organizer Verifikasi Pembayaran
1. Login sebagai organizer
2. Buka event yang dibuat
3. Klik "Lihat Transaksi" atau navigasi ke `/events/{event}/transactions`
4. Lihat daftar transaksi dengan status `pending`
5. Klik "Detail" untuk melihat bukti pembayaran
6. Jika valid, klik "Verifikasi Pembayaran"
7. Status berubah menjadi `paid`
8. Customer dapat melihat tiket terverifikasi

## 🎫 Mengelola Tiket (Organizer)

### Membuat Tiket Baru
1. Login sebagai organizer
2. Buka event yang dibuat
3. Klik "Kelola Tiket"
4. Klik "Tambah Tiket"
5. Isi form:
   - Nama tiket (contoh: VIP, Regular, Early Bird)
   - Deskripsi (opsional)
   - Harga (dalam Rupiah)
   - Kuota (jumlah tiket tersedia)
   - Periode penjualan (opsional)
   - Status aktif
6. Klik "Simpan Tiket"

### Edit Tiket
1. Dari halaman kelola tiket
2. Klik "Edit" pada tiket yang ingin diubah
3. Update informasi
   - **Catatan:** Kuota tidak bisa dikurangi di bawah jumlah yang sudah terjual
4. Klik "Simpan Perubahan"

### Hapus Tiket
1. Dari halaman kelola tiket
2. Klik "Hapus" pada tiket
3. Konfirmasi penghapusan
   - **Catatan:** Tidak bisa hapus tiket yang sudah memiliki transaksi paid

## 💡 Tips & Best Practices

### Untuk Organizer:
1. **Set Harga yang Tepat**
   - Pertimbangkan biaya admin 5%
   - Bandingkan dengan event sejenis

2. **Kelola Kuota**
   - Jangan set kuota terlalu besar jika kapasitas venue terbatas
   - Gunakan Early Bird untuk dorong penjualan awal

3. **Periode Penjualan**
   - Set sale_start untuk pre-sale
   - Set sale_end untuk deadline pembelian

4. **Verifikasi Cepat**
   - Cek transaksi pending secara berkala
   - Verifikasi pembayaran maksimal 1x24 jam

### Untuk Customer:
1. **Pesan Segera**
   - Tiket populer cepat habis
   - Manfaatkan Early Bird

2. **Bayar Tepat Waktu**
   - Batas pembayaran 24 jam
   - Setelah expired, kuota dikembalikan

3. **Upload Bukti Jelas**
   - Foto/screenshot yang jelas
   - Pastikan nominal dan tanggal terlihat

4. **Simpan Kode Transaksi**
   - Untuk referensi dan komplain
   - Format: TRX-XXXXXXXXXX

## 🔍 Troubleshooting

### Tiket tidak muncul saat booking
- Pastikan tiket sudah diaktifkan (`is_active = true`)
- Cek periode penjualan (sale_start & sale_end)
- Pastikan kuota masih tersedia

### Tidak bisa pesan tiket
- Login terlebih dahulu
- Cek role user (harus customer)
- Pastikan event sudah published

### Transaksi expired
- Batas pembayaran 24 jam
- Pesan ulang tiket baru
- Kuota otomatis dikembalikan

### Upload bukti gagal
- Max file size: 2MB
- Format: JPG, JPEG, PNG
- Cek koneksi internet

### Tidak bisa verifikasi pembayaran
- Pastikan login sebagai organizer event
- Pastikan bukti pembayaran sudah diupload
- Cek status transaksi (harus pending)

## 📊 Monitoring

### Dashboard Organizer
**Statistics yang tersedia:**
- Total transaksi
- Transaksi pending (menunggu verifikasi)
- Transaksi paid (berhasil)
- Total pendapatan

### Ticket Management
**Info yang ditampilkan:**
- Total kuota
- Jumlah terjual
- Jumlah tersedia
- Status tiket (aktif/nonaktif)

## 🔒 Keamanan

1. **Authentication Required**
   - Semua fitur booking & transaksi butuh login
   - Organizer hanya bisa kelola event miliknya

2. **Authorization Check**
   - Customer tidak bisa akses halaman organizer
   - Organizer tidak bisa verifikasi event orang lain

3. **Validation**
   - Semua input divalidasi server-side
   - CSRF protection aktif
   - File upload dengan whitelist extension

4. **Transaction Integrity**
   - Database transaction dengan rollback
   - Soft deletes untuk data history
   - Kuota management atomic

## 📞 Support

Jika menemui kendala:
1. Cek error log di `storage/logs/laravel.log`
2. Pastikan semua migrasi sudah dijalankan
3. Clear cache: `php artisan cache:clear`
4. Restart server

---

**Happy Ticketing! 🎉**
