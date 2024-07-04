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

// Guardado de datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa2']['ejecucion'] = $_POST;
    $_SESSION['message'] = [
        'text' => 'Los programas de trabajo se han guardado correctamente.',
        'type' => 'success'
    ];

    if (isset($_SESSION['proyecto']['etapa3'])){
        unset($_SESSION['proyecto']['etapa3']);

    }

    header('Location: ../etapa3/ChecklistVerificacionCritica.php');
    exit;
}

// Cargar datos de etapas y actividades
$etapas = json_decode(file_get_contents('../data/etapas.json'), true);
$etapas = array_keys($etapas);

$actividades = array();
foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    if (preg_match('/E[0-4]_actividad_/', $key)) {
        $actividades[] = $value;
    }
}

$actividadesPorEtapa = array();
$responsablePorActividad = array();
$tiempoPorActividad = array();
$costoPorActividad = array();
for ($i = 0; $i < count($etapas); $i++) {
    $actividadesPorEtapa[$etapas[$i]] = array();
    $responsablePorActividad[$etapas[$i]] = array();
    $tiempoPorActividad[$etapas[$i]] = array();
    $costoPorActividad[$etapas[$i]] = array();

    foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
        if (strpos($key, 'E' . $i . '_actividad_') !== false) {
            $actividadesPorEtapa[$etapas[$i]][] = $value;
        }

        if (strpos($key, 'E' . $i . '_responsable_') !== false) {
            $responsablePorActividad[$etapas[$i]][] = $value;
        }

        if (strpos($key, 'E' . $i . '_tiempo_') !== false) {
            $tiempoPorActividad[$etapas[$i]][] = $value;
        }

        if (strpos($key, 'E' . $i . '_costo_') !== false) {
            $costoPorActividad[$etapas[$i]][] = $value;
        }
    }
}

$actResTiempo = array();
$planGeneralTrabajo = array();
for ($i = 0; $i < count($etapas); $i++) {
    $actResTiempo[$etapas[$i]] = array();
    foreach ($actividadesPorEtapa[$etapas[$i]] as $j => $actividad) {
        $key = 'E' . $i . '_actividad_' . ($j);

        // Asignar el key al planGeneralTrabajo
        $planGeneralTrabajo[$key] = $actividad;

        // Llenar el arreglo actResTiempo con los detalles
        $actResTiempo[$etapas[$i]][$key] = array(
            'actividad' => $actividad,
            'responsable' => $responsablePorActividad[$etapas[$i]][$j],
            'tiempo' => $tiempoPorActividad[$etapas[$i]][$j],
            'costo' => $costoPorActividad[$etapas[$i]][$j]
        );
    }
}

$programasTrabajo = array(
    'Etapa 0 Inicio' => array('Idea de Negocio', 'Estudio de Mercado', 'Estudio Técnico', 'Estudio Económico', 'Estudio Financiero', 'Planes Estratégicos'),
    'Etapa 1 Planeación' => array('Constitución del Proyecto', 'Dirección del Proyecto', 'Tiempo del Proyecto', 'Alcance del Proyecto'),
    'Etapa 2 Ejecución' => array('Desarrollo e Implementación Tecnologica', 'Diseño, operación y modelado', 'Administración de insumos y adquisición de recursos', 'Finanzas y contabilidad del proyecto', 'Actividades de Contratación y Capacitación de Personal en Recursos Humanos'),
    'Etapa 3 Verificación y Control' => array('Verificación y Seguimiento de actividades', 'Control y Seguimiento de actividades'),
    'Etapa 4 Cierre' => array('Verificación de resultados del Proyecto', 'Evaluación de situación Económica y costos', 'Cierre de Proyecto')
);

