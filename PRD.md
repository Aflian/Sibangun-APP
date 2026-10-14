# PRD — Sistem Manajemen Toko Bangunan Multi-Cabang

## 1. Informasi Produk

**Nama Produk:** Sistem Manajemen Toko Bangunan Multi-Cabang
**Platform:** Web Application
**Backend:** Laravel
**Admin Panel:** Filament
**POS:** Custom Dashboard
**Database:** MySQL
**UI POS:** Custom UI menggunakan Laravel/Livewire dan Tailwind CSS

---

# 2. Ringkasan Produk

Sistem Manajemen Toko Bangunan Multi-Cabang adalah aplikasi berbasis web yang digunakan untuk mengelola operasional toko bangunan yang memiliki satu atau lebih cabang.

Sistem mengintegrasikan:

* Manajemen cabang
* Manajemen user dan role
* Manajemen produk
* Manajemen stok/gudang
* POS/kasir
* Manajemen pelanggan
* Limit hutang pelanggan
* Piutang pelanggan
* Piutang jatuh tempo
* Manajemen vendor
* Hutang kepada vendor
* Pembayaran hutang
* Kas per cabang
* Surat jalan
* Transfer stok antar cabang
* Pembukuan
* Laporan keuangan
* Laporan penjualan
* Laporan stok
* Dashboard konsolidasi seluruh cabang

Sistem menggunakan pendekatan **multi-cabang**, di mana stok, kas, transaksi, dan pembukuan dipisahkan berdasarkan cabang.

Sementara itu, data seperti **produk dan pelanggan bersifat global** dan dapat digunakan oleh seluruh cabang.

---

# 3. Tujuan Sistem

## 3.1 Tujuan Utama

Membangun sistem terintegrasi untuk membantu toko bangunan dalam mengelola operasional, transaksi, persediaan, keuangan, pelanggan, vendor, dan aktivitas antar-cabang secara terpusat.

## 3.2 Tujuan Khusus

1. Mempermudah proses penjualan melalui POS.
2. Mempermudah pengelolaan stok setiap cabang.
3. Mencegah pencampuran stok antar-cabang.
4. Mengelola kas secara terpisah berdasarkan cabang.
5. Mengelola pelanggan secara terpusat.
6. Mengelola limit hutang pelanggan.
7. Memantau piutang pelanggan.
8. Memantau piutang yang akan jatuh tempo.
9. Memantau piutang yang sudah terlambat.
10. Mengelola vendor dan hutang kepada vendor.
11. Mengelola surat jalan.
12. Mendukung transfer stok antar-cabang.
13. Menyediakan pembukuan setiap cabang.
14. Menyediakan laporan konsolidasi seluruh cabang.
15. Memberikan Admin gambaran kondisi bisnis secara keseluruhan.

---

# 4. Prinsip Arsitektur Sistem

Sistem menggunakan satu aplikasi Laravel dan satu database utama.

```text
                    LARAVEL
                       |
          +------------+------------+
          |                         |
    FILAMENT ADMIN              CUSTOM POS
          |                         |
          +------------+------------+
                       |
                SERVICE LAYER
                       |
                    DATABASE
                       |
       +---------------+---------------+
       |               |               |
     CABANG           STOK             KAS
       |               |               |
       +---------------+---------------+
                       |
                  TRANSAKSI
                       |
          +------------+------------+
          |                         |
       PIUTANG                    HUTANG
```

Business logic tidak boleh hanya berada di Filament atau POS.

Seluruh proses penting harus menggunakan Service Layer yang dapat digunakan oleh:

* Filament
* POS
* Controller
* Livewire
* API jika diperlukan di masa depan

---

# 5. Konsep Multi-Cabang

Sistem mendukung banyak cabang.

Contoh:

```text
Perusahaan
|
+-- Cabang Pekanbaru
|
+-- Cabang Bangkinang
|
+-- Cabang Duri
|
+-- Cabang Dumai
```

Cabang dapat ditambahkan oleh Admin melalui Admin Panel.

## 5.1 Data Cabang

Minimal memiliki:

