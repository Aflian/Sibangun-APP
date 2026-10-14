# Database Specification

# Sistem Manajemen Toko Bangunan Multi-Cabang

**Version:** 1.0
**Status:** Final MVP
**Database:** MySQL
**Backend:** Laravel
**Admin Panel:** Filament
**POS:** Custom POS
**Total Tables:** 21

---

# 1. Database Architecture

Database dirancang untuk mendukung sistem toko bangunan dengan konsep **multi-cabang**.

Prinsip utama:

* `products`, `categories`, `units`, `customers`, dan `vendors` bersifat global.
* `stock`, `sales`, `purchases`, `receivables`, `payables`, `cash_transactions`, dan `delivery_orders` terikat dengan cabang.
* Admin dapat melihat seluruh cabang.
* Staff hanya dapat mengakses cabang yang ditugaskan.
* `branch_stocks` menyimpan stok terkini.
* `stock_movements` menyimpan histori perubahan stok.
* `cash_transactions` menjadi ledger transaksi kas sederhana.
* Role hanya terdiri dari `admin` dan `staff`.
* Tidak menggunakan tabel `roles` dan `permissions` untuk MVP.

---

# 2. Daftar Tabel

## Authentication & Branch

1. `users`
2. `branches`

## Master Data

3. `categories`
4. `units`
5. `products`
6. `customers`
7. `vendors`

## Inventory

8. `branch_stocks`
9. `stock_movements`
10. `stock_transfers`
11. `stock_transfer_items`

## Sales

12. `sales`
13. `sale_items`
14. `payments`

## Purchasing

15. `purchases`
16. `purchase_items`

## Finance

17. `receivables`
18. `payables`
19. `cash_transactions`

## Delivery

20. `delivery_orders`
21. `delivery_order_items`

---

# 3. Relationship Overview

```text
branches
   │
   ├── users
   ├── branch_stocks
   ├── stock_movements
   ├── stock_transfers
   ├── sales
   ├── purchases
   ├── payments
   ├── receivables
   ├── payables
   ├── cash_transactions
   └── delivery_orders


categories ───────┐
                  │
units ────────────┼── products
                  │
                  ├── branch_stocks
                  ├── stock_movements
                  ├── sale_items
                  ├── purchase_items
                  ├── stock_transfer_items
                  └── delivery_order_items


customers
   ├── sales
   ├── receivables
   └── delivery_orders


vendors
   ├── purchases
   └── payables


sales
   ├── sale_items
   ├── payments
   └── receivables


purchases
   ├── purchase_items
   └── payables


stock_transfers
   └── stock_transfer_items


delivery_orders
   └── delivery_order_items
```

---

# 4. Table Specification

# 4.1 `branches`

Menyimpan data seluruh cabang toko.

## Fields

| Field      | Type                      | Null | Key    | Default | Description      |
| ---------- | ------------------------- | ---: | ------ | ------- | ---------------- |
| id         | BIGINT UNSIGNED           |   No | PK     | Auto    | ID cabang        |
| code       | VARCHAR(20)               |   No | UNIQUE | -       | Kode cabang      |
| name       | VARCHAR(100)              |   No | -      | -       | Nama cabang      |
| address    | TEXT                      |  Yes | -      | NULL    | Alamat           |
| phone      | VARCHAR(30)               |  Yes | -      | NULL    | Nomor telepon    |
| status     | ENUM('active','inactive') |   No | INDEX  | active  | Status cabang    |
| created_at | TIMESTAMP                 |  Yes | -      | NULL    | Waktu dibuat     |
| updated_at | TIMESTAMP                 |  Yes | -      | NULL    | Waktu diperbarui |

## Rules

* `code` harus unik.
* Cabang inactive tidak dapat digunakan untuk transaksi baru.
* Admin dapat memiliki `branch_id = NULL`.
* Staff wajib memiliki branch.

---

# 4.2 `users`

Menyimpan data pengguna sistem.

## Fields

| Field      | Type                      | Null | Key    | Default | Description      |
| ---------- | ------------------------- | ---: | ------ | ------- | ---------------- |
| id         | BIGINT UNSIGNED           |   No | PK     | Auto    | ID user          |
| branch_id  | BIGINT UNSIGNED           |  Yes | FK     | NULL    | Cabang user      |
| name       | VARCHAR(100)              |   No | -      | -       | Nama user        |
| email      | VARCHAR(150)              |   No | UNIQUE | -       | Email login      |
| password   | VARCHAR(255)              |   No | -      | -       | Password         |
| role       | ENUM('admin','staff')     |   No | INDEX  | staff   | Role             |
| status     | ENUM('active','inactive') |   No | INDEX  | active  | Status user      |
| created_at | TIMESTAMP                 |  Yes | -      | NULL    | Waktu dibuat     |
| updated_at | TIMESTAMP                 |  Yes | -      | NULL    | Waktu diperbarui |

## Relationships

```text
users.branch_id → branches.id
```

## Rules

### Admin

```text
role = admin
branch_id = NULL
```

### Staff

```text
role = staff
branch_id != NULL
```

Staff hanya dapat mengakses data:

```text
branch_id = auth()->user()->branch_id
```

---

# 4.3 `categories`

Menyimpan kategori produk.

## Fields

| Field       | Type                      | Null | Key   | Default |
| ----------- | ------------------------- | ---: | ----- | ------- |
| id          | BIGINT UNSIGNED           |   No | PK    | Auto    |
| name        | VARCHAR(100)              |   No | -     | -       |
| description | TEXT                      |  Yes | -     | NULL    |
| status      | ENUM('active','inactive') |   No | INDEX | active  |
| created_at  | TIMESTAMP                 |  Yes | -     | NULL    |
| updated_at  | TIMESTAMP                 |  Yes | -     | NULL    |
| deleted_at  | TIMESTAMP                 |  Yes | INDEX | NULL    |

