function cargarIdea() {
    fetch('idea/ideaNegocio.php')
        .then(response => response.text())
        .then(html => {
            document.getElementById('ideaNegocio').innerHTML = html;
        });
}

//cargar al cargar la pagina
document.addEventListener('DOMContentLoaded', cargarIdea);

