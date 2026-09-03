STORAGE – Jurnal Ilmiah Teknik dan Ilmu Komputer, Vol. x No. x, bulan, tahun, 1 - 15
e-ISSN: 2828-5344 | DOI: 10.55123

# Purwarupa Sistem Rekomendasi Usaha Mikro Berbasis Parameter Modal, Lokasi, dan Waktu Luang

1)Rachmad Syaid Darmawijaya, 2)Muhammad Fahmi, 3)Eka Arriyanti

1,2,3)Program Studi Teknik Informatika, STMIK Widya Cipta Dharma, Samarinda, Indonesia

1)12243127@wicida.ac.id, 2)2mfahmi@wicida.ac.id, 3)ekaarry@wicida.ac.id

1)082351046588, 2)+62 852-5064-2101, 3)+62 858-6041-6031

---

## INFO ARTIKEL

**Riwayat Artikel:**
Diterima : [tanggal diterima]
Disetujui : [tanggal disetujui]

---

## ABSTRAK

Pemilihan jenis usaha mikro yang sesuai dengan kondisi individu merupakan tantangan bagi calon wirausaha karena banyaknya alternatif yang tersedia serta minimnya informasi yang mempertimbangkan profil personal. Faktor seperti modal finansial, lokasi geografis, dan ketersediaan waktu luang sering kali menentukan keberhasilan usaha. Penelitian ini bertujuan mengembangkan sistem rekomendasi usaha mikro bernama IDEUSAHAKU berbasis Weighted Product Method (WPM) untuk membantu pihak pengguna dalam menetapkan jenis usaha yang paling sesuai dengan kondisi mereka. Data penelitian terdiri atas 50 ide usaha mikro kreatif yang dikompilasi dari publikasi Dealls, kemudian divalidasi oleh 40 responden (40% pelaku UMKM, 40% konsultan bisnis, 20% mahasiswa) melalui kuesioner. Sistem mengevaluasi alternatif berdasarkan tiga kriteria utama: modal finansial (bobot 20%), kategori lokasi (30%), dan waktu luang (50%). Setiap kriteria dikonversi ke nilai numerik dan diproses dengan metode WPM untuk menghasilkan nilai preferensi dan peringkat. Hasil validasi menunjukkan rata-rata kesesuaian kategori lokasi sebesar 2,97 dan waktu operasional 2,82 (skala 1-4). Selain itu, evaluasi terhadap 103 pengguna website IDEUSAHAKU menghasilkan rata-rata kepuasan sebesar 4,29 (skala 5), dengan 84,1% responden memberikan penilaian 4-5. Ide usaha dengan modal kecil dan fleksibilitas tinggi memperoleh skor tertinggi dalam rekomendasi. Sistem menyajikan proses perhitungan secara transparan sehingga memudahkan pengguna memahami dasar rekomendasi. Dengan demikian, calon pelaku usaha mikro dapat memanfaatkan IDEUSAHAKU sebagai instrumen yang efektif dalam menetapkan langkah bisnis.

**Kata Kunci :** sistem rekomendasi, usaha mikro, weighted product method, pengambilan keputusan, multi-kriteria

---

## ARTICLE INFO

**ABSTRACT**

The selection of a suitable micro-business based on individual conditions is a challenge for prospective entrepreneurs due to the large number of alternatives and the limited information that considers personal profiles. Factors such as financial capital, geographical location, and available spare time often determine business success. This study aims to develop a microbusiness recommendation system called IDEUSAHAKU, based on the Weighted Product Method (WPM), to help users determine the type of business best suited to their circumstances. The research data consists of 50 creative micro-business ideas compiled from Dealls publications, validated by 40 respondents (40% MSME actors, 40% business consultants, 20% students) through a questionnaire. The system evaluates alternatives based on three main criteria: financial capital (weight 20%), location category (30%), and spare time (50%). Each criterion is converted to numerical values and processed using the WPM to generate preference values and rankings. The validation results show an average location suitability of 2.97 and operational time suitability of 2.82 (on a scale of 1-4). Additionally, evaluation of 103 IDEUSAHAKU website users yielded an average satisfaction score of 4.29 (on a scale of 5), with 84.1% of respondents giving scores of 4-5. Business ideas with low capital and high flexibility obtained the highest scores in the recommendations. The system presents the calculation process transparently, thereby simplifying the process for users to understand the basis of the recommendations. Thus, prospective micro-entrepreneurs can utilize IDEUSAHAKU as an effective tool for planning their business steps.

**Keywords:** recommendation system, micro-business, weighted product method, decision making, multi-criteria

---

________________

## 1. PENDAHULUAN

Usaha Mikro, Kecil, dan Menengah (UMKM) memiliki peranan vital dalam perekonomian Indonesia melalui kontribusi nyata bagi Produk Domestik Bruto (PDB) serta penyediaan lapangan kerja. Data Kementerian Koperasi dan UKM memperlihatkan porsi UMKM melampaui 60% dari PDB nasional dan mengakomodasi hampir 97% tenaga kerja di Indonesia (Doduk dkk., 2024). Peran strategis tersebut menempatkan UMKM sebagai tulang punggung perekonomian yang perlu terus dikembangkan dan diberdayakan. Di tengah pesatnya perkembangan teknologi digital, proses digitalisasi memegang peranan yang sangat penting bagi kemajuan sektor UMKM untuk mendorong pertumbuhan ekonomi lokal secara lebih masif (Nenabu dkk., 2025). Proses digitalisasi saat ini telah menggeser pola kerja pelaku usaha dalam mengolah produk, melakukan pemasaran, sampai berinteraksi dengan konsumen, sehingga menuntut adaptasi yang cepat dari para pelaku UMKM.

Meskipun digitalisasi membuka peluang besar bagi pengembangan UMKM, tren ekonomi digital ini juga membawa dinamika tersendiri yang menghadirkan berbagai tantangan. Ketidakmampuan beradaptasi dengan teknologi dan tren pasar dapat mengancam keberlanjutan usaha UMKM, terutama pada sektor kuliner. Tantangan utama dalam digitalisasi UMKM meliputi kurangnya pemahaman teknologi, fasilitas pendukung yang terbatas, serta sulitnya menjangkau bimbingan kerja maupun permodalan (Hamidah dan Darmansyah, 2023). Bahkan, data memperlihatkan bahwa pergeseran ke arah sistem digital baru terealisasi sekitar 33,6% pada total UMKM nasional saat menjalankan usaha. Kondisi ini mengindikasikan adanya kesenjangan antara potensi besar UMKM dengan kapasitas aktual mereka dalam menghadapi tuntutan era digital.

