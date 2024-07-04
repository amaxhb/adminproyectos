<?php
require_once '../session.php';

if (!isset($_SESSION['proyecto']['idea'])) {
    $_SESSION['message'] = [
        'text' => 'Primero debes guardar la idea de Negocio',
        'type' => 'error'
    ];
    header('Location: ../idea/IdeaNegocio.php');
    exit;
}

//guardar los datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa0']['planOperativo'] = $_POST;

    if (isset($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'])) {
        //sustituir E2_actividad y E2_tiempo en planGeneralTrabajo
        foreach ($_SESSION['proyecto']['etapa0']['planOperativo'] as $key => $value) {
            if (strpos($key, 'E2_actividad') !== false) {
                $id = str_replace('E2_actividad_', '', $key);
                $_SESSION['proyecto']['etapa0']['planGeneralTrabajo']['E2_actividad_' . $id] = $value;
            }
            if (strpos($key, 'E2_tiempo') !== false) {
                $id = str_replace('E2_tiempo_', '', $key);
                $_SESSION['proyecto']['etapa0']['planGeneralTrabajo']['E2_tiempo_' . $id] = $value;
            }
        }
    }

    if (isset($_SESSION['proyecto']['etapa1']['cronograma'])) {
        //sustituir E2_actividad y E2_tiempo en cronograma
        foreach ($_SESSION['proyecto']['etapa0']['planOperativo'] as $key => $value) {
            if (strpos($key, 'E2_actividad') !== false) {
                $id = str_replace('E2_actividad_', '', $key);
                $_SESSION['proyecto']['etapa1']['cronograma']['E2_actividad_' . $id] = $value;
            }
            if (strpos($key, 'E2_tiempo') !== false) {
                $id = str_replace('E2_tiempo_', '', $key);
                $_SESSION['proyecto']['etapa1']['cronograma']['E2_tiempo_' . $id] = $value;
            }
        }
    }

    $_SESSION['message'] = [
        'text' => 'El plan operativo se ha guardado correctamente.',
        'type' => 'success'
    ];

    header('Location: ../etapa0/planComercializacion.php');
    exit;
}

const niveles = array('Estrategico', 'Tactico', 'Operativo');
const rowspanparaNivel = array(3, 3, 3);
const numFilas = 9;
const numColumnas = 5;
const Equipos = array('Tecnología', 'Operativa', 'Recursos Humanos', 'Finanzas', 'Comercial');

//ejemplo vardump
//array(33) { ["tituloIdea"]=> string(6) " para " ["objetivoIdea"]=> string(49) "El proyecto tiene como objetivo para como con " ["sector"]=> string(11) "Tecnología" ["giro"]=> string(10) "Industrial" ["pib"]=> string(4) "16.0" ["demandaMeta"]=> string(0) "" ["demandaMetaPorcentaje"]=> string(4) "0.00" ["demandaObjetivo"]=> string(0) "" ["demandaObjetivoPorcentaje"]=> string(4) "0.00" ["demandaPotencial"]=> string(0) "" ["demandaPotencialPorcentaje"]=> string(4) "0.00" ["costoCompetencia1"]=> string(0) "" ["costoCompetencia2"]=> string(0) "" ["costoPropuesta"]=> string(3) "300" ["funcionCompetencia1"]=> string(0) "" ["funcionCompetencia2"]=> string(0) "" ["funcionPropuesta"]=> string(0) "" ["calidadCompetencia1"]=> string(1) "1" ["calidadCompetencia2"]=> string(1) "1" ["calidadPropuesta"]=> string(1) "1" ["ventasAnio1"]=> string(3) "100" ["ventasAnio2"]=> string(3) "200" ["ventasAnio3"]=> string(3) "300" ["costoAnio1"]=> string(3) "300" ["costoAnio2"]=> string(3) "300" ["costoAnio3"]=> string(3) "300" ["ventaAnio1"]=> string(8) "30000.00" ["ventaAnio2"]=> string(8) "60000.00" ["ventaAnio3"]=> string(8) "90000.00" ["incrementoAnio1"]=> string(0) "" ["incrementoAnio2"]=> string(6) "100.00" ["incrementoAnio3"]=> string(5) "50.00" ["presupuestoNegocio"]=> string(8) "30000.00" } 

