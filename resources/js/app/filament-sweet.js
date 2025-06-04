
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