Seiring meningkatnya minat masyarakat untuk berwirausaha di tengah dinamika ekonomi digital, muncul tantangan mendasar dalam menentukan jenis usaha yang paling sesuai dengan kondisi dan kemampuan masing-masing individu. Pemilihan jenis usaha merupakan keputusan strategis yang berpengaruh langsung terhadap keberhasilan dan keberlanjutan bisnis. Dalam praktiknya, banyak calon pengusaha menentukan jenis usaha berdasarkan intuisi, rekomendasi dari lingkungan sekitar, atau informasi terbatas tanpa mempertimbangkan faktor-faktor penting secara sistematis (Qumairo dkk., 2025). Kondisi ini berpotensi menyebabkan ketidaksesuaian antara karakteristik usaha yang dipilih dengan sumber daya yang dimiliki calon pelaku usaha. Akibatnya, risiko kegagalan usaha, kerugian finansial, pemborosan waktu, serta rendahnya tingkat keberlanjutan usaha menjadi lebih besar.

Keberhasilan suatu usaha mikro umumnya dipengaruhi oleh berbagai faktor yang saling berkaitan. Modal usaha menjadi parameter utama yang memengaruhi profit UMKM. Modal kerja sangat berpengaruh secara signifikan terhadap laba usaha, penambahan kuantitas modal untuk produksi berbanding lurus dengan pertumbuhan hasil produksi sekaligus laba. Selain modal, lokasi usaha dan teknologi informasi juga memiliki pengaruh positif dan signifikan terhadap pendapatan UMKM, dengan nilai koefisien determinasi sebesar 62,7% (Atlantika dkk., 2023). Ketiga faktor tersebut modal, lokasi, dan waktu operasional memiliki tingkat pengaruh yang berbeda terhadap setiap jenis usaha sehingga memerlukan proses evaluasi yang objektif sebelum keputusan diambil. Namun, banyak calon pelaku usaha mengalami kesulitan dalam membandingkan berbagai alternatif usaha karena keterbatasan informasi dan tidak adanya mekanisme yang dapat membantu proses penilaian secara sistematis.

Penggunaan teknologi informasi untuk mendukung pengembangan UMKM telah banyak dilakukan dalam berbagai penelitian sebelumnya. Teknologi web berbasis Laravel telah dimanfaatkan untuk mengembangkan aplikasi marketplace UMKM yang berfungsi sebagai sarana promosi dan transaksi secara daring, dengan menerapkan konsep business to consumer (B2C) yang memungkinkan interaksi yang lebih baik antara toko dan pelanggan melalui fitur membership (Angel dkk., 2025). Melalui perluasan pasar, pemanfaatan teknologi digital terbukti dapat membantu menaikkan daya saing UMKM. Di samping itu, penerapan metode Sistem Pendukung Keputusan (SPK) juga sudah sering digunakan dalam membantu proses pengambilan keputusan bagi pelaku UMKM (Pratama dkk., 2025).

SPK berbasis web dengan memanfaatkan metode Simple Additive Weighting (SAW) telah dikembangkan untuk menentukan lokasi UMKM berdasarkan kriteria aksesibilitas, potensi pasar, tingkat persaingan, dan keamanan lingkungan. Metode SAW mampu memberikan rekomendasi lokasi usaha yang objektif dengan tingkat akurasi yang memadai. Pendekatan serupa juga dilakukan dengan menerapkan metode Simple Multi-Attribute Rating Technique (SMART) dalam pemilihan lokasi usaha di Kota Kupang dengan mempertimbangkan kriteria biaya sewa, potensi pasar, tingkat kompetisi, aksesibilitas, dan luas lahan. Selain itu, metode Weighted Aggregated Sum Product Assessment (WASPAS) telah diimplementasikan pada aplikasi desktop untuk menyeleksi produk unggulan UMKM di Kota Medan (Imawan dkk., 2018).

Meskipun berbagai penelitian tersebut telah berhasil menerapkan metode Multi-Criteria Decision Making (MCDM) seperti SAW, SMART, dan WASPAS dalam konteks UMKM, sebagian besar penelitian masih berfokus pada usaha yang telah berjalan atau hanya membahas aspek tertentu seperti pemilihan lokasi usaha maupun seleksi produk unggulan. Penelitian yang secara khusus membantu calon pengusaha dalam menentukan jenis usaha sebelum usaha tersebut dijalankan masih relatif terbatas. Selain itu, sebagian penelitian belum mengakomodasi kebutuhan pengguna untuk melakukan penyesuaian preferensi terhadap kriteria yang dianggap paling penting untuk mendukung pengambilan keputusan (Baharuddin dan Faisal, 2023). Padahal, setiap calon wirausaha memiliki kondisi dan prioritas yang berbeda-beda, sehingga diperlukan sistem yang fleksibel dan dapat dipersonalisasi.

Berdasarkan identifikasi kesenjangan tersebut, penelitian ini menawarkan pendekatan yang berbeda melalui pengembangan sistem rekomendasi jenis usaha mikro yang mempertimbangkan profil calon pengusaha secara lebih komprehensif. Sistem yang dikembangkan mengintegrasikan data numerik (modal) dan kategorikal (kategori lokasi dan waktu operasional) ke dalam satu mekanisme pengambilan keputusan menggunakan metode Weighted Product Method (WPM). Metode WPM dipilih karena memiliki keunggulan dalam menangani kriteria dengan skala berbeda melalui pendekatan perkalian berpangkat, serta memberikan sensitivitas yang lebih tinggi terhadap nilai rendah pada suatu kriteria (Saputra dan Witanti, 2024). Hal ini penting karena alternatif usaha yang memiliki kelemahan signifikan pada salah satu aspek penting (misalnya modal sangat besar) tidak akan memperoleh peringkat yang terlalu tinggi meskipun unggul pada aspek lainnya. Dengan demikian, hasil rekomendasi yang diharapkan lebih realistis dan sesuai dengan kondisi aktual pengguna.

Penelitian ini bertujuan untuk mengembangkan sistem pendukung keputusan berbasis web bernama IDEUSAHAKU yang menerapkan metode WPM sebagai mekanisme utama dalam proses rekomendasi usaha mikro. Sistem ini dirancang untuk menerima masukan pengguna berupa estimasi modal, preferensi kategori lokasi usaha (online, offline, rumahan, atau hybrid), dan ketersediaan waktu luang yang dapat dialokasikan setiap hari. Selanjutnya, sistem melakukan proses evaluasi terhadap berbagai alternatif usaha mikro yang bersumber dari 50 ide usaha kreatif yang dipublikasikan oleh platform Dealls, berdasarkan bobot dan nilai setiap kriteria. Hasil akhir dari proses tersebut berupa daftar rekomendasi usaha yang telah diurutkan berdasarkan tingkat kesesuaian, sehingga dapat membantu pengguna dalam menentukan pilihan usaha yang paling tepat.