---

# 4.4 `units`

Menyimpan satuan produk.

Contoh:

* pcs
* batang
* sak
* kg
* meter
* dus
* liter

## Fields

| Field      | Type            | Null | Key | Default |
| ---------- | --------------- | ---: | --- | ------- |
| id         | BIGINT UNSIGNED |   No | PK  | Auto    |
| name       | VARCHAR(50)     |   No | -   | -       |
| symbol     | VARCHAR(20)     |   No | -   | -       |
| created_at | TIMESTAMP       |  Yes | -   | NULL    |
| updated_at | TIMESTAMP       |  Yes | -   | NULL    |

---

# 4.5 `products`

Menyimpan master produk secara global.

Produk tidak dimiliki oleh satu cabang tertentu.

Stok produk per cabang disimpan pada:

```text
branch_stocks
```

## Fields

| Field          | Type                      | Null | Key    | Default |
| -------------- | ------------------------- | ---: | ------ | ------- |
| id             | BIGINT UNSIGNED           |   No | PK     | Auto    |
| category_id    | BIGINT UNSIGNED           |   No | FK     | -       |
| unit_id        | BIGINT UNSIGNED           |   No | FK     | -       |
| code           | VARCHAR(50)               |   No | UNIQUE | -       |
| barcode        | VARCHAR(100)              |  Yes | INDEX  | NULL    |
| name           | VARCHAR(150)              |   No | INDEX  | -       |
| brand          | VARCHAR(100)              |  Yes | -      | NULL    |
| purchase_price | DECIMAL(15,2)             |   No | -      | 0       |
| selling_price  | DECIMAL(15,2)             |   No | -      | 0       |
| minimum_stock  | DECIMAL(15,3)             |   No | -      | 0       |
| description    | TEXT                      |  Yes | -      | NULL    |
| status         | ENUM('active','inactive') |   No | INDEX  | active  |
| created_at     | TIMESTAMP                 |  Yes | -      | NULL    |
| updated_at     | TIMESTAMP                 |  Yes | -      | NULL    |
| deleted_at     | TIMESTAMP                 |  Yes | INDEX  | NULL    |

## Relationships

```text
products.category_id → categories.id
products.unit_id → units.id
```

## Important

`purchase_price` dan `selling_price` merupakan harga default/master.

Harga historis transaksi tetap disimpan pada:

```text
sale_items.unit_price
purchase_items.unit_price
```

---

# 4.6 `customers`

Menyimpan pelanggan secara global.

Pelanggan dapat melakukan transaksi pada beberapa cabang.

## Fields

| Field         | Type                         | Null | Key    | Default    |
| ------------- | ---------------------------- | ---: | ------ | ---------- |
| id            | BIGINT UNSIGNED              |   No | PK     | Auto       |
| code          | VARCHAR(50)                  |   No | UNIQUE | -          |
| name          | VARCHAR(150)                 |   No | INDEX  | -          |
| customer_type | ENUM('individual','company') |   No | -      | individual |
| phone         | VARCHAR(30)                  |  Yes | INDEX  | NULL       |
| email         | VARCHAR(150)                 |  Yes | -      | NULL       |
| address       | TEXT                         |  Yes | -      | NULL       |
| credit_limit  | DECIMAL(15,2)                |   No | -      | 0          |
| status        | ENUM('active','inactive')    |   No | INDEX  | active     |
| created_at    | TIMESTAMP                    |  Yes | -      | NULL       |
| updated_at    | TIMESTAMP                    |  Yes | -      | NULL       |
| deleted_at    | TIMESTAMP                    |  Yes | INDEX  | NULL       |

## Credit Limit

Batas kredit bersifat global.

Contoh:

```text
Credit Limit PT ABC = Rp50.000.000
```

Piutang:

```text
Cabang Pekanbaru = Rp10.000.000
Cabang Duri      = Rp5.000.000
Cabang Bangkinang = Rp3.000.000

Total = Rp18.000.000
```

Sisa kredit:

```text
Rp50.000.000 - Rp18.000.000
= Rp32.000.000
```

---

# 4.7 `vendors`

Menyimpan data pemasok/vendor.

## Fields

| Field          | Type                      | Null | Key    | Default |
| -------------- | ------------------------- | ---: | ------ | ------- |
| id             | BIGINT UNSIGNED           |   No | PK     | Auto    |
| code           | VARCHAR(50)               |   No | UNIQUE | -       |
| name           | VARCHAR(150)              |   No | INDEX  | -       |
| contact_person | VARCHAR(100)              |  Yes | -      | NULL    |
| phone          | VARCHAR(30)               |  Yes | -      | NULL    |
| email          | VARCHAR(150)              |  Yes | -      | NULL    |
| address        | TEXT                      |  Yes | -      | NULL    |
| status         | ENUM('active','inactive') |   No | INDEX  | active  |
| created_at     | TIMESTAMP                 |  Yes | -      | NULL    |
| updated_at     | TIMESTAMP                 |  Yes | -      | NULL    |
| deleted_at     | TIMESTAMP                 |  Yes | INDEX  | NULL    |

---

# 4.8 `branch_stocks`

Menyimpan **stok terkini setiap produk pada setiap cabang**.

Tabel ini sengaja dipertahankan sebagai bagian penting dari arsitektur.