$actividadesPorPrograma = array(
    'Idea de Negocio' => array('Determinar titulo del proyecto', 'Determinar objetivo del proyecto', 'Selección del lider del proyecto'),
    'Estudio de Mercado' => array('Determinar Mercado Objetivo', 'Estudio de Mercado'),
    'Estudio Técnico' => array('Determinar sector del proyecto', 'Determinar giro del proyecto', 'Estudio Técnico'),
    'Estudio Económico' => array('Determinar PIB del sector', 'Desarrollo del Benchmarking', 'Estudio Economico'),
    'Estudio Financiero' => array('Calculo de la demanda', 'Estudio Financiero'),
    'Planes Estratégicos' => array('Plan Operativo', 'Plan de Comercialización', 'Plan General de Trabajo'),
    'Constitución del Proyecto' => array('Acta Constitución del Proyecto', 'Gestión de interesados'),
    'Dirección del Proyecto' => array('Plan de Dirección del Proyecto'),
    'Tiempo del Proyecto' => array('Cronograma', 'Diagrama EDT'),
    'Alcance del Proyecto' => array('Gestión del Alcance', 'Verificación del Alcance', 'Control del Alcance'),
    'Verificación y Seguimiento de actividades' => array('Checklist de Verificación de Ruta Critica', 'Checklist de Verificación de Actividades de Holgura'),
    'Control y Seguimiento de actividades' => array('Control y Seguimiento', 'Control de Cambios'),
    'Verificación de resultados del Proyecto' => array('Resultados'),
    'Evaluación de situación Económica y costos' => array('Evaluación Económica', 'Control de Costos'),
    'Cierre de Proyecto' => array('Riesgos', 'Acta de Cierre del Proyecto')
);

// Inicialización de programas para la Etapa 2
foreach ($programasTrabajo['Etapa 2 Ejecución'] as $programa) {
    $actividadesPorPrograma[$programa] = array();
}

// Asignación de actividades por responsable en la Etapa 2
foreach ($actividadesPorEtapa['Etapa 2 Ejecución'] as $i => $actividad) {
    $responsable = $responsablePorActividad['Etapa 2 Ejecución'][$i];
    switch ($responsable) {
        case 'Tecnología':
            $actividadesPorPrograma['Desarrollo e Implementación Tecnologica'][] = $actividad;
            break;
        case 'Operativa':
            $actividadesPorPrograma['Diseño, operación y modelado'][] = $actividad;
            break;
        case 'Comercial':
            $actividadesPorPrograma['Administración de insumos y adquisición de recursos'][] = $actividad;
            break;
        case 'Recursos Humanos':
            $actividadesPorPrograma['Actividades de Contratación y Capacitación de Personal en Recursos Humanos'][] = $actividad;
            break;
        case 'Finanzas':
            $actividadesPorPrograma['Finanzas y contabilidad del proyecto'][] = $actividad;
            break;
    }
}

//planeacionDir es todos los programas de trabajo 
$programasDir = array();
foreach ($programasTrabajo as $etapa => $programas) {
    foreach ($programas as $programa) {
        $programasDir[] = $programa;
    }
}

$headerTablePrograms = array('Actividad', 'Fecha Inicio', 'Fecha Fin', 'Tiempo', 'Responsable', 'Costo');

// Asignación de keys del planGeneralTrabajo a actividadesPorPrograma
foreach ($actividadesPorPrograma as $programa => &$actividades) {
    foreach ($actividades as &$actividad) {
        $key = array_search($actividad, $planGeneralTrabajo);
        if ($key !== false) {
            $actividad = $key;
        }
    }
}


