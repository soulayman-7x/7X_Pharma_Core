function showToast(message, type = 'error') {
    const toastContainer = document.createElement('div');

    toastContainer.className = 'toast-notification ' + type;
    toastContainer.innerText = message;

    document.body.appendChild(toastContainer);

    setTimeout(() => {
        toastContainer.style.opacity = '0';
        setTimeout(() => {
            toastContainer.remove();
        }, 500);
    }, 3000);
}