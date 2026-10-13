//Kandidat
const btn_kandidat = document.getElementById("btn_kandidat");
const btn_non_kandidat = document.getElementById("btn_non_kandidat");
const btn_calon_kandidat = document.getElementById("btn_calon_kandidat");

const table_kandidat = document.getElementById("table_kandidat");
const table_non_kandidat = document.getElementById("table_non_kandidat");
const table_calon_kandidat = document.getElementById("table_calon_kandidat");

if (btn_kandidat && table_kandidat) {
    btn_kandidat.addEventListener("click", () => {
        table_kandidat.classList.remove("hidden");
        btn_kandidat.classList.add("bg-gray-700", "text-white");

        table_non_kandidat.classList.add("hidden");
        btn_non_kandidat.classList.remove("bg-gray-700", "text-white");

        table_calon_kandidat.classList.add("hidden");
        btn_calon_kandidat.classList.remove("bg-gray-700", "text-white");
    });
}

if (btn_non_kandidat && table_non_kandidat) {
    btn_non_kandidat.addEventListener("click", () => {
        table_kandidat.classList.add("hidden");
        btn_kandidat.classList.remove("bg-gray-700", "text-white");

        table_non_kandidat.classList.remove("hidden");
        btn_non_kandidat.classList.add("bg-gray-700", "text-white");

        table_calon_kandidat.classList.add("hidden");
        btn_calon_kandidat.classList.remove("bg-gray-700", "text-white");
    });
}

if (btn_calon_kandidat && table_calon_kandidat) {
    btn_calon_kandidat.addEventListener("click", () => {
        table_kandidat.classList.add("hidden");
        btn_kandidat.classList.remove("bg-gray-700", "text-white");

        table_non_kandidat.classList.add("hidden");
        btn_non_kandidat.classList.remove("bg-gray-700", "text-white");

        table_calon_kandidat.classList.remove("hidden");
        btn_calon_kandidat.classList.add("bg-gray-700", "text-white");
    });
}

//Perushaan
const btn_perusahaan = document.getElementById("btn_perusahaan");
const btn_recruitment = document.getElementById("btn_recruitment");
const btn_talent_hunter = document.getElementById("btn_talent_hunter");

const table_perusahaan = document.getElementById("table_perusahaan");
const table_recruitment = document.getElementById("table_recruitment");
const table_talent_hunter = document.getElementById("table_talent_hunter");

if (btn_perusahaan && table_perusahaan) {
    btn_perusahaan.addEventListener("click", () => {
        table_perusahaan.classList.remove("hidden");
        btn_perusahaan.classList.add("bg-gray-700", "text-white");

        table_recruitment.classList.add("hidden");
        btn_recruitment.classList.remove("bg-gray-700", "text-white");

        table_talent_hunter.classList.add("hidden");
        btn_talent_hunter.classList.remove("bg-gray-700", "text-white");
    });
}

if (btn_recruitment && table_recruitment) {
    btn_recruitment.addEventListener("click", () => {
        table_perusahaan.classList.add("hidden");
        btn_perusahaan.classList.remove("bg-gray-700", "text-white");

        table_recruitment.classList.remove("hidden");
        btn_recruitment.classList.add("bg-gray-700", "text-white");

        table_talent_hunter.classList.add("hidden");
        btn_talent_hunter.classList.remove("bg-gray-700", "text-white");
    });
}

if (btn_talent_hunter && table_talent_hunter) {
    btn_talent_hunter.addEventListener("click", () => {
        table_perusahaan.classList.add("hidden");
        btn_perusahaan.classList.remove("bg-gray-700", "text-white");

        table_recruitment.classList.add("hidden");
        btn_recruitment.classList.remove("bg-gray-700", "text-white");

        table_talent_hunter.classList.remove("hidden");
        btn_talent_hunter.classList.add("bg-gray-700", "text-white");
    });
}

//finance-Admin
const btn_koin = document.getElementById("btn_koin");
const btn_tunai = document.getElementById("btn_tunai");

const table_koin = document.getElementById("table_koin");
const table_tunai = document.getElementById("table_tunai");

if (btn_koin && table_koin) {
    btn_koin.addEventListener("click", () => {
        table_koin.classList.remove("hidden");
        btn_koin.classList.add("bg-gray-700", "text-white");

        table_tunai.classList.add("hidden");
        btn_tunai.classList.remove("bg-gray-700", "text-white");
    });
}

if (btn_tunai && table_tunai) {
    btn_tunai.addEventListener("click", () => {
        table_koin.classList.add("hidden");
        btn_koin.classList.remove("bg-gray-700", "text-white");

        table_tunai.classList.remove("hidden");
        btn_tunai.classList.add("bg-gray-700", "text-white");    
    });
}


// NOTIF
document.addEventListener('alpine:init', () => {
    Alpine.data('notifHandler', () => ({

        // ====== MARK AS READ ======
        async markAsRead(url, element) {
            try {
                let res = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": window.csrf,
                        "Accept": "application/json"
                    }
                });

                const data = await res.json();

                if (data.success) {

                    // Ubah warna notif jadi dibaca
                    element.classList.remove('bg-white');
                    element.classList.add('bg-gray-200');

                    // Kurangi jumlah badge merah
                    const badge = document.querySelector('#notif-badge');
                    if (badge) {
                        let count = parseInt(badge.textContent) - 1;

                        if (count <= 0) {
                            badge.remove(); // hapus badge kalau tidak ada unread
                        } else {
                            badge.textContent = count; // update angkanya
                        }
                    }
                }

            } catch (e) {
                console.error("Gagal mark as read:", e);
            }
        },

        // ====== HAPUS SATU ======
        async hapus(id) {
            if (typeof swalConfirm === 'function') {
                if (!await swalConfirm("Hapus notifikasi ini?")) return;
            } else if (!confirm("Hapus notifikasi ini?")) return;

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

        // ====== HAPUS SEMUA ======
        async hapusSemua() {
            if (typeof swalConfirm === 'function') {
                if (!await swalConfirm("Hapus semua notifikasi?")) return;
            } else if (!confirm("Hapus semua notifikasi?")) return;

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
                const badge = document.querySelector('#notif-badge');
                if (badge) badge.remove();
            }
        },

        // ====== HAPUS SEMUA YANG SUDAH DIBACA ======
        async hapusSemuaBaca() {
            if (typeof swalConfirm === 'function') {
                if (!await swalConfirm("Hapus semua notifikasi yang sudah dibaca?")) return;
            } else if (!confirm("Hapus semua notifikasi yang sudah dibaca?")) return;

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


// ====== FORM "TANDAI SEMUA BACA" (iframe) ======
document.querySelector('form[target="hiddenFrame"]').addEventListener('submit', () => {

    // semua item menjadi dibaca
    document.querySelectorAll('.notif-item').forEach(item => {
        item.classList.remove('bg-white');
        item.classList.add('bg-gray-200');
    });

    // hapus badge merah
    const badge = document.querySelector('#notif-badge');
    if (badge) badge.remove();
});
