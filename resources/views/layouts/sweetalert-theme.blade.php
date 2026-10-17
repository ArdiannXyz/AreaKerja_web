{{-- ============================================================
    SWEETALERT THEME — AreaKerja
    Styling compact modal & warna tombol konfirmasi biru (#00509d)
    ============================================================ --}}
<style>
    /* SweetAlert2 Global Compact & AreaKerja Blue Theme */
    .swal2-container {
        z-index: 999999 !important;
    }

    .swal2-container .swal2-popup:not(.swal2-toast),
    div:where(.swal2-container) div:where(.swal2-popup):not(.swal2-toast) {
        width: 22.5rem !important; /* ~360px: ukuran compact tidak terlalu besar */
        max-width: calc(100vw - 2rem) !important;
        padding: 1.5rem 1.25rem 1.35rem !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        font-family: 'Poppins', sans-serif !important;
    }

    /* Icon Swal: Gunakan transform scale agar struktur internal checkmark/silang tetap presisi dan tidak bergeser */
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-icon,
    div:where(.swal2-container) .swal2-icon {
        transform: scale(0.72) !important;
        transform-origin: center center !important;
        margin: 0.5rem auto -0.25rem !important;
    }

    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-title,
    div:where(.swal2-container) .swal2-title {
        font-size: 1.125rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        padding: 0 !important;
        margin: 0 0 0.5rem 0 !important;
        line-height: 1.4 !important;
        text-align: center !important;
    }

    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-html-container,
    div:where(.swal2-container) .swal2-html-container {
        font-size: 0.8125rem !important;
        color: #64748b !important;
        margin: 0 0 0.85rem 0 !important;
        padding: 0 0.25rem !important;
        line-height: 1.5 !important;
        text-align: center !important;
    }

    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-actions,
    div:where(.swal2-container) .swal2-actions {
        margin: 0.75rem auto 0 !important;
        gap: 0.5rem !important;
        width: 100% !important;
        justify-content: center !important;
    }

    /* Tombol OK / Konfirmasi: Default Biru AreaKerja #00509d */
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm,
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm {
        background-color: #00509d !important;
        color: #ffffff !important;
        font-size: 0.8125rem !important;
        font-weight: 600 !important;
        border-radius: 0.625rem !important;
        padding: 0.55rem 1.35rem !important;
        margin: 0 !important;
        box-shadow: 0 2px 6px rgba(0, 80, 157, 0.25) !important;
        transition: all 0.18s ease-in-out !important;
        outline: none !important;
    }

    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm:hover,
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm:hover {
        background-color: #003d7a !important;
        box-shadow: 0 4px 10px rgba(0, 80, 157, 0.35) !important;
        transform: translateY(-1px) !important;
    }

    /* Tombol Batal / Cancel */
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-cancel,
    div:where(.swal2-container) button:where(.swal2-styled).swal2-cancel {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        font-size: 0.8125rem !important;
        font-weight: 600 !important;
        border-radius: 0.625rem !important;
        padding: 0.55rem 1.35rem !important;
        margin: 0 !important;
        transition: all 0.18s ease-in-out !important;
        outline: none !important;
    }

    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-cancel:hover,
    div:where(.swal2-container) button:where(.swal2-styled).swal2-cancel:hover {
        background-color: #e2e8f0 !important;
        color: #1e293b !important;
    }

    /* Pengecualian jika tombol konfirmasi aksi bahaya (Hapus / Logout / Reset) */
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="rgb(239, 68, 68)"],
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="#ef4444"],
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="rgb(220, 38, 38)"],
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="#dc2626"],
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="rgb(225, 29, 72)"],
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="#e11d48"],
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="rgb(239, 68, 68)"],
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="#ef4444"],
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="rgb(220, 38, 38)"],
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="#dc2626"],
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="rgb(225, 29, 72)"],
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="#e11d48"] {
        background-color: #ef4444 !important;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.25) !important;
    }

    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="rgb(239, 68, 68)"]:hover,
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="#ef4444"]:hover,
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="rgb(220, 38, 38)"]:hover,
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="#dc2626"]:hover,
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="rgb(225, 29, 72)"]:hover,
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="#e11d48"]:hover,
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="rgb(239, 68, 68)"]:hover,
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="#ef4444"]:hover,
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="rgb(220, 38, 38)"]:hover,
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="#dc2626"]:hover,
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="rgb(225, 29, 72)"]:hover,
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="#e11d48"]:hover {
        background-color: #dc2626 !important;
    }

    /* Pengecualian jika tombol konfirmasi bertipe hijau / topup */
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="rgb(16, 185, 129)"],
    .swal2-container .swal2-popup:not(.swal2-toast) .swal2-styled.swal2-confirm[style*="#10b981"],
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="rgb(16, 185, 129)"],
    div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm[style*="#10b981"] {
        background-color: #10b981 !important;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25) !important;
    }
</style>

<script>
    (function () {
        function setupSweetAlertDefaults() {
            if (typeof Swal !== 'undefined' && !Swal._akThemed) {
                Swal._akThemed = true;
                const origFire = Swal.fire.bind(Swal);
                Swal.fire = function (...args) {
                    if (args.length === 1 && typeof args[0] === 'object' && args[0] !== null) {
                        const opts = args[0];
                        // Jadikan #00509d sebagai warna default tombol OK jika belum diset atau jika sebelumnya warna orange
                        if (!opts.confirmButtonColor ||
                            opts.confirmButtonColor === '#ff7a00' ||
                            opts.confirmButtonColor === '#f97316' ||
                            opts.confirmButtonColor === '#0054a6') {
                            opts.confirmButtonColor = '#00509d';
                        }
                    }
                    return origFire(...args);
                };
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupSweetAlertDefaults);
        } else {
            setupSweetAlertDefaults();
        }
        setupSweetAlertDefaults();
    })();
</script>