Kontribusi utama penelitian ini terletak pada tiga aspek. Pertama, implementasi metode WPM dalam sistem rekomendasi usaha mikro yang mengintegrasikan kriteria numerik dan kategorikal secara simultan. Kedua, penyediaan mekanisme konfigurasi bobot kriteria secara dinamis sesuai preferensi pengguna, yang membedakannya dari penelitian sejenis yang menggunakan bobot tetap (Darmanto dkk., 2025). Ketiga, perancangan dan pengembangan aplikasi online menyajikan desain antarmuka yang praktis agar tidak menyulitkan kalangan non-teknis. Selain memberikan manfaat praktis bagi calon pelaku usaha dalam menentukan jenis usaha yang sesuai dengan modal, preferensi lokasi, dan waktu luang yang dimiliki, harapannya juga penelitian ini dapat berfungsi sebagai bahan rujukan bagi pengembangan metode MCDM pada bidang kewirausahaan dan sistem rekomendasi usaha mikro di masa mendatang.

## 2. METODE

Penelitian ini dikategorikan sebagai penelitian terapan (applied research) dalam bidang Sistem Pendukung Keputusan (SPK) yang berfokus pada pengembangan sistem rekomendasi usaha mikro kreatif menggunakan metode Weighted Product Method (WPM). SPK dinilai sangat esensial karena mampu menganalisis berbagai aspek untuk memberikan alternatif keputusan yang objektif berdasarkan kriteria yang telah ditentukan. Penelitian ini bertujuan menghasilkan rekomendasi jenis usaha yang sesuai dengan kondisi calon pelaku usaha berdasarkan tiga kriteria utama: modal, kategori lokasi, dan waktu operasional.

Data alternatif usaha diperoleh dari artikel ide usaha kreatif yang dipublikasikan oleh platform Dealls. Dataset awal terdiri atas 50 ide usaha mikro kreatif yang memiliki atribut modal awal, kategori usaha, dan estimasi waktu operasional. Proses validasi karakteristik usaha ditempuh via pendekatan kuantitatif memanfaatkan pembagian kuesioner kepada 40 responden yang meliputi mahasiswa/pelajar (20%), pelaku UMKM (40%), dan konsultan bisnis (40%). Responden diminta menilai kesesuaian kategori lokasi dan waktu operasional untuk masing-masing ide usaha menggunakan skala Likert 1–4.

Alur penelitian ditempuh melalui urutan tahapan sistematis berikut ini. Pertama, identifikasi masalah dan kajian literatur untuk mengidentifikasi permasalahan pemilihan usaha mikro serta mengkaji teori SPK, metode WPM, kriteria usaha mikro, dan sumber data dari Dealls. Kedua, perancangan instrumen penelitian dengan menyusun kuesioner validasi karakteristik usaha yang mencakup penilaian kategori lokasi dan waktu operasional untuk 50 ide usaha. Ketiga, pengumpulan data dengan menyebarkan kuesioner secara daring kepada 40 responden. Keempat, pengolahan data dengan merekap hasil kuesioner, mengkonversi data kategorikal ke nilai numerik, dan menyusun matriks keputusan. Kelima, implementasi metode WPM dengan menghitung normalisasi bobot, nilai vektor S, nilai vektor V, dan pemeringkatan alternatif. Keenam, evaluasi hasil dengan menguji kesesuaian rekomendasi sistem dengan preferensi pengguna dan membandingkan dengan metode lain berdasarkan studi literatur.

Penelitian ini menggunakan tiga kriteria utama. Bobot kriteria ditentukan berdasarkan hasil wawancara awal dengan 5 praktisi UMKM yang menyatakan bahwa waktu luang menjadi prioritas utama bagi calon pengusaha pemula. Besaran bobot tersebut terangkum di dalam Tabel 1.

Tabel 1. Kriteria, Bobot, dan Jenis

| Kode | Kriteria | Bobot (%) | Jenis |
|------|----------|-----------|-------|
| C1 | Modal usaha | 20 | Benefit (semakin kecil modal, semakin baik) |
| C2 | Kategori lokasi | 30 | Benefit (online/hybrid > offline > rumahan) |
| C3 | Waktu operasional | 50 | Benefit (semakin ringan waktu, semakin baik) |

Konversi nilai numerik untuk setiap kriteria ditetapkan melalui proses validasi dan disajikan pada Tabel 2, 3, dan 4.

Tabel 2. Skala Penilaian Modal (Rentang dan Skor)

| Rentang Modal (Rp) | Skor |
|---------------------|------|
| ≤ 500.000 | 4 |
| 500.001 – 1.500.000 | 3 |
| 1.500.001 – 3.000.000 | 2 |
| > 3.000.000 | 1 |

Tabel 3. Skala Penilaian Kategori Lokasi

| Kategori | Skor | Keterangan |
|----------|------|------------|
| Online | 4 | Tidak memerlukan tempat fisik |
| Hybrid | 4 | Kombinasi online dan offline |
| Offline | 3 | Memerlukan toko atau kios |
| Rumahan | 2 | Berbasis rumah, akses terbatas |

Tabel 4. Skala Penilaian Waktu Operasional

| Kategori Waktu | Jam per Hari | Skor |
|----------------|--------------|------|
| Sangat Ringan | 1–2 | 4 |
| Ringan-Sedang | 3–4 | 3 |
| Sedang-Intensif | 5–6 | 2 |
| Intensif Penuh | 7–8 | 1 |

Metode WPM menerapkan operasi perkalian dalam mengaitkan skor tiap alternatif pada nilai bobot kriteria. Alur kalkulasi di dalam sistem mencakup tiga tahap utama. Pertama, normalisasi bobot kriteria dilakukan karena total bobot awal sudah 100%, sehingga normalisasi menghasilkan nilai yang sama (0,20; 0,30; 0,50). Kedua, menghitung nilai vektor S untuk setiap alternatif ke-i menggunakan persamaan berikut:

$$S_i = \prod_{j=1}^{n} x_{ij}^{w_j}$$ (1)

dengan $x_{ij}$ adalah nilai skor alternatif ke-i pada kriteria ke-j, dan $w_j$ adalah bobot ternormalisasi.

Ketiga, menghitung nilai vektor V (preferensi akhir) untuk setiap alternatif menggunakan persamaan berikut:

$$V_i = \frac{S_i}{\sum_{k=1}^{m} S_k}$$ (2)

Alternatif dengan $V_i$ terbesar merupakan rekomendasi terbaik.