* ID
* Kode cabang
* Nama cabang
* Alamat
* Nomor telepon
* Kepala cabang
* Status aktif/nonaktif
* Tanggal dibuat

---

# 6. Data Global dan Data Per Cabang

## 6.1 Data Global

Data berikut bersifat global:

* Produk
* Kategori produk
* Satuan
* Pelanggan
* Vendor
* User
* Role
* Permission

## 6.2 Data Per Cabang

Data berikut terikat dengan cabang:

* Stok
* Penjualan
* Pembelian
* Kas
* Pemasukan
* Pengeluaran
* Piutang berdasarkan transaksi
* Hutang berdasarkan transaksi
* Surat jalan
* Transfer stok
* Pembukuan
* Laporan operasional

Setiap transaksi harus memiliki `branch_id`.

---

# 7. Role dan Hak Akses

Sistem minimal memiliki dua role utama:

## 7.1 Admin

Admin memiliki akses penuh terhadap sistem.

Hak akses:

* Melihat seluruh cabang
* Menambah/mengubah cabang
* Mengelola user
* Mengelola role
* Mengelola produk
* Mengelola stok
* Mengelola pelanggan
* Mengelola vendor
* Melihat seluruh transaksi
* Mengelola keuangan
* Melihat laporan
* Melihat seluruh piutang
* Melihat piutang jatuh tempo
* Melihat hutang vendor
* Mengelola surat jalan
* Melakukan transfer stok antar-cabang
* Melihat dashboard konsolidasi seluruh cabang

## 7.2 Staf

Staf hanya dapat mengakses fitur yang diberikan kepadanya.

Staf terikat dengan satu cabang.

Contoh:

```text
Budi
Role   : Kasir
Cabang : Pekanbaru
```

Budi hanya dapat melihat transaksi dan data operasional yang diperbolehkan pada Cabang Pekanbaru.

---

# 8. Modul Produk

Produk bersifat global.

Data produk:

* Kode produk
* Barcode
* Nama produk
* Kategori
* Merek
* Satuan
* Harga beli
* Harga jual
* Stok minimum
* Status aktif
* Deskripsi

Produk yang sama dapat memiliki stok berbeda pada setiap cabang.

Contoh:

```text
Semen 50 Kg

Pekanbaru   : 150
Bangkinang  : 80
Duri        : 120
```

---

# 9. Modul Gudang dan Stok

## 9.1 Stok Per Cabang

Stok disimpan berdasarkan:

```text
branch_id
product_id
quantity
```

Stok Cabang A tidak boleh otomatis mengubah stok Cabang B.

## 9.2 Jenis Perubahan Stok

Sistem mendukung:

* Stok awal
* Barang masuk
* Barang keluar
* Penjualan
* Retur pembelian
* Retur penjualan
* Penyesuaian stok
* Transfer antar-cabang

## 9.3 Stok Minimum

Sistem memberikan peringatan apabila stok berada di bawah batas minimum.

Contoh:

```text
⚠ Stok Minimum

Semen 50 Kg
Stok: 10
Minimum: 20
```

---

# 10. Transfer Stok Antar-Cabang

Sistem memungkinkan pemindahan barang dari satu cabang ke cabang lain.

Contoh:

```text
Cabang Pekanbaru
       |
       | 50 Semen
       v
Surat Jalan Transfer
       |
       v
Cabang Bangkinang
```

Proses:

1. Admin/staf berwenang membuat permintaan transfer.
2. Sistem mencatat cabang asal.
3. Sistem mencatat cabang tujuan.
4. Produk dan jumlah ditentukan.
5. Surat jalan dibuat.
6. Stok cabang asal berkurang ketika barang dikirim.
7. Stok cabang tujuan bertambah ketika barang diterima.
8. Sistem mencatat riwayat transfer.

---

# 11. Modul POS Kasir

POS menggunakan custom dashboard dan bukan tampilan CRUD standar Filament.

Tujuan POS:

* Cepat digunakan kasir
* Mudah mencari produk
* Mudah mengubah jumlah
* Mendukung pembayaran
* Menampilkan keranjang
* Menghasilkan struk
* Terintegrasi langsung dengan stok dan keuangan

