//finance
let menuSelect = document.getElementById("menu_select");
let paketHarga = document.getElementById("paket_harga");
let riwayat = document.getElementById("riwayat");
let laporan = document.getElementById("laporan");

function showSection(section) {
    if (paketHarga) paketHarga.classList.add("hidden");
    if (riwayat) riwayat.classList.add("hidden");
    if (laporan) laporan.classList.add("hidden");

    if (section === "paket_harga" && paketHarga) paketHarga.classList.remove("hidden");
    if (section === "riwayat" && riwayat) riwayat.classList.remove("hidden");
    if (section === "laporan" && laporan) laporan.classList.remove("hidden");
}

// 🔹 Saat ganti tab, simpan ke localStorage
if (menuSelect) {
    menuSelect.addEventListener("change", () => {
        let val = menuSelect.value;
        localStorage.setItem("activeFinanceTab", val);
        showSection(val);
    });
}

// 🔹 Saat halaman reload, cek tab terakhir yang dipilih
document.addEventListener("DOMContentLoaded", () => {
    if (menuSelect) {
        let lastTab = localStorage.getItem("activeFinanceTab") || "paket_harga";
        menuSelect.value = lastTab;
        showSection(lastTab);
    }
});




// Data Pelamar Super Admin
let selectKategori = document.getElementById("kategori_select");
let btnAdd = document.getElementById("btnAdd");

let kandidat_table = document.getElementById("kandidat");
let non_kandidat_table = document.getElementById("non_kandidat");
let calon_kandidat_table = document.getElementById("calon_kandidat");
let title = document.getElementById("title");

if (selectKategori) {
    selectKategori.addEventListener("change", () => {
        let val = selectKategori.value;

        // update href tombol + berdasarkan route Laravel
        btnAdd.href = "/super_admin/pelamar/tambah/" + val;

        if (val === "kandidat") {
            kandidat_table.classList.remove("hidden");
            non_kandidat_table.classList.add("hidden");
            calon_kandidat_table.classList.add("hidden");
            title.innerHTML = "Data Kandidat";
        } else if (val === "non_kandidat") {
            kandidat_table.classList.add("hidden");
            non_kandidat_table.classList.remove("hidden");
            calon_kandidat_table.classList.add("hidden");
            title.innerHTML = "Data Non Kandidat";
        } else if (val === "calon_kandidat") {
            kandidat_table.classList.add("hidden");
            non_kandidat_table.classList.add("hidden");
            calon_kandidat_table.classList.remove("hidden");
            title.innerHTML = "Data Calon Kandidat";
        }
    });
}



//NOTIF
 // Tandai dibaca
          // Tandai dibaca
  document.addEventListener('alpine:init', () => {
    Alpine.data('notifHandler', () => ({

        async hapus(id) {
            if (!confirm("Hapus notifikasi ini?")) return;

            let url = window.routes.hapusNotif.replace(':id', id);

            let res = await fetch(url, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": window.csrf,
                    "Accept": "application/json"
                }
            });

            const data = await res.json();
            if (data.success) {
                document.querySelector(`.notif-item[data-id="${id}"]`)?.remove();
            }
        },

        async hapusSemua() {
            if (!confirm("Hapus semua notifikasi?")) return;

            let res = await fetch(window.routes.hapusSemua, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": window.csrf,
                    "Accept": "application/json"
                }
            });

            const data = await res.json();
            if (data.success) {
                document.querySelectorAll('.notif-item').forEach(e => e.remove());
            }
        },

        async hapusSemuaBaca() {
            if (!confirm("Hapus semua notifikasi yang sudah dibaca?")) return;

            let res = await fetch(window.routes.hapusSemuaBaca, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": window.csrf,
                    "Accept": "application/json"
                }
            });

            const data = await res.json();
            if (data.success) {
                document.querySelectorAll('.notif-item.bg-gray-200').forEach(e => e.remove());
            }
        }

    }));
});

        /////
        document.querySelector('form[target="hiddenFrame"]')?.addEventListener('submit', () => {
            document.querySelectorAll('.notif-item').forEach(item => {
                item.classList.remove('bg-white');
                item.classList.add('bg-gray-200');
            });
            const badge = document.querySelector('.absolute .bg-red-500');
            if (badge) badge.remove();
        });