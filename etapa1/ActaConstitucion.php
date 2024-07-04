<?php
require_once '../session.php';

// var_dump($_SESSION['proyecto']['etapa0']['planGeneralTrabajo']);
//hitos son las actividades en el plan general de trabajo, recuperalos de la sesión (solo aquellos que tienen en el key la cadena E0_actividad para E0, E1, E2, E3 y E4)

/*
En  layouts/header.php se incluye el siguiente código:
<?php if (isset($_SESSION['message'])) : ?>
        <div class="message <?php echo $_SESSION['message']['type']; ?>">
            <p><?php echo $_SESSION['message']['text']; ?></p>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    */

//mensaje si no existe la sesión para planGeneralTrabajo, devolverá a la página anterior y mostrará un mensaje

if (!isset($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'])) {
    $_SESSION['message'] = array(
        'type' => 'error',
        'text' => 'Primero debes completar el Plan General de Trabajo para tener los entregables e hitos del proyecto.'
    );
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}


$hitos = array();
foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    if (strpos($key, 'E0_actividad') !== false || strpos($key, 'E1_actividad') !== false || strpos($key, 'E2_actividad') !== false || strpos($key, 'E3_actividad') !== false || strpos($key, 'E4_actividad') !== false) {
        $hitos[] = $value;
    }
}

$entregables = array();
foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    if (strpos($key, 'E0_entregable') !== false || strpos($key, 'E1_entregable') !== false || strpos($key, 'E2_entregable') !== false || strpos($key, 'E3_entregable') !== false || strpos($key, 'E4_entregable') !== false) {
        $entregables[] = $value;
    }
}



//guardar los datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['proyecto']['etapa1']['actaConstitucion'] = $_POST;
    $_SESSION['message'] = [
        'text' => 'El acta de constitución se ha guardado correctamente.',
        'type' => 'success'
    ];

    header('Location: ../etapa1/GestionInteresados.php');
    exit;
};

$intereados = array('Cliente', 'Patrocinador', 'Líder de Proyecto', 'Director Tecnología', 'Director de Recursos Humanos', 'Director Operativo', 'Director de Finanzas', 'Director Comercial', 'Contratados');

$planeacionDir = array('ActaConstitucion', 'GestionInteresados', 'PlanDireccionProyecto', 'GestionAlcance', 'DiagramaEDT', 'Requisitos', 'VerificacionAlcance', 'ControlAlcance', 'Cronograma');

//presupuesto
if (isset($_SESSION['proyecto']['idea'])) {
    $presupuestoNegocio = $_SESSION['proyecto']['idea']['presupuestoNegocio'];
}

//ejemplo vardump
//array(33) { ["tituloIdea"]=> string(6) " para " ["objetivoIdea"]=> string(49) "El proyecto tiene como objetivo para como con " ["sector"]=> string(11) "Tecnología" ["giro"]=> string(10) "Industrial" ["pib"]=> string(4) "16.0" ["demandaMeta"]=> string(0) "" ["demandaMetaPorcentaje"]=> string(4) "0.00" ["demandaObjetivo"]=> string(0) "" ["demandaObjetivoPorcentaje"]=> string(4) "0.00" ["demandaPotencial"]=> string(0) "" ["demandaPotencialPorcentaje"]=> string(4) "0.00" ["costoCompetencia1"]=> string(0) "" ["costoCompetencia2"]=> string(0) "" ["costoPropuesta"]=> string(3) "300" ["funcionCompetencia1"]=> string(0) "" ["funcionCompetencia2"]=> string(0) "" ["funcionPropuesta"]=> string(0) "" ["calidadCompetencia1"]=> string(1) "1" ["calidadCompetencia2"]=> string(1) "1" ["calidadPropuesta"]=> string(1) "1" ["ventasAnio1"]=> string(3) "100" ["ventasAnio2"]=> string(3) "200" ["ventasAnio3"]=> string(3) "300" ["costoAnio1"]=> string(3) "300" ["costoAnio2"]=> string(3) "300" ["costoAnio3"]=> string(3) "300" ["ventaAnio1"]=> string(8) "30000.00" ["ventaAnio2"]=> string(8) "60000.00" ["ventaAnio3"]=> string(8) "90000.00" ["incrementoAnio1"]=> string(0) "" ["incrementoAnio2"]=> string(6) "100.00" ["incrementoAnio3"]=> string(5) "50.00" ["presupuestoNegocio"]=> string(8) "30000.00" } 

//el presupuesto de proyecto es el presupuesto del Negocio en la idea 

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