## 11.1 Tampilan POS

Komponen utama:

```text
+------------------------------------------------+
| Nama Toko | Cabang | Kasir                     |
+--------------------------+---------------------+
| Search Produk            | Keranjang           |
|                          |                     |
| Kategori                | Produk              |
|                          | Qty                 |
| Daftar Produk            | Harga               |
|                          |                     |
|                          | Subtotal            |
|                          | Diskon              |
|                          | TOTAL               |
|                          |                     |
|                          | [ BAYAR ]           |
+--------------------------+---------------------+
```

## 11.2 Proses Penjualan

```text
Pilih Produk
     |
Input Jumlah
     |
Cek Stok
     |
Keranjang
     |
Hitung Total
     |
Pilih Pembayaran
     |
Simpan Transaksi
     |
Kurangi Stok
     |
Catat Kas/Piutang
     |
Cetak Struk
```

---

# 12. Metode Pembayaran

Sistem minimal mendukung:

* Cash
* Transfer
* QRIS
* Debit
* Kredit/Hutang

Metode pembayaran dapat dikembangkan sesuai kebutuhan bisnis.

---

# 13. Modul Pelanggan

Pelanggan bersifat global.

Data pelanggan:

* Kode pelanggan
* Nama
* Jenis pelanggan
* Nomor telepon
* Email
* Alamat
* NPWP jika diperlukan
* Limit hutang
* Status aktif

Pelanggan dapat melakukan transaksi di beberapa cabang.

Contoh:

```text
PT ABC

Pekanbaru    : Rp10.000.000
Bangkinang   : Rp5.000.000
Duri         : Rp5.000.000

Total Piutang:
Rp20.000.000
```

---

# 14. Limit Hutang Pelanggan

Setiap pelanggan dapat memiliki limit hutang.

Contoh:

```text
Limit Hutang       : Rp50.000.000
Total Piutang      : Rp20.000.000
Sisa Limit         : Rp30.000.000
```

Ketika transaksi kredit dilakukan:

```text
Piutang Saat Ini + Transaksi Baru
                |
                v
        Apakah > Limit?
          /          \
        Ya            Tidak
        |              |
      Tolak          Izinkan
```

Sistem harus mencegah transaksi kredit apabila melewati limit, kecuali terdapat otorisasi khusus dari Admin.

---

# 15. Modul Piutang

Piutang terbentuk ketika pelanggan melakukan transaksi kredit.

Data piutang minimal:

* Nomor invoice
* Pelanggan
* Cabang
* Tanggal transaksi
* Tanggal jatuh tempo
* Total transaksi
* Total dibayar
* Sisa piutang
* Status

Status:

* Belum jatuh tempo
* Jatuh tempo hari ini
* Terlambat
* Sebagian dibayar
* Lunas

---

# 16. Piutang Jatuh Tempo

Sistem harus menyediakan fitur khusus untuk memantau jatuh tempo.

Kategori:

### Belum Jatuh Tempo

Tanggal jatuh tempo masih di masa depan.

### Jatuh Tempo Hari Ini

Tanggal hari ini sama dengan tanggal jatuh tempo.

### Terlambat

Tanggal saat ini sudah melewati tanggal jatuh tempo dan masih memiliki saldo.

Contoh:

```text
PT ABC
Piutang       : Rp5.000.000
Jatuh Tempo   : 01 Oktober 2026
Hari Ini      : 08 Oktober 2026

Status:
TERLAMBAT 7 HARI
```

---

# 17. Dashboard Piutang

Admin dapat melihat:

* Total piutang
* Piutang belum jatuh tempo
* Piutang jatuh tempo hari ini
* Piutang terlambat
* Total pembayaran piutang
* Pelanggan dengan piutang terbesar
* Piutang berdasarkan cabang

Contoh:

```text
Total Piutang          Rp75.000.000
Belum Jatuh Tempo      Rp45.000.000
Jatuh Tempo Hari Ini   Rp10.000.000
Terlambat              Rp20.000.000
```

