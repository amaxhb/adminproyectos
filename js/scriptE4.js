document.addEventListener('DOMContentLoaded', () => {
    const planes = document.querySelectorAll('li');
    planes.forEach(plan => {
        plan.addEventListener('click', () => {
            cargarPlan(plan.textContent);
        });
    });

    const plan = document.querySelector('li');
});

document.addEventListener('DOMContentLoaded', cargarPlan('ControlCostos'));


function cargarPlan(plan) {
    const section = document.getElementById('sectionE4');
    const url = `etapa4/${plan}.php`;
    fetch(url)
        .then(response => response.text())
        .then(html => section.innerHTML = html)
        .catch(error => console.error(error));
}