## Fields

| Field             | Type            | Null | Key | Default |
| ----------------- | --------------- | ---: | --- | ------- |
| id                | BIGINT UNSIGNED |   No | PK  | Auto    |
| branch_id         | BIGINT UNSIGNED |   No | FK  | -       |
| product_id        | BIGINT UNSIGNED |   No | FK  | -       |
| quantity          | DECIMAL(15,3)   |   No | -   | 0       |
| reserved_quantity | DECIMAL(15,3)   |   No | -   | 0       |
| created_at        | TIMESTAMP       |  Yes | -   | NULL    |
| updated_at        | TIMESTAMP       |  Yes | -   | NULL    |

## Constraints

```text
UNIQUE(branch_id, product_id)
```

## Relationships

```text
branch_stocks.branch_id → branches.id
branch_stocks.product_id → products.id
```

## Fungsi

`branch_stocks` adalah sumber utama untuk mengetahui stok terkini.

Contoh:

```text
Produk Semen 50kg

Cabang Pekanbaru:
quantity = 100

Cabang Duri:
quantity = 75

Cabang Bangkinang:
quantity = 50
```

---

# 4.9 `stock_movements`

Menyimpan histori seluruh perubahan stok.

## Fields

| Field          | Type                                                                                  | Null | Key   | Default |
| -------------- | ------------------------------------------------------------------------------------- | ---: | ----- | ------- |
| id             | BIGINT UNSIGNED                                                                       |   No | PK    | Auto    |
| branch_id      | BIGINT UNSIGNED                                                                       |   No | FK    | -       |
| product_id     | BIGINT UNSIGNED                                                                       |   No | FK    | -       |
| user_id        | BIGINT UNSIGNED                                                                       |  Yes | FK    | NULL    |
| reference_type | VARCHAR(100)                                                                          |  Yes | INDEX | NULL    |
| reference_id   | BIGINT UNSIGNED                                                                       |  Yes | INDEX | NULL    |
| type           | ENUM('in','out','sale','purchase','transfer_in','transfer_out','adjustment','return') |   No | INDEX | -       |
| quantity       | DECIMAL(15,3)                                                                         |   No | -     | 0       |
| stock_before   | DECIMAL(15,3)                                                                         |  Yes | -     | NULL    |
| stock_after    | DECIMAL(15,3)                                                                         |  Yes | -     | NULL    |
| note           | TEXT                                                                                  |  Yes | -     | NULL    |
| created_at     | TIMESTAMP                                                                             |  Yes | INDEX | NULL    |

## Contoh

Penjualan 5 semen:

```text
stock_before = 100
quantity = 5
stock_after = 95
type = sale
```

---

# 4.10 `stock_transfers`

Header perpindahan stok antar-cabang.

## Fields

| Field                 | Type                                                      | Null | Key    | Default |
| --------------------- | --------------------------------------------------------- | ---: | ------ | ------- |
| id                    | BIGINT UNSIGNED                                           |   No | PK     | Auto    |
| transfer_number       | VARCHAR(50)                                               |   No | UNIQUE | -       |
| source_branch_id      | BIGINT UNSIGNED                                           |   No | FK     | -       |
| destination_branch_id | BIGINT UNSIGNED                                           |   No | FK     | -       |
| user_id               | BIGINT UNSIGNED                                           |  Yes | FK     | NULL    |
| transfer_date         | DATE                                                      |   No | INDEX  | -       |
| status                | ENUM('draft','approved','shipped','received','cancelled') |   No | INDEX  | draft   |
| note                  | TEXT                                                      |  Yes | -      | NULL    |
| created_at            | TIMESTAMP                                                 |  Yes | -      | NULL    |
| updated_at            | TIMESTAMP                                                 |  Yes | -      | NULL    |

## Rules

```text
source_branch_id != destination_branch_id
```

---

# 4.11 `stock_transfer_items`

Detail produk yang dipindahkan.

## Fields

| Field             | Type            | Null | Key | Default |
| ----------------- | --------------- | ---: | --- | ------- |
| id                | BIGINT UNSIGNED |   No | PK  | Auto    |
| stock_transfer_id | BIGINT UNSIGNED |   No | FK  | -       |
| product_id        | BIGINT UNSIGNED |   No | FK  | -       |
| quantity          | DECIMAL(15,3)   |   No | -   | 0       |
| created_at        | TIMESTAMP       |  Yes | -   | NULL    |
| updated_at        | TIMESTAMP       |  Yes | -   | NULL    |

---

# 4.12 `sales`

Header transaksi penjualan.

## Fields

| Field            | Type                                  | Null | Key    | Default |
| ---------------- | ------------------------------------- | ---: | ------ | ------- |
| id               | BIGINT UNSIGNED                       |   No | PK     | Auto    |
| branch_id        | BIGINT UNSIGNED                       |   No | FK     | -       |
| customer_id      | BIGINT UNSIGNED                       |  Yes | FK     | NULL    |
| user_id          | BIGINT UNSIGNED                       |   No | FK     | -       |
| invoice_number   | VARCHAR(50)                           |   No | UNIQUE | -       |
| transaction_date | DATETIME                              |   No | INDEX  | -       |
| subtotal         | DECIMAL(15,2)                         |   No | -      | 0       |
| discount         | DECIMAL(15,2)                         |   No | -      | 0       |
| tax              | DECIMAL(15,2)                         |   No | -      | 0       |
| total            | DECIMAL(15,2)                         |   No | -      | 0       |
| paid_amount      | DECIMAL(15,2)                         |   No | -      | 0       |
| status           | ENUM('draft','completed','cancelled') |   No | INDEX  | draft   |
| note             | TEXT                                  |  Yes | -      | NULL    |
| created_at       | TIMESTAMP                             |  Yes | -      | NULL    |
| updated_at       | TIMESTAMP                             |  Yes | -      | NULL    |

