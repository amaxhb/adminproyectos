<?php
require_once '../session.php';

if (!isset($_SESSION['proyecto']['etapa1']['cronograma'])) {
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Primero debes completar el cronograma y asignar actividades críticas y de holgura.'
    );
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if (!isset($_SESSION['proyecto']['etapa2']['ejecucion'])) {
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Primero debes guardar los Programas de Trabajo.'
    );
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Datos y lógica para etapas y actividades (el código que proporcionaste arriba)
$etapas = json_decode(file_get_contents('../data/etapas.json'), true);
$etapas = array_keys($etapas);

$actividadesPlaneadas = array();
// de acuerdo al ejemplo E0_criticaHolgura_0 recuperar si es de holgura o critica
foreach ($_SESSION['proyecto']['etapa1']['cronograma'] as $key => $value) {
    if (strpos($key, 'actividad') !== false) {
        $actividadesPlaneadas[$key] = $value;
    }
    if (strpos($key, 'criticaHolgura') !== false) {
        $actividadesPlaneadas[$key] = $value;
    }
}

$actividadesPlaneadasHol = array();
foreach ($actividadesPlaneadas as $key => $value) {
    if ($value == 'Holgura') {
        $actividadesPlaneadasHol[$key] = $value;
    }
}

$actividadesReales = array();
foreach ($_SESSION['proyecto']['etapa2']['ejecucion'] as $key => $value) {
    if (strpos($key, 'actividad') !== false) {
        $actividadesReales[$key] = $value;
    }
}
$headerTable = array('Actividad Planeada', 'Actividad Real', 'Cumple');

// Guardado de datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa3']['ChecklistVerificacionHolgura'] = $_POST;
    $_SESSION['message'] = [
        'text' => 'El Checklist de Verificación: Holgura se ha guardado correctamente.',
        'type' => 'success'
    ];

    header('Location: ../etapa3/ControlySeguimiento.php');
    exit;
}
?>
<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
    <?php include_once './header3.php'; ?>


        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="planGeneralTrabajoForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
            <h2>Checklist de Verificación de Holgura</h2>
                <table>
                    <thead>
                        <tr>
                            <?php foreach ($headerTable as $header) : ?>
                                <th><?php echo $header; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- imprimir solo las actividades de holgura -->
                        <?php foreach ($actividadesPlaneadasHol as $key => $value) : ?>
                            <?php $key = str_replace('criticaHolgura', 'actividad', $key); ?>
                            <?php $keyCumple = str_replace('actividad', 'cumple', $key); ?>
                            <tr>
                                <td> <textarea class="input-non-editable" readonly  type="text" name="<?php echo $key; ?>"><?php echo $actividadesPlaneadas[$key]; ?></textarea></td>
                                <td> <textarea class="input-non-editable" readonly  type="text" name="<?php echo $key; ?>"><?php echo $actividadesReales[$key]; ?></textarea></td>
                                <td>
                                    <select class="select-editable" name="<?php echo $keyCumple; ?>" id="<?php echo $keyCumple; ?>">
                                        <option value="cumple" <?php if(isset($_SESSION['proyecto']['etapa3']['ChecklistVerificacionHolgura'][$keyCumple]) && $_SESSION['proyecto']['etapa3']['ChecklistVerificacionHolgura'][$keyCumple] == 'cumple') echo 'selected'; ?>>Cumple</option>
                                        <?php if($actividadesPlaneadas[$key] != $actividadesReales[$key] && !isset($_SESSION['proyecto']['etapa3']['ChecklistVerificacionHolgura'][$keyCumple])): ?>
                                            <option value="noCumple" selected>No Cumple</option>
                                        <?php else: ?>
                                            <option value="noCumple" <?php if(isset($_SESSION['proyecto']['etapa3']['ChecklistVerificacionHolgura'][$keyCumple]) && $_SESSION['proyecto']['etapa3']['ChecklistVerificacionHolgura'][$keyCumple] == 'noCumple') echo 'selected'; ?>>No Cumple</option>
                                        <?php endif; ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <button type="submit" class="btn">Guardar</button>
        </form>


    </main>

</body>