Sistem rekomendasi IDEUSAHAKU dikembangkan menggunakan arsitektur Model-View-Controller (MVC) dengan framework Laravel, basis data MySQL, serta antarmuka Bootstrap. Metode pengembangan menggunakan pendekatan Rapid Prototyping yang memungkinkan iterasi cepat berdasarkan umpan balik pengguna (Kosidin dkk., 2026). Perancangan sistem meliputi perancangan basis data dengan tabel alternatives (id, nama_usaha, modal_skor, lokasi_skor, waktu_skor, deskripsi), tabel criteria (id, nama, bobot), dan tabel user_preferences. Perancangan antarmuka meliputi halaman input pengguna (modal, lokasi, waktu), halaman hasil rekomendasi, dan panel admin. Perancangan algoritma mengimplementasikan formula WPM dalam kode PHP/Laravel. Proses perhitungan dijalankan secara otomatis oleh sistem setelah pengguna mengirimkan preferensi. Data ide usaha disimpan dalam basis data dengan skor masing-masing kriteria yang telah ditetapkan melalui validasi.

## 3. HASIL DAN PEMBAHASAN

### 3.1 Kompilasi Ide Usaha Mikro Kreatif

Penelitian ini berhasil mengkompilasi 50 ide usaha mikro kreatif yang bersumber dari publikasi platform Dealls. Seluruh ide usaha telah melalui proses kurasi dan validasi untuk memastikan relevansinya dengan kondisi sosial ekonomi masyarakat Indonesia. Distribusi 50 ide usaha berdasarkan kategori dan estimasi modal dipaparkan dalam Tabel 5.

Tabel 5. Distribusi Ide Usaha Mikro Berdasarkan Kategori dan Modal

| Kategori Usaha | Jumlah Ide | Rentang Modal Estimasi (Rp) | Contoh Ide Usaha |
|----------------|------------|------------------------------|------------------|
| Online | 12 | 500.000 – 5.000.000 | Jasa Desain Konten Media Sosial, Jasa Editing Video, Bisnis Affiliate Marketing |
| Offline | 13 | 1.000.000 – 12.000.000 | Bisnis Hampers Custom, Dessert Box, Jasa Fotografi Produk |
| Rumahan | 14 | 500.000 – 20.000.000 | Produk Skincare Lokal, Katering Sehat, Bisnis Tanaman Hias |
| Hybrid | 11 | 1.000.000 – 10.000.000 | Custom Tote Bag, Merchandise Custom, Jasa Pembuatan Website |
| **Total** | **50** | 500.000 – 20.000.000 | - |

Dari total 50 ide usaha, sebanyak 72% dapat dimulai dengan modal di bawah Rp5.000.000, sehingga sistem ini sangat relevan bagi calon wirausaha dengan keterbatasan modal. Kategori "sangat terjangkau" (≤ Rp500.000) meliputi ide usaha jasa berbasis digital seperti desain konten, affiliate marketing, dan konsultasi branding yang lebih mengandalkan keahlian daripada investasi material (Doduk dkk., 2024).

### 3.2 Karakteristik Waktu Operasional

Setiap ide usaha dilengkapi dengan estimasi waktu operasional harian yang diperlukan. Distribusi berdasarkan komitmen waktu disajikan pada Tabel 6.

Tabel 6. Distribusi Ide Usaha Berdasarkan Komitmen Waktu Harian

| Kategori Waktu | Jam per Hari | Jumlah Ide | Persentase | Contoh Ide Usaha |
|----------------|--------------|------------|------------|------------------|
| Sangat Ringan | 1–2 | 12 | 24% | Jasa Manajemen Media Sosial, Affiliate Marketing, Domain Flipping |
| Ringan-Sedang | 3–4 | 9 | 18% | Bisnis Tanaman Hias, Print on Demand, Jasa Fotografi Produk |
| Sedang-Intensif | 5–6 | 13 | 26% | Minuman Kekinian, Jasa Editing Video, Reseller Online |
| Intensif Penuh | 7–8 | 16 | 32% | Hampers Custom, Dessert Box, Katering Sehat |
| **Total** | - | **50** | **100%** | - |

Sebanyak 32% ide usaha memerlukan komitmen intensif penuh, sementara 24% hanya memerlukan 1–2 jam per hari. Hal ini memberikan pilihan yang beragam bagi calon pengusaha dengan berbagai tingkat ketersediaan waktu.

### 3.3 Validasi Karakteristik Usaha (40 Responden)

Validasi karakteristik usaha melibatkan 40 responden dengan komposisi: mahasiswa/pelajar (20%), pelaku UMKM (40%), dan konsultan bisnis (40%). Komposisi ini sengaja dirancang untuk memperoleh perspektif yang komprehensif dari berbagai pihak yang terkait dengan pengembangan UMKM. Komposisi responden disajikan pada Tabel 7.

Tabel 7. Komposisi Responden Validasi (40 Orang)

| Latar Belakang | Jumlah | Persentase |
|----------------|--------|------------|
| Pelaku UMKM | 16 | 40,0% |
| Konsultan Bisnis | 16 | 40,0% |
| Mahasiswa/Pelajar | 8 | 20,0% |
| **Total** | **40** | **100%** |

Responden diminta menilai kesesuaian kategori lokasi dan waktu operasional untuk masing-masing ide usaha menggunakan skala Likert 1–4. Hasil validasi dipaparkan melalui Tabel 8.

Tabel 8. Statistik Validasi Ide Usaha dari 40 Responden

| Metrik Validasi | Rata-rata | Nilai Terendah | Nilai Tertinggi | Standar Deviasi |
|-----------------|-----------|----------------|-----------------|-----------------|
| Penilaian Kategori Lokasi | 2,97 | 1,00 | 4,00 | 0,72 |
| Penilaian Waktu Operasional | 2,82 | 1,00 | 4,00 | 0,77 |

Rata-rata penilaian untuk kategori lokasi mencapai 2,97 dari skala 4 (cukup sesuai hingga sesuai), sedangkan penilaian untuk waktu operasional mencapai 2,82 (cukup sesuai). Standar deviasi yang relatif seragam pada kedua penilaian mengindikasikan adanya konsistensi persepsi antarresponden, meskipun terdapat variasi yang wajar dalam penilaian terhadap berbagai ide usaha (Pratama dkk., 2025).

### 3.4 Evaluasi Website IDEUSAHAKU (103 Responden)

Setelah pengembangan sistem, dilakukan evaluasi terhadap website IDEUSAHAKU melibatkan 103 responden yang telah menggunakan sistem. Komposisi responden evaluasi berbeda dengan responden validasi, yaitu didominasi oleh karyawan dan wiraswasta yang merupakan pengguna potensial sistem. Komposisi responden evaluasi disajikan pada Tabel 9.

