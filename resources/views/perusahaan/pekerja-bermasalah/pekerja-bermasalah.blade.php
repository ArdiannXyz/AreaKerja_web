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
        <div class="flex justify-between items-center px-8 py-4 mt-16">
            <!-- <a href="{{ route('perusahaan.data.pekerja') }}"
                class="flex items-center gap-2 bg-[#00509d] hover:bg-[#003d7a] text-white px-6 py-2 rounded-lg text-sm">
                <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.92187 13.3327L1.08854 7.49935L6.92188 1.66602" stroke="white" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Kembali
            </a> -->
            <span class="bg-[#00509d] hover:bg-[#003d7a] text-white px-8 py-3 rounded-xl text-sm font-semibold mx-auto">
                List Pekerja Bermasalah
            </span>
            
        </div>

        <!-- Content -->
        <div class="px-6 py-8 max-w-4xl mx-auto">
            <!-- Title -->
            <h2 class="font-semibold text-xl mb-1">Lorem Ipsum</h2>
            <p class="text-sm font-medium mb-6 leading-relaxed">
                Halaman ini dibuat untuk membantu perusahaan dalam proses perekrutan dengan menyediakan akses ke daftar
                track record pekerja yang pernah bermasalah.
                Dengan mengklik tombol 'request', Anda dapat mengajukan permintaan untuk menerima daftar tersebut melalui
                WhatsApp atau aplikasi pilihan lainnya.
                Fitur ini memberikan informasi penting yang bisa menjadi bahan pertimbangan tambahan sebelum mengambil
                keputusan untuk merekrut kandidat.
            </p>

            <!-- Box Section -->
            <div class="flex items-start gap-4 p-4">
                <!-- Icon -->
                <div class="shrink-0">
                    <svg width="110" height="110" viewBox="0 0 117 117" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="3" width="111" height="111" rx="25" stroke="#F79C4B" stroke-width="6" />
                        <path
                            d="M59 23.3333C51.9276 23.3333 45.1448 26.1428 40.1438 31.1438C35.1428 36.1448 32.3333 42.9276 32.3333 50V55.3333H37.6667C39.0812 55.3333 40.4377 55.8952 41.4379 56.8954C42.4381 57.8956 43 59.2522 43 60.6667V76.6667C43 78.0811 42.4381 79.4377 41.4379 80.4379C40.4377 81.4381 39.0812 82 37.6667 82H32.3333C30.9188 82 29.5623 81.4381 28.5621 80.4379C27.5619 79.4377 27 78.0811 27 76.6667V50C27 45.7977 27.8277 41.6365 29.4359 37.7541C31.044 33.8717 33.4011 30.3441 36.3726 27.3726C39.3441 24.4011 42.8717 22.044 46.7541 20.4359C50.6365 18.8277 54.7977 18 59 18C63.2023 18 67.3634 18.8277 71.2459 20.4359C75.1283 22.044 78.6559 24.4011 81.6274 27.3726C84.5989 30.3441 86.956 33.8717 88.5641 37.7541C90.1723 41.6365 91 45.7977 91 50V82C91 85.5362 89.5952 88.9276 87.0948 91.4281C84.5943 93.9286 81.2029 95.3333 77.6667 95.3333H66.2853C65.8172 96.1441 65.144 96.8173 64.3333 97.2854C63.5225 97.7535 62.6028 98 61.6667 98H56.3333C54.9188 98 53.5623 97.4381 52.5621 96.4379C51.5619 95.4377 51 94.0811 51 92.6667C51 91.2522 51.5619 89.8956 52.5621 88.8954C53.5623 87.8952 54.9188 87.3333 56.3333 87.3333H61.6667C62.6028 87.3333 63.5225 87.5798 64.3333 88.0479C65.144 88.516 65.8172 89.1892 66.2853 90H77.6667C79.7884 90 81.8232 89.1571 83.3235 87.6568C84.8238 86.1565 85.6667 84.1217 85.6667 82H80.3333C78.9188 82 77.5623 81.4381 76.5621 80.4379C75.5619 79.4377 75 78.0811 75 76.6667V60.6667C75 59.2522 75.5619 57.8956 76.5621 56.8954C77.5623 55.8952 78.9188 55.3333 80.3333 55.3333H85.6667V50C85.6667 46.4981 84.9769 43.0305 83.6368 39.7951C82.2967 36.5598 80.3324 33.62 77.8562 31.1438C75.38 28.6676 72.4402 26.7033 69.2049 25.3632C65.9695 24.0231 62.5019 23.3333 59 23.3333Z"
                            fill="#032068" />
                    </svg>
                </div>

                <!-- Text -->
                <div class="flex-1">
                    <h2 class="font-semibold text-xl mb-1">Permintaan Panggilan</h2>
                    <p class="text-medium font-medium">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis et est elit. Etiam sagittis ut mauris
                        molestie lacinia.
                        Maecenas porttitor eros id dui iaculis malesuada
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer Button -->
        <div class="flex justify-end items-center px-6 py-5">
            <button id="BtnList"
                class="bg-[#00509d] hover:bg-[#003d7a] text-white px-8 py-2 rounded-lg text-sm font-medium mr-4">
                Kirim Permintaan
            </button>
        </div><br>


    </body>

    </html>


    <script>
        document.getElementById('BtnList').addEventListener('click', function() {

            fetch("{{ route('perusahaan.pekerja.bermasalah.wa') }}", {
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

