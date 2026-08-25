document.addEventListener('DOMContentLoaded', function () {
    var editorEl = document.getElementById('editor');

    if (!editorEl) {
        return;
    }

    var quill = new Quill('#editor', { theme: 'snow' });

    document.getElementById('form-producto').addEventListener('submit', function () {
        document.getElementById('descripcion_html').value = quill.root.innerHTML;
    });
});
