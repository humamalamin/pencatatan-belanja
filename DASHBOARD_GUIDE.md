# Panduan Dashboard Grafik Penjualan

## Widget yang Tersedia

Dashboard ini telah dilengkapi dengan beberapa widget untuk menampilkan grafik dan statistik pengeluaran:

### 1. ExpenseStats Widget
- **Fungsi**: Menampilkan statistik ringkasan pengeluaran
- **Informasi yang ditampilkan**:
  - Pengeluaran hari ini
  - Pengeluaran bulan ini
  - Total transaksi keseluruhan

### 2. ExpenseChart Widget
- **Fungsi**: Grafik line chart untuk tren pengeluaran 7 hari terakhir
- **Tipe**: Line chart dengan area fill
- **Warna**: Amber/Orange theme

### 3. ExpenseCategoryChart Widget
- **Fungsi**: Grafik doughnut chart untuk pengeluaran berdasarkan kategori item
- **Tipe**: Doughnut chart
- **Data**: Mengelompokkan berdasarkan nama item (sebagai proxy kategori)

### 4. MonthlyExpenseChart Widget
- **Fungsi**: Grafik bar chart untuk tren pengeluaran 6 bulan terakhir
- **Tipe**: Bar chart
- **Data**: Total pengeluaran per bulan

### 5. LatestExpenses Widget
- **Fungsi**: Tabel daftar pengeluaran terbaru
- **Informasi**: Judul, tanggal, total, dan foto struk
- **Limit**: 5 transaksi terbaru

## Cara Mengakses Dashboard

1. Login ke aplikasi Filament admin
2. Akses URL: `/admin`
3. Dashboard akan menampilkan semua widget secara otomatis

## Konfigurasi Widget

### Mengubah Urutan Widget
Edit file `app/Providers/Filament/AdminPanelProvider.php` dan ubah urutan dalam array `widgets`:

```php
->widgets([
    ExpenseStats::class,           // Urutan 1
    ExpenseChart::class,           // Urutan 2
    ExpenseCategoryChart::class,   // Urutan 3
    MonthlyExpenseChart::class,    // Urutan 4
    LatestExpenses::class,         // Urutan 5
    // ...
])
```

### Mengubah Sorting Widget
Edit properti `$sort` di masing-masing widget file:

```php
protected static ?int $sort = 1; // Angka lebih kecil = posisi lebih atas
```

### Mengubah Ukuran Widget
Edit properti `$columnSpan` di widget:

```php
protected int|string|array $columnSpan = 'full'; // Full width
// atau
protected int|string|array $columnSpan = 2; // 2 kolom
```

## Kustomisasi Grafik

### Mengubah Warna
Edit array `backgroundColor` dan `borderColor` di method `getData()`:

```php
'backgroundColor' => '#f59e0b20', // Warna background dengan opacity
'borderColor' => '#f59e0b',       // Warna border
```

### Mengubah Periode Data
Edit variabel di method data collection:

```php
// Untuk ExpenseChart - ubah jumlah hari
$days = 14; // Dari 7 hari menjadi 14 hari

// Untuk MonthlyExpenseChart - ubah jumlah bulan
$months = 12; // Dari 6 bulan menjadi 12 bulan
```

## Troubleshooting

### Widget Tidak Muncul
1. Pastikan widget sudah didaftarkan di `AdminPanelProvider`
2. Clear cache: `php artisan config:clear`
3. Clear view cache: `php artisan view:clear`

### Data Tidak Tampil
1. Pastikan ada data expense di database
2. Periksa field `date_shopping` terisi dengan benar
3. Periksa field `amount` terisi dengan benar

### Grafik Error
1. Pastikan Chart.js sudah ter-load dengan benar
2. Periksa console browser untuk error JavaScript
3. Pastikan data yang dikembalikan sesuai format Chart.js

## Penambahan Widget Baru

Untuk menambahkan widget baru:

1. Buat file widget di `app/Filament/Widgets/`
2. Extend class yang sesuai (ChartWidget, StatsOverviewWidget, TableWidget)
3. Daftarkan di `AdminPanelProvider`
4. Clear cache aplikasi 