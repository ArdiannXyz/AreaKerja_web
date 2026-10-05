@extends('layouts.index-perusahaan')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>List Pekerja Bermasalah</title>
    </head>

    <body class="bg-white text-gray-800 mt-16"><br>

        <!-- Header Buttons -->
        <div class="flex justify-center items-center px-8 py-4 mt-16">
            <button
                class="flex items-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white px-6 py-2 rounded-xl text-medium">
                Laporan Harian Pekerja
            </button>
        </div>
        
        </div>

        <!-- Content -->
        <div class="px-6 py-8 max-w-4xl mx-auto">
            <!-- Title -->
            <h2 class="font-semibold text-xl mb-1">Lorem Ipsum</h2>
            <p class="text-sm font-medium mb-8 leading-relaxed">
                Halaman ini dibuat untuk membantu perusahaan dalam proses perekrutan dengan menyediakan akses ke daftar
                track record pekerja yang pernah bermasalah.
                Dengan mengklik tombol 'request', Anda dapat mengajukan permintaan untuk menerima daftar tersebut melalui
                WhatsApp atau aplikasi pilihan lainnya.
                Fitur ini memberikan informasi penting yang bisa menjadi bahan pertimbangan tambahan sebelum mengambil
                keputusan untuk merekrut kandidat.
            </p><br>


        </div>

        <!-- Footer Button -->
        <div class="flex justify-end items-center px-6 py-5 ">
            <button id="BtnLaporan" class="bg-[#00509d] hover:bg-[#003d7a] text-white px-8 py-2 rounded-lg text-sm font-medium mr-4">
                Kirim Permintaan
            </button>
        </div><br>


    </body>

    </html>


     <script>
        document.getElementById('BtnLaporan').addEventListener('click', function() {

            fetch("{{ route('perusahaan.laporan.harian.pekerja.wa') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({}) // WAJIB ADA
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect_url;
                    } else {
                        alert(data.message || "Gagal memproses permintaan.");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Terjadi kesalahan. Coba lagi.");
                });

        });
    </script>
    @include('layouts.footer')
@endsection

