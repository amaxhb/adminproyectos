<?php
require_once '../session.php';

//guardar datos enviados por el usuario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa0']['planComercializacion'] = $_POST;
    $_SESSION['message'] = [
        'text' => 'El plan de comercialización se ha guardado correctamente',
        'type' => 'success'
    ];
    header('Location: ../etapa0/planGeneralTrabajo.php');
    exit;
}

//ejemplo de datos guardados en la sesión
//array(6) { ["poEntradaTextArea"]=> string(6) "asdasd" ["poEntradaSelect"]=> string(0) "" ["poProcesoTextArea"]=> string(24) " asasdasd" ["poProcesoSelect"]=> string(0) "" ["poSalidaTextArea"]=> string(16) " " ["poSalidaSelect"]=> string(0) "" } 

$headerTable = array('Actividad', 'Tiempo', 'Recurso', 'Costo', 'Responsable');

$articles = [
    [
        'title' => 'Entrada',
        'textareaId' => 'poEntrada',
    ],
    [
        'title' => 'Proceso',
        'textareaId' => 'poProceso',
    ],
    [
        'title' => 'Salida',
        'textareaId' => 'poSalida',
    ],
];

$selectRange = range(1, 10);
?>

<?php include_once '../layouts/head.php'; ?>
<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <?php include_once './header0.php'; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="planComercializacionForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
        <h2>Plan de Comercialización</h2>
            <?php foreach ($articles as $article) : ?>
                <article>
                    <h3><label for="<?php echo $article['textareaId']; ?>"><?php echo $article['title']; ?></label></h3>
                    <textarea name="<?php echo $article['textareaId']; ?>TextArea" id="<?php echo $article['textareaId']; ?>TextArea" placeholder="Redacción..."><?php echo isset($_SESSION['proyecto']['etapa0']['planComercializacion']) ? $_SESSION['proyecto']['etapa0']['planComercializacion'][$article['textareaId'] . 'TextArea'] : ''; ?></textarea>
                    <select class="select-editable" name="<?php echo $article['textareaId']; ?>Select" id="<?php echo $article['textareaId']; ?>Select">
                        <option value="">Selecciona una opción</option>
                        <?php foreach ($selectRange as $range) : ?>
                            <option value="<?php echo $range; ?>" <?php echo isset($_SESSION['proyecto']['etapa0']['planComercializacion']) && $_SESSION['proyecto']['etapa0']['planComercializacion'][$article['textareaId'] . 'Select'] == $range ? 'selected' : ''; ?>><?php echo $range; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <table id="<?php echo $article['textareaId']; ?>Table">
                        <thead>
                            <?php foreach ($headerTable as $header) : ?>
                                <th><?php echo $header; ?></th>
                            <?php endforeach; ?>
                        </thead>
                        <tbody>
                            <?php if (isset($_SESSION['proyecto']['etapa0']['planComercializacion'])) : ?>
                                <?php for ($i = 0; $i < $_SESSION['proyecto']['etapa0']['planComercializacion'][$article['textareaId'] . 'Select']; $i++) : ?>
                                    <tr>
                                        <?php for ($j = 0; $j < 5; $j++) : ?>
                                            <td>
                                                <input type="text" class="input-editable" name="<?php echo $article['textareaId']; ?>[<?php echo $i; ?>][<?php echo $j; ?>]" id="<?php echo $article['textareaId']; ?>[<?php echo $i; ?>][<?php echo $j; ?>]" value="<?php echo $_SESSION['proyecto']['etapa0']['planComercializacion'][$article['textareaId']][$i][$j]; ?>">
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </article>
            <?php endforeach; ?>
            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>
</body>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        //añadir numeros a todos los select. Buscar select por id y añadirle los numeros
        const selects = document.querySelectorAll('select');

        //añadir filas a las tablas de acuerdo al numero seleccionado en el select, considerando el id del select y la tabla
        selects.forEach(select => {
            select.addEventListener('change', (e) => {
                const tableId = e.target.id.replace('Select', 'Table');
                const table = document.getElementById(tableId);
                const tbody = table.querySelector('tbody');
                tbody.innerHTML = '';
                for (let i = 0; i < e.target.value; i++) {
                    const tr = document.createElement('tr');
                    for (let j = 0; j < 5; j++) {
                        const td = document.createElement('td');
                        const input = document.createElement('input');
                        input.type = 'text';
                        input.classList.add('input-editable');
                        input.name = `${tableId.replace('Table', '')}[${i}][${j}]`;
                        input.id = `${tableId.replace('Table', '')}[${i}][${j}]`;
                        td.appendChild(input);
                        tr.appendChild(td);
                    }
                    tbody.appendChild(tr);
                }
            });
        });
    });
        
</script>