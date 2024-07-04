document.addEventListener('DOMContentLoaded', function () {
    //altura de textareas se realiza de forma dinámica en función del contenido

    var textareas = document.querySelectorAll('textarea');

    textareas.forEach(function (textarea) {
        textarea.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });

    //inputs se adaptan a la longitud del texto
    var inputs = document.querySelectorAll('input[type="text"]');
    inputs.forEach(function (input) {
        input.addEventListener('input', function () {
            if (this.value.length === 0) {
                this.style.width = '100px'; // Set a base width if the input is empty
            } else {
                this.style.width = (this.value.length * 5 + 100) + 'px';
            }
        });
    });

    //hacer un evento input al cargar la página
    var event = new Event('input');

    inputs.forEach(function (input) {
        input.dispatchEvent(event);
    });

    textareas.forEach(function (textarea) {
        textarea.dispatchEvent(event);
    });


    //no permitir que se cambie el tamaño de los textarea de forma manual
    textareas.forEach(function (textarea) {
        textarea.style.resize = 'none';
    });

    
});