// Konfirmasi sebelum menghapus: form dengan atribut data-confirm
document.addEventListener('submit', function (event) {
    const form = event.target;
    const pesan = form.getAttribute('data-confirm');

    if (pesan && !confirm(pesan)) {
        event.preventDefault();
    }
});