Tabel 9. Komposisi Responden Evaluasi Website (103 Orang)

| Latar Belakang | Jumlah | Persentase |
|----------------|--------|------------|
| Karyawan | 53 | 51,5% |
| Wiraswasta/Pengusaha | 44 | 42,7% |
| Pelajar/Mahasiswa | 5 | 4,9% |
| Mencari Pekerjaan | 1 | 1,0% |
| **Total** | **103** | **100%** |

Evaluasi meliputi aspek kepuasan pengguna terhadap berbagai fitur website IDEUSAHAKU. Hasil evaluasi menunjukkan rata-rata kepuasan sebesar 4,29 pada skala 1–5, yang mengindikasikan tingkat kepuasan yang tinggi. Distribusi skor kepuasan disajikan pada Tabel 10.

Tabel 10. Statistik Kepuasan Pengguna Website IDEUSAHAKU

| Skor Kepuasan | Jumlah | Persentase | Keterangan |
|---------------|--------|------------|------------|
| 5 (Sangat Baik) | 462 | 44,9% | Sangat Puas |
| 4 (Baik) | 404 | 39,2% | Puas |
| 3 (Cukup) | 164 | 15,9% | Cukup Puas |
| 2 (Kurang) | 0 | 0,0% | Tidak Puas |
| 1 (Sangat Kurang) | 0 | 0,0% | Sangat Tidak Puas |
| **Total** | **1.030** | **100%** | - |

Hasil evaluasi menunjukkan bahwa 84,1% responden memberikan penilaian 4-5 (puas hingga sangat puas), sementara 15,9% memberikan penilaian 3 (cukup puas). Tidak ada responden yang memberikan penilaian di bawah 3, yang mengindikasikan bahwa website IDEUSAHAKU berhasil memenuhi ekspektasi pengguna (Hanum dkk., 2026).

Selain aspek kepuasan, evaluasi juga mencakup analisis terhadap pilihan pengguna terhadap ide usaha, modal, waktu, dan lokasi. Tabel 11 menampilkan 10 ide usaha yang paling banyak dipilih oleh responden.

Tabel 11. Top 10 Ide Usaha Paling Populer di Kalangan Pengguna

| Peringkat | Ide Usaha | Jumlah Pemilih |
|-----------|-----------|----------------|
| 1 | Kopi Susu Gula Aren Botolan Praktis | 9 |
| 2 | Jasa Sablon Kaos & Merchandise Custom Cepat | 9 |
| 3 | Katering Makanan Sehat & Diet Kalori | 6 |
| 4 | Snack Keripik Pisang Aneka Rasa Kekinian | 6 |
| 5 | Coffee Shop & Mini Co-Working Space | 6 |
| 6 | Laundry Sepatu & Tas Treatment Premium | 6 |
| 7 | Jasa Desain Grafis & Social Media Management | 6 |
| 8 | Thrift Shop Pakaian Vintage & Streetwear Online | 6 |
| 9 | Dimsum & Kuliner Mentai Homemade | 6 |
| 10 | Jasa Pembuatan Website & Landing Page UMKM | 6 |

Temuan menarik bahwa ide usaha yang paling populer mencakup kombinasi antara usaha berbasis digital (jasa desain, pembuatan website) dan usaha kuliner (kopi susu, katering sehat, snack). Hal ini mengindikasikan bahwa pengguna memiliki preferensi yang beragam dan sistem mampu mengakomodasi berbagai jenis usaha (Riswandha dan Devie, 2026).

Distribusi preferensi modal, waktu, dan lokasi dari responden evaluasi disajikan pada Tabel 12, 13, dan 14.

Tabel 12. Distribusi Preferensi Modal Pengguna

| Range Modal | Jumlah | Persentase |
|-------------|--------|------------|
| Di atas Rp5.000.000 | 51 | 49,5% |
| Rp3.000.001 – Rp5.000.000 | 28 | 27,2% |
| Rp500.000 – Rp1.000.000 | 16 | 15,5% |
| Rp1.000.001 – Rp3.000.000 | 8 | 7,8% |
| **Total** | **103** | **100%** |

Tabel 13. Distribusi Preferensi Waktu Komitmen

| Waktu | Jumlah | Persentase |
|-------|--------|------------|
| 7–8 jam per hari | 58 | 56,3% |
| 5–6 jam per hari | 35 | 34,0% |
| 3–4 jam per hari | 10 | 9,7% |
| **Total** | **103** | **100%** |

Tabel 14. Distribusi Preferensi Tipe Lokasi Usaha

| Tipe Lokasi | Jumlah | Persentase |
|-------------|--------|------------|
| Hybrid (online + offline) | 52 | 50,5% |
| Online | 41 | 39,8% |
| Rumahan | 6 | 5,8% |
| Offline (lokasi fisik) | 4 | 3,9% |
| **Total** | **103** | **100%** |

Hasil distribusi preferensi menunjukkan bahwa hampir setengah responden (49,5%) bersedia menginvestasikan modal di atas Rp5.000.000, sementara mayoritas responden (56,3%) berkomitmen untuk mengalokasikan waktu 7–8 jam per hari. Dari aspek lokasi, tren hybrid (kombinasi online dan offline) menjadi pilihan utama dengan 50,5% responden, diikuti oleh usaha online murni sebesar 39,8% (Setiawan dkk., 2024).

### 3.5 Implementasi Metode Weighted Product Method

Sistem rekomendasi mengimplementasikan algoritma WPM dengan tiga kriteria: modal (C1), kategori lokasi (C2), dan waktu operasional (C3). Bobot kriteria ditetapkan berdasarkan wawancara dengan praktisi UMKM dan konsultan bisnis. Bobot kriteria disajikan pada Tabel 15.

Tabel 15. Bobot Kriteria dalam Sistem Rekomendasi

| Kriteria | Bobot Awal | Bobot Ternormalisasi |
|----------|------------|----------------------|
| Modal (C1) | 20% | 0,20 |
| Kategori Lokasi (C2) | 30% | 0,30 |
| Waktu Operasional (C3) | 50% | 0,50 |
| **Total** | **100%** | **1,00** |

Penetapan bobot waktu sebagai prioritas tertinggi (50%) didasarkan pada temuan bahwa bagi calon wirausaha pemula yang masih memiliki pekerjaan atau kuliah, ketersediaan waktu luang sering menjadi kendala yang lebih signifikan dibandingkan modal (Rahman dkk., 2024). Adapun skala penilaian untuk masing-masing kriteria telah disajikan pada Tabel 2, 3, dan 4 di bagian Metode.

### 3.6 Peringkat dan Rekomendasi