---

# 18. Pembayaran Piutang

Pelanggan dapat membayar piutang secara penuh atau sebagian.

Contoh:

```text
Piutang       Rp10.000.000
Pembayaran    Rp4.000.000
-------------------------
Sisa          Rp6.000.000
```

Jika pembayaran terakhir melunasi saldo:

```text
Status = LUNAS
```

Pembayaran harus tercatat pada kas cabang tempat pembayaran dilakukan.

---

# 19. Modul Vendor

Data vendor:

* Kode vendor
* Nama vendor
* Nomor telepon
* Email
* Alamat
* Kontak person
* Status
* Catatan

Vendor dapat memiliki transaksi pembelian dan hutang.

---

# 20. Hutang Vendor

Sistem mencatat:

* Pembelian kredit
* Total hutang
* Pembayaran hutang
* Sisa hutang
* Jatuh tempo
* Status hutang

Contoh:

```text
Pembelian Vendor     Rp30.000.000
Pembayaran           Rp20.000.000
Sisa Hutang          Rp10.000.000
```

---

# 21. Modul Kas Per Cabang

Setiap cabang mempunyai kas sendiri.

Contoh:

```text
Kas Pekanbaru      Rp30.000.000
Kas Bangkinang     Rp25.000.000
Kas Duri           Rp25.000.000
```

Kas tidak boleh bercampur antar-cabang.

## 21.1 Transaksi Kas

Kas dapat berubah karena:

### Kas Masuk

* Penjualan cash
* Pembayaran piutang
* Pendapatan lainnya

### Kas Keluar

* Pembelian
* Pembayaran vendor
* Pengeluaran operasional
* Biaya lainnya

---

# 22. Modul Pembukuan

Pembukuan dilakukan berdasarkan cabang.

Setiap cabang memiliki catatan:

* Pendapatan
* Pengeluaran
* Pembelian
* Penjualan
* Kas
* Piutang
* Hutang

Admin dapat melihat laporan per cabang maupun konsolidasi seluruh cabang.

---

# 23. Laporan Laba Rugi

Sistem menyediakan laporan laba rugi berdasarkan periode.

Contoh:

```text
CABANG PEKANBARU

Penjualan             Rp200.000.000
Harga Pokok           Rp140.000.000
------------------------------------
Laba Kotor             Rp60.000.000

Pengeluaran Operasional Rp10.000.000
------------------------------------
Laba Bersih             Rp50.000.000
```

---

# 24. Dashboard Konsolidasi Admin

Admin dapat melihat performa seluruh cabang.

Contoh:

```text
+------------------------------------------------+
| DASHBOARD PERUSAHAAN                           |
+------------------------------------------------+
| Total Penjualan       Rp500.000.000            |
| Total Kas             Rp80.000.000             |
| Total Piutang         Rp75.000.000             |
| Total Hutang          Rp40.000.000             |
| Laba Bersih           Rp107.000.000            |
+------------------------------------------------+
```

## Performa Cabang

| Cabang     |    Penjualan |         Kas |     Piutang |         Laba |
| ---------- | -----------: | ----------: | ----------: | -----------: |
| Pekanbaru  |     Rp200 jt |     Rp30 jt |     Rp25 jt |      Rp50 jt |
| Bangkinang |     Rp150 jt |     Rp25 jt |     Rp20 jt |      Rp32 jt |
| Duri       |     Rp150 jt |     Rp25 jt |     Rp30 jt |      Rp25 jt |
| **Total**  | **Rp500 jt** | **Rp80 jt** | **Rp75 jt** | **Rp107 jt** |

---

# 25. Modul Surat Jalan

Sistem mendukung:

1. Surat jalan barang masuk.
2. Surat jalan barang keluar.
3. Surat jalan pengiriman pelanggan.
4. Surat jalan transfer antar-cabang.

Data surat jalan:

* Nomor surat jalan
* Tanggal
* Cabang asal
* Cabang tujuan
* Vendor/pelanggan
* Produk
* Jumlah
* Kendaraan jika diperlukan
* Driver jika diperlukan
* Status
* Catatan

