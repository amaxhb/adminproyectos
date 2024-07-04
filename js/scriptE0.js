document.addEventListener('DOMContentLoaded', () => {
    const planes = document.querySelectorAll('li');
    planes.forEach(plan => {
        plan.addEventListener('click', () => {
            cargarPlan(plan.textContent);
        });
    });

    const plan = document.querySelector('li');
});

document.addEventListener('DOMContentLoaded', cargarPlan('PlanOperativo'));


function cargarPlan(plan) {
    const section = document.getElementById('sectionE0');
    const url = `etapa0/${plan}.php`;
    fetch(url)
        .then(response => response.text())
        .then(html => section.innerHTML = html)
        .catch(error => console.error(error));
}