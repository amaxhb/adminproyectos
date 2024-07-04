<?php
require_once '../session.php';



// Datos y lógica para etapas y actividades (el código que proporcionaste arriba)
$etapas = json_decode(file_get_contents('../data/etapas.json'), true);
$etapas = array_keys($etapas);

$costoPGT = array();
// de acuerdo al ejemplo E0_criticaHolgura_0 recuperar si es de holgura o critica
foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    if (strpos($key, 'costo') !== false) {
        $etapa = substr($key, 0, 2);
        $costoPGT[$etapa][$key] = $value;
    }
}

/* var_dump($_SESSION['proyecto']['idea']);
 array(33) { ["tituloIdea"]=> string(6) " para " ["objetivoIdea"]=> string(49) "El proyecto tiene como objetivo para como con " ["sector"]=> string(11) "Tecnología" ["giro"]=> string(10) "Industrial" ["pib"]=> string(0) "" ["demandaMeta"]=> string(0) "" ["demandaMetaPorcentaje"]=> string(4) "0.00" ["demandaObjetivo"]=> string(0) "" ["demandaObjetivoPorcentaje"]=> string(4) "0.00" ["demandaPotencial"]=> string(0) "" ["demandaPotencialPorcentaje"]=> string(4) "0.00" ["costoCompetencia1"]=> string(0) "" ["costoCompetencia2"]=> string(0) "" ["costoPropuesta"]=> string(3) "100" ["funcionCompetencia1"]=> string(0) "" ["funcionCompetencia2"]=> string(0) "" ["funcionPropuesta"]=> string(0) "" ["calidadCompetencia1"]=> string(1) "1" ["calidadCompetencia2"]=> string(1) "1" ["calidadPropuesta"]=> string(1) "1" ["ventasAnio1"]=> string(3) "100" ["ventasAnio2"]=> string(3) "200" ["ventasAnio3"]=> string(3) "300" ["costoAnio1"]=> string(3) "100" ["costoAnio2"]=> string(3) "100" ["costoAnio3"]=> string(3) "100" ["ventaAnio1"]=> string(8) "10000.00" ["ventaAnio2"]=> string(8) "20000.00" ["ventaAnio3"]=> string(8) "30000.00" ["incrementoAnio1"]=> string(0) "" ["incrementoAnio2"]=> string(6) "
*/


$ventas = array();
$ventas['anio1'] = $_SESSION['proyecto']['idea']['ventasAnio1'];
$ventas['anio2'] = $_SESSION['proyecto']['idea']['ventasAnio2'];
$ventas['anio3'] = $_SESSION['proyecto']['idea']['ventasAnio3'];

$costos = array();
$costos['anio1'] = $_SESSION['proyecto']['idea']['costoAnio1'];
$costos['anio2'] = $_SESSION['proyecto']['idea']['costoAnio2'];
$costos['anio3'] = $_SESSION['proyecto']['idea']['costoAnio3'];

$venta = array();
$venta['anio1'] = $_SESSION['proyecto']['idea']['ventaAnio1'];
$venta['anio2'] = $_SESSION['proyecto']['idea']['ventaAnio2'];
$venta['anio3'] = $_SESSION['proyecto']['idea']['ventaAnio3'];


?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <header>
            <h1>Evaluacion Economica</h1>
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


        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="evaluacionEconomicaForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
            <article>
                datos
                <table>
                    <thead>
                        <th></th>
                        <th></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </article>

            <article>
                <table>
                    <thead>
                        <th>Año</th>
                        <th>Año1</th>
                        <th>Año2</th>
                        <th>Año3</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Demanda</td>
                            <?php $i = 1; ?>
                            <?php foreach ($ventas as $vent) : ?>
                                <td>
                                    <input class="input-non-editable" readonly type="text" name="demandaAnio<?php echo $i; ?>" value="<?php echo $vent; ?>">
                                </td>
                                <?php $i++; ?>
                            <?php endforeach; ?>

                        </tr>
                        <tr>
                            <td>Precio</td>
                            <?php $i = 1; ?>
                            <?php foreach ($costos as $costo) : ?>
                                <td>
                                    <input class="input-non-editable" readonly type="text" name="precioAnio<?php echo $i; ?>" value="<?php echo $costo; ?>">
                                </td>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td>Venta</td>
                            <?php $i = 1; ?>
                            <?php foreach ($venta as $v) : ?>
                                <td>
                                    <input class="input-non-editable" readonly type="text" name="ventaAnio<?php echo $i; ?>" value="<?php echo $v; ?>">
                                </td>
                                <?php $i++; ?>
                            <?php endforeach; ?>

                        </tr>
                        <tr>
                            <td>CP</td>
                        </tr>
                        <tr>
                            <td>Costo Beneficio</td>
                        </tr>
                    </tbody>
                </table>
            </article>
            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>

</body>