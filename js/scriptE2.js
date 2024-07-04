document.addEventListener('DOMContentLoaded', () => {
    const planes = document.querySelectorAll('li');
    planes.forEach(plan => {
        plan.addEventListener('click', () => {
            cargarPlan(plan.textContent);
        });
    });

    const plan = document.querySelector('li');
});

document.addEventListener('DOMContentLoaded', cargarPlan('Ejecucion'));


function cargarPlan(plan) {
    const section = document.getElementById('sectionE2');
    const url = `etapa2/${plan}.php`;
    fetch(url)
        .then(response => response.text())
        .then(html => section.innerHTML = html)
        .catch(error => console.error(error));
}