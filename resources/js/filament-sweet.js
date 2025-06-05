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

window.showModalConfirm = function(message, confirmText = 'Sí', cancelText = 'No') {
  return Swal.fire({
    icon: 'question',
    title: '¿Estás seguro?',
    html: message,
    showCancelButton: true,
    confirmButtonText: confirmText,
    cancelButtonText: cancelText,
    confirmButtonColor: '#6366f1',
    cancelButtonColor: '#f472b6',
    background: isDarkMode() ? '#23272F' : '#fff',
    color: isDarkMode() ? '#fff' : '#23272F'
  });
};