## Relationships

```text
sales.branch_id → branches.id
sales.customer_id → customers.id
sales.user_id → users.id
```

---

# 4.13 `sale_items`

Detail produk pada transaksi penjualan.

## Fields

| Field      | Type            | Null | Key | Default |
| ---------- | --------------- | ---: | --- | ------- |
| id         | BIGINT UNSIGNED |   No | PK  | Auto    |
| sale_id    | BIGINT UNSIGNED |   No | FK  | -       |
| product_id | BIGINT UNSIGNED |   No | FK  | -       |
| quantity   | DECIMAL(15,3)   |   No | -   | 0       |
| unit_price | DECIMAL(15,2)   |   No | -   | 0       |
| discount   | DECIMAL(15,2)   |   No | -   | 0       |
| subtotal   | DECIMAL(15,2)   |   No | -   | 0       |
| created_at | TIMESTAMP       |  Yes | -   | NULL    |
| updated_at | TIMESTAMP       |  Yes | -   | NULL    |

## Important

Harga transaksi tidak boleh bergantung pada harga produk saat ini.

Contoh:

```text
Harga produk saat ini = Rp60.000

Harga saat transaksi = Rp55.000
```

Maka:

```text
sale_items.unit_price = 55.000
```

---

# 4.14 `payments`

Menyimpan pembayaran transaksi penjualan.

Satu transaksi dapat memiliki lebih dari satu pembayaran.

## Fields

| Field            | Type                                   | Null | Key   | Default |
| ---------------- | -------------------------------------- | ---: | ----- | ------- |
| id               | BIGINT UNSIGNED                        |   No | PK    | Auto    |
| sale_id          | BIGINT UNSIGNED                        |   No | FK    | -       |
| branch_id        | BIGINT UNSIGNED                        |   No | FK    | -       |
| payment_method   | ENUM('cash','transfer','qris','debit') |   No | INDEX | cash    |
| amount           | DECIMAL(15,2)                          |   No | -     | 0       |
| payment_date     | DATETIME                               |   No | INDEX | -       |
| reference_number | VARCHAR(100)                           |  Yes | -     | NULL    |
| note             | TEXT                                   |  Yes | -     | NULL    |
| created_by       | BIGINT UNSIGNED                        |  Yes | FK    | NULL    |
| created_at       | TIMESTAMP                              |  Yes | -     | NULL    |

## Contoh Mixed Payment

```text
Total transaksi = Rp1.000.000

Cash = Rp400.000
QRIS = Rp600.000
```

Maka terdapat dua record pada `payments`.

---

# 4.15 `purchases`

Header transaksi pembelian.

## Fields

| Field           | Type                                   | Null | Key    | Default |
| --------------- | -------------------------------------- | ---: | ------ | ------- |
| id              | BIGINT UNSIGNED                        |   No | PK     | Auto    |
| branch_id       | BIGINT UNSIGNED                        |   No | FK     | -       |
| vendor_id       | BIGINT UNSIGNED                        |   No | FK     | -       |
| user_id         | BIGINT UNSIGNED                        |   No | FK     | -       |
| purchase_number | VARCHAR(50)                            |   No | UNIQUE | -       |
| purchase_date   | DATETIME                               |   No | INDEX  | -       |
| subtotal        | DECIMAL(15,2)                          |   No | -      | 0       |
| discount        | DECIMAL(15,2)                          |   No | -      | 0       |
| tax             | DECIMAL(15,2)                          |   No | -      | 0       |
| total           | DECIMAL(15,2)                          |   No | -      | 0       |
| paid_amount     | DECIMAL(15,2)                          |   No | -      | 0       |
| payment_method  | ENUM('cash','transfer','qris','debit') |  Yes | INDEX  | NULL    |
| status          | ENUM('draft','completed','cancelled')  |   No | INDEX  | draft   |
| note            | TEXT                                   |  Yes | -      | NULL    |
| created_at      | TIMESTAMP                              |  Yes | -      | NULL    |
| updated_at      | TIMESTAMP                              |  Yes | -      | NULL    |

---

# 4.16 `purchase_items`

Detail produk pembelian.

## Fields

| Field       | Type            | Null | Key | Default |
| ----------- | --------------- | ---: | --- | ------- |
| id          | BIGINT UNSIGNED |   No | PK  | Auto    |
| purchase_id | BIGINT UNSIGNED |   No | FK  | -       |
| product_id  | BIGINT UNSIGNED |   No | FK  | -       |
| quantity    | DECIMAL(15,3)   |   No | -   | 0       |
| unit_price  | DECIMAL(15,2)   |   No | -   | 0       |
| subtotal    | DECIMAL(15,2)   |   No | -   | 0       |
| created_at  | TIMESTAMP       |  Yes | -   | NULL    |
| updated_at  | TIMESTAMP       |  Yes | -   | NULL    |

---

# 4.17 `receivables`

Menyimpan piutang pelanggan.

## Fields