Status:

```text
Draft
Dibuat
Dikirim
Diterima
Selesai
Dibatalkan
```

---

# 26. Laporan

Sistem menyediakan:

## Laporan Penjualan

* Penjualan harian
* Mingguan
* Bulanan
* Tahunan
* Per cabang
* Per produk
* Per pelanggan
* Per kasir
* Per metode pembayaran

## Laporan Stok

* Stok saat ini
* Stok minimum
* Stok masuk
* Stok keluar
* Mutasi stok
* Transfer antar-cabang

## Laporan Piutang

* Total piutang
* Piutang per pelanggan
* Piutang per cabang
* Piutang jatuh tempo
* Piutang terlambat
* Pembayaran piutang

## Laporan Hutang

* Hutang vendor
* Hutang jatuh tempo
* Pembayaran vendor
* Hutang per cabang

## Laporan Keuangan

* Kas
* Pemasukan
* Pengeluaran
* Laba rugi
* Pembukuan per cabang
* Konsolidasi seluruh cabang

---

# 27. Aturan Bisnis Utama

## BR-001 — Cabang

Setiap transaksi operasional harus memiliki `branch_id`.

## BR-002 — Stok

Stok harus dipisahkan berdasarkan cabang.

## BR-003 — Kas

Kas harus dipisahkan berdasarkan cabang.

## BR-004 — Pelanggan

Pelanggan bersifat global dan dapat melakukan transaksi di beberapa cabang.

## BR-005 — Limit Hutang

Transaksi kredit tidak boleh melewati limit hutang pelanggan tanpa otorisasi.

## BR-006 — Piutang

Setiap transaksi kredit menghasilkan piutang.

## BR-007 — Jatuh Tempo

Piutang memiliki tanggal jatuh tempo.

## BR-008 — Piutang Terlambat

Piutang yang melewati tanggal jatuh tempo dan belum lunas harus berstatus terlambat.

## BR-009 — Pembayaran

Pembayaran piutang mengurangi saldo piutang.

## BR-010 — Kas

Pembayaran yang diterima masuk ke kas cabang yang menerima pembayaran.

## BR-011 — Penjualan

Penjualan mengurangi stok cabang yang melakukan penjualan.

## BR-012 — Transfer Stok

Transfer stok tidak boleh langsung dianggap selesai sampai proses penerimaan selesai.

## BR-013 — User

Staf hanya dapat mengakses cabang yang ditugaskan.

## BR-014 — Admin

Admin dapat melihat seluruh cabang.

---

# 28. Service Layer

Business logic utama harus dibuat dalam service.

Contoh:

```text
app/Services/

SaleService.php
StockService.php
PaymentService.php
ReceivableService.php
PayableService.php
CashService.php
BranchTransferService.php
DeliveryOrderService.php
```

Contoh alur:

```text
POS
 |
 v
SaleService
 |
 +-- Validate Stock
 +-- Create Sale
 +-- Create Sale Items
 +-- Reduce Stock
 +-- Create Cash Transaction
 +-- Create Receivable jika kredit
 +-- Create Accounting Record
 |
 v
Commit Transaction
```

Semua proses transaksi penting menggunakan database transaction.

---

# 29. Struktur Modul Teknis

```text
app/
├── Models/
├── Services/
├── Actions/
├── Policies/
├── Filament/
│   ├── Resources/
│   ├── Pages/
│   └── Widgets/
├── Livewire/
│   └── POS/
├── Http/
│   ├── Controllers/
│   └── Requests/
└── Enums/
```

Enum yang disarankan:

```text
UserRole
TransactionStatus
PaymentMethod
ReceivableStatus
PayableStatus
StockMovementType
DeliveryOrderStatus
BranchStatus
```

---

# 30. Database Konseptual

Tabel utama yang direncanakan:

```text
users
roles
permissions

branches

categories
units
products
branch_stocks
stock_movements
stock_transfers
stock_transfer_items

customers
customer_credit_limits

vendors

sales
sale_items
payments

receivables
receivable_payments

purchases
purchase_items

payables
payable_payments

cash_accounts
cash_transactions

expenses
incomes

delivery_orders
delivery_order_items

accounting_entries
accounting_entry_items
```

