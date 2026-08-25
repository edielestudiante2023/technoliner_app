document.addEventListener('DOMContentLoaded', function () {
    var editorEl = document.getElementById('editor');

    if (!editorEl) {
        return;
    }

    var quill = new Quill('#editor', { theme: 'snow' });

    document.getElementById('form-articulo').addEventListener('submit', function () {
        document.getElementById('contenido_html').value = quill.root.innerHTML;
    });
});
