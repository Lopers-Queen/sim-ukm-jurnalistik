<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder anggota dari file Absen Ujur 2025-2026 (NEW).docx
 * 136 anggota (selain BPI/BPH yang sudah di DatabaseSeeder)
 */
class AnggotaAbsenSeeder extends Seeder
{
    public function run(): void
    {
        $anggotaBaru = [
            // === PERS & PENYIARAN (20 orang) ===
            ['nim' => '226512006', 'nama' => 'Zheyla Maharani Erwin', 'divisi' => 'pers_penyiaran'],
            ['nim' => '226521011', 'nama' => 'Jihan Fadilla Salsabila Shabuna', 'divisi' => 'pers_penyiaran'],
            ['nim' => '226661002', 'nama' => 'Rifki Firmansyah', 'divisi' => 'pers_penyiaran'],
            ['nim' => '226661029', 'nama' => 'Muhammad Ardika', 'divisi' => 'pers_penyiaran'],
            ['nim' => '226671028', 'nama' => 'Alifah Za\'ima Nurhasanah', 'divisi' => 'pers_penyiaran'],
            ['nim' => '236611043', 'nama' => 'Wafiyyah', 'divisi' => 'pers_penyiaran'],
            ['nim' => '246221024', 'nama' => 'Lidya Pratiwi Anggraeny', 'divisi' => 'pers_penyiaran'],
            ['nim' => '246521044', 'nama' => 'Luthfi Affifah', 'divisi' => 'pers_penyiaran'],
            ['nim' => '246521046', 'nama' => 'Tiara Puspa Sari', 'divisi' => 'pers_penyiaran'],
            ['nim' => '246521035', 'nama' => 'Cut Nurhasanah', 'divisi' => 'pers_penyiaran'],
            ['nim' => '246221001', 'nama' => 'Nur Afni Agustina', 'divisi' => 'pers_penyiaran'],
            ['nim' => '246652023', 'nama' => 'M. Muklas Arifin', 'divisi' => 'pers_penyiaran'],
            ['nim' => '256152021', 'nama' => 'Kevin Christensen Immanuel P', 'divisi' => 'pers_penyiaran'],
            ['nim' => '256521019', 'nama' => 'Intan Hartama', 'divisi' => 'pers_penyiaran'],
            ['nim' => '256221022', 'nama' => 'Siti Maulida Rahmah', 'divisi' => 'pers_penyiaran'],
            ['nim' => '256211022', 'nama' => 'Ocha Sanustika', 'divisi' => 'pers_penyiaran'],
            ['nim' => '256211028', 'nama' => 'Rizky Al Farisi', 'divisi' => 'pers_penyiaran'],
            ['nim' => '256511051', 'nama' => 'Amalia Tara Asya', 'divisi' => 'pers_penyiaran'],
            ['nim' => '256511052', 'nama' => 'Noor Nadila Fitryadi', 'divisi' => 'pers_penyiaran'],
            ['nim' => '246222018', 'nama' => 'Azis Muslimin', 'divisi' => 'pers_penyiaran'],

            // === FOTOGRAFI (75 orang) ===
            ['nim' => '226411006', 'nama' => 'Muhammad Wahyu Ramadhan', 'divisi' => 'fotografi'],
            ['nim' => '226411039', 'nama' => 'Muhammad Syahrul Yusuf', 'divisi' => 'fotografi'],
            ['nim' => '226411045', 'nama' => 'Muhammad Abdillah', 'divisi' => 'fotografi'],
            ['nim' => '226511035', 'nama' => 'Pasha Alfitri Nazira Haq', 'divisi' => 'fotografi'],
            ['nim' => '226511036', 'nama' => 'Silpa Widiastanti', 'divisi' => 'fotografi'],
            ['nim' => '226651037', 'nama' => 'Muhammad Andi Iqbal', 'divisi' => 'fotografi'],
            ['nim' => '226222013', 'nama' => 'Natasya Dwi Agustin', 'divisi' => 'fotografi'],
            ['nim' => '236101017', 'nama' => 'Muhammad Raihan Kamil', 'divisi' => 'fotografi'],
            ['nim' => '236101050', 'nama' => 'Ahmad Meidian Agib', 'divisi' => 'fotografi'],
            ['nim' => '236111010', 'nama' => 'Ibnu Raudhatul Firdaus', 'divisi' => 'fotografi'],
            ['nim' => '236111015', 'nama' => 'Chairul Fajar', 'divisi' => 'fotografi'],
            ['nim' => '236111020', 'nama' => 'Ifandi Ramadhan', 'divisi' => 'fotografi'],
            ['nim' => '236161012', 'nama' => 'Jessica Theresia', 'divisi' => 'fotografi'],
            ['nim' => '236221061', 'nama' => 'Nurul Aini Syam', 'divisi' => 'fotografi'],
            ['nim' => '236651002', 'nama' => 'Henriyanto', 'divisi' => 'fotografi'],
            ['nim' => '236651010', 'nama' => 'Yemima Meilinda Munte', 'divisi' => 'fotografi'],
            ['nim' => '236151001', 'nama' => 'M Zakki Nabilah', 'divisi' => 'fotografi'],
            ['nim' => '246152017', 'nama' => 'Muhammad Fadhillah', 'divisi' => 'fotografi'],
            ['nim' => '246152022', 'nama' => 'Yoseph Ratsinger Bata', 'divisi' => 'fotografi'],
            ['nim' => '246201039', 'nama' => 'Nurwakhid Sahrul Ramadhan', 'divisi' => 'fotografi'],
            ['nim' => '246202006', 'nama' => 'Elga Ariyon Saputra', 'divisi' => 'fotografi'],
            ['nim' => '246211024', 'nama' => 'Muhammad Yudhaprastya A.D', 'divisi' => 'fotografi'],
            ['nim' => '246222008', 'nama' => 'Ferry Septian Gymnastiar', 'divisi' => 'fotografi'],
            ['nim' => '246231014', 'nama' => 'Gita Amelia Saputri', 'divisi' => 'fotografi'],
            ['nim' => '246521037', 'nama' => 'Anggun Nur Alfiani', 'divisi' => 'fotografi'],
            ['nim' => '246651022', 'nama' => 'Dennis Alfarizki A.', 'divisi' => 'fotografi'],
            ['nim' => '246651029', 'nama' => 'Hanafi Purnama Yudi', 'divisi' => 'fotografi'],
            ['nim' => '246661049', 'nama' => 'Dwi Nur Yulia Ningsih', 'divisi' => 'fotografi'],
            ['nim' => '246222014', 'nama' => 'Kevin Junior Sagala', 'divisi' => 'fotografi'],
            ['nim' => '246222020', 'nama' => 'M. Aldi Dwi Kurniawan', 'divisi' => 'fotografi'],
            ['nim' => '246222024', 'nama' => 'Muhammad Fajrin Al Irfan', 'divisi' => 'fotografi'],
            ['nim' => '246231013', 'nama' => 'Maichael Eldianto', 'divisi' => 'fotografi'],
            ['nim' => '246521025', 'nama' => 'Shasa Syahira', 'divisi' => 'fotografi'],
            ['nim' => '246521029', 'nama' => 'Aurora Pingky Destaliya', 'divisi' => 'fotografi'],
            ['nim' => '246521040', 'nama' => 'Fadila Safitri', 'divisi' => 'fotografi'],
            ['nim' => '246411059', 'nama' => 'Ahmad Zen Abror', 'divisi' => 'fotografi'],
            ['nim' => '253222034', 'nama' => 'Aqila Marindra Zulfa', 'divisi' => 'fotografi'],
            ['nim' => '256121026', 'nama' => 'Muhammad Luqman Hakim', 'divisi' => 'fotografi'],
            ['nim' => '256141008', 'nama' => 'Siti Nuraini', 'divisi' => 'fotografi'],
            ['nim' => '256141034', 'nama' => 'Syawal Syafrah', 'divisi' => 'fotografi'],
            ['nim' => '256201039', 'nama' => 'Malik', 'divisi' => 'fotografi'],
            ['nim' => '256201041', 'nama' => 'Putra Elan Pasha', 'divisi' => 'fotografi'],
            ['nim' => '256201045', 'nama' => 'Andi', 'divisi' => 'fotografi'],
            ['nim' => '256221028', 'nama' => 'Aprilia Kartikasari', 'divisi' => 'fotografi'],
            ['nim' => '256221029', 'nama' => 'Leily Mira Yanti', 'divisi' => 'fotografi'],
            ['nim' => '256221046', 'nama' => 'Noval Kholik Kurniawan', 'divisi' => 'fotografi'],
            ['nim' => '256221053', 'nama' => 'Tsaniya Salwa Putri A.', 'divisi' => 'fotografi'],
            ['nim' => '256221054', 'nama' => 'Salsabilla Choirunnissa', 'divisi' => 'fotografi'],
            ['nim' => '256222009', 'nama' => 'Ahmad Alif Rozan', 'divisi' => 'fotografi'],
            ['nim' => '256222027', 'nama' => 'Muhammad Nabil', 'divisi' => 'fotografi'],
            ['nim' => '256222033', 'nama' => 'Nindia Kustiani', 'divisi' => 'fotografi'],
            ['nim' => '256222035', 'nama' => 'Dhaifah Athiiyyah Marziya', 'divisi' => 'fotografi'],
            ['nim' => '256431050', 'nama' => 'Tegar Maulana Satya A.', 'divisi' => 'fotografi'],
            ['nim' => '256441036', 'nama' => 'Iksan Maulana Nugroho', 'divisi' => 'fotografi'],
            ['nim' => '256521047', 'nama' => 'Muhammad Arya Dira', 'divisi' => 'fotografi'],
            ['nim' => '256522003', 'nama' => 'Muhammad Ridwan', 'divisi' => 'fotografi'],
            ['nim' => '256522008', 'nama' => 'Amelia Syahrina', 'divisi' => 'fotografi'],
            ['nim' => '256651048', 'nama' => 'Aulia Putri Utari', 'divisi' => 'fotografi'],
            ['nim' => '256652002', 'nama' => 'Dhavid Herlan Safitra', 'divisi' => 'fotografi'],
            ['nim' => '256652022', 'nama' => 'Eka Nurhayati Aruan', 'divisi' => 'fotografi'],
            ['nim' => '256661014', 'nama' => 'Muh. Fadnur Fajrin', 'divisi' => 'fotografi'],
            ['nim' => '256661016', 'nama' => 'Rizky Nur Aulia', 'divisi' => 'fotografi'],
            ['nim' => '256661015', 'nama' => 'Rehan Nasywa Ramadhan', 'divisi' => 'fotografi'],
            ['nim' => '256661020', 'nama' => 'Ahmad Ridho Ikhsani', 'divisi' => 'fotografi'],
            ['nim' => '256661022', 'nama' => 'Aldo Surya Parintak', 'divisi' => 'fotografi'],
            ['nim' => '256661028', 'nama' => 'Muhammad Farel Rizqi Dwi Putra', 'divisi' => 'fotografi'],
            ['nim' => '256661040', 'nama' => 'Putra Naimar Alzero Rahmad Fahrezi', 'divisi' => 'fotografi'],
            ['nim' => '256661045', 'nama' => 'Muhammad Leonal Messi Hakim', 'divisi' => 'fotografi'],
            ['nim' => '256662007', 'nama' => 'Zaki Uswa Raziq', 'divisi' => 'fotografi'],
            ['nim' => '256671027', 'nama' => 'Maissy Vania Nurhaliza', 'divisi' => 'fotografi'],
            ['nim' => '256222005', 'nama' => 'Muhammad Farid Syahrinda', 'divisi' => 'fotografi'],
            ['nim' => '256441002', 'nama' => 'Nisrina Zulfa Aulia', 'divisi' => 'fotografi'],
            ['nim' => '256661047', 'nama' => 'Dzaki Naufal Pratama', 'divisi' => 'fotografi'],
            ['nim' => '246651025', 'nama' => 'Yudistira Ramadhan', 'divisi' => 'fotografi'],
            ['nim' => '246411032', 'nama' => 'Romualdus Jeno', 'divisi' => 'fotografi'],

            // === VIDEOGRAFI (11 orang) ===
            ['nim' => '226412005', 'nama' => 'Alfian Didani', 'divisi' => 'videografi'],
            ['nim' => '236202016', 'nama' => 'Ishak', 'divisi' => 'videografi'],
            ['nim' => '236651035', 'nama' => 'Asri Nurbaiti Rachmah', 'divisi' => 'videografi'],
            ['nim' => '236651045', 'nama' => 'Alwi', 'divisi' => 'videografi'],
            ['nim' => '246221058', 'nama' => 'Syifa\'a', 'divisi' => 'videografi'],
            ['nim' => '246651024', 'nama' => 'Gerin Rizky', 'divisi' => 'videografi'],
            ['nim' => '256651024', 'nama' => 'Nazwa Amalia Syahmita', 'divisi' => 'videografi'],
            ['nim' => '256421026', 'nama' => 'Abd Rahman', 'divisi' => 'videografi'],
            ['nim' => '256651021', 'nama' => 'Bilqis Nazwa Azzika', 'divisi' => 'videografi'],
            ['nim' => '256201012', 'nama' => 'Ayudhya Viantyca Febriarty Purnomo', 'divisi' => 'videografi'],
            ['nim' => '256661012', 'nama' => 'Andika Maulana', 'divisi' => 'videografi'],

            // === UNIT INVENTORY (5 orang) ===
            ['nim' => '236652013', 'nama' => 'Annisa Nurmala Putri', 'divisi' => 'inventory'],
            ['nim' => '246101045', 'nama' => 'Rahmat Fitono', 'divisi' => 'inventory'],
            ['nim' => '246101064', 'nama' => 'Gofur Syam Ganda', 'divisi' => 'inventory'],
            ['nim' => '246221029', 'nama' => 'Indy Amalia Chantika', 'divisi' => 'inventory'],
            ['nim' => '246111028', 'nama' => 'Zaini', 'divisi' => 'inventory'],

            // === UNIT KOMINFO (13 orang) ===
            ['nim' => '226221021', 'nama' => 'Dea Salsa Novianda', 'divisi' => 'kominfo'],
            ['nim' => '236201006', 'nama' => 'Muh. Zul Fiqri Syarif', 'divisi' => 'kominfo'],
            ['nim' => '236121035', 'nama' => 'Muhammad Jhunian Dhavie', 'divisi' => 'kominfo'],
            ['nim' => '236131033', 'nama' => 'Muhammad Reza Pahlevi', 'divisi' => 'kominfo'],
            ['nim' => '236611035', 'nama' => 'Asty Tangdi Seru', 'divisi' => 'kominfo'],
            ['nim' => '246521048', 'nama' => 'Nadine Syabila Triharyono', 'divisi' => 'kominfo'],
            ['nim' => '246651028', 'nama' => 'Dinda Triamita', 'divisi' => 'kominfo'],
            ['nim' => '246221033', 'nama' => 'Adelia Nuria Khasanah', 'divisi' => 'kominfo'],
            ['nim' => '246221021', 'nama' => 'Rafli Ramadhani', 'divisi' => 'kominfo'],
            ['nim' => '246652005', 'nama' => 'Anas Fadhil Darojat', 'divisi' => 'kominfo'],
            ['nim' => '246652018', 'nama' => 'Hanif Izmi Azra', 'divisi' => 'kominfo'],
            ['nim' => '236651091', 'nama' => 'Anastasya Aprilianti Sambe', 'divisi' => 'kominfo'],
            ['nim' => '236652003', 'nama' => 'Nurul Kurnia', 'divisi' => 'kominfo'],

            // === UNIT REDAKSI (12 orang) ===
            ['nim' => '226661049', 'nama' => 'M. Bintang Al-Kausar', 'divisi' => 'redaksi'],
            ['nim' => '236611016', 'nama' => 'Fardha Alisya Nurhafiza', 'divisi' => 'redaksi'],
            ['nim' => '236651029', 'nama' => 'Andi Fitri Novianti', 'divisi' => 'redaksi'],
            ['nim' => '236201007', 'nama' => 'Adriansyah', 'divisi' => 'redaksi'],
            ['nim' => '236611048', 'nama' => 'Azsyzah Nur Auliya', 'divisi' => 'redaksi'],
            ['nim' => '236431030', 'nama' => 'Naurah Azzahra', 'divisi' => 'redaksi'],
            ['nim' => '246231006', 'nama' => 'Herlita Adhalia Nurul Fatimah', 'divisi' => 'redaksi'],
            ['nim' => '246672006', 'nama' => 'Putri Sayektiani Safe\'i', 'divisi' => 'redaksi'],
            ['nim' => '246111062', 'nama' => 'Michael Dasten', 'divisi' => 'redaksi'],
            ['nim' => '246201004', 'nama' => 'Rannia Herma Putri', 'divisi' => 'redaksi'],
            ['nim' => '246411005', 'nama' => 'Ahmad Sabil Al Fathir', 'divisi' => 'redaksi'],
            ['nim' => '246221044', 'nama' => 'Ezra Muqofa Ul Wasyi', 'divisi' => 'redaksi'],
        ];

        $counter = 0;
        foreach ($anggotaBaru as $data) {
            // Skip jika sudah ada
            if (Anggota::where('nim', $data['nim'])->exists()) {
                continue;
            }

            $anggota = Anggota::create([
                'nim'                => $data['nim'],
                'nama_lengkap'       => $data['nama'],
                'email'              => strtolower(str_replace([' ', "'", '.'], ['', '', ''], $data['nama'])) . '@polnes.ac.id',
                'password'           => Hash::make('01012004'), // Default password
                'tanggal_lahir'      => '2004-01-01', // Placeholder
                'jenis_kelamin'      => 'L',
                'divisi'             => $data['divisi'],
                'jabatan_struktural' => 'anggota',
                'status_keanggotaan' => 'aktif',
                'tanggal_bergabung'  => '2024-09-01',
                'is_first_login'     => true,
            ]);
            $anggota->assignRole('anggota_aktif');
            $counter++;
        }

        $this->command->info("✅ {$counter} anggota berhasil ditambahkan dari data absen.");
    }
}
