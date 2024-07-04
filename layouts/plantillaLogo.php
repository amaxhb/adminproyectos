<?php 

$costoPGT = array();
// de acuerdo al ejemplo E0_criticaHolgura_0 recuperar si es de holgura o critica
foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    if (strpos($key, 'costo') !== false) {
        $costoPGT[$key] = $value;
    }
}
$totalPGT = array_sum($costoPGT);



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

$costoProyecto = $totalPGT + $totalPC;


if (isset($_SESSION['proyecto']['idea'])) {
    $presupuestoNegocio = $_SESSION['proyecto']['idea']['presupuestoNegocio'];
    
} else {
    $presupuestoNegocio = 0;
}

$presupuestoPatrocinador = number_format($presupuestoNegocio - $costoProyecto, 2);
if ($presupuestoPatrocinador < 0) {
    $presupuestoPatrocinador = 0;
}

$presupuestoNegocio = number_format($presupuestoNegocio, 2);
$costoProyecto = number_format($costoProyecto, 2);

?>


<table id="plantillaLogo">
    <tr>
        <td rowspan="6">
            <?php $username = $_SESSION['username']; ?>

            <img src="../data/usersImages/<?php echo $username; ?>.png" alt="Logo">
        </td>
        <td>
            Nombre del Proyecto
        </td>
        <td>
        <?php echo isset($_SESSION['proyecto']['idea']['tituloIdea']) ? $_SESSION['proyecto']['idea']['tituloIdea'] : ''; ?>
        </td>
    </tr>
    <tr>
        <td>
            Nombre de Plantilla:
        </td>
        <td>
            <?php
            $url = $_SERVER['REQUEST_URI'];
            $url = explode('/', $url);
            $url = end($url);
            $url = explode('.', $url);
            $plantillas = [
                'planComercializacion' => 'Plan de Comercialización',
                'planGeneralTrabajo' => 'Plan General de Trabajo',
                'planOperativo' => 'Plan Operativo',
                'ActaConstitucion' => 'Acta Constitución',
                'ControlAlcance' => 'Control de Alcance',
                'Cronograma' => 'Cronograma',
                'GestionAlcance' => 'Gestión de Alcance',
                'GestionInteresados' => 'Gestión de Interesados',
                'PlanDireccionProyecto' => 'Plan de Dirección de Proyecto',
                'DiagramaEDT'=>'Diagrama EDT',
                'Requisitos' => 'Requisitos',
                'ChecklistVerificacionCritica' => 'Checklist de Verificación: Ruta Crítica',
                'ChecklistVerificacionHolgura' => 'Checklist de Verificación: Holgura',
                'ControlCambios' => 'Control de Cambios',
                'ControlySeguimiento' => 'Control y Seguimiento',
                'ActaCierreProyecto' => 'Acta de Cierre de Proyecto',
                'ControlCostos' => 'Control de Costos',
                'Riesgos' => 'Gestión de Riesgos',
                'EvaluacionEconomica' => 'Evaluación Económica',
                'Resultados' => 'Resultados'
            ];
            foreach ($plantillas as $key => $value) {
                if ($key == $url[0]) {
                    echo $value;
                }
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>
            Etapa:
        </td>
        <?php //ejemplo de etapa para https://localhost/adminproyectos/etapa1/ActaConstitucion.php sería la etapa se consigue del url?>
        <td>
            <?php
            //la etapa se obtiene del url y se convierte a número
            $etapasCsC = [
                'Etapa 0 Inicio',
                'Etapa 1 Planeación',
                'Etapa 2 Ejecución',
                'Etapa 3 Verificación y Control',
                'Etapa 4 Cierre'
            ];

            $etapasC = $urlEtapas = explode('/', $_SERVER['REQUEST_URI']);
            $etapasC = $etapasC[2];
            $etapasC = explode('.', $etapasC);
            $etapasC = $etapasC[0];
            $etapasC = explode('etapa', $etapasC);
            $etapasC = $etapasC[1];
            $etapasC = (int)$etapasC;
            echo $etapasCsC[$etapasC];

            

            
            ?>
        </td>
    </tr>
    <tr>
        <td>
            Presupuesto del Proyecto:
        </td>
        <td>
            <?php if (isset($_SESSION['proyecto']['idea'])) {
                echo '$' . $presupuestoNegocio;
            } else {
                echo '$0.00';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>Presupuesto del patrocinador</td>
        <td>
            <?php if (isset($_SESSION['proyecto']['idea']) && isset($_SESSION['proyecto']['etapa0']['planComercializacion']) && isset($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'])) {
                echo '$' . $presupuestoPatrocinador;
            } else {
                echo '$0.00';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>Fecha de Inicio</td>
        <td>
            <input type="date" name="fechaInicio" id="fechaInicio" >
        </td>
    </tr>
</table>