?>
<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <?php include_once './header2.php'; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="planGeneralTrabajoForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
        <h2>Programas de Trabajo</h2>
            <section id="sectionProgramas">
                <aside>
                    <nav id="verticalNav">
                        <ul>
                            <?php foreach ($programasDir as $programa) : ?>
                                <li data-program="<?php echo $programa; ?>"><?php echo $programa; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </aside>
                <article>
                    <?php $e = 0; ?>
                    <?php foreach ($etapas as $etapa) : ?>
                        <h3><?php echo $etapa; ?></h3>
                        <div id="<?php echo $etapa; ?>">
                            <?php $d = 0; ?>
                            <?php foreach ($programasTrabajo[$etapa] as $programa) : ?>
                                <div id="<?php echo $programa;?>" class="programa">
                                    <h4>Programa de Trabajo: <?php echo $programa; ?></h4>

                                    <table>
                                        <thead>
                                            <tr>
                                                <?php foreach ($headerTablePrograms as $header) : ?>
                                                    <th><?php echo $header; ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $a = 0; ?>
                                            <?php $x = 0; ?>
                                            <?php foreach ($actividadesPorPrograma[$programa] as $value) : ?>
                                                <tr>
                                                    <?php if ($e == 2) {
                                                        $name = 'E' . $e . '_actividad_' . ($a + $d);
                                                    } else {
                                                        $name = $value;
                                                    } ?>
                                                    <?php $numeroActividad = substr($name, 13); ?>
                                                    <td> <textarea class="input-editable" type="text" name="<?php echo $name; ?>"><?php echo isset($_SESSION['proyecto']['etapa2']['ejecucion']) ? $_SESSION['proyecto']['etapa2']['ejecucion'][$name] : $actResTiempo[$etapa][$name]['actividad']; ?></textarea> </td>
                                                    <td> <input class="input-editable" type="date" name="E<?php echo $e ?>_fechaInicio_<?php echo $numeroActividad; ?>" value="<?php if (isset($_SESSION['proyecto']['etapa2']['ejecucion'])) { echo $_SESSION['proyecto']['etapa2']['ejecucion']['E' . $e . '_fechaInicio_' . $numeroActividad]; } ?>"> </td> 
                                                    <td> <input class="input-editable" type="date" name="E<?php echo $e ?>_fechaFin_<?php echo $numeroActividad; ?>" value="<?php if (isset($_SESSION['proyecto']['etapa2']['ejecucion'])) { echo $_SESSION['proyecto']['etapa2']['ejecucion']['E' . $e . '_fechaFin_' . $numeroActividad]; } ?>"> </td>
                                                        <td> <input class="input-editable" type="text" name="E<?php echo $e ?>_tiempo_<?php echo $numeroActividad; ?>" value="<?php if (isset($_SESSION['proyecto']['etapa2']['ejecucion'])) { echo $_SESSION['proyecto']['etapa2']['ejecucion']['E' . $e . '_tiempo_' . $numeroActividad]; } else { echo $actResTiempo[$etapa][$name]['tiempo']; } ?>"> </td>
                                                        <td> <input class="input-non-editable" readonly type="text" name="E<?php echo $e ?>_responsable_<?php echo $numeroActividad; ?>" value="<?php if (isset($_SESSION['proyecto']['etapa2']['ejecucion'])) { echo $_SESSION['proyecto']['etapa2']['ejecucion']['E' . $e . '_responsable_' . $numeroActividad]; } else { echo $actResTiempo[$etapa][$name]['responsable']; } ?>"> </td>
                                                    <td> <input class="input-editable" type="text" name="E<?php echo $e ?>_costo_<?php echo $numeroActividad; ?>" value="<?php if (isset($_SESSION['proyecto']['etapa2']['ejecucion'])) { echo $_SESSION['proyecto']['etapa2']['ejecucion']['E' . $e . '_costo_' . $numeroActividad]; } else { echo $actResTiempo[$etapa][$name]['costo']; } ?>"> </td>
                                                </tr>
                                                    <?php $x++; ?>
                                                <?php $a = $a + 5; ?>
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </div>
                                <?php $d++; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php $e++; ?>
                    <?php endforeach; ?>
                </article>
            </section>
            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>

</body>

<script>
    // Script para cambiar de programa de trabajo, sólo se mostrará el programa seleccionado en el menú vertical
    document.addEventListener('DOMContentLoaded', () => {
        const verticalNav = document.getElementById('verticalNav');
        const programas = document.querySelectorAll('.programa');

        verticalNav.addEventListener('click', (e) => {
            if (e.target.tagName === 'LI') {
                const programa = e.target.dataset.program;
                programas.forEach((programa) => {
                    programa.style.display = 'none';
                });
                document.getElementById(programa).style.display = 'block';
            }
        });

        //cargar el primer programa de trabajo al cargar la página y ocultar los demás
        const primerPrograma = document.querySelector('.programa');
        primerPrograma.style.display = 'block';
        programas.forEach((programa) => {
            if (programa !== primerPrograma) {
                programa.style.display = 'none';
            }
        });
    });

</script>
