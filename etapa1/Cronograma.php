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


$etapas = json_decode(file_get_contents('../data/etapas.json'), true);
$etapas = array_keys($etapas);

$actividades = array();
foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    if (strpos($key, 'E0_actividad_') !== false || strpos($key, 'E1_actividad_') !== false || strpos($key, 'E2_actividad_') !== false || strpos($key, 'E3_actividad_') !== false || strpos($key, 'E4_actividad_') !== false) {
        $actividades[] = $value;
    }
}

$tiempo = array();
foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    if (strpos($key, 'E0_tiempo_') !== false || strpos($key, 'E1_tiempo_') !== false || strpos($key, 'E2_tiempo_') !== false || strpos($key, 'E3_tiempo_') !== false || strpos($key, 'E4_tiempo_') !== false) {
        $tiempo[] = $value;
    }
}

$actividadesPorEtapa = array();
//Etapa 0 son las actividades con el prefijo E0_actividad_ separar por etapa de acuerdo a E1_actividad_ E2_actividad_ E3_actividad_ E4_actividad_
for ($i = 0; $i < count($etapas); $i++) {
    $actividadesPorEtapa[$etapas[$i]] = array();
    foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
        if (strpos($key, 'E' . $i . '_actividad_') !== false) {
            $actividadesPorEtapa[$etapas[$i]][] = $value;
        }
    }
}


$letras = array();
//asignar letras por cantidad de actividades
//Letra será una secuencia de letras de la A a la Z, cuando se llegue a la Z se agregará una letra más a la secuencia Por ejemplo: AA, AB, AC, AD, etc. 
for ($i = 0; $i < count($actividades); $i++) {
    if ($i < 26) {
        $letras[] = chr(65 + $i);
    } else {
        $letras[] = chr(65 + floor($i / 26) - 1) . chr(65 + ($i % 26));
    }
}


const encabezado = array('Etapas', 'Actividades', 'Letra/Numero', 'Precedencia', 'Tiempo (en días)', 'Costo', 'Critica u Holgura');

//guardar los datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa1']['cronograma'] = $_POST;
    $_SESSION['message'] = [
        'text' => 'El cronograma se ha guardado correctamente.',
        'type' => 'success'
    ];

    if (isset($_SESSION['proyecto']['etapa2']['ejecucion'])) {
        unset($_SESSION['proyecto']['etapa2']['ejecucion']);
    }

    header('Location: ../etapa2/Ejecucion.php');
    exit;
};

