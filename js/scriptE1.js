document.addEventListener('DOMContentLoaded', () => {
    const planes = document.querySelectorAll('li');
    planes.forEach(plan => {
        plan.addEventListener('click', () => {
            cargarPlan(plan.textContent);
        });
    });

    const plan = document.querySelector('li');
});

document.addEventListener('DOMContentLoaded', cargarPlan('ActaConstitucion'));


function cargarPlan(plan) {
    const section = document.getElementById('sectionE1');
    const url = `etapa1/${plan}.php`;
    fetch(url)
        .then(response => response.text())
        .then(html => section.innerHTML = html)
        .catch(error => console.error(error));
}