<?php
require_once '../session.php';

if (!isset($_SESSION['proyecto']['etapa3']['ChecklistVerificacionHolgura'])) {
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Primero debes completar la Checklist de Verificación: Holgura.'
    );
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

// Guardado de datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa3']['ControlySeguimiento'] = $_POST;
    header('Location: ../etapa4/ControlCostos.php');
    exit;
}

$headerTable = array('Numero', 'Actividad', 'Tiempo para hacerlo', 'Costo', 'Responsable', 'Avance (%)');

//filtrar actividades de criticas que no cumplieron
$actividadesHolgura = array_filter($_SESSION['proyecto']['etapa3']['ChecklistVerificacionHolgura'], function ($value, $key) {
    return strpos($key, 'cumple') !== false && $value !== 'cumple';
}, ARRAY_FILTER_USE_BOTH);

$actividadesPlaneadas = array();
foreach ($actividadesHolgura as $key => $value) {
    $key = str_replace('cumple', 'actividad', $key);
    $actividadesPlaneadas[$key] = $_SESSION['proyecto']['etapa1']['cronograma'][$key];

    //recuperar tiempo, costo, responsable
    $key = str_replace('actividad', 'tiempo', $key);
    $actividadesPlaneadas[$key] = $_SESSION['proyecto']['etapa2']['ejecucion'][$key];

    $key = str_replace('tiempo', 'costo', $key);
    $actividadesPlaneadas[$key] = $_SESSION['proyecto']['etapa1']['cronograma'][$key];

    $key = str_replace('costo', 'responsable', $key);
    $actividadesPlaneadas[$key] = $_SESSION['proyecto']['etapa0']['planGeneralTrabajo'][$key];
}
?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <?php include_once './header3.php'; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="planGeneralTrabajoForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
        <h2>Control y seguimiento</h2>
                <?php $n = 1; ?>
                <?php foreach ($actividadesHolgura as $key => $value) : ?>
                    <table>
                        <thead>
                            <tr>
                                <?php foreach ($headerTable as $header) : ?>
                                    <th><?php echo $header; ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><?php $key = str_replace('cumple', 'actividad', $key);?>
                                    <textarea class="input-non-editable" readonly type="text" name="<?php echo $key; ?>"><?php if (isset($_SESSION['proyecto']['etapa3']['ControlySeguimiento'])) echo $_SESSION['proyecto']['etapa3']['ControlySeguimiento'][$key]; else echo $actividadesPlaneadas[$key]; ?></textarea>
                            </td>
                                <td><?php $key = str_replace('actividad', 'tiempo', $key);?>
                                    <input class="input-editable" type="text" name="<?php echo $key; ?>" value="<?php if (isset($_SESSION['proyecto']['etapa3']['ControlySeguimiento'])) echo $_SESSION['proyecto']['etapa3']['ControlySeguimiento'][$key]; else echo $actividadesPlaneadas[$key]; ?>"></td>
                            </td>
                                <td><?php $key = str_replace('tiempo', 'costo', $key); ?>
                                    <input class="input-editable" type="text" name="<?php echo $key; ?>" value="<?php if (isset($_SESSION['proyecto']['etapa3']['ControlySeguimiento'])) echo $_SESSION['proyecto']['etapa3']['ControlySeguimiento'][$key]; else echo $actividadesPlaneadas[$key]; ?>"> </td>

                                <td><?php $key = str_replace('costo', 'responsable', $key);
                                    echo $actividadesPlaneadas[$key]; ?></td>
                                    <?php 
                                    $key = str_replace('responsable', 'avance', $key);
                                    ?>
                                <td><input class="input-editable" type="text" name="<?php echo $key; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa3']['ControlySeguimiento'][$key]) ? $_SESSION['proyecto']['etapa3']['ControlySeguimiento'][$key] : ''; ?>"></td>
                            </tr>

                        </tbody>
                    </table>
                    <br>
                    <?php $n++; ?>
                <?php endforeach; ?>


            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>

</body>