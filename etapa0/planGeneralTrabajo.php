<?php

require_once '../session.php';

// Guardado de datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] = $_POST;
    //if isset session cronograma y no esta vacio entonces guardar actividades, tiempo y responsables en la sesion
    if (isset($_SESSION['proyecto']['etapa1']['cronograma']) || isset($_SESSION['proyecto']['etapa1']['gestionAlcance'])) {
        //destroy session cronograma
        unset($_SESSION['proyecto']['etapa1']['cronograma']);
        unset($_SESSION['proyecto']['etapa1']['gestionAlcance']);
        $_SESSION['message'] = [
            'text' => 'El cronograma y la gesiton del alcance fueron vaciado, por favor vuelva a llenarlos',
            'type' => 'alert'
        ];

        header('Location: ../etapa1/ActaConstitucion.php');
        exit;
    } else {
        $_SESSION['message'] = [
            'text' => 'El plan general de trabajo fue guardado exitosamente',
            'type' => 'success'
        ];
        
        header('Location: ../etapa1/ActaConstitucion.php');
        exit;
    }
};


if (!isset($_SESSION['proyecto']['etapa0']['planOperativo'])) {
    $_SESSION['message'] = [
        'text' => 'Primero debes guardar el Plan Operativo',
        'type' => 'error'
    ];
    header('Location: ./planOperativo.php');
    exit;
}

$etapas = json_decode(file_get_contents('../data/etapas.json'), true);

// Las actividades de la etapa 2 se obtienen de la sesión y son las mismas que el plan operativo
if (isset($_SESSION['proyecto']['etapa0']['planOperativo'])) {
    $etapas['Etapa 2 Ejecución'] = array_filter($_SESSION['proyecto']['etapa0']['planOperativo'], function ($key) {
        return strpos($key, 'E2_actividad_') === 0;
    }, ARRAY_FILTER_USE_KEY);
}

$tiempo = array_filter($_SESSION['proyecto']['etapa0']['planOperativo'], function ($key) {
    return strpos($key, 'E2_tiempo_') === 0;
}, ARRAY_FILTER_USE_KEY);

const EQUIPOS = ['Tecnología', 'Operativa', 'Recursos Humanos', 'Finanzas', 'Comercial'];
const COLUMNAS = ['Etapas', 'Actividades', 'Tiempo (en días)', 'Recurso', 'Costo', 'Responsable', 'Entregable'];



// Función para obtener el responsable de una actividad según el índice de la actividad
function getResponsable($actividadIndex)
{
    switch ($actividadIndex % 5) {
        case 0:
            return 'Tecnología';
        case 1:
            return 'Operativa';
        case 2:
            return 'Recursos Humanos';
        case 3:
            return 'Finanzas';
        case 4:
            return 'Comercial';
    }
}

?>