Struktur database detail akan ditentukan pada tahap ERD dan database design.

---

# 31. Keamanan

Sistem harus menerapkan:

* Authentication
* Authorization
* Role & Permission
* Branch-level access
* Validasi input
* CSRF protection
* Password hashing
* Audit log untuk transaksi penting
* Database transaction untuk transaksi finansial
* Pencegahan akses data cabang lain oleh staf

---

# 32. Audit Trail

Sistem sebaiknya mencatat aktivitas penting.

Contoh:

```text
Budi
08 Oktober 2026 10:30

Membuat transaksi:
INV-000123

Cabang:
Pekanbaru

Total:
Rp750.000
```

Aktivitas yang dapat dicatat:

* Login
* Membuat transaksi
* Mengubah transaksi
* Membatalkan transaksi
* Pembayaran
* Perubahan stok
* Transfer stok
* Perubahan limit hutang
* Perubahan harga
* Perubahan data pelanggan

---

# 33. Dashboard Staf

Dashboard staf dibuat lebih sederhana.

Contoh:

```text
Cabang Pekanbaru

Penjualan Hari Ini
Rp8.500.000

Transaksi
32

Kas Saat Ini
Rp15.000.000

Piutang Hari Ini
Rp2.500.000

Stok Menipis
7 Produk
```

Staf hanya melihat informasi sesuai cabangnya.

---

# 34. Dashboard POS

POS dibuat terpisah secara UI dari Filament.

Prioritas:

1. Cepat
2. Mudah digunakan
3. Responsif
4. Mudah mencari produk
5. Mudah melakukan transaksi
6. Minim klik
7. Mendukung keyboard shortcut jika diperlukan
8. Mudah digunakan pada layar kasir

---

# 35. Non-Functional Requirements

## Performance

* Halaman POS harus ringan.
* Query stok harus efisien.
* Dashboard menggunakan query agregasi yang optimal.
* Laporan besar dapat menggunakan filter periode.

## Reliability

Transaksi finansial dan stok harus menggunakan database transaction.

Jika salah satu proses gagal, transaksi harus di-rollback.

Contoh:

```text
Penjualan berhasil
↓
Stok gagal dikurangi
↓
Rollback transaksi
```

Sistem tidak boleh menghasilkan transaksi setengah selesai.

## Scalability

Sistem harus dapat menambahkan cabang baru tanpa perubahan struktur database utama.

Contoh:

```text
Saat ini:
3 cabang

Kemudian:
10 cabang

Kemudian:
50 cabang
```

Struktur sistem tetap menggunakan `branch_id`.

---

# 36. MVP

Versi pertama sistem sebaiknya fokus pada:

### Prioritas 1

* Authentication
* Role
* Cabang
* Produk
* Kategori
* Pelanggan
* Vendor
* Stok per cabang
* POS
* Penjualan
* Pembayaran
* Kas per cabang

### Prioritas 2

* Piutang
* Limit hutang
* Jatuh tempo
* Pembayaran piutang
* Pembelian
* Hutang vendor
* Pembayaran vendor

### Prioritas 3

* Surat jalan
* Transfer stok antar-cabang
* Pembukuan
* Laba rugi
* Dashboard konsolidasi

### Prioritas 4

* Audit log
* Notifikasi jatuh tempo
* Notifikasi stok minimum
* Export Excel/PDF
* Advanced reporting

---

# 37. Out of Scope Versi Awal

Fitur berikut tidak menjadi prioritas versi pertama:

* Mobile application native
* Integrasi marketplace
* Integrasi ERP eksternal
* Integrasi pajak kompleks
* Payroll karyawan
* Akuntansi perusahaan tingkat lanjut
* Integrasi bank otomatis
* Multi-perusahaan

Fitur tersebut dapat dipertimbangkan pada fase berikutnya.

---

# 38. Acceptance Criteria Utama

Sistem dianggap berhasil apabila:

