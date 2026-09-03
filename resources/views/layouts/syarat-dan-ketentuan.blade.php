@extends(auth()->check() && auth()->user()->role === 'perusahaan' ? 'layouts.index-perusahaan' : 'layouts.index')
@section('content')
    <!-- Hero Section -->
    <section class="relative pt-20 w-full bg-slate-900">
        <img src="{{ url('/images/syarat-dan-ketentuan-banner.png') }}" alt="Syarat dan Ketentuan" class="w-full h-auto object-cover block">
    </section>

    <div class="bg-white flex justify-center py-6 sm:py-10">
        <div class="p-5 sm:p-10 w-full max-w-4xl leading-relaxed text-justify break-words">


            <h1 class="text-2xl font-bold mb-6">Syarat &amp; Ketentuan Penggunaan</h1>

            <p class="mb-4">
                Pada dokumen Syarat dan Ketentuan Penggunaan ("SKK ini") menyatakan syarat dan ketentuan yang perlu Anda
                penuhi dan setujui untuk dapat menggunakan aplikasi (disebut juga situs yang Kami sediakan ("Layanan"))
                dengan segala fitur dan kegunaannya, dan hubungan Anda dengan Area Kerja sebagai penyedia Layanan. Harap
                baca SKK dengan seksama karena isinya akan mempengaruhi hak dan kewajiban Anda berdasarkan hukum. Jika Anda
                tidak menyetujui SKK ini, mohon untuk tidak mendaftar atau menggunakan Layanan.
            </p>

            <p class="mb-4">
                Penggunaan Layanan melibatkan beberapa pihak yang masing-masing memiliki hak dan kewajiban yang perlu
                dipenuhi. Pihak-pihak tersebut adalah:
            </p>
            <ol class="list-decimal ml-6 mb-6">
                <li>Area Kerja, sebagai penyedia Layanan.</li>
                <li>Pengguna Layanan ("Pengguna"), yaitu individu yang bertujuan untuk mencari informasi lowongan pekerjaan
                    ("Pencari Kerja") atau Perusahaan ("Perusahaan") yaitu individu yang memiliki wewenang sebagai perantara
                    pekerjaan ataupun Layanan ("Perusahaan") atas individu yang bertujuan memposting Layanan untuk
                    mempromosikan lowongan kerja pada fitur Belajar yang terdapat pada Layanan.</li>
            </ol>

            <h1 class="text-2xl font-bold mb-4">Ketentuan Umum Pengguna</h1>

            <p class="mb-4">Dengan menggunakan Layanan, Anda menyatakan bahwa:</p>
            <ol class="list-[lower-alpha] ml-6 mb-6">
                <li> Anda setuju untuk terikat dengan S&K yang ditetapkan serta kebijakan mengenai pembaruan S&K di kemudian
                    hari.
                    Anda dipersilahkan untuk tidak menggunakan dan/atau mengakses Layanan jika tidak setuju untuk terikat
                    dengan S&K
                    ini atau pun pembaruannya.</li>
                <li> Apabila Anda belum dewasa menurut ketentuan perundang-undangan yang berlaku, wajib mendapatkan
                    bimbingan dan
                    pengawasan orang tua dalam menggunakan Layanan.</li>
                <li>Jika ada pertentangan antara S&K dengan kontrak apa pun yang Anda miliki dengan Area Kerja, Ketentuan
                    ini
                    yang akan diutamakan (kecuali dinyatakan lain).</li>
            </ol>

            <ol class="list-[lower-alpha]">
                <li class="mb-4 font-bold text-2xl ml-8">Registrasi</li>
                <ul class="list-disc ml-6 mb-6">
                    <li> Untuk mendaftarkan akun pada Layanan, anda harus berusia 18 tahun ke atas.</li>
                    <li> Data-data yang anda masukkan untuk proses registrasi harus merupakan data tepat dan lengkap.
                    </li>
                    <li> Anda diperbolehkan mendaftar atas nama pribadi atau perusahaan tempat anda bekerja (apabila Anda
                        memiliki hak),
                        dan tidak diperbolehkan mendaftar atas nama orang atau perusahaan lain.</li>
                    <li> Anda bertanggung jawab untuk menjaga kerahasiaan data yang Anda gunakan untuk masuk ke akun Anda.
                    </li>
                    <li>Dalam hal Anda lupa kata sandi akun, Anda dapat mengikuti langkah-langkah yang terdapat pada Layanan
                        untuk
                        membuat kata sandi baru. Apabila terdapat langkah tertentu yang tidak dapat Anda penuhi, maka anda
                        tidak akan
                        mendapatkan akses untuk membuat kata sandi baru.</li>
                    <li> Jika kami memiliki alasan untuk meyakini bahwa akan terjadi pelanggaran keamanan atau
                        penyalahgunaan akun Anda
                        oleh pihak yang tidak berhak, kami akan meminta Anda untuk mengubah kata sandi Anda atau kami dapat
                        menangguhkan
                        akun Anda demi keamanan.</li>
                </ul>


                <li class="mb-4 font-bold text-2xl ml-8">Kekayaan Intelektual</li>
                <ul class="list-disc ml-6 mb-6">

                    <li>
                        Aplikasi, situs, akun media sosial, dan semua layanan yang dimiliki AreaKerja adalah milik eksklusif
                        AreaKerja atau pemberi lisensinya.
                    </li>
                    <li>
                        AreaKerja dan Layanan dilindungi oleh hak cipta, merek dagang, dan hak kekayaan intelektual lainnya.
                    </li>
                    <li>
                        Anda dapat menggunakan dan menikmati layanan dan tampilan Layanan. Anda tidak boleh mereproduksi,
                        memodifikasi, menyalin atau mendistribusikan atau menggunakan untuk tujuan komersial hal apa pun di
                        Layanan,
                        tanpa izin tertulis dari AreaKerja.
                    </li>
                    <li>
                        Setiap pengguna Layanan setuju untuk tidak menggunakan Layanan dengan cara apa pun yang melanggar
                        hak
                        kekayaan intelektual atau hak milik orang lain seperti:
                    </li>


                    <ol class="list-[lower-alpha] ml-6 mb-6">
                        <li>
                            mencetak, mengunduh, menggandakan, mengirimkan atau menyalin, mereproduksi, mendistribusikan
                            ulang,
                            menerbitkan ulang, atau menggunakan informasi pribadi apapun tentang pengguna lain, kecuali
                            telah
                            mendapatkan izin tertulis atau dinyatakan lain pada S&K;
                        </li>
                        <li>
                            merekayasa balik (reverse engineer) atau mendekompilasi bagian mana pun dari Layanan;
                        </li>
                        <li>
                            membuat akun dengan identitas orang lain, menggunakan nama perusahaan secara tanpa hak, atau
                            mengunggah
                            lowongan pekerjaan secara tanpa hak;
                        </li>
                        <li>
                            menghapus atau mengubah materi apa pun yang diunggah oleh orang atau entitas lain mana pun;
                        </li>
                        <li>
                            mengakses data yang tidak ditujukan untuk Anda atau masuk ke server atau akun yang tidak
                            diizinkan untuk Anda akses; dan
                        </li>
                        <li>
                            hal-hal lain yang dilakukan secara tanpa hak dan dapat menimbulkan kerugian secara materiil
                            atau pun immateriil bagi pihak lain.
                        </li>
                    </ol>


                    <li>
                        Kami tidak mengklaim kepemilikan konten Pengguna yang diposting pada Layanan dan Anda bebas untuk
                        berbagi
                        konten Anda dengan orang lain pada media yang telah Kami sediakan di Layanan. Namun, Kami memerlukan
                        izin
                        hukum tertentu dari Pengguna (dikenal sebagai "lisensi") untuk menyediakan Layanan. Saat Pengguna
                        membagikan, memposting, atau mengunggah konten yang dilindungi oleh hak kekayaan intelektual
                        (seperti foto
                        atau video) pada atau sehubungan dengan Layanan Kami, Pengguna dengan ini memberikan kepada Kami
                        konten
                        non-eksklusif, bebas royalti, dapat dialihkan, disublisensikan, lisensi di seluruh dunia untuk
                        menggunakan,
                        mendistribusikan, memodifikasi, menjalankan, menyalin, dan menampilkan secara publik. Lisensi ini
                        akan
                        berakhir ketika konten Pengguna tersebut dihapus dari sistem Kami.
                    </li>
                    <li>
                        Segala konten yang terdapat pada Layanan tersimpan dalam bentuk elektronik atau dicetakkan dalam
                        satu
                        salinan untuk penggunaan pribadi dan non-komersial bagi Pengguna, asalkan Pengguna menjaga semua hak
                        kekayaan intelektual yang terdapat didalamnya. Pengguna tidak boleh mereproduksi, memodifikasi,
                        menyalin
                        atau mendistribusikan atau menggunakan untuk tujuan komersial atau terhadap setiap konten pada
                        Layanan tanpa
                        izin tertulis dari Kami.
                    </li>
                    <li>
                        Dengan menggunakan Layanan, Pengguna (khususnya Perusahaan) bersedia memberikan lisensi atas merek
                        dan logo
                        miliknya kepada AreaKerja sepanjang digunakan untuk tujuan-tujuan promosional dengan cara yang tidak
                        melanggar hukum yang berlaku di Indonesia.
                        ‍
                    </li>
                </ul>
            </ol>
        </div>
    </div>


    @include('layouts.footer')
@endsection