| Field            | Type                                                  | Null | Key   | Default |
| ---------------- | ----------------------------------------------------- | ---: | ----- | ------- |
| id               | BIGINT UNSIGNED                                       |   No | PK    | Auto    |
| branch_id        | BIGINT UNSIGNED                                       |   No | FK    | -       |
| customer_id      | BIGINT UNSIGNED                                       |   No | FK    | -       |
| sale_id          | BIGINT UNSIGNED                                       |   No | FK    | -       |
| due_date         | DATE                                                  |   No | INDEX | -       |
| original_amount  | DECIMAL(15,2)                                         |   No | -     | 0       |
| paid_amount      | DECIMAL(15,2)                                         |   No | -     | 0       |
| remaining_amount | DECIMAL(15,2)                                         |   No | -     | 0       |
| status           | ENUM('unpaid','partial','due_today','overdue','paid') |   No | INDEX | unpaid  |
| created_at       | TIMESTAMP                                             |  Yes | -     | NULL    |
| updated_at       | TIMESTAMP                                             |  Yes | -     | NULL    |

## Status

### unpaid

Belum ada pembayaran.

### partial

Sebagian sudah dibayar.

### due_today

Jatuh tempo hari ini.

### overdue

Melewati tanggal jatuh tempo.

### paid

Sudah lunas.

---

# 4.18 `payables`

Menyimpan hutang perusahaan kepada vendor.

## Fields

| Field            | Type                                                  | Null | Key   | Default |
| ---------------- | ----------------------------------------------------- | ---: | ----- | ------- |
| id               | BIGINT UNSIGNED                                       |   No | PK    | Auto    |
| branch_id        | BIGINT UNSIGNED                                       |   No | FK    | -       |
| vendor_id        | BIGINT UNSIGNED                                       |   No | FK    | -       |
| purchase_id      | BIGINT UNSIGNED                                       |   No | FK    | -       |
| due_date         | DATE                                                  |   No | INDEX | -       |
| original_amount  | DECIMAL(15,2)                                         |   No | -     | 0       |
| paid_amount      | DECIMAL(15,2)                                         |   No | -     | 0       |
| remaining_amount | DECIMAL(15,2)                                         |   No | -     | 0       |
| status           | ENUM('unpaid','partial','due_today','overdue','paid') |   No | INDEX | unpaid  |
| created_at       | TIMESTAMP                                             |  Yes | -     | NULL    |
| updated_at       | TIMESTAMP                                             |  Yes | -     | NULL    |

---

# 4.19 `cash_transactions`

Ledger transaksi keuangan sederhana per cabang.

Tabel ini digunakan untuk mencatat arus kas.

## Fields

| Field            | Type                                                                                                      | Null | Key   | Default |
| ---------------- | --------------------------------------------------------------------------------------------------------- | ---: | ----- | ------- |
| id               | BIGINT UNSIGNED                                                                                           |   No | PK    | Auto    |
| branch_id        | BIGINT UNSIGNED                                                                                           |   No | FK    | -       |
| user_id          | BIGINT UNSIGNED                                                                                           |  Yes | FK    | NULL    |
| type             | ENUM('income','expense','sale','purchase','receivable_payment','payable_payment','transfer','adjustment') |   No | INDEX | -       |
| reference_type   | VARCHAR(100)                                                                                              |  Yes | INDEX | NULL    |
| reference_id     | BIGINT UNSIGNED                                                                                           |  Yes | INDEX | NULL    |
| payment_method   | ENUM('cash','transfer','qris','debit')                                                                    |   No | INDEX | cash    |
| amount           | DECIMAL(15,2)                                                                                             |   No | -     | 0       |
| transaction_date | DATETIME                                                                                                  |   No | INDEX | -       |
| description      | TEXT                                                                                                      |  Yes | -     | NULL    |
| created_at       | TIMESTAMP                                                                                                 |  Yes | -     | NULL    |

## Contoh

Penjualan cash:

```text
type = sale
payment_method = cash
amount = 500000
```

Pembayaran piutang:

```text
type = receivable_payment
payment_method = transfer
amount = 1000000
```

Pengeluaran:

```text
type = expense
payment_method = cash
amount = 250000
```

---

# 4.20 `delivery_orders`

Menyimpan surat jalan/pengiriman barang.

## Fields

| Field               | Type                                             | Null | Key    | Default |
| ------------------- | ------------------------------------------------ | ---: | ------ | ------- |
| id                  | BIGINT UNSIGNED                                  |   No | PK     | Auto    |
| branch_id           | BIGINT UNSIGNED                                  |   No | FK     | -       |
| customer_id         | BIGINT UNSIGNED                                  |  Yes | FK     | NULL    |
| stock_transfer_id   | BIGINT UNSIGNED                                  |  Yes | FK     | NULL    |
| delivery_number     | VARCHAR(50)                                      |   No | UNIQUE | -       |
| delivery_date       | DATE                                             |   No | INDEX  | -       |
| destination_address | TEXT                                             |  Yes | -      | NULL    |
| driver_name         | VARCHAR(100)                                     |  Yes | -      | NULL    |
| vehicle_number      | VARCHAR(30)                                      |  Yes | -      | NULL    |
| status              | ENUM('draft','prepared','delivered','cancelled') |   No | INDEX  | draft   |
| note                | TEXT                                             |  Yes | -      | NULL    |
| created_by          | BIGINT UNSIGNED                                  |  Yes | FK     | NULL    |
| created_at          | TIMESTAMP                                        |  Yes | -      | NULL    |
| updated_at          | TIMESTAMP                                        |  Yes | -      | NULL    |

## Fungsi

Digunakan untuk:

1. Pengiriman barang kepada customer.
2. Pengiriman barang antar-cabang.

---

# 4.21 `delivery_order_items`

Detail barang yang dikirim.

## Fields