### Multi-Cabang

* Admin dapat menambah cabang.
* Staf terhubung dengan cabang.
* Staf tidak dapat melihat data cabang lain.
* Admin dapat melihat seluruh cabang.

### Stok

* Stok setiap cabang terpisah.
* Penjualan mengurangi stok cabang.
* Transfer dapat memindahkan stok antar-cabang.
* Riwayat mutasi stok tersedia.

### POS

* Kasir dapat mencari produk.
* Kasir dapat memasukkan produk ke keranjang.
* Sistem mengecek stok.
* Sistem menghitung total.
* Sistem menerima pembayaran.
* Transaksi tersimpan.
* Stok berkurang.
* Kas bertambah atau piutang terbentuk.

### Pelanggan

* Pelanggan bersifat global.
* Pelanggan dapat bertransaksi di beberapa cabang.
* Limit hutang dapat ditentukan.
* Sistem mencegah hutang melewati limit.

### Piutang

* Transaksi kredit menghasilkan piutang.
* Piutang memiliki jatuh tempo.
* Pembayaran mengurangi saldo.
* Sistem mengetahui piutang terlambat.
* Admin dapat melihat piutang berdasarkan cabang maupun keseluruhan.

### Kas

* Setiap cabang memiliki kas sendiri.
* Transaksi memengaruhi kas cabang terkait.
* Admin dapat melihat saldo kas setiap cabang.
* Admin dapat melihat total kas seluruh cabang.

### Laporan

* Admin dapat melihat laporan per cabang.
* Admin dapat melihat laporan konsolidasi.
* Total pendapatan seluruh cabang dapat dihitung.
* Laporan piutang jatuh tempo tersedia.
* Laporan stok tersedia.
* Laporan kas tersedia.
* Laporan laba rugi tersedia.

---

# 39. Roadmap Pengembangan

```text
PHASE 1
Foundation
    ↓
Authentication
Role
Branch
User
    ↓

PHASE 2
Master Data
    ↓
Product
Category
Unit
Customer
Vendor
    ↓

PHASE 3
Inventory
    ↓
Branch Stock
Stock Movement
Purchase
Delivery
    ↓

PHASE 4
POS
    ↓
Cart
Checkout
Payment
Receipt
Cash
    ↓

PHASE 5
Credit System
    ↓
Customer Limit
Receivable
Due Date
Payment
    ↓

PHASE 6
Vendor Finance
    ↓
Payable
Vendor Payment
    ↓

PHASE 7
Multi-Branch
    ↓
Stock Transfer
Delivery Order
Branch Reports
    ↓

PHASE 8
Accounting & Reporting
    ↓
Cash
Income
Expense
Profit/Loss
Consolidated Dashboard
    ↓

PHASE 9
Optimization
    ↓
Audit Log
Notifications
Export
Advanced Reports
```

---

# 40. Kesimpulan

Sistem ini dirancang sebagai sistem manajemen toko bangunan multi-cabang yang mengintegrasikan operasional toko, POS, gudang, stok, pelanggan, piutang, vendor, hutang, kas, surat jalan, pembukuan, dan laporan.

Prinsip utama sistem:

```text
Produk       = GLOBAL
Pelanggan    = GLOBAL
Vendor       = GLOBAL

Stok         = PER CABANG
Kas          = PER CABANG
Penjualan    = PER CABANG
Pembelian    = PER CABANG
Piutang      = BERDASARKAN TRANSAKSI/CABANG
Hutang       = BERDASARKAN TRANSAKSI/CABANG
Pembukuan    = PER CABANG

ADMIN        = MELIHAT SEMUA CABANG
STAF         = TERBATAS PADA CABANG
POS          = CUSTOM DASHBOARD
ADMIN PANEL  = FILAMENT
BACKEND      = LARAVEL
DATABASE     = MYSQL
```

Tujuan akhirnya adalah menyediakan satu sistem terpusat yang memungkinkan pemilik/admin mengetahui kondisi setiap cabang sekaligus mendapatkan gambaran keseluruhan bisnis melalui dashboard konsolidasi.
