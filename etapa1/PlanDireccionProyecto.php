<?php
require_once '../session.php';

if (!isset($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'])) {
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Primero debes completar el Plan General de Trabajo.'
    );
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

//guardar los datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa1']['planDireccionProyecto'] = $_POST;
    $_SESSION['message'] = [
        'text' => 'El plan de dirección de proyecto se ha guardado correctamente.',
        'type' => 'success'
    ];

    header('Location: ../etapa1/GestionAlcance.php');
    exit;
}

//asignar valores de actividades en la etapa 1
$actividades = array();
$tiempo = array();
$responsable = array();

foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    if (strpos($key, 'E1_actividad_') !== false) {
        $actividades[] = $value;
    }
    if (strpos($key, 'E1_tiempo_') !== false) {
        $tiempo[] = $value;
    }
    if (strpos($key, 'E1_responsable_') !== false) {
        $responsable[] = $value;
    }
};
?>


<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <?php include_once './header1.php'; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="planDireccionProyectoForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
            <table>
                <thead>
                    <th>Actividad</th>
                    <th>Tiempo</th>
                    <th>Responsable</th>
                </thead>
                <tbody>
                    <?php foreach ($actividades as $key => $actividad) : ?>
                        <tr>
                            <td><?php echo $actividad; ?></td>
                            <td>
                                <input class="input-non-editable" readonly  type="text" name="E1_tiempo_<?php echo $key; ?>" id="E1_tiempo_<?php echo $key; ?>" value="<?php echo $tiempo[$key]; ?>">
                            </td>
                            <td>
                                <input class="input-non-editable" readonly  type="text" name="E1_responsable_<?php echo $key; ?>" id="E1_responsable_<?php echo $key; ?>" value="<?php echo $responsable[$key]; ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>

</body>