| Field             | Type            | Null | Key | Default |
| ----------------- | --------------- | ---: | --- | ------- |
| id                | BIGINT UNSIGNED |   No | PK  | Auto    |
| delivery_order_id | BIGINT UNSIGNED |   No | FK  | -       |
| product_id        | BIGINT UNSIGNED |   No | FK  | -       |
| quantity          | DECIMAL(15,3)   |   No | -   | 0       |
| note              | TEXT            |  Yes | -   | NULL    |
| created_at        | TIMESTAMP       |  Yes | -   | NULL    |
| updated_at        | TIMESTAMP       |  Yes | -   | NULL    |

---

# 11. Foreign Key Relationships

## Branch

```text
users.branch_id
    → branches.id

branch_stocks.branch_id
    → branches.id

stock_movements.branch_id
    → branches.id

stock_transfers.source_branch_id
    → branches.id

stock_transfers.destination_branch_id
    → branches.id

sales.branch_id
    → branches.id

payments.branch_id
    → branches.id

purchases.branch_id
    → branches.id

receivables.branch_id
    → branches.id

payables.branch_id
    → branches.id

cash_transactions.branch_id
    → branches.id

delivery_orders.branch_id
    → branches.id
```

---

# 12. Product Relationships

```text
products.category_id
    → categories.id

products.unit_id
    → units.id

branch_stocks.product_id
    → products.id

stock_movements.product_id
    → products.id

stock_transfer_items.product_id
    → products.id

sale_items.product_id
    → products.id

purchase_items.product_id
    → products.id

delivery_order_items.product_id
    → products.id
```

---

# 13. Customer Relationships

```text
sales.customer_id
    → customers.id

receivables.customer_id
    → customers.id

delivery_orders.customer_id
    → customers.id
```

---

# 14. Vendor Relationships

```text
purchases.vendor_id
    → vendors.id

payables.vendor_id
    → vendors.id
```

---

# 15. Sales Relationships

```text
sale_items.sale_id
    → sales.id

payments.sale_id
    → sales.id

receivables.sale_id
    → sales.id
```

---

# 16. Purchase Relationships

```text
purchase_items.purchase_id
    → purchases.id

payables.purchase_id
    → purchases.id
```

---

# 17. Stock Transfer Relationships

```text
stock_transfer_items.stock_transfer_id
    → stock_transfers.id

delivery_orders.stock_transfer_id
    → stock_transfers.id
```

---

# 18. Stock Architecture

Stok menggunakan dua tabel utama:

```text
branch_stocks
       +
stock_movements
```

## `branch_stocks`

Berfungsi sebagai:

> Current Stock

Contoh:

```text
Cabang Pekanbaru
Produk Semen
Stock = 100
```

## `stock_movements`

Berfungsi sebagai:

> Stock History / Stock Ledger

Contoh:

```text
08:00 Pembelian +50
10:00 Penjualan -10
13:00 Transfer -20
15:00 Adjustment +5
```

Sehingga:

```text
Current Stock = 125
```

Namun aplikasi tidak perlu menghitung ulang dari seluruh histori setiap kali POS membutuhkan stok.

---

# 19. Stock Transaction Rules

## Penjualan

```text
sales
   ↓
sale_items
   ↓
branch_stocks.quantity -
   ↓
stock_movements
   ↓
cash_transactions
```

Jika kredit:

```text
sales
   ↓
receivables
```

---

## Pembelian

```text
purchases
   ↓
purchase_items
   ↓
branch_stocks.quantity +
   ↓
stock_movements
   ↓
cash_transactions
```

Jika kredit:

```text
purchases
   ↓
payables
```

---

# 20. Stock Transfer Flow

```text
Cabang A
   │
   │ Transfer 20 produk
   ▼
stock_transfers
   │
   ▼
stock_transfer_items
   │
   ├───────────────┐
   ▼               ▼
Cabang A         Cabang B
Stock -20        Stock +20
   │               │
   ▼               ▼
transfer_out    transfer_in
```

Semua perubahan harus tercatat pada `stock_movements`.

---

# 21. Sales Flow

## Cash Sale

```text
Customer
   ↓
POS
   ↓
sales
   ↓
sale_items
   ↓
payments
   ↓
branch_stocks (-)
   ↓
stock_movements
   ↓
cash_transactions
```

---

# 22. Credit Sale Flow

```text
Customer
   ↓
POS
   ↓
sales
   ↓
sale_items
   ↓
branch_stocks (-)
   ↓
stock_movements
   ↓
receivables
```

Ketika customer membayar:

```text
Payment
   ↓
receivables.paid_amount
   ↓
receivables.remaining_amount
   ↓
cash_transactions
```

---

# 23. Purchase Flow

```text
Vendor
   ↓
Purchase
   ↓
purchase_items
   ↓
branch_stocks (+)
   ↓
stock_movements
```

Jika pembelian tunai:

```text
purchase
   ↓
cash_transactions
```

Jika pembelian kredit:

```text
purchase
   ↓
payables
```

---

# 24. Cash Calculation

Saldo kas dapat dihitung berdasarkan transaksi:

```text
Saldo =
Total Income
-
Total Expense
```

Dengan filter:

```text
branch_id
payment_method
periode
```

Contoh:

```text
Cabang Pekanbaru

Cash In  = Rp100.000.000
Cash Out = Rp70.000.000

Saldo = Rp30.000.000
```

---

# 25. Customer Credit Calculation

Credit limit pelanggan disimpan langsung di:

```text
customers.credit_limit
```

Total piutang pelanggan:

```text
SUM(receivables.remaining_amount)
```

Dengan filter:

```text
customer_id
```

Sisa limit:

```text
credit_limit - total_outstanding
```

