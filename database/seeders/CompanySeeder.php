<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan tabel sebelum seeding untuk menghindari duplikasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Company::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $companies = [
            [
                'name' => 'KPU Kota Makassar',
                'address' => 'Jl Perumnas Antang Raya No. 2A, Makassar',
                'phone' => '081242117575 (Pak Amhar)',
                'description' => 'Lembaga penyelenggara pemilihan umum yang bertugas mengelola proses demokrasi dan pemilu di tingkat daerah.',
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => '-'
            ],
            [
                'name' => 'TVRI Sulawesi Selatan',
                'address' => 'Jl. Pajonga Dg. Ngalle No.14 (Kakatua), Makassar',
                'phone' => '087788122090 (Bu Andi Hamdianah)',
                'description' => 'Lembaga penyiaran publik yang bergerak di bidang produksi konten televisi, berita, dan media penyiaran.',
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Ruswandi (Alumni TMJ), Muh Nur Alim (0823 9459 2542), Siti Nurhaliza (0853 9757 6656)'
            ],
            [
                'name' => 'PT. Mimoza Multimedia',
                'address' => 'Jln HB. Jassin, Kota Gorontalo',
                'phone' => '0811438313 (Pak Budy Santoso)',
                'description' => 'Perusahaan yang bergerak di bidang teknologi informasi, multimedia, dan layanan digital.',
                'jalur' => 'Mandiri',
                'kuota' => '2 Orang',
                'alumni_magang' => '-'
            ],
            [
                'name' => 'Bapenda Sulsel (Samsat Pembantu)',
                'address' => 'Jl. A. P. Pettarani No.1, Masale, Kec. Panakkukang, Kota Makassar, Sulawesi Selatan',
                'phone' => '082189903646 (Bu Irmawati)',
                'description' => 'Instansi pemerintah yang bertanggung jawab atas pengelolaan pendapatan daerah dan pelayanan administrasi kendaraan bermotor.',
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => '-'
            ],
            [
                'name' => 'PT. CLOUD HOSTING INDONESIA',
                'address' => 'Jl. Raya Pajajaran No.17, RT.06/RW.13, Baranangsiang, Kec. Bogor Tim., Kota Bogor, Jawa Barat',
                'phone' => '-',
                'description' => 'Perusahaan penyedia layanan cloud computing, web hosting, server, dan infrastruktur teknologi informasi.',
                'jalur' => 'MSIB dan Mandiri',
                'kuota' => '1 Orang',
                'alumni_magang' => 'Azwar Meizia Kusumah (089515575275)'
            ],
            [
                'name' => 'PT. Surabaya Industrial Estate Rungkut',
                'address' => 'Jl. Rungkut Industri Raya No.10, Rungkut Tengah, Kec. Gn. Anyar, Surabaya, Jawa Timur 60293',
                'phone' => '-',
                'description' => 'Perusahaan pengelola kawasan industri yang menyediakan fasilitas dan layanan bagi berbagai sektor industri.',
                'jalur' => 'MSIB',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Andi Yusdar Al Imran (082393529701)'
            ],
            [
                'name' => 'Bakti Kominfo Wilker 6 Makassar',
                'address' => 'Jl. A.P. Pettarani, Masale, Kec. Panakkukang, Graha Pettarani Kota Makassar Sulawesi Selatan',
                'phone' => '081241590003',
                'description' => 'Unit kerja Kementerian Komunikasi dan Digital yang mendukung pemerataan akses telekomunikasi dan transformasi digital.',
                'jalur' => 'Mandiri',
                'kuota' => '6 Orang',
                'alumni_magang' => 'Guido Septivianus Prasetio Ventura (081243196922), Brigitha Hedvika Vanesha (085394347750), Andi Muhammad Nuzul (082189641051), Muhammad Kahfy Noor Alwani (087864959745), Wahyu Hidayah (085240232469), Ardi Ansyah (085210225253)'
            ],
            [
                'name' => 'PT. Angkasa Pura I Bandar Udara Sultan Hasanuddin',
                'address' => 'Jalan Raya Airport No. 1 Makassar, Sulawesi 90552, Indonesia',
                'phone' => '+62 411 550123',
                'description' => 'Perusahaan pengelola bandar udara yang menyediakan layanan operasional dan fasilitas penerbangan.',
                'jalur' => 'Mandiri',
                'kuota' => '4 Orang',
                'alumni_magang' => 'Achmad Fitrah (082190367250)'
            ],
            [
                'name' => 'PT. HADJI KALLA',
                'address' => 'Jl. DR. Sam Ratulangi No.8, Kunjung Mae, Kec. Makassar',
                'phone' => '-',
                'description' => 'Kelompok usaha nasional yang bergerak di bidang otomotif, energi, logistik, konstruksi, dan properti.',
                'jalur' => 'Mandiri',
                'kuota' => '4 Orang',
                'alumni_magang' => 'Andi Yuditya Mustika Ramadani (085342589658)'
            ],
            [
                'name' => 'AirNav Indonesia cabang MATSC',
                'address' => 'Baji Mangngai, Kec. Mandai, Kabupaten Maros, Sulawesi Selatan 90552',
                'phone' => '082192977949',
                'description' => 'Lembaga penyelenggara pelayanan navigasi penerbangan untuk menjamin keselamatan lalu lintas udara.',
                'jalur' => 'Mandiri',
                'kuota' => '4 Orang',
                'alumni_magang' => 'Yulianti (085340297738)'
            ],
            [
                'name' => 'PT Telkom Witel Sultra',
                'address' => 'Jl. Jend. Ahmad Yani No.08, Pondambea, Kec. Kadia, Kota Kendari, Sulawesi Tenggara 93111',
                'phone' => '-',
                'description' => 'Unit PT Telkom Indonesia yang menyediakan layanan telekomunikasi dan teknologi informasi di wilayah Sulawesi Tenggara.',
                'jalur' => 'Mandiri',
                'kuota' => '4 Orang',
                'alumni_magang' => 'Ahmad Muliyadi K (082393176310)'
            ],
            [
                'name' => 'Kantor Otoritas Bandar Udara Wilayah V Makassar',
                'address' => 'Jl. Otoritas Bandara No.5, Kabupaten Maros, Sulawesi Selatan',
                'phone' => '081242427575',
                'description' => 'Instansi pemerintah yang mengawasi dan mengatur operasional penerbangan serta bandar udara.',
                'jalur' => 'Mandiri',
                'kuota' => '2 Orang',
                'alumni_magang' => 'Milzam Fauzan Amlik (082344530443), Rafif Abdullah (088245715939)'
            ],
            [
                'name' => 'PT. Telekomunikasi Indonesia Tbk (PT TELKOM REGIONAL V)',
                'address' => 'Jl. A. P. Pettarani No.02, Gn Sari, Kec. Rappocini, Kota Makassar, Sulawesi Selatan 90223',
                'phone' => '081333387284',
                'description' => 'Perusahaan telekomunikasi nasional yang menyediakan layanan internet, komunikasi, dan solusi digital.',
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Nadhifa Dwi Hariady (085901646799)'
            ],
            [
                'name' => 'Dinas Pendidikan Sulsel',
                'address' => 'Jl. Perintis Kemerdekaan KM 10, Tamalanrea Indah, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan 90245',
                'phone' => '-',
                'description' => 'Instansi pemerintah yang mengelola dan mengembangkan sektor pendidikan di Provinsi Sulawesi Selatan.',
                'jalur' => 'Mandiri',
                'kuota' => '2 Orang',
                'alumni_magang' => 'Selpi Sera (082256760575)'
            ],
            [
                'name' => 'UPT TIK PNUP',
                'address' => 'Jl. Perintis Kemerdekaan, KM. 10, Ujung Pandang, Kota Makassar',
                'phone' => '085231052056 (Pak Ahyar)',
                'description' => 'Unit pelaksana teknis yang bertanggung jawab terhadap pengelolaan teknologi informasi dan komunikasi di Politeknik Negeri Ujung Pandang.',
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'MUHAMMAD ZAEDIL (085256629509)'
            ],
            [
                'name' => 'Balai Besar Pengembangan SDM dan Penelitian Komunikasi dan Informatika Makassar',
                'address' => 'Jl. Prof. Abdurrahman Basalamah II No.25, Karampuang, Kec. Panakkukang, Kota Makassar, Sulawesi Selatan 90231',
                'phone' => '-',
                'description' => 'Unit Kementerian Komunikasi dan Digital yang berfokus pada pengembangan sumber daya manusia dan penelitian di bidang TIK.',
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Muhammad Faisal (085346064591), Andi Arma Gansa (082265413233)'
            ],
            [
                'name' => 'Kalla Group',
                'address' => 'Jl. DR Ratulangi No. 8 Kota Makassar',
                'phone' => '-',
                'description' => 'Kelompok perusahaan yang bergerak di berbagai sektor, seperti otomotif, energi, logistik, properti, dan pendidikan.',
                'jalur' => 'MSIB dan Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Anugrah Syawal Syamdi Putra (085656085376)'
            ],
            [
                'name' => 'Jurusan Teknik Informatika dan Komputer PNUP',
                'address' => 'Jl. Perintis Kemerdekaan, KM. 10, Ujung Pandang, Kota Makassar',
                'phone' => '082189903646 (Bu Irmawati)',
                'description' => 'Unit akademik yang menyelenggarakan pendidikan dan pengembangan di bidang teknologi informasi dan komputer.',
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Muh Rezki (081937268916), Muh. Fauzan Arkas (0895321003005), Muh Yasril Wasna (085349617881)'
            ],
            [
                'name' => 'Balai Besar Wilayah Sungai Pompengan Jeneberang',
                'address' => 'Jl. Sekolah Guru Perawat No. 3 Kota Makassar, Sulawesi Selatan, 90222',
                'phone' => '-',
                'description' => 'Instansi pemerintah yang mengelola sumber daya air serta pembangunan dan pemeliharaan infrastruktur pengairan.',
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Muhammad Budi Setiawan (087831200387), Nur Rohman Toyib Prasetyo (082192111792), Muhammad Ghaza Maulana (082217783724), Zhafirah Majdiyah (Alumni TMJ)'
            ],
            [
                'name' => 'Badan Pengembangan SDM Kementerian Kominfo di Kab. Pohuwato, Gorontalo',
                'address' => 'Jl. M. H. Thamrin, Palopo, Kec. Marisa, Kabupaten Pohuwato, Gorontalo 96265',
                'phone' => '-',
                'description' => 'Instansi yang berfokus pada peningkatan kompetensi sumber daya manusia di bidang komunikasi, informatika, dan teknologi digital.',
                'jalur' => 'MSIB',
                'kuota' => '7 Orang',
                'alumni_magang' => 'Jeel Jaya (082248245214)'
            ],
            // 16 Sisanya (Deskripsi generik atau kosong)
            [
                'name' => 'PT. Mitra Visindo Teknologi Makassar',
                'address' => 'JL. Bau Mangga I, Desa/Kelurahan Masale, Kec. Panakkukang, Kota Makassar',
                'phone' => '082189903646 (Bu Irmawati)',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Andi Rifqial Nur (085263563381)'
            ],
            [
                'name' => 'Kantor Perwakilan Bank Indonesia. Prov Sulsel',
                'address' => 'Jl. Jend. Sudirman  No.3 Pisang Utara, Kec. Ujung Pandang, Makassar',
                'phone' => '0812 4193 5508',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Afriza Maulana Ilham (0823 9342 1544), Supratman (0812 43104825)'
            ],
            [
                'name' => 'PT. Bumi Jasa Utama',
                'address' => 'Jl. Perintis Kemerdekaan Km.16 No.2, Pai, Makassar',
                'phone' => '-',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Muh. Hasyim (0822 9065 7189), Muhammad Irwan Nur (0822-9368-3173)'
            ],
            [
                'name' => 'PT. Semen Tonasa',
                'address' => 'Bontoa, Kec. Minasatene, Kab. Pangkep',
                'phone' => '0811 4491 608',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'A. Alam Nurhidayat (0877 5425 2962), Muh Azim (0895 4234 73900), Hasmar (0896 0422 3348)'
            ],
            [
                'name' => 'PT. Media Fajar Indonesia',
                'address' => 'Graha Pena, Jl. Urip Sumoharjo No.20 Lantai 4, Pampang, Kec. Panakkukang, Kota Makassar, Sulawesi Selatan 90231',
                'phone' => '-',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Jesica Septarina, Widya Fadillah S (0853-4212-9455), Muhammad Syafaat Sahir'
            ],
            [
                'name' => 'Hotel Grand Maleo',
                'address' => 'Jl. Pelita Raya VIII No.1, Bua Kana, Kec. Rappocini, Kota Makassar, Sulawesi Selatan',
                'phone' => '-',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => '1 Orang (Laki-Laki Bagian IT)',
                'alumni_magang' => 'Iis Karisma (0853-5830-8735)'
            ],
            [
                'name' => 'Afila Media Karya',
                'address' => 'Perumahan Bumi Aroepala Ruko, Blok A6, Paccinongang, Somba Opu, Gowa',
                'phone' => '081144411201',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => '3 Orang',
                'alumni_magang' => 'Fajar Abdillah Darwis (0857 5618 7504)'
            ],
            [
                'name' => 'Bank Sulselbar Makassar',
                'address' => 'Jl. Dr. Sam Ratulangi No. 16 Makassar',
                'phone' => '-',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Nurul Izzah Dwi Nurdinah (0853-4062-1239)'
            ],
            [
                'name' => 'PT Distritek Solusi Indonesia',
                'address' => 'Jl. Taman Gosyen Raya Desa/Kelurahan Minasa Upa, Kec. Rappocini, Kota Makassar, Provinsi Sulawesi Selatan',
                'phone' => '082322248822',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => '5 Orang',
                'alumni_magang' => 'Rayyan Anugerah (081945528583)'
            ],
            [
                'name' => 'Lembaga Layanan Pendidikan Tinggi Wilayah IX',
                'address' => 'Jl. Bung, Tamalanrea Jaya, Makassar, Kota Makassar',
                'phone' => '0821 8738 0977',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Andi Muh Safrillah (08219848 3197), Muh. Ade Henriansyah (0878 7252 8713)'
            ],
            [
                'name' => 'PT Aplikanusa Lintas Arta',
                'address' => 'Jl. Raya Pendidikan F1, RW.2, Tidung, Rappocini, Makassar',
                'phone' => '0812-7212-1145 (Pak Ardiansyah)',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Tarmuji Rauf, Muhammad Arham (Alumni TMJ)'
            ],
            [
                'name' => 'Brilyan Trimatra Utama',
                'address' => '-',
                'phone' => '-',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => '2 Orang',
                'alumni_magang' => 'Fajria Nur (Alumni TMJ)'
            ],
            [
                'name' => 'Animatown',
                'address' => 'BTP Blok  H, Baru, Jl. Kerukunan Timur No. 641, Tamalanrea',
                'phone' => '083132457782',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => '4-6 Orang',
                'alumni_magang' => 'Muhammad Naufal Rezki (Alumni TMJ)'
            ],
            [
                'name' => 'Phinisi Point Mall',
                'address' => 'Jl. Metro Tanjung Bunga No.2 Makassar',
                'phone' => '-',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => 'Tidak Menentu',
                'alumni_magang' => 'Nur Masyita (0877 7909 1353)'
            ],
            [
                'name' => 'Asrama Haji',
                'address' => 'Jl. Asrama Haji Sudiang',
                'phone' => '0812-5810-3737',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => '3-4 Orang',
                'alumni_magang' => 'Alwali (Alumni TMJ)'
            ],
            [
                'name' => 'PLN Nusa Daya',
                'address' => 'Jl. Bonto Ramba No.9, Mannuruki, Kec. Tamalate, Kota Makassar, Sulawesi Selatan 90223',
                'phone' => '0821-9523-1056',
                'description' => null,
                'jalur' => 'Mandiri',
                'kuota' => '2-3 Orang',
                'alumni_magang' => 'Anggi (Alumni TMJ)'
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