//el presupuesto de negocio es el mismo que el de la idea, y tomara el porcentaje de incremento del año 1 al año 2.
//se insertará en la columna de presupuesto una división equitativa 

$presupuestoNegocio = $_SESSION['proyecto']['idea']['presupuestoNegocio'];
$presupuesto = number_format($presupuestoNegocio / numFilas, 2);
$porcentajeIncremento = $_SESSION['proyecto']['idea']['incrementoAnio2'];
$porcentajeFilas = number_format($porcentajeIncremento / numFilas, 2);
$presupuestoEtapa = number_format($presupuestoNegocio / 3, 2);
$porcentajeEtapa = number_format($porcentajeIncremento / 3, 2);
$presupuestoEquipo = number_format($presupuestoNegocio / 5, 2);
$porcentajeEquipo = number_format($porcentajeIncremento / 5, 2);

?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <?php include_once './header0.php'; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="planOperativoForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
        <h2>Plan Operativo</h2>
            <table id="planOperativoTable">
                <thead>
                    <th>Nivel</th>
                    <th>Presupuesto</th>
                    <?php foreach (Equipos as $equipo) : ?>
                        <th>Equipo: <?php echo $equipo; ?>
                        <br>
                            <br> Presupuesto: <?php echo  '$' . $presupuestoEquipo; ?>
                            <br> Porcentaje: <?php echo $porcentajeEquipo . '%'; ?>
                        </th>
                    <?php endforeach; ?>
                </thead>
                <tbody>
                    <?php $nivelIndex = 0; ?>
                    <?php for ($i = 0; $i < numFilas; $i++) : ?>
                        <tr>
                            <?php if ($i % 3 === 0) : ?>
                                <td rowspan="<?php echo rowspanparaNivel[$nivelIndex]; ?>">
                                    <?php echo niveles[$nivelIndex]; ?>
                                    <br>
                                    <br> Presupuesto: <?php echo  '$' . $presupuestoEtapa; ?>
                                    <br> Porcentaje: <?php echo $porcentajeEtapa . '%'; ?>
                                </td>
                                <?php $nivelIndex++; ?>
                            <?php endif; ?>
                            <td>
                                
                                <?php echo '$' . $presupuesto; ?>
                                <br>
                                <?php echo $porcentajeFilas . '%'; ?>
                            </td>
                            <?php for ($j = 0; $j < numColumnas; $j++) : ?>
                                <?php $id = $i * numColumnas + $j; ?>
                                <td>
                                    <label for="E2_cuanto_<?php echo $id; ?>">Cuanto</label>
                                    <input class="input-editable" type="text" name="E2_cuanto_<?php echo $id; ?>" id="E2_cuanto_<?php echo $id; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa0']['planOperativo']['E2_cuanto_' . $id]) ? $_SESSION['proyecto']['etapa0']['planOperativo']['E2_cuanto_' . $id] : ''; ?>">
                                    <br>
                                    <label for="E2_actividad_<?php echo $id; ?>">Actividad</label>
                                    <textarea name="E2_actividad_<?php echo $id; ?>" id="E2_actividad_<?php echo $id; ?>"><?php echo isset($_SESSION['proyecto']['etapa0']['planOperativo']['E2_actividad_' . $id]) ? $_SESSION['proyecto']['etapa0']['planOperativo']['E2_actividad_' . $id] : ''; ?></textarea>
<br>
                                    <label for="E2_tiempo_<?php echo $id; ?>">En cuantos días</label>
                                    <input class="input-editable" type="text" name="E2_tiempo_<?php echo $id; ?>" id="E2_tiempo_<?php echo $id; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa0']['planOperativo']['E2_tiempo_' . $id]) ? $_SESSION['proyecto']['etapa0']['planOperativo']['E2_tiempo_' . $id] : ''; ?>">
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>

</body>
