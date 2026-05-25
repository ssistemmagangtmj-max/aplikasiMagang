<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // ADMIN (NIP: 8 digit angka)
        // =============================================
        User::create([
            'name'        => 'Admin Anti Gravity',
            'id_pengguna' => 10000001,
            'role'        => 'admin',
            'password'    => Hash::make('10000001'),
            'status'      => 'approved',
        ]);

        // =============================================
        // KOORDINATOR / KOPS (NIP: 8 digit angka)
        // =============================================
        User::create([
            'name'        => 'Koordinator Prodi',
            'id_pengguna' => 20000001,
            'role'        => 'kops',
            'password'    => Hash::make('20000001'),
            'status'      => 'approved',
        ]);

        // =============================================
        // DOSEN PEMBIMBING (NIDN: 8 digit angka)
        // =============================================
        User::create([
            'name'        => 'Dr. Budi Santoso',
            'id_pengguna' => 30000001,
            'role'        => 'dosen',
            'jurusan'     => 'Teknik Informatika dan Komputer',
            'prodi'       => 'D4 Teknik Komputer dan Jaringan',
            'password'    => Hash::make('30000001'),
            'status'      => 'approved',
        ]);

        User::create([
            'name'        => 'Prof. Siti Rahayu',
            'id_pengguna' => 30000002,
            'role'        => 'dosen',
            'jurusan'     => 'Teknik Informatika dan Komputer',
            'prodi'       => 'D4 Teknik Multimedia dan Jaringan',
            'password'    => Hash::make('30000002'),
            'status'      => 'approved',
        ]);

        // =============================================
        // MAHASISWA (NIM: format 473340xx — 8 digit)
        // =============================================
        $mahasiswaData = [
            ['name' => 'Ahmad Fauzi',      'nim' => 47334001, 'jurusan' => 'Teknik Informatika dan Komputer', 'prodi' => 'D4 Teknik Komputer dan Jaringan'],
            ['name' => 'Dewi Anggraeni',   'nim' => 47334002, 'jurusan' => 'Teknik Informatika dan Komputer', 'prodi' => 'D4 Teknik Multimedia dan Jaringan'],
            ['name' => 'Rizky Pratama',    'nim' => 47334003, 'jurusan' => 'Teknik Elektro',                  'prodi' => 'D4 Teknik Listrik'],
            ['name' => 'Nurul Hidayah',    'nim' => 47334004, 'jurusan' => 'Teknik Elektro',                  'prodi' => 'D4 Teknologi Rekayasa Sistem Elektronika'],
            ['name' => 'Bagas Wicaksono',  'nim' => 47334005, 'jurusan' => 'Administrasi Niaga',              'prodi' => 'D3 Administrasi Bisnis'],
            ['name' => 'Sari Indah Lestari','nim' => 47334006,'jurusan' => 'Administrasi Niaga',              'prodi' => 'D4 Manajemen Bisnis Jasa Pariwisata'],
        ];

        foreach ($mahasiswaData as $mhs) {
            User::create([
                'name'        => $mhs['name'],
                'id_pengguna' => $mhs['nim'],
                'role'        => 'mahasiswa',
                'jurusan'     => $mhs['jurusan'],
                'prodi'       => $mhs['prodi'],
                'password'    => Hash::make((string) $mhs['nim']),
                'status'      => 'approved',
            ]);
        }

        // =============================================
        // PERUSAHAAN (Data Dummy)
        // =============================================
        Company::create([
            'name'               => 'PT. Teknologi Nusantara',
            'description'        => 'Perusahaan teknologi terkemuka yang bergerak di bidang pengembangan software enterprise dan solusi digital.',
            'address'            => 'Jl. Sudirman No. 123, Jakarta Selatan',
            'operational_hours'  => 'Senin - Jumat, 08:00 - 17:00',
            'phone'              => '021-5551234',
            'email'              => 'hr@teknologinusantara.co.id',
        ]);

        Company::create([
            'name'               => 'CV. Digital Kreasi',
            'description'        => 'Creative digital agency yang fokus pada web development, mobile apps, dan UI/UX design.',
            'address'            => 'Jl. Gatot Subroto No. 45, Bandung',
            'operational_hours'  => 'Senin - Jumat, 09:00 - 18:00',
            'phone'              => '022-7891234',
            'email'              => 'info@digitalkreasi.com',
        ]);

        Company::create([
            'name'               => 'PT. Cloud Indonesia',
            'description'        => 'Penyedia layanan cloud computing dan infrastruktur IT untuk enterprise.',
            'address'            => 'Jl. HR Rasuna Said Kav. 10, Jakarta',
            'operational_hours'  => 'Senin - Jumat, 08:30 - 17:30',
            'phone'              => '021-2345678',
            'email'              => 'careers@cloudindonesia.id',
        ]);

        Company::create([
            'name'               => 'PT. Data Analitika',
            'description'        => 'Perusahaan yang bergerak di bidang big data analytics dan artificial intelligence.',
            'address'            => 'Jl. Pemuda No. 78, Surabaya',
            'operational_hours'  => 'Senin - Jumat, 08:00 - 16:30',
            'phone'              => '031-9876543',
            'email'              => 'recruit@dataanalitika.co.id',
        ]);
    }
}
