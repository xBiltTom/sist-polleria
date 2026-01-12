{{-- Component to show SweetAlert from session flash --}}
@if(session('swal'))
    <script>
        // Mostrar inmediatamente sin esperar DOMContentLoaded
        if (window.Swal) {
            setTimeout(() => {
                Swal.fire({
                    title: @json(session('swal')['title']),
                    text: @json(session('swal')['text'] ?? ''),
                    icon: @json(session('swal')['icon']),
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        confirmButton: 'inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-polleria-500 hover:bg-polleria-600 dark:bg-polleria-dark-600 dark:hover:bg-polleria-dark-500 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-polleria-500',
                        popup: 'dark:bg-polleria-dark-900 dark:border dark:border-polleria-dark-700',
                        title: 'dark:text-white',
                        htmlContainer: 'dark:text-gray-300',
                    },
                    buttonsStyling: false,
                });
            }, 100);
        } else {
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: @json(session('swal')['title']),
                    text: @json(session('swal')['text'] ?? ''),
                    icon: @json(session('swal')['icon']),
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        confirmButton: 'inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-polleria-500 hover:bg-polleria-600 dark:bg-polleria-dark-600 dark:hover:bg-polleria-dark-500 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-polleria-500',
                        popup: 'dark:bg-polleria-dark-900 dark:border dark:border-polleria-dark-700',
                        title: 'dark:text-white',
                        htmlContainer: 'dark:text-gray-300',
                    },
                    buttonsStyling: false,
                });
            });
        }
    </script>
@endif
