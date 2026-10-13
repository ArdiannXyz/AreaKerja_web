//pelamar
const btn_pelamar = document.getElementById("btn_pelamar");
const btn_perusahaan = document.getElementById("btn_perusahaan");


const regis_pelamar = document.getElementById("regis_pelamar");
const regis_perusahaan = document.getElementById("regis_perusahaan");

if (btn_pelamar && regis_pelamar) {
    btn_pelamar.addEventListener("click", () => {
        // tampilkan form pelamar
        regis_pelamar.classList.remove("hidden");
        regis_perusahaan.classList.add("hidden");

        // styling aktif
        btn_pelamar.classList.add("bg-orange-500", "text-white");
        btn_pelamar.classList.remove("bg-gray-200", "text-gray-600");

        // styling nonaktif
        btn_perusahaan.classList.add("bg-gray-200", "text-gray-600");
        btn_perusahaan.classList.remove("bg-orange-500", "text-white");
    });
}

if (btn_perusahaan && regis_perusahaan) {
    btn_perusahaan.addEventListener("click", () => {
        // tampilkan form perusahaan
        regis_perusahaan.classList.remove("hidden");
        regis_pelamar.classList.add("hidden");

        // styling aktif
        btn_perusahaan.classList.add("bg-orange-500", "text-white");
        btn_perusahaan.classList.remove("bg-gray-200", "text-gray-600");

        // styling nonaktif
        btn_pelamar.classList.add("bg-gray-200", "text-gray-600");
        btn_pelamar.classList.remove("bg-orange-500", "text-white");
    });
}




//PROFILE
//ORGANISASI
$("#formOrganisasi").on("submit", function(e) {
    e.preventDefault();

    $.ajax({
        url: "{{ route('organisasi.store', $pelamar->id) }}",
        type: "POST",
        data: $(this).serialize(),
        success: function(res) {
            // append data yang baru ditambahkan
            $("#listOrganisasi").append(`
                <div class="border p-2 mb-2 rounded">
                    <b>${res.nama_organisasi}</b> - ${res.jabatan} (${res.tahun_awal} - ${res.tahun_akhir ?? '-'})
                    <br>
                    <small>${res.deskripsi ?? ''}</small>
                </div>
            `);

            // reset form & tutup modal
            $("#formOrganisasi")[0].reset();
            $("#crud-modal").modal('hide'); // kalau modal pakai bootstrap
        },
        error: function(err) {
            console.error(err.responseText);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menambahkan data' });
            } else {
                alert("Gagal menambahkan data");
            }
        }
    });
});


//PENGALAMAN KERJA
document.getElementById('crud-modalkerja').classList.add('hidden');

$("#formkerja").on("submit", function(e) {
    e.preventDefault();

    $.ajax({
        url: "{{ route('kerja.store', $pelamar->id) }}",
        type: "POST",
        data: $(this).serialize(),
        success: function(res) {
            // append data yang baru ditambahkan
            $("#listKerja").append(`
                <div class="border p-2 mb-2 rounded">
                    <b>${res.nama_perusahaan}</b> - ${res.posisi_pekerjaan} (${res.tahun_awal} - ${res.tahun_akhir ?? '-'})
                    <br>
                    <small>${res.deskripsi ?? ''}</small>
                </div>
            `);

            // reset form & tutup modal
            $("#formKerja")[0].reset();
            $("#crud-modalkerja").modal('hide'); // kalau modal pakai bootstrap
        },
        error: function(err) {
            console.error(err.responseText);
            if (typeof Swal !== 'undefined') {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menambahkan data' });
            } else {
                alert("Gagal menambahkan data");
            }
        }
    });
});



//email sama
$('#formRegis').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: "{{ route('regis.proses') }}",
        type: "POST",
        data: $(this).serialize(),

        success: function(res) {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Akun berhasil dibuat!',
                    text: 'Silahkan login menggunakan akun anda.',
                });

                // Clear form agar ketika back tidak muncul alert lagi
                $('#formRegis')[0].reset();
            }
        },

        error: function(err) {
            let errors = err.responseJSON.errors;

            // Jika email sudah terdaftar
            if (errors.email) {
                Swal.fire({
                    icon: 'error',
                    title: 'Email sudah digunakan!',
                    text: errors.email[0]
                });
                return;
            }

            // Jika username sudah ada
            if (errors.username) {
                Swal.fire({
                    icon: 'error',
                    title: 'Username sudah digunakan!',
                    text: errors.username[0]
                });
                return;
            }

            // Jika telepon kosong
            if (errors.telepon_pelamar) {
                Swal.fire({
                    icon: 'error',
                    title: 'Nomor telepon wajib diisi!',
                    text: errors.telepon_pelamar[0]
                });
                return;
            }

            // Jika error lain
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Periksa kembali data yang kamu isi.'
            });
        }
    });
});

// //STATUS PELAMAR
// document.getElementById('statusSelect').addEventListener('change', function () {

//     let selected = this.value;
//     let kategori = document.getElementById('kategoriPelamar').value;

//     // Hanya kandidat aktif yang boleh munculkan modal
//     if (selected === 'Bekerja' && kategori === 'kandidat aktif') {
//         document.getElementById('modalPeringatan').classList.remove('hidden');
//     }
// });

// // Modal Peringatan
// document.getElementById('yaPeringatan').onclick = function () {
//     document.getElementById('modalPeringatan').classList.add('hidden');
//     document.getElementById('modalNonaktif').classList.remove('hidden');
// };

// document.getElementById('tidakPeringatan').onclick = function () {
//     document.getElementById('modalPeringatan').classList.add('hidden');
//     document.getElementById('statusSelect').value = ""; // batalkan perubahan
// };


// // Modal Nonaktif
// document.getElementById('yaNonaktif').onclick = function () {

//     fetch("/pelamar/update-kategori/{{ $pelamar->id }}", {
//         method: "POST",
//         headers: {
//             "X-CSRF-TOKEN": "{{ csrf_token() }}",
//             "Content-Type": "application/json",
//         },
//         body: JSON.stringify({
//             kategori: "kandidat nonaktif"
//         }),
//     })
//     .then(res => res.json())
//     .then(data => {
//         location.reload(); // refresh halaman
//     });
// };

// document.getElementById('tidakNonaktif').onclick = function () {
//     document.getElementById('modalNonaktif').classList.add('hidden');
//     document.getElementById('statusSelect').value = "";
// };