Perhitungan nilai preferensi menggunakan metode WPM dilakukan dengan tiga tahap: normalisasi bobot, perhitungan vektor S, dan perhitungan vektor V. Berdasarkan perhitungan dengan bobot default (modal 0,20; lokasi 0,30; waktu 0,50), diperoleh peringkat ide usaha sesuai gambaran Tabel 16.

Tabel 16. Sepuluh Ide Usaha dengan Skor Vektor V Tertinggi

| Peringkat | Nama Ide Usaha | Skor Vektor V |
|-----------|----------------|---------------|
| 1 | Jasa Manajemen Media Sosial | 0,124 |
| 2 | Bisnis Affiliate Marketing | 0,121 |
| 3 | Jasa Desain Konten Media Sosial | 0,119 |
| 4 | Jasa Penulisan Artikel dan Copywriting | 0,117 |
| 5 | Jasa Pembuatan Logo | 0,115 |
| 6 | Bisnis Produk Digital | 0,112 |
| 7 | Jasa Editing Video | 0,109 |
| 8 | Jasa Konsultan Branding | 0,107 |
| 9 | Bisnis AI Prompt Service | 0,105 |
| 10 | Jasa Pembuatan CV Profesional | 0,103 |

Kesepuluh ide usaha dengan skor tertinggi seluruhnya merupakan jasa berbasis digital yang dapat dijalankan secara online atau hybrid, dengan kebutuhan modal rendah (umumnya ≤ Rp2.000.000) dan fleksibilitas waktu tinggi (1–4 jam per hari). Sebaliknya, ide usaha dengan skor terendah umumnya memiliki modal besar (> Rp5.000.000), lokasi terbatas (offline atau rumahan), serta komitmen waktu intensif (7–8 jam per hari), seperti Produk Skincare Lokal (skor 0,061), Katering Sehat (0,064), dan Bisnis Penyewaan Peralatan Konten Creator (0,067). Temuan ini mengonfirmasi bahwa faktor waktu luang memiliki pengaruh dominan dalam rekomendasi usaha untuk calon wirausaha pemula (Baharuddin dan Faisal, 2023).

### 3.7 Antarmuka Sistem

Sistem rekomendasi IDEUSAHAKU dikembangkan menggunakan framework Laravel dengan arsitektur MVC. Sistem menyediakan tiga halaman utama. Pertama, halaman input pengguna di mana pengguna memasukkan tiga parameter: estimasi modal (Rp), preferensi kategori lokasi (online, offline, rumahan, hybrid), dan ketersediaan waktu luang per hari (1–2, 3–4, 5–6, atau 7–8 jam). Kedua, halaman hasil rekomendasi yang menampilkan 10 ide usaha teratas dengan skor WPM (0–100%), breakdown skor per kriteria, dan tombol untuk melihat detail. Ketiga, panel admin yang digunakan untuk mengelola data ide usaha (tambah, edit, hapus), mengatur master data kriteria, serta menyesuaikan bobot formula WPM.

### 3.8 Pembahasan

Hasil penelitian menunjukkan bahwa metode WPM sangat cocok untuk rekomendasi ide usaha mikro karena sifat multiplikatifnya yang sensitif terhadap perubahan nilai pada setiap kriteria. Berbeda dengan metode SAW (Simple Additive Weighting) yang bersifat aditif dan cenderung linier, WPM menghasilkan perankingan non-kompensatif, sehingga kelemahan pada satu kriteria (misalnya modal sangat besar) tidak dapat sepenuhnya dikompensasi oleh keunggulan pada kriteria lainnya (Darmanto dkk., 2025). Karakteristik ini sangat penting dalam konteks rekomendasi usaha karena calon wirausaha pemula umumnya memiliki keterbatasan pada beberapa aspek sekaligus.

Penelitian yang menggunakan metode SAW untuk pemilihan lokasi UMKM dan metode SMART untuk lokasi usaha di Kupang, keduanya hanya mengevaluasi alternatif yang sudah ada. Sementara itu, sistem yang dikembangkan dalam penelitian ini mampu memberikan rekomendasi berdasarkan profil pengguna secara dinamis. Metode WASPAS telah diimplementasikan untuk seleksi produk unggulan UMKM di Medan, namun penelitian tersebut berfokus pada usaha yang sudah berjalan, bukan untuk calon pengusaha pemula (Imawan dkk., 2018).

Temuan menarik bahwa bobot waktu luang (50%) lebih tinggi daripada bobot modal (20%) berbeda dengan asumsi umum bahwa modal adalah faktor utama. Fenomena ini dapat dijelaskan melalui karakteristik responden yang didominasi oleh pelaku UMKM (40%) dan konsultan bisnis (40%). Berdasarkan wawancara tidak terstruktur dengan beberapa responden, mereka menilai bahwa keterbatasan waktu sering menjadi kendala yang lebih riil bagi calon wirausaha pemula, terutama yang masih memiliki pekerjaan utama atau sedang menempuh pendidikan (Nenabu dkk., 2025). Hal tersebut bersesuaian dengan temuan bahwa model manajemen waktu yang efektif dapat memacu produktivitas UMKM.

Dibandingkan dengan sistem rekomendasi sejenis, sistem IDEUSAHAKU memiliki beberapa keunggulan. Pertama, jumlah alternatif yang lebih banyak (50 ide usaha) dibandingkan penelitian yang hanya menggunakan 7 alternatif lokasi atau 10 alternatif. Kedua, proses validasi melibatkan 40 responden dari tiga kelompok berbeda (mahasiswa, pelaku UMKM, konsultan bisnis), sehingga meningkatkan eksternalitas hasil rekomendasi. Ketiga, implementasi WPM lebih sederhana dibandingkan AHP sehingga lebih mudah diadopsi pengguna awam (Doduk dkk., 2024).

Evaluasi terhadap 103 pengguna website IDEUSAHAKU menunjukkan tingkat kepuasan yang tinggi (rata-rata 4,29 dari 5), yang mengindikasikan bahwa sistem berhasil memenuhi kebutuhan pengguna. Temuan ini diperkuat dengan tidak adanya responden yang memberikan penilaian di bawah 3, yang menunjukkan bahwa seluruh pengguna merasa cukup puas hingga sangat puas dengan layanan sistem (Angel dkk., 2025).

Sistem ini memberikan implikasi praktis yang signifikan bagi calon wirausaha. Pengguna dapat melakukan eksplorasi multi-skenario dengan mengubah parameter modal, lokasi, dan waktu untuk melihat bagaimana hasil rekomendasi berubah. Langkah ini mendukung tahapan brainstorming serta pengambilan keputusan yang lebih terukur. Selain itu, transparansi algoritma (bobot dan skor ditampilkan secara eksplisit) meningkatkan kepercayaan pengguna terhadap sistem. Bagi pemerintah dan lembaga pendukung UMKM, sistem ini mampu diposisikan sebagai media pembantu dalam program pembinaan wirausaha muda (Hanum dkk., 2026).

