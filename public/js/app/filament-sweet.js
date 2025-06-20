function isDarkMode() {
  return document.documentElement.classList.contains('dark') ||
         document.body.classList.contains('dark');
}

window.showToastSuccess = function(message) {
  Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: message,
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
    background: isDarkMode() ? '#23272F' : '#fff',
    color: isDarkMode() ? '#fff' : '#23272F'
  });
};

window.showToastError = function(message) {
  Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'error',
    title: message,
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
    background: isDarkMode() ? '#23272F' : '#fff',
    color: isDarkMode() ? '#fff' : '#23272F'
  });
};

window.showModalSuccess = function(message) {
  Swal.fire({
    icon: 'success',
    title: '¡Éxito!',
    text: message,
    confirmButtonColor: '#2563eb',
    background: isDarkMode() ? '#23272F' : '#fff',
    color: isDarkMode() ? '#fff' : '#23272F'
  });
};

window.showModalError = function(message) {
  Swal.fire({
    icon: 'error',
    title: 'Error',
    text: message,
    confirmButtonColor: '#ef4444',
    background: isDarkMode() ? '#23272F' : '#fff',
    color: isDarkMode() ? '#fff' : '#23272F'
  });
};

window.showToastInfo = function(message) {
  Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'info',
    title: message,
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
    background: isDarkMode() ? '#23272F' : '#fff',
    color: isDarkMode() ? '#fff' : '#23272F'
  });
};

window.showModalInfo = function(message) {
  Swal.fire({
    icon: 'info',
    title: 'Información',
    text: message,
    confirmButtonColor: '#2563eb',
    background: isDarkMode() ? '#23272F' : '#fff',
    color: isDarkMode() ? '#fff' : '#23272F'
  });
};

window.showModalConfirm = function (
  message,
  confirmText = 'Confirmar',
  cancelText = 'Cancelar',
  options = {}
) {
  const dark = isDarkMode();
  const defaultIcon = options.icon || 'question';
  
  // Configuración de colores para ambos modos
  const colorScheme = {
    light: {
      background: '#ffffff',
      text: '#1f2937',
      confirmButton: '#4f46e5',
      cancelButton: '#e11d48',
      popupBorder: '#e5e7eb'
    },
    dark: {
      background: '#1f2937',
      text: '#f9fafb',
      confirmButton: '#6366f1',
      cancelButton: '#f43f5e',
      popupBorder: '#374151'
    }
  };

  const colors = dark ? colorScheme.dark : colorScheme.light;

  return Swal.fire({
    icon: defaultIcon,
    title: options.title || '¿Estás seguro?',
    html: `<div class="text-center">${message}</div>`,
    showCancelButton: true,
    confirmButtonText: confirmText,
    cancelButtonText: cancelText,
    confirmButtonColor: colors.confirmButton,
    cancelButtonColor: colors.cancelButton,
    background: colors.background,
    color: colors.text,
      customClass: {
      popup: `rounded-xl shadow-2xl border ${dark ? 'border-gray-700' : 'border-gray-200'}`,
      confirmButton: `px-5 py-2.5 font-medium text-sm rounded-lg transition-all hover:scale-[1.02] ${dark ? 'hover:shadow-indigo-500/20' : 'hover:shadow-indigo-200'} hover:shadow-lg`,
      cancelButton: `px-5 py-2.5 font-medium text-sm rounded-lg transition-all hover:scale-[1.02] ${dark ? 'hover:shadow-rose-500/20' : 'hover:shadow-rose-200'} hover:shadow-lg`,
      title: 'text-xl font-semibold mb-2',
      htmlContainer: 'text-base'
    },
    showClass: {
      popup: 'animate__animated animate__fadeIn animate__faster'
    },
    hideClass: {
      popup: 'animate__animated animate__fadeOut animate__faster'
    },
    ...options
  });
};