Contoh:

```text
Credit Limit       Rp50.000.000
Outstanding        Rp18.000.000
--------------------------------
Available Credit   Rp32.000.000
```

---

# 26. Branch Data Isolation

## Staff

Semua query operasional staff harus menggunakan:

```php
$user->branch_id
```

Contoh:

```php
Sale::where('branch_id', auth()->user()->branch_id)
    ->get();
```

Untuk stok:

```php
BranchStock::where(
    'branch_id',
    auth()->user()->branch_id
)->get();
```

Staff tidak boleh menentukan cabang melalui request:

```text
?branch_id=2
```

Cabang harus berasal dari user yang sedang login.

---

# 27. Admin Consolidated Data

Admin dapat melihat seluruh cabang.

Contoh total penjualan:

```sql
SELECT SUM(total)
FROM sales
WHERE status = 'completed';
```

Penjualan per cabang:

```sql
SELECT
    branch_id,
    SUM(total) AS total_sales
FROM sales
WHERE status = 'completed'
GROUP BY branch_id;
```

---

# 28. Index Recommendation

Index utama yang perlu dibuat:

## branches

```text
code UNIQUE
status INDEX
```

## users

```text
email UNIQUE
branch_id INDEX
role INDEX
status INDEX
```

## products

```text
code UNIQUE
barcode INDEX
name INDEX
category_id INDEX
unit_id INDEX
status INDEX
```

## customers

```text
code UNIQUE
name INDEX
phone INDEX
status INDEX
```

## vendors

```text
code UNIQUE
name INDEX
status INDEX
```

## branch_stocks

```text
UNIQUE(branch_id, product_id)
```

## stock_movements

```text
branch_id INDEX
product_id INDEX
type INDEX
reference_type INDEX
reference_id INDEX
created_at INDEX
```

## stock_transfers

```text
transfer_number UNIQUE
source_branch_id INDEX
destination_branch_id INDEX
status INDEX
transfer_date INDEX
```

## sales

```text
invoice_number UNIQUE
branch_id INDEX
customer_id INDEX
user_id INDEX
transaction_date INDEX
status INDEX
```

## purchases

```text
purchase_number UNIQUE
branch_id INDEX
vendor_id INDEX
purchase_date INDEX
status INDEX
```

## receivables

```text
branch_id INDEX
customer_id INDEX
sale_id INDEX
due_date INDEX
status INDEX
```

## payables

```text
branch_id INDEX
vendor_id INDEX
purchase_id INDEX
due_date INDEX
status INDEX
```

## cash_transactions

```text
branch_id INDEX
type INDEX
reference_type INDEX
reference_id INDEX
payment_method INDEX
transaction_date INDEX
```

---

# 29. Delete Rules

Untuk data master yang sudah pernah digunakan dalam transaksi, sebaiknya menggunakan:

```text
Soft Delete
```

Terutama:

```text
categories
products
customers
vendors
```

Jangan melakukan hard delete terhadap produk yang sudah mempunyai histori transaksi.

Contoh:

```text
products.deleted_at
```

digunakan untuk menandai produk tidak lagi aktif.

---

# 30. Transaction Integrity

Operasi berikut wajib menggunakan:

```php
DB::transaction()
```

### Penjualan

```text
sales
sale_items
payments
branch_stocks
stock_movements
cash_transactions
receivables
```

### Pembelian

```text
purchases
purchase_items
branch_stocks
stock_movements
cash_transactions
payables
```

### Transfer

```text
stock_transfers
stock_transfer_items
branch_stocks source
branch_stocks destination
stock_movements source
stock_movements destination
```

Tujuannya menjaga konsistensi data.

---

# 31. Status Rules

## Sales

```text
draft
completed
cancelled
```

Hanya transaksi:

```text
completed
```

yang memengaruhi stok dan laporan penjualan.

---

## Purchases

```text
draft
completed
cancelled
```

Hanya:

```text
completed
```

yang memengaruhi stok dan laporan pembelian.

---

## Stock Transfer

```text
draft
approved
shipped
received
cancelled
```

Alur:

```text
draft
 ↓
approved
 ↓
shipped
 ↓
received
```

---

## Delivery Order

```text
draft
prepared
delivered
cancelled
```

---

# 32. Financial Integrity

Nilai berikut harus dihitung dengan hati-hati:

```text
sales.total
sales.paid_amount

receivables.original_amount
receivables.paid_amount
receivables.remaining_amount

payables.original_amount
payables.paid_amount
payables.remaining_amount
```

Rule:

```text
remaining_amount =
original_amount - paid_amount
```

Nilai tidak boleh menjadi negatif.

---

# 33. MVP Limitations

Database ini belum dirancang sebagai sistem akuntansi double-entry penuh.

Belum tersedia:

```text
accounts
accounting_entries
accounting_entry_items
```

Dengan demikian sistem dapat menyediakan:

* laporan penjualan;
* laporan pembelian;
* laporan kas;
* laporan piutang;
* laporan hutang;
* laporan stok;
* laporan transaksi per cabang.

Namun belum ditujukan untuk:

* General Ledger;
* Balance Sheet;
* Trial Balance;
* jurnal double-entry;
* laporan akuntansi lengkap.

Jika sistem berkembang menjadi ERP/accounting system, tabel tersebut dapat ditambahkan kemudian.

---

# 34. Cash Account Limitation

MVP menggunakan:

```text
cash_transactions
```

dengan:

```text
payment_method
```

sebagai pembeda metode pembayaran.

Belum menggunakan tabel:

```text
cash_accounts
```