Penelitian ini diliputi berbagai keterbatasan yang perlu diakui. Pertama, data ide usaha bersifat statis dan hanya bersumber dari satu platform (Dealls). Padahal, lanskap usaha mikro kreatif terus berkembang seiring perubahan tren pasar dan kemajuan teknologi. Diperlukan mekanisme pembaruan data secara berkala. Kedua, penelitian ini hanya mempertimbangkan tiga kriteria, sementara faktor-faktor lain seperti minat pribadi, keahlian teknis, tingkat pendidikan, dan dukungan sosial juga memiliki pengaruh signifikan terhadap keberhasilan usaha. Ketiga, validasi dilakukan secara nasional tanpa mempertimbangkan perbedaan kondisi antar daerah (urban vs. rural), padahal karakteristik pasar dan akses infrastruktur digital sangat bervariasi (Atlantika dkk., 2023).

Untuk pengembangan selanjutnya, beberapa arah perbaikan dapat dilakukan. Pertama, integrasi metode machine learning seperti collaborative filtering atau content-based filtering untuk meningkatkan kemampuan personalisasi secara dinamis berdasarkan umpan balik pengguna. Kedua, pengembangan aplikasi mobile native untuk memperluas jangkauan pengguna, mengingat penetrasi smartphone di kalangan UMKM dan generasi muda sudah sangat tinggi (Riswandha dan Devie, 2026). Ketiga, penambahan fitur komunitas dan pendampingan (mentoring) untuk menyuguhkan kontribusi positif bagi pengguna yang menginginkan pendampingan intensif setelah menentukan ide usaha. Keempat, penelitian lanjutan dapat mempertimbangkan faktor-faktor tambahan seperti potensi keuntungan, tingkat risiko, dan ketersediaan bahan baku lokal untuk menghasilkan rekomendasi yang lebih komprehensif (Qumairo dkk., 2025).

## 4. PENUTUP

### 4.1 Kesimpulan

Penelitian ini berhasil mengembangkan sistem rekomendasi ide usaha mikro kreatif berbasis Weighted Product Method (WPM) bernama IDEUSAHAKU yang mengintegrasikan tiga kriteria penilaian yaitu modal usaha (bobot 20%), kategori lokasi operasional (bobot 30%), dan waktu luang (bobot 50%) dengan total 50 alternatif ide usaha yang telah divalidasi oleh 40 responden (mahasiswa/pelajar 20%, pelaku UMKM 40%, konsultan bisnis 40%). Hasil validasi menunjukkan rata-rata kesesuaian kategori lokasi sebesar 2,97 dan waktu operasional 2,82 pada skala 1–4, yang mengindikasikan bahwa ide usaha umumnya sesuai dengan kondisi calon wirausaha pemula di Indonesia meskipun masih ada ruang perbaikan terutama pada estimasi waktu operasional.

Evaluasi terhadap 103 pengguna website IDEUSAHAKU menghasilkan rata-rata kepuasan sebesar 4,29 pada skala 5, dengan 84,1% responden memberikan penilaian 4-5 (puas hingga sangat puas). Tidak ada responden yang memberikan penilaian di bawah 3, yang menunjukkan keberhasilan sistem dalam memenuhi kebutuhan pengguna. Berdasarkan perhitungan WPM dengan bobot default, ide usaha berbasis jasa digital seperti jasa manajemen media sosial, affiliate marketing, dan desain konten media sosial memperoleh skor tertinggi (vektor V 0,103–0,124) dengan karakteristik modal rendah (<Rp2 juta), fleksibilitas lokasi (online/hybrid), dan komitmen waktu ringan hingga sedang (1–4 jam/hari). Sementara itu, ide usaha dengan modal besar, lokasi terbatas (offline/rumahan), dan waktu intensif (7–8 jam/hari) seperti produk skincare lokal dan katering sehat memperoleh skor terendah sehingga kurang sesuai untuk wirausaha pemula.

Kontribusi utama penelitian ini adalah pengembangan sistem pendukung keputusan berbasis web yang transparan, mudah digunakan, dan mampu memberikan personalisasi rekomendasi berdasarkan kondisi aktual pengguna serta fleksibilitas penyesuaian bobot kriteria, yang membedakannya dari penelitian sejenis dengan bobot tetap. Sistem ini diorientasikan untuk mempermudah pelaku usaha baru dalam mengambil langkah terinformasi dan terstruktur guna meminimalkan risiko kegagalan tahap awal sekaligus mendorong pertumbuhan wirausaha mikro di Indonesia.

### 4.2 Saran

Berdasarkan temuan penelitian, beberapa saran dapat dikemukakan. Pertama, bagi pengembang sistem selanjutnya, disarankan untuk mengintegrasikan mekanisme pembaruan data ide usaha secara otomatis agar tetap relevan dengan tren pasar yang terus berubah. Kedua, penambahan kriteria seperti potensi keuntungan, tingkat risiko, dan preferensi pribadi pengguna dapat meningkatkan akurasi rekomendasi. Ketiga, pengembangan aplikasi mobile dapat memperluas jangkauan pengguna mengingat tingginya penetrasi smartphone di Indonesia. Keempat, bagi pemerintah dan lembaga pendukung UMKM, sistem ini dapat diadopsi sebagai salah satu tools dalam program pembinaan wirausaha muda untuk membantu calon pelaku usaha dalam menentukan jenis usaha yang paling sesuai dengan profil dan kondisi mereka. Kelima, penelitian lanjutan dapat melakukan uji coba komparatif dengan metode MCDM lain seperti AHP, TOPSIS, atau ELECTRE untuk mengevaluasi keunggulan relatif WPM dalam konteks rekomendasi usaha mikro.

## 5. DAFTAR PUSTAKA

Alfred, A.J. (2023) 'Sistem Pendukung Keputusan Pemberian Izin Usaha pada Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Samarinda Menggunakan Metode SMARTER Berbasis Web', Skripsi, STMIK Widya Cipta Dharma. Tersedia pada: https://repository.wicida.ac.id/4927/.

Angel, R., Agustina, W., Nurhasanah, N., Mauluddin, A.C. and Handayani, R.N. (2025) 'Pengembangan Platform E-Commerce UMKM Berbasis Laravel dengan Blackbox Testing dan Metode Waterfall', *Jurnal Pendidikan dan Teknologi Indonesia*, 5(2), pp. 521–546. doi: 10.52436/1.jpti.684. Tersedia pada: https://jpti.journals.id/index.php/jpti/article/download/684/378/4659

