<?php
require_once '../session.php';

if (!isset($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'])) {
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Primero debes el guardar el planGeneralTrabajo.'
    );
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if (!isset($_SESSION['proyecto']['etapa0']['planComercializacion'])) {
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Primero debes guardar el Plan de Comercialización.'
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

if (!isset($_SESSION['proyecto']['etapa3']['ControlySeguimiento'])) {
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Primero debes completar el Control y Seguimiento.'
    );
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if (!isset($_SESSION['proyecto']['etapa3']['ControlCambios'])) {
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Primero debes completar el Control de Cambios.'
    );
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


// Guardado de datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa4']['ControlCostos'] = $_POST;
    header('Location: ../etapa4/Resultados.php');
    exit;
}


// Datos y lógica para etapas y actividades (el código que proporcionaste arriba)
$etapas = json_decode(file_get_contents('../data/etapas.json'), true);
$etapas = array_keys($etapas);

$costoPGT = array();
// de acuerdo al ejemplo E0_criticaHolgura_0 recuperar si es de holgura o critica
foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    if (strpos($key, 'costo') !== false) {
        $costoPGT[$key] = $value;
    }
}
$totalPGT = array_sum($costoPGT);


/*
ejemplo vardump de $_SESSION['proyecto']['etapa0']['planComercializacion']
array(9) { ["poEntradaTextArea"]=> string(6) "asdasd" ["poEntradaSelect"]=> string(1) "7" ["poEntrada"]=> array(7) { [0]=> array(5) { [0]=> string(4) "ACT1" [1]=> string(4) "1000" [2]=> string(6) "asdsad" [3]=> string(2) "10" [4]=> string(6) "asdasd" } [1]=> array(5) { [0]=> string(0) "" [1]=> string(0) "" [2]=> string(0) "" [3]=> string(0) "" [4]=> string(0) "" } [2]=> array(5) { [0]=> string(0) "" [1]=> string(0) "" [2]=> string(0) "" [3]=> string(0) "" [4]=> string(0) "" } [3]=> array(5) { [0]=> string(0) "" [1]=> string(0) "" [2]=> string(0) "" [3]=> string(0) "" [4]=> string(0) "" } [4]=> array(5) { [0]=> string(0) "" [1]=> string(0) "" [2]=> string(0) "" [3]=> string(0) "" [4]=> string(0) "" } [5]=> array(5) { [0]=> string(0) "" [1]=> string(0) "" [2]=> string(0) "" [3]=> string(0) "" [4]=> string(0) "" } [6]=> array(5) { [0]=> string(0) "" [1]=> string(0) "" [2]=> string(0) "" [3]=> string(0) "" [4]=> string(0) "" } } ["poProcesoTextArea"]=> string(11) "asasdasd111" ["poProcesoSelect"]=> string(1) "2" ["poProceso"]=> array(2) { [0]=> array(5) { [0]=> string(3) "asd" [1]=> string(2) "10" [2]=> string(6) "asdasa" [3]=> string(4) "1000" [4]=> string(0) "" } [1]=> array(5) { [0]=> string(0) "" [1]=> string(0) "" [2]=> string(0) "" [3]=> string(0) "" [4]=> string(0) "" } } ["poSalidaTextArea"]=> string(0) "" ["poSalidaSelect"]=> string(1) "1" ["poSalida"]=> array(1) { [0]=> array(5) { [0]=> string(0) "" [1]=> string(0) "" [2]=> string(0) "" [3]=> string(0) "" [4]=> string(0) "" } } } 


*/

 $costoPC = array();
 //el costo es el ultimo valor del array en la posicion 3

 if (!isset($_SESSION['proyecto']['etapa0']['planComercializacion']['poEntrada'])) {
    $costoPC['poEntrada0'] = 0;
    
} else {
    foreach ($_SESSION['proyecto']['etapa0']['planComercializacion']['poEntrada'] as $key => $value) {
        $costoPC['poEntrada' . $key] = $value[3];
    }
}

if (!isset($_SESSION['proyecto']['etapa0']['planComercializacion']['poProceso'])) {
    $costoPC['poProceso0'] = 0;
    
} else {
    foreach ($_SESSION['proyecto']['etapa0']['planComercializacion']['poProceso'] as $key => $value) {
        $costoPC['poProceso' . $key] = $value[3];
    }
}

if (!isset($_SESSION['proyecto']['etapa0']['planComercializacion']['poSalida'])) {
    $costoPC['poSalida0'] = 0;
    
} else {
    foreach ($_SESSION['proyecto']['etapa0']['planComercializacion']['poSalida'] as $key => $value) {
        $costoPC['poSalida' . $key] = $value[3];
    }
}

$totalPC = array_sum($costoPC);




$costoProgramas = array();
//se obtienen de la etapa 2 en la ejecucion
foreach ($_SESSION['proyecto']['etapa2']['ejecucion'] as $key => $value) {
    if (strpos($key, 'costo') !== false) {
        $costoProgramas[$key] = $value;
    }
}

$totalProgramas = array_sum($costoProgramas);

$costoCS = array();
//se obtienen de la etapa 3 en el control y seguimiento
foreach ($_SESSION['proyecto']['etapa3']['ControlySeguimiento'] as $key => $value) {
    if (strpos($key, 'costo') !== false) {
        $costoCS[$key] = $value;
    }
}

$totalCS = array_sum($costoCS);

$costoCC = array();
//se obtienen de la etapa 3 en el control de cambios
foreach ($_SESSION['proyecto']['etapa3']['ControlCambios'] as $key => $value) {
    if (strpos($key, 'costo') !== false) {
        $costoCC[$key] = $value;
    }
}

$totalCC = array_sum($costoCC);


$headerTable1 = array('Item', 'Costo');
$items1 = array('planGeneralTrabajo', 'planComercializacion');
$headerTable2 = array('Item', 'Costo');
$items2 = array('Programas', 'ControlySeguimiento', 'ControlCambios');

// Guardado de datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa4']['ControlCostos'] = $_POST;
    header('Location: ../etapa3.php');
    exit;
}

$costosTotales1 = array();
$costosTotales1['planGeneralTrabajo'] = $totalPGT;
$costosTotales1['planComercializacion'] = $totalPC;
$costosTotales2['Programas'] = $totalProgramas;
$costosTotales2['ControlySeguimiento'] = $totalCS;
$costosTotales2['ControlCambios'] = $totalCC;



?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
    <?php include_once './header4.php'; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="planGeneralTrabajoForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
            <h3>Control de Costos</h3>
            <section>
                <h4>Costos Totales</h4>
                <table>
                    <thead>
                        <tr>
                            <?php foreach ($headerTable1 as $header) : ?>
                                <th><?php echo $header; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items1 as $item) : ?>
                            <tr>
                                <td><?php echo $item; ?></td>
                                <td><?php echo $costosTotales1[$item]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td>Total</td>
                            <td><?php echo array_sum($costosTotales1); ?></td>
                        </tr>
                    </tbody>
                </table>

                <table>
                    <thead>
                        <tr>
                            <?php foreach ($headerTable2 as $header) : ?>
                                <th><?php echo $header; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items2 as $item) : ?>
                            <tr>
                                <td><?php echo $item; ?></td>
                                <td><?php echo $costosTotales2[$item]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td>Total</td>
                            <td><?php echo array_sum($costosTotales2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>

</body>

