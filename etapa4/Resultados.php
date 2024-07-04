<?php
require_once '../session.php';

if (!isset($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'])) {
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Primero debes el planGeneralTrabajo.'
    );
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

/*
if (!isset($_SESSION['proyecto']['etapa0']['planComercializacion'])) {
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Primero debes completar el Plan de Comercialización.'
    );
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

*/

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
    $_SESSION['proyecto']['etapa4']['Resultados'] = $_POST;
    header('Location: ../etapa4/EvaluacionEconomica.php');
    exit;
}

// Datos y lógica para etapas y actividades (el código que proporcionaste arriba)
$etapas = json_decode(file_get_contents('../data/etapas.json'), true);
$etapas = array_keys($etapas);

$costoPGT = array();
$tiempoPGT = array();
// de acuerdo al ejemplo E0_criticaHolgura_0 recuperar si es de holgura o critica
foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    if (strpos($key, 'costo') !== false) {
        $etapa = substr($key, 0, 2);
        $costoPGT[$etapa][$key] = $value;
    }

    //aplicar para tiempo
    if (strpos($key, 'tiempo') !== false) {
        $etapa = substr($key, 0, 2);
        $tiempoPGT[$etapa][$key] = $value;
    }
}
$totalPorEtapa = array();
foreach ($costoPGT as $etapa => $costos) {
    $totalPorEtapa[$etapa] = array_sum($costos);
}

$tiempoPorEtapa = array();
foreach ($tiempoPGT as $etapa => $tiempos) {
    $tiempoPorEtapa[$etapa] = array_sum($tiempos);
}


$costoProgramas = array();
$tiempoProgramas = array();
//se obtienen de la etapa 2 en la ejecucion
foreach ($_SESSION['proyecto']['etapa2']['ejecucion'] as $key => $value) {
    if (strpos($key, 'costo') !== false) {
        $etapa = substr($key, 0, 2);
        $costoProgramas[$etapa][$key] = $value;
    }

    //aplicar para tiempo
    if (strpos($key, 'tiempo') !== false) {
        $etapa = substr($key, 0, 2);
        $tiempoProgramas[$etapa][$key] = $value;
    }
}

$totalProgramas = array();
foreach ($costoProgramas as $etapa => $costos) {
    $totalProgramas[$etapa] = array_sum($costos);
}

$tiempoTotalProgramas = array();
foreach ($tiempoProgramas as $etapa => $tiempos) {
    $tiempoTotalProgramas[$etapa] = array_sum($tiempos);
}




$headerTable = array('Etapa', 'Tiempo Planeado', 'Costo Planeado', 'Tiempos Real', 'Costos Real');




?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <header>
            <h1>Resultados</h1>
            <nav>
                <ul>
                    <li><a href="./ControlCostos.php">Control de Costos</a></li>
                    <li><a href="./Resultados.php">Resultados</a></li>
                    <li><a href="./EvaluacionEconomica.php">Evaluación Económica</a></li>
                    <li><a href="./Riesgos.php">Riesgos</a></li>
                    <li><a href="./ActaCierreProyecto.php">Acta de Cierre del Proyecto</a></li>
                </ul>
            </nav>
        </header>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="planGeneralTrabajoForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
            <h3>Resultados</h3>
            <section>
                <table>
                    <thead>
                        <tr>
                            <?php foreach ($headerTable as $header) : ?>
                                <th><?php echo $header; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $e = 0; ?>
                        <?php foreach ($etapas as $etapa) : ?>
                            <?php $et = 'E' . $e; ?>
                            <tr>
                                <td><?php echo $etapa; ?></td>
                                <td><?php echo $tiempoPorEtapa[$et]; ?></td>
                                <td><?php echo $totalPorEtapa[$et]; ?></td>
                                <td><?php echo $tiempoTotalProgramas[$et]; ?></td>
                                <td><?php echo $totalProgramas[$et]; ?></td>

                            </tr>
                            <?php $e++; ?>
                        <?php endforeach; ?>
                        <tr>
                            <td>Total</td>
                            <td><?php echo array_sum($tiempoPorEtapa); ?></td>
                            <td><?php echo array_sum($totalPorEtapa); ?></td>
                            <td><?php echo array_sum($tiempoTotalProgramas); ?></td>
                            <td><?php echo array_sum($totalProgramas); ?></td>
                        </tr>
                    </tbody>
                </table>

            </section>

            <button type="submit" class="btn">Guardar</button>
        </form>




        <?php


        ?>