import './bootstrap';
import Swal from 'sweetalert2';

window.Swal = Swal;

// Configuración por defecto de SweetAlert2
const swalConfig = {
    customClass: {
        confirmButton: 'inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-polleria-500 hover:bg-polleria-600 dark:bg-polleria-dark-600 dark:hover:bg-polleria-dark-500 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-polleria-500',
        cancelButton: 'inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:text-gray-300 dark:bg-polleria-dark-800 dark:border-polleria-dark-600 dark:hover:bg-polleria-dark-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500',
        actions: 'gap-3',
        popup: 'dark:bg-polleria-dark-900 dark:border dark:border-polleria-dark-700',
        title: 'dark:text-white',
        htmlContainer: 'dark:text-gray-300',
    },
    buttonsStyling: false,
    timer: null,
    timerProgressBar: false,
};

// Event listener para alertas de confirmación
document.addEventListener('livewire:initialized', () => {
    // Alerta de confirmación
    Livewire.on('swal:confirm', (data) => {
        const config = data[0];
        Swal.fire({
            title: config.title,
            text: config.text,
            icon: config.icon,
            showCancelButton: true,
            confirmButtonText: config.confirmButtonText,
            cancelButtonText: config.cancelButtonText,
            reverseButtons: true,
            ...swalConfig
        }).then((result) => {
            if (result.isConfirmed) {
                // Llamar al método del componente con los parámetros
                if (config.params && Object.keys(config.params).length > 0) {
                    Livewire.dispatch(config.method, config.params);
                } else {
                    Livewire.dispatch(config.method);
                }
            }
        });
    });

    // Alerta de éxito
    Livewire.on('swal:success', (data) => {
        const config = data[0];
        Swal.fire({
            title: config.title,
            text: config.text,
            icon: 'success',
            confirmButtonText: 'Aceptar',
            ...swalConfig
        });
    });

    // Alerta de error
    Livewire.on('swal:error', (data) => {
        const config = data[0];
        Swal.fire({
            title: config.title,
            text: config.text,
            icon: 'error',
            confirmButtonText: 'Aceptar',
            ...swalConfig
        });
    });

    // Alerta de información
    Livewire.on('swal:info', (data) => {
        const config = data[0];
        Swal.fire({
            title: config.title,
            text: config.text,
            icon: 'info',
            confirmButtonText: 'Aceptar',
            ...swalConfig
        });
    });

    // Alerta de advertencia
    Livewire.on('swal:warning', (data) => {
        const config = data[0];
        Swal.fire({
            title: config.title,
            text: config.text,
            icon: 'warning',
            confirmButtonText: 'Aceptar',
            ...swalConfig
        });
    });
});