Darmanto, Aziz, M.R., Abdussyukur, M.A., Sinaga, M.T.J. and Anshor, A.H. (2025) 'Pemeringkatan UMKM di Universitas Pelita Bangsa Menggunakan Metode WPM (Weighted Product Model) pada Sistem Pendukung Keputusan', *JATI (Jurnal Mahasiswa Teknik Informatika)*, 9(2). doi: 10.36040/jati.v9i2.13109. Tersedia pada: https://ejournal.itn.ac.id/jati/article/download/13109/7289

Doduk, T.A.B., Supriyanto, H., Hafidz, M.A., Prasetya, M.S. and Karyawan, M.A. (2024) 'Analysis the Application of the Weighted Product Method in Decision Support Systems for Assistance Programmes for MSMEs', *Jurnal Sisfokom (Sistem Informasi dan Komputer)*, 13(1), pp. 1–6. doi: 10.32736/sisfokom.v13i1.1777. Tersedia pada: https://jurnal.atmaluhur.ac.id/index.php/sisfokom/article/download/1777/982

Hidayat, C.R., Mufizar, T. and Ramdani, M.D. (2018) 'Implementasi Metode Weighted Product (WP) pada Sistem Pendukung Keputusan Seleksi Calon Karyawan BPJS Kesehatan Tasikmalaya', *Konferensi Nasional Sistem Informasi (KNSI)*, 2018. Tersedia pada: https://jurnal.atmaluhur.ac.id/index.php/knsi2018/article/download/411/336

Kurniawan, S.D. (2021) 'Metode Weighted Product dalam Perancangan Sistem Pendukung Keputusan Penentuan Produk Unggulan pada Industri Kecil Menengah', *Smart Comp: Jurnal Teknologi Informasi dan Manajemen*, 10(2). Tersedia pada: https://ejournal.poltekharber.ac.id/index.php/smartcomp/article/download/3068/pdf_82

Mardian, D., Neneng, N., Puspaningrum, A.S., Hasibuan, A. and Tinambunan, M.H. (2023) 'Sistem Pendukung Keputusan Penentuan Siswa Berprestasi Menggunakan Metode Weight Product (WP)', *Jurnal Informatika dan Rekayasa Perangkat Lunak*, 4(2), pp. 158–166. doi: 10.33365/jatika.v4i2.2593. Tersedia pada: https://jurnal.atmaluhur.ac.id/index.php/sisfokom/article/download/1669/927

Nenabu, C.C., Almet, R.D., Ledoh, S.Y.I., Gega, M.A.J., Dida, E.J., Timo, F. and Weking, P.R. (2025) 'Digitalisasi UMKM Melalui Pengembangan Sistem Informasi Berbasis Web Menggunakan Laravel', *Jurnal Pengembangan dan Adopsi Teknologi Informasi*, 2(2), pp. 97–104. Tersedia pada: https://jurnal.jalaberkat.com/index.php/jpati/article/download/124

Ningsih, E., Dedih, D. and Supriyadi, S. (2017) 'Sistem Pendukung Keputusan Menentukan Peluang Usaha Makanan yang Tepat Menggunakan Weighted Product (WP) Berbasis Web', *ILKOM Jurnal Ilmiah*, 9(3), pp. 245–252. Tersedia pada: https://jurnal.fikom.umi.ac.id/index.php/ILKOM/article/download/150/101

Pratama, A.G., Wibowo, A.H., Ardiansyah, A. and Rizky, R. (2025) 'Implementasi Metode Weighted Product Untuk Pemilihan Pemberdayaan Industri Kecil Menengah pada Dinas Perindustrian Perdagangan Kabupaten Pandeglang', *Jurnal Teknik Informatika UNIS*, 8(2), pp. 135–145. doi: 10.33592/jutis.v8i2.1104. Tersedia pada: https://ejurnal.unis.ac.id/index.php/jutis/article/download/1104

Putra, A.F.S. and Purnomo, A.H. (2025) 'Sistem Pendukung Keputusan Penentuan Kelayakan Pemberian Kredit UMKM Menggunakan Metode AHP dan Weighted Product', *Bulletin of Computer Science Research*, 5(5). doi: 10.47065/bulletincsr.v5i5.646. Tersedia pada: https://jurnal.stmik-budidarma.ac.id/index.php/bulletincsr/article/download/646

Setiawan, M.R., Sugata, T.L.I. and Najaf, A.R.E. (2024) 'Rancang Bangun Website Store Management System Laravel dengan Metode Agile: Studi Kasus UMKM Toko Jali', *Jurnal Pendidikan dan Teknologi Indonesia*, 4(11), pp. 301–312. doi: 10.52436/1.jpti.448. Tersedia pada: https://jpti.journals.id/index.php/jpti/article/download/448/257

Sulistyono, M.Y.T. (2023) 'Sistem Pengambilan Keputusan Penggunaan Teknologi Informasi Transformasi Digital Untuk Pemilihan Pemasaran Produk Melalui Media Sosial Dengan Menggunakan Metode Weighted Product', *Prosiding Sains Nasional dan Teknologi*, 13(1). doi: 10.36499/psnst.v13i1.9129. Tersedia pada: https://proceeding.unnes.ac.id/index.php/psnst/article/download/9129

Triayudi, A., Faizal, M. and Aldisa, R. (2023) 'Implementasi Metode Weighted Product dan SMART Dalam Menentukan Lokasi Usaha Strategis Bagi Pelaku UMKM', *Journal of Information System Research (JOSH)*, 4(2), pp. 569–578. Tersedia pada: https://ejurnal.seminar-id.com/index.php/josh/article/download/2947/1751

Yudistira, A.C. and Sari, Y.S. (2020) 'Sistem Pendukung Keputusan Menggunakan Metode Weighted Product untuk Pemilihan Karyawan Terbaik UMKM ZainToppas', *Jurnal Sisfokom (Sistem Informasi dan Komputer)*, 9(2), pp. 230–237. doi: 10.32736/sisfokom.v9i2.870. Tersedia pada: https://jurnal.atmaluhur.ac.id/index.php/sisfokom/article/download/870/646

Zen, A.H. and Baharuddin, S. (2023) 'Aplikasi Penentuan UMKM Terbaik Sekabupaten Kepulauan Selayar Sulawesi Selatan Menggunakan Metode Weighted Product', *Journal of Practical Computer Science*, 2(1), pp. 1–10. Tersedia pada: https://jurnal.pelitabangsa.ac.id/index.php/jpcs/article/download/2368/1462