<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main class="main">
        <?php include_once './header1.php'; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="actaConstitucionForm" class="form">
            <?php include_once '../layouts/plantillaLogo.php'; ?>
            <h2>Acta de Constitución</h2>
            <article>
                <h3>Titulo del proyecto:</h3>
                <h3><?php echo isset($_SESSION['proyecto']['idea']['tituloIdea']) ? $_SESSION['proyecto']['idea']['tituloIdea'] : ''; ?></h3>
                <h3>Objetivo del Proyecto</h3>
                <h3><?php echo isset($_SESSION['proyecto']['idea']['objetivoIdea']) ? $_SESSION['proyecto']['idea']['objetivoIdea'] : ''; ?></h3>
            </article>
            <article>
                <h3>Enunciado del Trabajo del Proyecto</h3>
                <textarea name="enunciadoTrabajo" id="enunciadoTrabajo" name="enunciadoTrabajo"><?= isset($_SESSION['proyecto']['etapa1']['actaConstitucion']['enunciadoTrabajo']) ? $_SESSION['proyecto']['etapa1']['actaConstitucion']['enunciadoTrabajo'] : ''; ?></textarea>
            </article>
            <article>
                <h3>Caso del Negocio</h3>
                <textarea name="casoNegocio" id="casoNegocio" name="casoNegocio"><?= isset($_SESSION['proyecto']['etapa1']['actaConstitucion']['casoNegocio']) ? $_SESSION['proyecto']['etapa1']['actaConstitucion']['casoNegocio'] : ''; ?></textarea>
            </article>
            <article>
                <h3>Acuerdos</h3>
                <textarea name="acuerdos" id="acuerdos" name="acuerdos"><?= isset($_SESSION['proyecto']['etapa1']['actaConstitucion']['acuerdos']) ? $_SESSION['proyecto']['etapa1']['actaConstitucion']['acuerdos'] : ''; ?></textarea>
            </article>
            <article>
                <h3>Objetivos del Proyecto</h3>
                <textarea name="objetivos" id="objetivos" name="objetivos"><?= isset($_SESSION['proyecto']['etapa1']['actaConstitucion']['objetivos']) ? $_SESSION['proyecto']['etapa1']['actaConstitucion']['objetivos'] : ''; ?></textarea>
            </article>
            <article>
                <h3>Objetivos del Producto</h3>
                <textarea name="objetivosProducto" id="objetivosProducto" name="objetivosProducto"><?= isset($_SESSION['proyecto']['etapa1']['actaConstitucion']['objetivosProducto']) ? $_SESSION['proyecto']['etapa1']['actaConstitucion']['objetivosProducto'] : ''; ?></textarea>
            </article>
            <article>
                <h3>Principales Entregables</h3>
                <ol>
                    <?php foreach ($entregables as $entregable) : ?>
                        <?php if (!empty($entregable)) : ?>
                            <li><?php echo $entregable; ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </article>
            <article>
                <h3>Ambito de Aplicacion</h3>
                <textarea name="ambitoAplicacion" id="ambitoAplicacion" name="ambitoAplicacion"><?= isset($_SESSION['proyecto']['etapa1']['actaConstitucion']['ambitoAplicacion']) ? $_SESSION['proyecto']['etapa1']['actaConstitucion']['ambitoAplicacion'] : ''; ?></textarea>
            </article>
            <article>
                <h3>Actores Clave</h3>
                <table>
                    <thead>
                        <th>Interesado</th>
                        <th>Encargado</th>
                    </thead>
                    <tbody>
                        <?php foreach ($intereados as $key => $interesado) : ?>
                            <tr>
                                <td>
                                    Título: <input class="input-non-editable" readonly type="text" name="interesado_<?php echo $key; ?>" id="interesado_<?php echo $key; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['interesado_' . $key] : $interesado; ?>">
                                </td>
                                <td>
                                    Nombre: <input class="input-non-editable" readonly type="text" placeholder="Llenar gestion alcance" name="nombreInteresado_<?php echo $key; ?>" id="nombreInteresado_<?php echo $key; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['nombreInteresado_' . $key] : ''; ?>">
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </article>
            <article>
                <h3>Hitos del Proyecto</h3>
                <ol>
                    <?php foreach ($hitos as $hito) : ?>
                        <li><?php echo $hito; ?></li>
                    <?php endforeach; ?>
                </ol>
            </article>
            <article>
                <h3>Exclusiones</h3>
                <textarea name="exclusiones" id="exclusiones" name="exclusiones"><?= isset($_SESSION['proyecto']['etapa1']['actaConstitucion']['exclusiones']) ? $_SESSION['proyecto']['etapa1']['actaConstitucion']['exclusiones'] : ''; ?></textarea>
            </article>
            <article>
                <h3>Presupuesto</h3>
                <table>
                    <thead>
                        <th>Presupuesto</th>
                        <th>Valor</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Prespuesto del Proyecto</td>
                            <td><?php echo $costoProyecto; ?></td>
                        </tr>
                        <tr>
                            <td>Presupuesto del Negocio</td>
                            <td><?php if (isset($_SESSION['proyecto']['idea'])) echo $presupuestoNegocio;
                                else echo 'No se ha definido un presupuesto de negocio'; ?>
                            </td>
                            </td>
                        </tr>
                        <tr>
                            <td>Presupuesto del Patrocinador</td>
                            <td><?php echo $presupuestoPatrocinador; ?></td>
                        </tr>
                    </tbody>
                </table>
            </article>
            <article>
                <h3>Limitaciones, supuestos, riesgos y dependencias</h3>
                <table>
                    <thead>
                        <th>Tipo</th>
                        <th>Descripcion</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Limitaciones</td>
                            <td><textarea class="input-non-editable" name="fueraAlcance" id="fueraAlcance" placeholder="Se llenara una vez esté completa la gestión de riesgos"><?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['fueraAlcance'] : ''; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Supuestos</td>
                            <td>
                                <textarea class="input-non-editable" name="supuestos" id="supuestos" placeholder="Se llenara una vez esté completa la gestión de riesgos"><?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['supuestos'] : ''; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Riesgos y dependencias</td>
                            <td><textarea class="input-editable" name="riesgosDependencias" id="riesgosDependencias" placeholder="Completa este campo"><?php echo isset($_SESSION['proyecto']['etapa1']['actaConstitucion']['riesgosDependencias']) ? $_SESSION['proyecto']['etapa1']['actaConstitucion']['riesgosDependencias'] : ''; ?></textarea></td>
                        </tr>
                </table>
            </article>

            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>

    <?php include_once '../layouts/footer.php'; ?>


</body>