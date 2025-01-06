## Tentang Aplikasi Web4 Mahasiswa
Ini adalah source code praktikum matakuliah pemrograman web4 program studi sistem informasi
Universitas Primagraha

## Cara Install
Cara instalalasi, sebagai berikut:

- git clone https://github.com/rusdiansyah/web4_mahasiswa.git
- composer install
- copy .env di terminal dengan cara copy .env.example .env
- buat database pada mysql (phpmyadmin / Squel Ace)
- edit .env sesuaikan dengan database yang dibuat
- php artisan migrate
- php artisan make:filament-user 

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
