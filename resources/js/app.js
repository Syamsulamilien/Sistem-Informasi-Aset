import './bootstrap';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Alpine = Alpine;
window.Swal = Swal;

// Global SIASET Swal helper configuration (Clean, lightweight, elegant)
window.showAlert = function ({ title = '', text = '', icon = 'info', timer = null, confirmButtonText = 'OK' }) {
    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
        timer: timer,
        timerProgressBar: timer ? true : false,
        confirmButtonText: confirmButtonText,
        confirmButtonColor: '#2563eb', // blue-600
        background: '#ffffff',
        customClass: {
            popup: 'rounded-3xl shadow-2xl border border-gray-100 font-sans',
            title: 'text-xl font-bold text-gray-900',
            htmlContainer: 'text-sm text-gray-600',
            confirmButton: 'rounded-xl font-bold px-6 py-2.5 shadow-md shadow-blue-500/20 text-sm'
        }
    });
};

// Global SIASET Swal Toast helper
window.showToast = function ({ message = '', icon = 'success', timer = 3000 }) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: timer,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        },
        customClass: {
            popup: 'rounded-2xl shadow-xl border border-gray-100 font-sans text-sm font-semibold'
        }
    });

    return Toast.fire({
        icon: icon,
        title: message
    });
};

// Global SIASET Swal Delete Confirmation helper
window.confirmDelete = function ({
    title = 'Konfirmasi Hapus',
    text = 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
    confirmButtonText = 'Ya, Hapus',
    cancelButtonText = 'Batal',
    onConfirmed = null
}) {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        confirmButtonColor: '#e11d48', // rose-600
        cancelButtonColor: '#94a3b8',  // slate-400
        reverseButtons: true,
        background: '#ffffff',
        customClass: {
            popup: 'rounded-3xl shadow-2xl border border-gray-100 font-sans p-6',
            title: 'text-lg font-black text-gray-900',
            htmlContainer: 'text-sm text-gray-600 mt-1',
            confirmButton: 'rounded-xl font-bold px-5 py-2.5 shadow-md shadow-rose-500/20 text-sm',
            cancelButton: 'rounded-xl font-semibold px-5 py-2.5 text-sm'
        }
    }).then((result) => {
        if (result.isConfirmed && typeof onConfirmed === 'function') {
            onConfirmed();
        }
        return result;
    });
};

// Global Table Sorting with Header Arrows (▲▼)
import { initAllSortableTables, initTable } from './table-sort';
window.initAllSortableTables = initAllSortableTables;
window.initTableSorting = initTable;

Alpine.start();