?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
    <?php include_once './header1.php'; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="cronogramaForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
            <table>
                <thead>
                    <tr>
                        <?php foreach (encabezado as $columna) { ?>
                            <th><?php echo $columna; ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $e = 0; ?>
                    <?php $a = 0; ?>
                    <?php foreach ($actividadesPorEtapa as $etapa => $actividades) : ?>
                        <tr>
                            <td rowspan="<?php echo count($actividades) + 1; ?>"><?php echo $etapa; ?></td>
                            <?php $c = 0; ?>
                            <?php foreach ($actividades as $actividad) : ?>
                        <tr>
                            <td>
                                <textarea class="input-non-editable" readonly type="text" name="<?php echo 'E' . $e . '_' . 'actividad' . '_' . $c; ?>"><?php
                                if (isset($_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'actividad' . '_' . $c])) {
                                    echo $_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'actividad' . '_' . $c];
                                } else {
                                    echo $actividad;
                                }
                                ?></textarea>
                            </td>
                            <td>
                                <input class="input-non-editable" readonly type="text" name="<?php echo 'E' . $e . '_' . 'letra' . '_' . $c; ?>" 
                                value="<?php
                                if (isset($_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'letra' . '_' . $c])) {
                                    echo $_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'letra' . '_' . $c];
                                } else {
                                    echo $letras[$a];
                                }
                                ?>">
                            </td>
                            <td>
                                <input class="input-editable" type="text" name="<?php echo 'E' . $e . '_' . 'precedencia' . '_' . $c; ?>" value="<?php
                                                                                                                                                    if (isset($_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'precedencia' . '_' . $c])) {
                                                                                                                                                        echo $_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'precedencia' . '_' . $c];
                                                                                                                                                    } else {
                                                                                                                                                        echo '';
                                                                                                                                                    }
                                                                                                                                                    ?>">

                            <td>
                                <input class="input-non-editable" readonly type="text" name="<?php echo 'E' . $e . '_' . 'tiempo' . '_' . $c; ?>" value="<?php if (isset($_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'tiempo' . '_' . $c])) {
                                                                                                                                                                echo $_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'tiempo' . '_' . $c];
                                                                                                                                                            } else {
                                                                                                                                                                echo $tiempo[$a];
                                                                                                                                                            }
                                                                                                                                                            ?>">
                            </td>
                            <td>
                                <input class="input-editable" type="text" name="<?php echo 'E' . $e . '_' . 'costo' . '_' . $c; ?>" value="<?php if (isset($_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'costo' . '_' . $c])) {
                                                                                                                                                echo $_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'costo' . '_' . $c];
                                                                                                                                            } else {
                                                                                                                                                echo '';
                                                                                                                                            }
                                                                                                                                            ?>">
                            </td>
                            <td>
                                <select class="select-editable" name="<?php echo 'E' . $e . '_' . 'criticaHolgura' . '_' . $c; ?>">
                                    <option value="Critica" <?php if (isset($_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'criticaHolgura' . '_' . $c]) && $_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'criticaHolgura' . '_' . $c] == 'Critica') echo 'selected'; ?>>Critica</option>
                                    <option value="Holgura" <?php if (isset($_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'criticaHolgura' . '_' . $c]) && $_SESSION['proyecto']['etapa1']['cronograma']['E' . $e . '_' . 'criticaHolgura' . '_' . $c] == 'Holgura') echo 'selected'; ?>>Holgura</option>
                                </select>
                            </td>




                            </td>
                        </tr>
                        <?php $c++; ?>
                        <?php $a++; ?>
                    <?php endforeach; ?>
                    <?php $e++; ?>
                    </tr>
                <?php endforeach; ?>

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
/*
array(468) { ["E0_actividad_0"]=> string(30) "Determinar titulo del proyecto" ["E0_tiempo_0"]=> string(2) "13" ["E0_recurso_0"]=> string(3) "asd" ["E0_costo_0"]=> string(4) "2333" ["E0_responsable_0"]=> string(16) "Recursos Humanos" ["E0_entregable_0"]=> string(4) "Test" ["E0_actividad_1"]=> string(32) "Determinar objetivo del proyecto" ["E0_tiempo_1"]=> string(0) "" ["E0_recurso_1"]=> string(0) "" ["E0_costo_1"]=> string(0) "" ["E0_responsable_1"]=> string(1) "0" ["E0_entregable_1"]=> string(0) "" ["E0_actividad_2"]=> string(30) "Determinar sector del proyecto" ["E0_tiempo_2"]=> string(0) "" ["E0_recurso_2"]=> string(0) "" ["E0_costo_2"]=> string(0) "" ["E0_responsable_2"]=> string(1) "0" ["E0_entregable_2"]=> string(0) "" ["E0_actividad_3"]=> string(28) "Determinar giro del proyecto" ["E0_tiempo_3"]=> string(0) "" ["E0_recurso_3"]=> string(0) "" ["E0_costo_3"]=> string(0) "" ["E0_responsable_3"]=> string(1) "0" ["E0_entregable_3"]=> string(0) "" ["E0_actividad_4"]=> string(25) "Determinar PIB del sector" ["E0_tiempo_4"]=> string(0) "" ["E0_recurso_4"]=> string(0) "" ["E0_costo_4"]=> string(0) "" ["E0_responsable_4"]=> string(1) "0" ["E0_entregable_4"]=> string(0) "" ["E0_actividad_5"]=> string(27) "Determinar Mercado Objetivo" ["E0_tiempo_5"]=> string(0) "" ["E0_recurso_5"]=> string(0) "" ["E0_costo_5"]=> string(0) "" ["E0_responsable_5"]=> string(1) "0" ["E0_entregable_5"]=> string(0) "" ["E0_actividad_6"]=> string(27) "Desarrollo del Benchmarking" ["E0_tiempo_6"]=> string(0) "" ["E0_recurso_6"]=> string(0) "" ["E0_costo_6"]=> string(0) "" ["E0_responsable_6"]=> string(1) "0" ["E0_entregable_6"]=> string(0) "" ["E0_actividad_7"]=> string(21) "Calculo de la demanda" ["E0_tiempo_7"]=> string(0) "" ["E0_recurso_7"]=> string(0) "" ["E0_costo_7"]=> string(0) "" ["E0_responsable_7"]=> string(1) "0" ["E0_entregable_7"]=> string(0) "" ["E0_actividad_8"]=> string(33) "Selección del lider del proyecto" ["E0_tiempo_8"]=> string(0) "" ["E0_recurso_8"]=> string(0) "" ["E0_costo_8"]=> string(0) "" ["E0_responsable_8"]=> string(1) "0" ["E0_entregable_8"]=> string(0) "" ["E0_actividad_9"]=> string(18) "Estudio de Mercado" ["E0_tiempo_9"]=> string(0) "" ["E0_recurso_9"]=> string(0) "" ["E0_costo_9"]=> string(0) "" ["E0_responsable_9"]=> string(1) "0" ["E0_entregable_9"]=> string(0) "" ["E0_actividad_10"]=> string(16) "Estudio Técnico" ["E0_tiempo_10"]=> string(0) "" ["E0_recurso_10"]=> string(0) "" ["E0_costo_10"]=> string(0) "" ["E0_responsable_10"]=> string(1) "0" ["E0_entregable_10"]=> string(0) "" ["E0_actividad_11"]=> string(17) "Estudio Economico" ["E0_tiempo_11"]=> string(0) "" ["E0_recurso_11"]=> string(0) "" ["E0_costo_11"]=> string(0) "" ["E0_responsable_11"]=> string(1) "0" ["E0_entregable_11"]=> string(0) "" ["E0_actividad_12"]=> string(18) "Estudio Financiero" ["E0_tiempo_12"]=> string(0) "" ["E0_recurso_12"]=> string(0) "" ["E0_costo_12"]=> string(0) "" ["E0_responsable_12"]=> string(1) "0" ["E0_entregable_12"]=> string(0) "" ["E0_actividad_13"]=> string(14) "Plan Operativo" ["E0_tiempo_13"]=> string(0) "" ["E0_recurso_13"]=> string(0) "" ["E0_costo_13"]=> string(0) "" ["E0_responsable_13"]=> string(1) "0" ["E0_entregable_13"]=> string(0) "" ["E0_actividad_14"]=> string(25) "Plan de Comercialización" ["E0_tiempo_14"]=> string(0) "" ["E0_recurso_14"]=> string(0) "" ["E0_costo_14"]=> string(0) "" ["E0_responsable_14"]=> string(1) "0" ["E0_entregable_14"]=> string(0) "" ["E0_actividad_15"]=> string(23) "Plan General de Trabajo" ["E0_tiempo_15"]=> string(0) "" ["E0_recurso_15"]=> string(0) "" ["E0_costo_15"]=> string(0) "" ["E0_responsable_15"]=> string(1) "0" ["E0_entregable_15"]=> string(0) "" ["E1_actividad_0"]=> string(31) "Acta Constitución del Proyecto" ["E1_tiempo_0"]=> string(1) "1" ["E1_recurso_0"]=> string(6) "Prueba" ["E1_costo_0"]=> string(2) "30" ["E1_responsable_0"]=> string(9) "Operativa" ["E1_entregable_0"]=> string(7) "Prueba2" ["E1_actividad_1"]=> string(23) "Gestión de interesados" ["E1_tiempo_1"]=> string(0) "" ["E1_recurso_1"]=> string(0) "" ["E1_costo_1"]=> string(0) "" ["E1_responsable_1"]=> string(1) "0" ["E1_entregable_1"]=> string(0) "" ["E1_actividad_2"]=> string(31) "Plan de Dirección del Proyecto" ["E1_tiempo_2"]=> string(0) "" ["E1_recurso_2"]=> string(0) "" ["E1_costo_2"]=> string(0) "" ["E1_responsable_2"]=> string(1) "0" ["E1_entregable_2"]=> string(0) "" ["E1_actividad_3"]=> string(20) "Gestión del Alcance" ["E1_tiempo_3"]=> string(0) "" ["E1_recurso_3"]=> string(0) "" ["E1_costo_3"]=> string(0) "" ["E1_responsable_3"]=> string(1) "0" ["E1_entregable_3"]=> string(0) "" ["E1_actividad_4"]=> string(12) "Diagrama EDT" ["E1_tiempo_4"]=> string(0) "" ["E1_recurso_4"]=> string(0) "" ["E1_costo_4"]=> string(0) "" ["E1_responsable_4"]=> string(1) "0" ["E1_entregable_4"]=> string(0) "" ["E1_actividad_5"]=> string(25) "Verificación del Alcance" ["E1_tiempo_5"]=> string(0) "" ["E1_recurso_5"]=> string(0) "" ["E1_costo_5"]=> string(0) "" ["E1_responsable_5"]=> string(1) "0" ["E1_entregable_5"]=> string(0) "" ["E1_actividad_6"]=> string(19) "Control del Alcance" ["E1_tiempo_6"]=> string(0) "" ["E1_recurso_6"]=> string(0) "" ["E1_costo_6"]=> string(0) "" ["E1_responsable_6"]=> string(1) "0" ["E1_entregable_6"]=> string(0) "" ["E1_actividad_7"]=> string(10) "Cronograma" ["E1_tiempo_7"]=> string(0) "" ["E1_recurso_7"]=> string(0) "" ["E1_costo_7"]=> string(0) "" ["E1_responsable_7"]=> string(1) "0" ["E1_entregable_7"]=> string(0) "" ["E2_actividad_0"]=> string(1) "a" ["E2_tiempo_0"]=> string(1) "3" ["E2_recurso_0"]=> string(0) "" ["E2_costo_0"]=> string(0) "" ["E2_responsable_0"]=> string(11) "Tecnología" ["E2_entregable_0"]=> string(0) "" ["E2_actividad_1"]=> string(0) "" ["E2_tiempo_1"]=> string(0) "" ["E2_recurso_1"]=> string(0) "" ["E2_costo_1"]=> string(0) "" ["E2_responsable_1"]=> string(9) "Operativa" ["E2_entregable_1"]=> string(0) "" ["E2_actividad_2"]=> string(0) "" ["E2_tiempo_2"]=> string(0) "" ["E2_recurso_2"]=> string(0) "" ["E2_costo_2"]=> string(0) "" ["E2_responsable_2"]=> string(16) "Recursos Humanos" ["E2_entregable_2"]=> string(0) "" ["E2_actividad_3"]=> string(0) "" ["E2_tiempo_3"]=> string(0) "" ["E2_recurso_3"]=> string(0) "" ["E2_costo_3"]=> string(0) "" ["E2_responsable_3"]=> string(8) "Finanzas" ["E2_entregable_3"]=> string(0) "" ["E2_actividad_4"]=> string(0) "" ["E2_tiempo_4"]=> string(0) "" ["E2_recurso_4"]=> string(0) "" ["E2_costo_4"]=> string(0) "" ["E2_responsable_4"]=> string(9) "Comercial" ["E2_entregable_4"]=> string(0) "" ["E2_actividad_5"]=> string(0) "" ["E2_tiempo_5"]=> string(0) "" ["E2_recurso_5"]=> string(0) "" ["E2_costo_5"]=> string(0) "" ["E2_responsable_5"]=> string(11) "Tecnología" ["E2_entregable_5"]=> string(0) "" ["E2_actividad_6"]=> string(1) "a" ["E2_tiempo_6"]=> string(1) "4" ["E2_recurso_6"]=> string(0) "" ["E2_costo_6"]=> string(0) "" ["E2_responsable_6"]=> string(9) "Operativa" ["E2_entregable_6"]=> string(0) "" ["E2_actividad_7"]=> string(0) "" ["E2_tiempo_7"]=> string(0) "" ["E2_recurso_7"]=> string(0) "" ["E2_costo_7"]=> string(0) "" ["E2_responsable_7"]=> string(16) "Recursos Humanos" ["E2_entregable_7"]=> string(0) "" ["E2_actividad_8"]=> string(0) "" ["E2_tiempo_8"]=> string(0) "" ["E2_recurso_8"]=> string(0) "" ["E2_costo_8"]=> string(0) "" ["E2_responsable_8"]=> string(8) "Finanzas" ["E2_entregable_8"]=> string(0) "" ["E2_actividad_9"]=> string(0) "" ["E2_tiempo_9"]=> string(0) "" ["E2_recurso_9"]=> string(0) "" ["E2_costo_9"]=> string(0) "" ["E2_responsable_9"]=> string(9) "Comercial" ["E2_entregable_9"]=> string(0) "" ["E2_actividad_10"]=> string(0) "" ["E2_tiempo_10"]=> string(0) "" ["E2_recurso_10"]=> string(0) "" ["E2_costo_10"]=> string(0) "" ["E2_responsable_10"]=> string(11) "Tecnología" ["E2_entregable_10"]=> string(0) "" ["E2_actividad_11"]=> string(1) "u" ["E2_tiempo_11"]=> string(1) "9" ["E2_recurso_11"]=> string(0) "" ["E2_costo_11"]=> string(0) "" ["E2_responsable_11"]=> string(9) "Operativa" ["E2_entregable_11"]=> string(0) "" ["E2_actividad_12"]=> string(0) "" ["E2_tiempo_12"]=> string(0) "" ["E2_recurso_12"]=> string(0) "" ["E2_costo_12"]=> string(0) "" ["E2_responsable_12"]=> string(16) "Recursos Humanos" ["E2_entregable_12"]=> string(0) "" ["E2_actividad_13"]=> string(0) "" ["E2_tiempo_13"]=> string(0) "" ["E2_recurso_13"]=> string(0) "" ["E2_costo_13"]=> string(0) "" ["E2_responsable_13"]=> string(8) "Finanzas" ["E2_entregable_13"]=> string(0) "" ["E2_actividad_14"]=> string(0) "" ["E2_tiempo_14"]=> string(0) "" ["E2_recurso_14"]=> string(0) "" ["E2_costo_14"]=> string(0) "" ["E2_responsable_14"]=> string(9) "Comercial" ["E2_entregable_14"]=> string(0) "" ["E2_actividad_15"]=> string(0) "" ["E2_tiempo_15"]=> string(0) "" ["E2_recurso_15"]=> string(0) "" ["E2_costo_15"]=> string(0) "" ["E2_responsable_15"]=> string(11) "Tecnología" ["E2_entregable_15"]=> string(0) "" ["E2_actividad_16"]=> string(0) "" ["E2_tiempo_16"]=> string(0) "" ["E2_recurso_16"]=> string(0) "" ["E2_costo_16"]=> string(0) "" ["E2_responsable_16"]=> string(9) "Operativa" ["E2_entregable_16"]=> string(0) "" ["E2_actividad_17"]=> string(0) "" ["E2_tiempo_17"]=> string(0) "" ["E2_recurso_17"]=> string(0) "" ["E2_costo_17"]=> string(0) "" ["E2_responsable_17"]=> string(16) "Recursos Humanos" ["E2_entregable_17"]=> string(0) "" ["E2_actividad_18"]=> string(0) "" ["E2_tiempo_18"]=> string(0) "" ["E2_recurso_18"]=> string(0) "" ["E2_costo_18"]=> string(0) "" ["E2_responsable_18"]=> string(8) "Finanzas" ["E2_entregable_18"]=> string(0) "" ["E2_actividad_19"]=> string(0) "" ["E2_tiempo_19"]=> string(0) "" ["E2_recurso_19"]=> string(0) "" ["E2_costo_19"]=> string(0) "" ["E2_responsable_19"]=> string(9) "Comercial" ["E2_entregable_19"]=> string(0) "" ["E2_actividad_20"]=> string(0) "" ["E2_tiempo_20"]=> string(0) "" ["E2_recurso_20"]=> string(0) "" ["E2_costo_20"]=> string(0) "" ["E2_responsable_20"]=> string(11) "Tecnología" ["E2_entregable_20"]=> string(0) "" ["E2_actividad_21"]=> string(0) "" ["E2_tiempo_21"]=> string(0) "" ["E2_recurso_21"]=> string(0) "" ["E2_costo_21"]=> string(0) "" ["E2_responsable_21"]=> string(9) "Operativa" ["E2_entregable_21"]=> string(0) "" ["E2_actividad_22"]=> string(0) "" ["E2_tiempo_22"]=> string(0) "" ["E2_recurso_22"]=> string(0) "" ["E2_costo_22"]=> string(0) "" ["E2_responsable_22"]=> string(16) "Recursos Humanos" ["E2_entregable_22"]=> string(0) "" ["E2_actividad_23"]=> string(0) "" ["E2_tiempo_23"]=> string(0) "" ["E2_recurso_23"]=> string(0) "" ["E2_costo_23"]=> string(0) "" ["E2_responsable_23"]=> string(8) "Finanzas" ["E2_entregable_23"]=> string(0) "" ["E2_actividad_24"]=> string(0) "" ["E2_tiempo_24"]=> string(0) "" ["E2_recurso_24"]=> string(0) "" ["E2_costo_24"]=> string(0) "" ["E2_responsable_24"]=> string(9) "Comercial" ["E2_entregable_24"]=> string(0) "" ["E2_actividad_25"]=> string(0) "" ["E2_tiempo_25"]=> string(0) "" ["E2_recurso_25"]=> string(0) "" ["E2_costo_25"]=> string(0) "" ["E2_responsable_25"]=> string(11) "Tecnología" ["E2_entregable_25"]=> string(0) "" ["E2_actividad_26"]=> string(0) "" ["E2_tiempo_26"]=> string(0) "" ["E2_recurso_26"]=> string(0) "" ["E2_costo_26"]=> string(0) "" ["E2_responsable_26"]=> string(9) "Operativa" ["E2_entregable_26"]=> string(0) "" ["E2_actividad_27"]=> string(0) "" ["E2_tiempo_27"]=> string(0) "" ["E2_recurso_27"]=> string(0) "" ["E2_costo_27"]=> string(0) "" ["E2_responsable_27"]=> string(16) "Recursos Humanos" ["E2_entregable_27"]=> string(0) "" ["E2_actividad_28"]=> string(0) "" ["E2_tiempo_28"]=> string(0) "" ["E2_recurso_28"]=> string(0) "" ["E2_costo_28"]=> string(0) "" ["E2_responsable_28"]=> string(8) "Finanzas" ["E2_entregable_28"]=> string(0) "" ["E2_actividad_29"]=> string(0) "" ["E2_tiempo_29"]=> string(0) "" ["E2_recurso_29"]=> string(0) "" ["E2_costo_29"]=> string(0) "" ["E2_responsable_29"]=> string(9) "Comercial" ["E2_entregable_29"]=> string(0) "" ["E2_actividad_30"]=> string(0) "" ["E2_tiempo_30"]=> string(0) "" ["E2_recurso_30"]=> string(0) "" ["E2_costo_30"]=> string(0) "" ["E2_responsable_30"]=> string(11) "Tecnología" ["E2_entregable_30"]=> string(0) "" ["E2_actividad_31"]=> string(0) "" ["E2_tiempo_31"]=> string(0) "" ["E2_recurso_31"]=> string(0) "" ["E2_costo_31"]=> string(0) "" ["E2_responsable_31"]=> string(9) "Operativa" ["E2_entregable_31"]=> string(0) "" ["E2_actividad_32"]=> string(0) "" ["E2_tiempo_32"]=> string(0) "" ["E2_recurso_32"]=> string(0) "" ["E2_costo_32"]=> string(0) "" ["E2_responsable_32"]=> string(16) "Recursos Humanos" ["E2_entregable_32"]=> string(0) "" ["E2_actividad_33"]=> string(0) "" ["E2_tiempo_33"]=> string(0) "" ["E2_recurso_33"]=> string(0) "" ["E2_costo_33"]=> string(0) "" ["E2_responsable_33"]=> string(8) "Finanzas" ["E2_entregable_33"]=> string(0) "" ["E2_actividad_34"]=> string(0) "" ["E2_tiempo_34"]=> string(0) "" ["E2_recurso_34"]=> string(0) "" ["E2_costo_34"]=> string(0) "" ["E2_responsable_34"]=> string(9) "Comercial" ["E2_entregable_34"]=> string(0) "" ["E2_actividad_35"]=> string(0) "" ["E2_tiempo_35"]=> string(0) "" ["E2_recurso_35"]=> string(0) "" ["E2_costo_35"]=> string(0) "" ["E2_responsable_35"]=> string(11) "Tecnología" ["E2_entregable_35"]=> string(0) "" ["E2_actividad_36"]=> string(0) "" ["E2_tiempo_36"]=> string(0) "" ["E2_recurso_36"]=> string(0) "" ["E2_costo_36"]=> string(0) "" ["E2_responsable_36"]=> string(9) "Operativa" ["E2_entregable_36"]=> string(0) "" ["E2_actividad_37"]=> string(0) "" ["E2_tiempo_37"]=> string(0) "" ["E2_recurso_37"]=> string(0) "" ["E2_costo_37"]=> string(0) "" ["E2_responsable_37"]=> string(16) "Recursos Humanos" ["E2_entregable_37"]=> string(0) "" ["E2_actividad_38"]=> string(0) "" ["E2_tiempo_38"]=> string(0) "" ["E2_recurso_38"]=> string(0) "" ["E2_costo_38"]=> string(0) "" ["E2_responsable_38"]=> string(8) "Finanzas" ["E2_entregable_38"]=> string(0) "" ["E2_actividad_39"]=> string(0) "" ["E2_tiempo_39"]=> string(0) "" ["E2_recurso_39"]=> string(0) "" ["E2_costo_39"]=> string(0) "" ["E2_responsable_39"]=> string(9) "Comercial" ["E2_entregable_39"]=> string(0) "" ["E2_actividad_40"]=> string(0) "" ["E2_tiempo_40"]=> string(0) "" ["E2_recurso_40"]=> string(0) "" ["E2_costo_40"]=> string(0) "" ["E2_responsable_40"]=> string(11) "Tecnología" ["E2_entregable_40"]=> string(0) "" ["E2_actividad_41"]=> string(0) "" ["E2_tiempo_41"]=> string(0) "" ["E2_recurso_41"]=> string(0) "" ["E2_costo_41"]=> string(0) "" ["E2_responsable_41"]=> string(9) "Operativa" ["E2_entregable_41"]=> string(0) "" ["E2_actividad_42"]=> string(0) "" ["E2_tiempo_42"]=> string(0) "" ["E2_recurso_42"]=> string(0) "" ["E2_costo_42"]=> string(0) "" ["E2_responsable_42"]=> string(16) "Recursos Humanos" ["E2_entregable_42"]=> string(0) "" ["E2_actividad_43"]=> string(0) "" ["E2_tiempo_43"]=> string(0) "" ["E2_recurso_43"]=> string(0) "" ["E2_costo_43"]=> string(0) "" ["E2_responsable_43"]=> string(8) "Finanzas" ["E2_entregable_43"]=> string(0) "" ["E2_actividad_44"]=> string(0) "" ["E2_tiempo_44"]=> string(0) "" ["E2_recurso_44"]=> string(0) "" ["E2_costo_44"]=> string(0) "" ["E2_responsable_44"]=> string(9) "Comercial" ["E2_entregable_44"]=> string(0) "" ["E3_actividad_0"]=> string(42) "Checklist de Verificación de Ruta Critica" ["E3_tiempo_0"]=> string(0) "" ["E3_recurso_0"]=> string(0) "" ["E3_costo_0"]=> string(0) "" ["E3_responsable_0"]=> string(1) "0" ["E3_entregable_0"]=> string(0) "" ["E3_actividad_1"]=> string(52) "Checklist de Verificación de Actividades de Holgura" ["E3_tiempo_1"]=> string(0) "" ["E3_recurso_1"]=> string(0) "" ["E3_costo_1"]=> string(0) "" ["E3_responsable_1"]=> string(1) "0" ["E3_entregable_1"]=> string(0) "" ["E3_actividad_2"]=> string(21) "Control y Seguimiento" ["E3_tiempo_2"]=> string(0) "" ["E3_recurso_2"]=> string(0) "" ["E3_costo_2"]=> string(0) "" ["E3_responsable_2"]=> string(1) "0" ["E3_entregable_2"]=> string(0) "" ["E3_actividad_3"]=> string(18) "Control de Cambios" ["E3_tiempo_3"]=> string(0) "" ["E3_recurso_3"]=> string(0) "" ["E3_costo_3"]=> string(0) "" ["E3_responsable_3"]=> string(1) "0" ["E3_entregable_3"]=> string(0) "" ["E4_actividad_0"]=> string(10) "Resultados" ["E4_tiempo_0"]=> string(0) "" ["E4_recurso_0"]=> string(0) "" ["E4_costo_0"]=> string(0) "" ["E4_responsable_0"]=> string(1) "0" ["E4_entregable_0"]=> string(0) "" ["E4_actividad_1"]=> string(17) "Control de Costos" ["E4_tiempo_1"]=> string(0) "" ["E4_recurso_1"]=> string(0) "" ["E4_costo_1"]=> string(0) "" ["E4_responsable_1"]=> string(1) "0" ["E4_entregable_1"]=> string(0) "" ["E4_actividad_2"]=> string(22) "Evaluación Económica" ["E4_tiempo_2"]=> string(0) "" ["E4_recurso_2"]=> string(0) "" ["E4_costo_2"]=> string(0) "" ["E4_responsable_2"]=> string(1) "0" ["E4_entregable_2"]=> string(0) "" ["E4_actividad_3"]=> string(7) "Riesgos" ["E4_tiempo_3"]=> string(0) "" ["E4_recurso_3"]=> string(0) "" ["E4_costo_3"]=> string(0) "" ["E4_responsable_3"]=> string(1) "0" ["E4_entregable_3"]=> string(0) "" ["E4_actividad_4"]=> string(27) "Acta de Cierre del Proyecto" ["E4_tiempo_4"]=> string(0) "" ["E4_recurso_4"]=> string(0) "" ["E4_costo_4"]=> string(0) "" ["E4_responsable_4"]=> string(1) "0" ["E4_entregable_4"]=> string(0) "" }
*/

?>