Jika kebutuhan berkembang menjadi:

```text
Kasir 1
Kasir 2
Bank BCA
Bank Mandiri
QRIS
Kas Besar
```

dengan saldo masing-masing, maka tabel `cash_accounts` dapat ditambahkan pada fase berikutnya.

---

# 35. Receivable Payment Limitation

MVP menggunakan:

```text
receivables.paid_amount
receivables.remaining_amount
```

untuk pengelolaan pembayaran.

Untuk pembayaran sederhana:

```text
1 pembayaran → 1 piutang
```

sudah cukup.

Jika nantinya diperlukan:

```text
1 pembayaran → banyak invoice
```

maka dapat ditambahkan:

```text
receivable_payments
receivable_payment_allocations
```

Namun tabel tersebut **tidak diperlukan untuk MVP**.

---

# 36. Payable Payment Limitation

Konsep yang sama berlaku untuk hutang.

MVP:

```text
1 pembayaran → 1 payable
```

Jika membutuhkan:

```text
1 pembayaran → banyak invoice vendor
```

dapat ditambahkan sistem payment allocation pada fase selanjutnya.

---

# 37. Inventory Valuation

MVP menyimpan:

```text
products.purchase_price
```

sebagai harga beli default.

Namun harga pembelian aktual disimpan di:

```text
purchase_items.unit_price
```

Hal ini penting karena harga vendor dapat berubah.

Contoh:

```text
Pembelian Januari
Rp50.000

Pembelian Februari
Rp55.000

Pembelian Maret
Rp52.000
```

Ketiga harga harus tetap tersimpan pada histori pembelian.

---

# 38. COGS / Profit Limitation

MVP belum menentukan metode perhitungan:

```text
COGS / HPP
```

seperti:

```text
FIFO
Average Cost
Weighted Average
```

Jika sistem membutuhkan laporan laba yang akurat, metode costing harus ditentukan terlebih dahulu.

Jangan menghitung laba hanya dengan:

```text
selling_price - products.purchase_price
```

karena harga beli aktual dapat berbeda-beda.

---

# 39. Migration Order

Migration Laravel disarankan dibuat dengan urutan berikut:

```text
01_create_branches_table
02_create_users_table
03_create_categories_table
04_create_units_table
05_create_products_table
06_create_customers_table
07_create_vendors_table
08_create_branch_stocks_table
09_create_stock_movements_table
10_create_stock_transfers_table
11_create_stock_transfer_items_table
12_create_sales_table
13_create_sale_items_table
14_create_payments_table
15_create_purchases_table
16_create_purchase_items_table
17_create_receivables_table
18_create_payables_table
19_create_cash_transactions_table
20_create_delivery_orders_table
21_create_delivery_order_items_table
```

---

# 40. Final Database Structure

```text
21 TABLES
│
├── AUTH
│   ├── users
│   └── branches
│
├── MASTER
│   ├── categories
│   ├── units
│   ├── products
│   ├── customers
│   └── vendors
│
├── STOCK
│   ├── branch_stocks
│   ├── stock_movements
│   ├── stock_transfers
│   └── stock_transfer_items
│
├── SALES
│   ├── sales
│   ├── sale_items
│   └── payments
│
├── PURCHASE
│   ├── purchases
│   └── purchase_items
│
├── FINANCE
│   ├── receivables
│   ├── payables
│   └── cash_transactions
│
└── DELIVERY
    ├── delivery_orders
    └── delivery_order_items
```

---

# 41. Final Design Decision

Database MVP **ditetapkan menggunakan 21 tabel**.

Tidak dilakukan penyederhanaan lebih lanjut karena setiap tabel memiliki tanggung jawab yang jelas.

Khususnya:

```text
branch_stocks
```

**WAJIB dipertahankan** sebagai penyimpan stok terkini per cabang.

Arsitektur stok:

```text
                 PRODUCTS
                    │
          ┌─────────┴─────────┐
          │                   │
   BRANCH_STOCKS       STOCK_MOVEMENTS
          │                   │
      CURRENT STOCK       STOCK HISTORY
```

Dengan desain tersebut:

* POS dapat mengambil stok dengan cepat.
* Dashboard dapat menampilkan stok dengan cepat.
* Histori stok tetap tersedia.
* Audit perubahan stok dapat dilakukan.
* Multi-cabang dapat berjalan dengan jelas.
* Transfer stok dapat dilacak.
* Database tidak terlalu kompleks.
* Database tetap dapat dikembangkan ke sistem yang lebih besar.

---

# 42. Tables Not Included in MVP

Tabel berikut **sengaja tidak dibuat** pada tahap MVP:

```text
roles
permissions
role_user
permission_role

customer_credit_limits

payment_methods

cash_accounts

expenses
incomes

receivable_payments
payable_payments

accounts
accounting_entries
accounting_entry_items

activity_logs
```

Tabel tersebut dapat ditambahkan apabila kebutuhan sistem berkembang.

---

# 43. MVP Principle

Prinsip utama database:

> **Sederhana, tetapi tidak terlalu sederhana.**

Sistem tidak dibuat dengan terlalu banyak tabel yang belum dibutuhkan, tetapi juga tidak menghilangkan tabel penting yang dapat menyebabkan masalah ketika aplikasi mulai digunakan secara nyata.

Struktur final:

```text
21 TABLES
+
BRANCH_STOCKS
+
SERVICE LAYER
+
TRANSACTIONAL INTEGRITY
+
MULTI-BRANCH ISOLATION
```

menjadi dasar implementasi sistem Manajemen Toko Bangunan Multi-Cabang.
