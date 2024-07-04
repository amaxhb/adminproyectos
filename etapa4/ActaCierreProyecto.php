<?php
require_once '../session.php';


//guardar datos enviados por el usuario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa4']['ActaCierreProyecto'] = $_POST;
    $_SESSION['message'] = array(
        'type' => 'success',
        'text' => 'Acta de cierre del proyecto guardada correctamente.'
    );

    header('Location: ../etapa4/ActaCierreProyecto.php');
    exit;
}



$intereados = array('Cliente', 'Patrocinador', 'Líder de Proyecto', 'Director Tecnología', 'Director de Recursos Humanos', 'Director Operativo', 'Director de Finanzas', 'Director Comercial', 'Contratados');
$areas = array('Cliente', 'Patrocinador', 'Líder de Proyecto', 'Tecnología', 'Recursos Humanos', 'Operativo', 'Finanzas', 'Comercial');
const razonesCierre = array ('Entrega de todos los productos de conformidad con los requerimientos del cliente', 'Entrega parcial de productos y cancelación de otros de conformidad con los requerimientos del cliente.', 'Cancelación de todos los productos asociados con el proyecto');


$entregables = array();

$etapas = array('E0', 'E1', 'E2', 'E3', 'E4');

foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    foreach ($etapas as $etapa) {
        if (strpos($key, $etapa . '_entregable') !== false) {
            $entregables[$etapa][] = $value;
        }
    }
}


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


?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <?php include_once './header4.php'; ?>


        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="actaCierreForm" class="form">
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
                <h3>Patrocinadores</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th>Área</th>
                        </tr>


                    </thead>
                    <tbody>
                        <?php $i = 0; ?>
                        <?php foreach ($intereados as $key => $interesado) : ?>
                            <?php if ($i > 2  && $i < 8) { ?>
                                <tr>
                                    <td>
                                        <input class="input-non-editable" readonly type="text" placeholder="Llenar gestion alcance" name="nombreInteresado_<?php echo $key + 3; ?>" id="nombreInteresado_<?php echo $key; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['nombreInteresado_' . $key] : ''; ?>">
                                    </td>
                                    <td>
                                        <input class="input-non-editable" readonly type="text" name="interesado_<?php echo $key; ?>" id="interesado_<?php echo $key; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['interesado_' . $key] : $interesado; ?>">
                                    </td>

                                    <td>
                                        <input class="input-non-editable" readonly type="text" name="area_<?php echo $key; ?>" id="area_<?php echo $key; ?>" value=<?php $areas[$key] ?>> 
                                    </td>
                                </tr>

                            <?php } ?>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </article>
            <article>
                <h3>Razón del Cierre</h3>
                <table>
                    <thead>
                        <th>Razón del cierre</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><textarea name="razonCierre" id="razonCierre"><?php if (isset($_SESSION['proyecto']['etapa4']['ActaCierreProyecto']['razonCierre'])) {
                                                                                    echo $_SESSION['proyecto']['etapa4']['ActaCierreProyecto']['razonCierre'];
                                                                                } ?></textarea></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table>
                    <thead>
                    <th colspan="2">Marcar Con X la razón del Cierre</th>
                    </thead>
                    <tbody>
                        <?php $i = 0; ?>
                        <?php foreach (razonesCierre as $key => $razon) : ?>
                            <tr>
                            <td><label for="razonCierre_<?php echo $key; ?>"><?php echo $razon; ?></label></td>
                            <td><input type="radio" name="razonCierre" id="razonCierre_<?php echo $key; ?>" value="<?php echo $razon; ?>"></td>
                                
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </article>

            <article>
                <h3>Aceptación de los entregables</h3>
                <table>
                    <thead>
                        <th>Etapa</th>
                        <th>Entregable</th>
                        <th>Aceptación (si o no)</th>
                    </thead>
                    <tbody>
                        <?php $i = 0; ?>
                        <?php foreach ($entregables as $etapa => $entregable) : ?>
                            <?php foreach ($entregable as $key => $value) : ?>
                                
                                <?php if (!empty($value)) : ?>
                                    <tr>
                                        <td><?php echo $etapa; ?></td>
                                        <td><?php echo $value; ?></td>
                                        <td>
                                            <select class="select-editable" name="aceptacionEntregable_<?php echo $i; ?>" id="aceptacionEntregable_<?php echo $i; ?>">
                                                <option value="si" <?php if (isset($_SESSION['proyecto']['etapa4']['ActaCierreProyecto']['aceptacionEntregable_' . $i]) && $_SESSION['proyecto']['etapa4']['ActaCierreProyecto']['aceptacionEntregable_' . $i] === 'si') { echo 'selected'; } ?>>Si</option>
                                                <option value="no" <?php if (isset($_SESSION['proyecto']['etapa4']['ActaCierreProyecto']['aceptacionEntregable_' . $i]) && $_SESSION['proyecto']['etapa4']['ActaCierreProyecto']['aceptacionEntregable_' . $i] === 'no') { echo 'selected'; } ?>>No</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </article>

            <article>
                <h3>Presupuesto</h3>
                <table>
                    <thead>
                        <th>Etapa</th>
                        <th>Costos</th>
                    </thead>
                    <tbody>
                        <?php foreach ($totalPorEtapa as $etapa => $costo) : ?>
                            <tr>
                                <td><?php echo $etapa; ?></td>
                                <td><input class="input-non-editable" readonly type="text" name="costoEtapa_<?php echo $etapa; ?>" id="costoEtapa_<?php echo $etapa; ?>" value="<?php echo $costo; ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td>Total</td>
                            <td><input class="input-non-editable" readonly type="text" name="costoTotal" id="costoTotal" value="<?php echo array_sum($totalPorEtapa); ?>"></td>
                        </tr>
                    </tbody>
                </table>


            </article>

            <article>
                <h3>Tiempo Final</h3>
                <table>
                    <thead>
                        <th>Etapa</th>
                        <th>Tiempo</th>
                    </thead>
                    <tbody>
                        <?php foreach ($tiempoPorEtapa as $etapa => $tiempo) : ?>
                            <tr>
                                <td><?php echo $etapa; ?></td>
                                <td><input class="input-non-editable" readonly type="text" name="tiempoEtapa_<?php echo $etapa; ?>" id="tiempoEtapa_<?php echo $etapa; ?>" value="<?php echo $tiempo; ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td>Total</td>
                            <td><input class="input-non-editable" readonly type="text" name="tiempoTotal" id="tiempoTotal" value="<?php echo array_sum($tiempoPorEtapa); ?>"></td>
                        </tr>
                    </tbody>
                </table>
            </article>
            <button type="submit" class="btn">Guardar</button>
        </form>
    </main>

    <?php include_once '../layouts/footer.php'; ?>
    
</body>