<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
    <?php include_once './header0.php'; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="planGeneralTrabajoForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
            <h2>Plan General de Trabajo</h2>
            <table>
                <thead>
                    <tr>
                        <?php foreach (COLUMNAS as $columna) { ?>
                            <th><?php echo $columna; ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- ir asignando ids unicos con {Etapa} (como E0, E1, Etc)_ columna (Tiempo, Recurso, Costo, etc)_Numero de actividad 
                            Por ejemplo: E0_actividad_0, E0_actividad_1, Etc  -
                            Para tiempo: E0_tiempo_0, E0_tiempo_1, Etc
            -->
                    <?php
                    $e = 0;
                    ?>
                    <?php foreach ($etapas as $etapa => $actividades) { ?>
                        <tr>
                            <td rowspan="<?php echo count($actividades) + 1; ?>"><?php echo $etapa; ?></td>
                            <?php $a = 0 ?>
                            <?php foreach ($actividades as $index => $actividad) { ?>
                        <tr>
                            <td>
                                <textarea type="text" name="E<?php echo $e; ?>_actividad_<?php echo $a; ?>" id="E<?php echo $e; ?>_actividad_<?php echo $a; ?>" <?php if ($e === 2) { echo 'readonly class"input-non-editable"'; } ?>><?php echo isset($_SESSION['proyecto']['etapa0']['planGeneralTrabajo']) ? $_SESSION['proyecto']['etapa0']['planGeneralTrabajo']['E' . $e . '_actividad_' . $a] : $actividad; ?></textarea>
                            </td>
                            <td> <input class="input-editable" type="text" name="E<?php echo $e; ?>_tiempo_<?php echo $a; ?>" id="E<?php echo $e; ?>_tiempo_<?php echo $a; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa0']['planGeneralTrabajo']) ? $_SESSION['proyecto']['etapa0']['planGeneralTrabajo']['E' . $e . '_tiempo_' . $a] : ($e === 2 && isset($tiempo['E2_tiempo_' . $a]) ? $tiempo['E2_tiempo_' . $a] : ''); ?>"></td>
                            <td> <textarea type="text" name="E<?php echo $e; ?>_recurso_<?php echo $a; ?>" id="E<?php echo $e; ?>_recurso_<?php echo $a; ?>"> <?php echo isset($_SESSION['proyecto']['etapa0']['planGeneralTrabajo']) ? $_SESSION['proyecto']['etapa0']['planGeneralTrabajo']['E' . $e . '_recurso_' . $a] : ''; ?></textarea> </td>
                            <td> <input class="input-editable" type="text" name="E<?php echo $e; ?>_costo_<?php echo $a; ?>" id="E<?php echo $e; ?>_costo_<?php echo $a; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa0']['planGeneralTrabajo']) ? $_SESSION['proyecto']['etapa0']['planGeneralTrabajo']['E' . $e . '_costo_' . $a] : ''; ?>"></td>
                            <td>
                                <select name="E<?php echo $e; ?>_responsable_<?php echo $a; ?>" id="E<?php echo $e; ?>_responsable_<?php echo $a; ?>" <?php if ($e === 2) {
                                                                                                                                                            echo 'class="select-non-editable"';
                                                                                                                                                        } else {
                                                                                                                                                            echo 'class="select-editable"';
                                                                                                                                                        } ?>>
                                    <option value="0">Seleccionar</option>
                                    <?php foreach (EQUIPOS as $equipo) { ?>
                                        <option value="<?php echo $equipo; ?>" <?php
                                                                                if (isset($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'])) {
                                                                                    foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
                                                                                        if ($key === 'E' . $e . '_responsable_' . $a && $value === $equipo) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                    }
                                                                                } else if ($e === 2 && getResponsable($a) === $equipo) {
                                                                                    echo 'selected';
                                                                                }
                                                                                ?>>
                                            <?php echo $equipo; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><textarea type="text" name="E<?php echo $e; ?>_entregable_<?php echo $a; ?>" id="E<?php echo $e; ?>_entregable_<?php echo $a; ?>"><?php echo isset($_SESSION['proyecto']['etapa0']['planGeneralTrabajo']) ? $_SESSION['proyecto']['etapa0']['planGeneralTrabajo']['E' . $e . '_entregable_' . $a] : ''; ?></textarea></td>
                        </tr>
                        <?php $a++ ?>
                    <?php } ?>
                    </tr>
                    <?php $e++ ?>
                <?php } ?>
                <tr>
                    
                </tr>
                </tbody>
            </table>
            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>

</body>


<?php

/*
Ejemplo de JSON en data/etapas.json
{
    "Etapa 0 Inicio": {
        "E0_actividad_0": "Determinar titulo del proyecto",
        "E0_actividad_1": "Determinar objetivo del proyecto",
        "E0_actividad_2": "Determinar sector del proyecto",
        "E0_actividad_3": "Determinar giro del proyecto",
        "E0_actividad_4": "Determinar PIB del sector",
        "E0_actividad_5": "Determinar Mercado Objetivo",
        "E0_actividad_6": "Desarrollo del Benchmarking",
        "E0_actividad_7": "Calculo de la demanda",
        "E0_actividad_8": "Selección del lider del proyecto",
        "E0_actividad_9": "Estudio de Mercado",
        "E0_actividad_10": "Estudio Técnico",
        "E0_actividad_11": "Estudio Economico",
        "E0_actividad_12": "Estudio Financiero",
        "E0_actividad_13": "Plan Operativo",
        "E0_actividad_14": "Plan de Comercialización",
        "E0_actividad_15": "Plan General de Trabajo"
    },
    "Etapa 1 Planeación": {
        "E1_actividad_0": "Acta Constitución del Proyecto",
        "E1_actividad_1": "Gestión de interesados",
        "E1_actividad_2": "Plan de Dirección del Proyecto",
        "E1_actividad_3": "Gestión del Alcance",
        "E1_actividad_4": "Diagrama EDT",
        "E1_actividad_5": "Verificación del Alcance",
        "E1_actividad_6": "Control del Alcance",
        "E1_actividad_7": "Cronograma"
    },
    "Etapa 2 Ejecución": {},
    "Etapa 3 Verificación y Control": {
        "E3_actividad_0": "Checklist de Verificación de Ruta Critica",
        "E3_actividad_1": "Checklist de Verificación de Actividades de Holgura",
        "E3_actividad_2": "Control y Seguimiento",
        "E3_actividad_3": "Control de Cambios"
    },
    "Etapa 4 Cierre": {
        "E4_actividad_0": "Resultados",
        "E4_actividad_1": "Control de Costos",
        "E4_actividad_2": "Evaluación Económica",
        "E4_actividad_3": "Riesgos",
        "E4_actividad_4": "Acta de Cierre del Proyecto"
    }
}




*/

//Ejemplo de var_dump($_SESSION) para mostrar los datos guardados en la sesión

?>