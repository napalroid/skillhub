<?php

return [
    'templates' => [
        'barber' => [
            'name' => 'Barber / Potong Rambut',
            'fields' => [
                [
                    'name' => 'jenis_potongan',
                    'type' => 'select',
                    'label' => 'Jenis Potongan',
                    'required' => true,
                    'options' => ['Crewcut', 'Fade', 'Undercut', 'Pompadour', 'Layered', 'Buzzcut'],
                ],
                [
                    'name' => 'lama_estimasi',
                    'type' => 'number',
                    'label' => 'Estimasi Lama (menit)',
                    'required' => true,
                    'min' => 15,
                    'max' => 90,
                    'default' => 30,
                ],
                [
                    'name' => 'catatan_khusus',
                    'type' => 'textarea',
                    'label' => 'Catatan Khusus',
                    'required' => false,
                    'placeholder' => 'Alergi, preferensi khusus, dll...',
                ],
            ],
        ],

        'delivery' => [
            'name' => 'Antar Jemput / Delivery',
            'fields' => [
                [
                    'name' => 'alamat_pickup',
                    'type' => 'textarea',
                    'label' => 'Alamat Pick-up',
                    'required' => true,
                    'placeholder' => 'Jl. Contoh No. 10, Jakarta Pusat',
                ],
                [
                    'name' => 'alamat_dropoff',
                    'type' => 'textarea',
                    'label' => 'Alamat Drop-off',
                    'required' => true,
                    'placeholder' => 'Jl. Tujuan No. 5, Jakarta Selatan',
                ],
                [
                    'name' => 'jenis_barang',
                    'type' => 'select',
                    'label' => 'Jenis Barang',
                    'required' => true,
                    'options' => ['Dokumen', 'Makanan', 'Paket Kecil (<1kg)', 'Paket Sedang (1-5kg)', 'Paket Besar (>5kg)', 'Elektronik'],
                ],
                [
                    'name' => 'berat_perkiraan',
                    'type' => 'number',
                    'label' => 'Perkiraan Berat (kg)',
                    'required' => false,
                    'min' => 0.1,
                    'max' => 50,
                    'step' => 0.1,
                ],
                [
                    'name' => 'instruksi_kurir',
                    'type' => 'textarea',
                    'label' => 'Instruksi Khusus untuk Kurir',
                    'required' => false,
                    'placeholder' => 'Tempat parkir, lantai, dsb...',
                ],
            ],
        ],

        'joki_ml' => [
            'name' => 'Joki Rank Mobile Legends',
            'fields' => [
                [
                    'name' => 'rank_sekarang',
                    'type' => 'select',
                    'label' => 'Rank Sekarang',
                    'required' => true,
                    'options' => ['Warrior', 'Elite', 'Master', 'Grandmaster', 'Epic', 'Legend', 'Mythic', 'Mythic Glory'],
                ],
                [
                    'name' => 'rank_target',
                    'type' => 'select',
                    'label' => 'Rank yang Ingin Dicapai',
                    'required' => true,
                    'options' => ['Epic', 'Legend', 'Mythic', 'Mythic Glory'],
                ],
                [
                    'name' => 'username_ml',
                    'type' => 'text',
                    'label' => 'Username Akun ML',
                    'required' => true,
                    'maxlength' => 50,
                ],
                [
                    'name' => 'password_akun',
                    'type' => 'password',
                    'label' => 'Password Akun',
                    'required' => true,
                    'help_text' => 'Password akan dienkripsi dan hanya booster yang bisa lihat',
                ],
                [
                    'name' => 'catatan_hero',
                    'type' => 'textarea',
                    'label' => 'Hero Favorit / Yang Dilarang',
                    'required' => false,
                    'placeholder' => 'Contoh: Suka pakai Lancelot, jangan pakai Layla',
                ],
            ],
        ],

        'les_privat' => [
            'name' => 'Les Privat / Bimbingan Belajar',
            'fields' => [
                [
                    'name' => 'mata_pelajaran',
                    'type' => 'text',
                    'label' => 'Mata Pelajaran',
                    'required' => true,
                    'placeholder' => 'Contoh: Matematika, Fisika, Bahasa Inggris',
                ],
                [
                    'name' => 'tingkatan',
                    'type' => 'select',
                    'label' => 'Tingkatan',
                    'required' => true,
                    'options' => ['SD', 'SMP', 'SMA/SMK', 'Mahasiswa', 'Umum'],
                ],
                [
                    'name' => 'format_les',
                    'type' => 'select',
                    'label' => 'Format Les',
                    'required' => true,
                    'options' => ['Online (video call)', 'Offline (tempat saya)', 'Offline (tempat guru)', 'Fleksibel'],
                ],
                [
                    'name' => 'durasi_per_sesi',
                    'type' => 'select',
                    'label' => 'Durasi per Sesi (menit)',
                    'required' => true,
                    'options' => ['30', '45', '60', '90', '120'],
                    'default' => '60',
                ],
                [
                    'name' => 'catatan_materi',
                    'type' => 'textarea',
                    'label' => 'Materi yang Ingin Dipelajari',
                    'required' => false,
                    'placeholder' => 'Jelaskan topik atau bab yang ingin dipelajari',
                ],
            ],
        ],

        'desain' => [
            'name' => 'Desain Grafis / Kreatif',
            'fields' => [
                [
                    'name' => 'tipe_desain',
                    'type' => 'select',
                    'label' => 'Tipe Desain',
                    'required' => true,
                    'options' => ['Logo', 'Poster/Flyer', 'Banner', 'Brosur', 'Kartu Nama', 'Social Media Post', 'Lainnya'],
                ],
                [
                    'name' => 'ukuran_file',
                    'type' => 'text',
                    'label' => 'Ukuran/Dimensi yang Diinginkan',
                    'required' => false,
                    'placeholder' => 'Contoh: A4, 1080x1080px, Instagram Story',
                ],
                [
                    'name' => 'warna_preferensi',
                    'type' => 'text',
                    'label' => 'Preferensi Warna',
                    'required' => false,
                    'placeholder' => 'Contoh: Biru, Merah, atau kombinasi warna brand',
                ],
                [
                    'name' => 'deskripsi_desain',
                    'type' => 'textarea',
                    'label' => 'Deskripsi Desain yang Diinginkan',
                    'required' => true,
                    'placeholder' => 'Jelaskan konsep, target audience, gaya desain yang diinginkan',
                ],
            ],
        ],

        'perbaikan' => [
            'name' => 'Service / Perbaikan',
            'fields' => [
                [
                    'name' => 'tipe_barang',
                    'type' => 'select',
                    'label' => 'Tipe Barang',
                    'required' => true,
                    'options' => ['Smartphone', 'Laptop', 'PC/Komputer', 'Tablet', 'Elektronik Lainnya'],
                ],
                [
                    'name' => 'merk_model',
                    'type' => 'text',
                    'label' => 'Merk & Model',
                    'required' => true,
                    'placeholder' => 'Contoh: iPhone 15, Samsung Galaxy S24',
                ],
                [
                    'name' => 'keluhan',
                    'type' => 'textarea',
                    'label' => 'Keluhan / Masalah',
                    'required' => true,
                    'placeholder' => 'Jelaskan masalah yang dialami',
                ],
            ],
        ],
    ],

    'max_fields_per_service' => 15,
    'max_options_per_field' => 20,

    'field_types' => [
        'text' => 'Teks Singkat',
        'textarea' => 'Teks Panjang',
        'number' => 'Angka',
        'select' => 'Pilihan Dropdown',
        'radio' => 'Pilihan Radio',
        'checkbox' => 'Checkbox',
        'date' => 'Tanggal',
        'time' => 'Waktu',
        'password' => 'Password (terenkripsi)',
    ],
];
