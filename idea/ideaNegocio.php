<?php
require_once '../session.php';

//guardar datos enviados por el usuario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['idea'] = $_POST;

    $_SESSION['message'] = [
        'text' => 'La idea del negocio se ha guardado correctamente.',
        'type' => 'success'
    ];
    header('Location: ../etapa0/planOperativo.php');
    exit;
}


const sectores = array('Tecnología', 'Educación', 'Salud', 'Publico', 'Privado');
const giros = array('Industrial', 'De servicios', 'Comercial');

//titulo de la idea
$tituloIdea = $_SESSION['proyecto']['preguntas']['iQue'] . ' ' .  $_SESSION['proyecto']['preguntas']['iPara'];

//objetivo con que, para como, con y en cuanto
$objetivoIdea = "El proyecto tiene como objetivo " .  $_SESSION['proyecto']['preguntas']['iQue'] .
    " " . $_SESSION['proyecto']['preguntas']['iPara'] .
    " " . $_SESSION['proyecto']['preguntas']['iComo'] .
    " " . $_SESSION['proyecto']['preguntas']['iCon'];
?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>

    <main>
        <header>
            <h1>Idea del Negocio</h1>
        </header>
        
        <form method="POST" id="formIdea" class="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);  ?>">
        
            <article>
                <h3>Titulo del proyecto</h3>
                <label for="tituloIdea">Titulo</label>
                <textarea type="text" name="tituloIdea" id="tituloIdea"><?= isset($_SESSION['proyecto']['idea']['tituloIdea']) ? $_SESSION['proyecto']['idea']['tituloIdea'] : $tituloIdea; ?></textarea>
            </article>
            <article>
                <h3>Objetivo del proyecto</h3>
                <label for="objetivoIdea">Objetivo</label>
                <textarea name="objetivoIdea" id="objetivoIdea"><?= isset($_SESSION['proyecto']['idea']['objetivoIdea']) ? $_SESSION['proyecto']['idea']['objetivoIdea'] : $objetivoIdea; ?></textarea>
            </article>
            <article>
                <h3>Sector del Proyecto</h3>
                <label for="sector">Sector</label>
                <select class="select-editable" name="sector" id="sector" required>
                    <?php
                    if (isset($_SESSION['proyecto']['idea']['sector'])) {
                        $selectedSector = $_SESSION['proyecto']['idea']['sector'];
                    } else {
                        $selectedSector = '';
                    }

                    foreach (sectores as $sector) {
                        $selected = ($sector == $selectedSector) ? 'selected' : '';
                        echo "<option value='$sector' $selected>$sector</option>";
                    }
                    ?>
                </select>
            </article>
            <article>
                <h3>Giro</h3>
                <label for="giro">Giro</label>
                <select class="select-editable"  name="giro" id="giro" required>
                    <?php
                    if (isset($_SESSION['proyecto']['idea']['giro'])) {
                        $selectedGiro = $_SESSION['proyecto']['idea']['giro'];
                    } else {
                        $selectedGiro = '';
                    }

                    foreach (giros as $giro) {
                        $selected = ($giro == $selectedGiro) ? 'selected' : '';
                        echo "<option value='$giro' $selected>$giro</option>";
                    }
                    ?>
                </select>
            </article>
            <article>
                <h3>PIB</h3>
                <label for="pib">PIB %</label>
                <input class="input-editable"  type="text" name="pib" id="pib" min="0.00" step="0.05" max="100.00" maxlength="6" placeholder="0.00" value="<?= isset($_SESSION['proyecto']['idea']['pib']) ? $_SESSION['proyecto']['idea']['pib'] : ''; ?>">
            </article>

            <article>
                <h3>Determinación de Demanda y Benchmarking</h3>
                <h4>Demanda</h4>
                <table>
                    <thead>
                        <th>Tipo de Mercado</th>
                        <th>Demanda Potencial</th>
                        <th>Demanda (%)</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mercado Meta</td>
                            <td><input class="input-editable" required type="text" name="demandaMeta" id="demandaMeta" min="0" step="0.05" value="<?= isset($_SESSION['proyecto']['idea']['demandaMeta']) ? $_SESSION['proyecto']['idea']['demandaMeta'] : ''; ?>"> * 0.50</td>
                            <td><input class="input-non-editable" readonly type="text" name="demandaMetaPorcentaje" id="demandaMetaPorcentaje"  min="0.00" step="0.05" max="100.00" maxlength="6" placeholder="demandaMeta * 0.50" value="<?= isset($_SESSION['proyecto']['idea']['demandaMetaPorcentaje']) ? $_SESSION['proyecto']['idea']['demandaMetaPorcentaje'] : ''; ?>"></td>
                        </tr>
                        <tr>
                            <td>Mercado Objetivo</td>
                            <td><input class="input-editable" required type="text" name="demandaObjetivo" id="demandaObjetivo" min="0" step="0.05" value="<?= isset($_SESSION['proyecto']['idea']['demandaObjetivo']) ? $_SESSION['proyecto']['idea']['demandaObjetivo'] : ''; ?>"> * 0.50</td>
                            <td><input class="input-non-editable" readonly  type="text" name="demandaObjetivoPorcentaje" id="demandaObjetivoPorcentaje"  min="0.00" step="0.05" max="100.00" maxlength="6" placeholder="demanda Objetivo * 0.50" value="<?= isset($_SESSION['proyecto']['idea']['demandaObjetivoPorcentaje']) ? $_SESSION['proyecto']['idea']['demandaObjetivoPorcentaje'] : ''; ?>"></td>
                        </tr>
                        <tr>
                            <td>Mercado Potencial</td>
                            <td><input class="input-editable" required type="text" name="demandaPotencial" id="demandaPotencial" min="0" step="0.05" value="<?= isset($_SESSION['proyecto']['idea']['demandaPotencial']) ? $_SESSION['proyecto']['idea']['demandaPotencial'] : ''; ?>"> * 0.50</td>
                            <td><input class="input-non-editable" readonly type="text" name="demandaPotencialPorcentaje" id="demandaPotencialPorcentaje"  min="0.00" step="0.05" max="100.00" maxlength="6" placeholder="demandaPotencial * 0.50" value="<?= isset($_SESSION['proyecto']['idea']['demandaPotencialPorcentaje']) ? $_SESSION['proyecto']['idea']['demandaPotencialPorcentaje'] : ''; ?>"></td>
                        </tr>
                    </tbody>
                </table>
                <h4>Benchmarking</h4>
                <table>
                    <thead>
                        <th>Atributos</th>
                        <th>Competencia1</th>
                        <th>Competencia2</th>
                        <th>Propuesta</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Costo</td>
                            <td><input class="input-editable" required type="text" name="costoCompetencia1" id="costoCompetencia1" min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['costoCompetencia1']) ? $_SESSION['proyecto']['idea']['costoCompetencia1'] : ''; ?>"></td>
                            <td><input class="input-editable" required type="text" name="costoCompetencia2" id="costoCompetencia2" min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['costoCompetencia2']) ? $_SESSION['proyecto']['idea']['costoCompetencia2'] : ''; ?>"></td>
                            <td><input class="input-editable" type="text" name="costoPropuesta" id="costoPropuesta" required min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['costoPropuesta']) ? $_SESSION['proyecto']['idea']['costoPropuesta'] : ''; ?>"></td>
                        </tr>
                        <tr>
                            <td>Función</td>
                            <td><textarea name="funcionCompetencia1" id="funcionCompetencia1"><?= isset($_SESSION['proyecto']['idea']['funcionCompetencia1']) ? $_SESSION['proyecto']['idea']['funcionCompetencia1'] : ''; ?></textarea></td>
                            <td><textarea name="funcionCompetencia2" id="funcionCompetencia2"><?= isset($_SESSION['proyecto']['idea']['funcionCompetencia2']) ? $_SESSION['proyecto']['idea']['funcionCompetencia2'] : ''; ?></textarea></td>
                            <td><textarea name="funcionPropuesta" id="funcionPropuesta"><?= isset($_SESSION['proyecto']['idea']['funcionPropuesta']) ? $_SESSION['proyecto']['idea']['funcionPropuesta'] : ''; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Calidad (select)</td>
                            <td><select class="select-editable" name="calidadCompetencia1" id="calidadCompetencia1" required>
                                    <option value="1" <?= isset($_SESSION['proyecto']['idea']['calidadCompetencia1']) && $_SESSION['proyecto']['idea']['calidadCompetencia1'] == 1 ? 'selected' : ''; ?>>Terrible</option>
                                    <option value="2" <?= isset($_SESSION['proyecto']['idea']['calidadCompetencia1']) && $_SESSION['proyecto']['idea']['calidadCompetencia1'] == 2 ? 'selected' : ''; ?>>Muy malo</option>
                                    <option value="3" <?= isset($_SESSION['proyecto']['idea']['calidadCompetencia1']) && $_SESSION['proyecto']['idea']['calidadCompetencia1'] == 3 ? 'selected' : ''; ?>>Regular</option>
                                    <option value="4" <?= isset($_SESSION['proyecto']['idea']['calidadCompetencia1']) && $_SESSION['proyecto']['idea']['calidadCompetencia1'] == 4 ? 'selected' : ''; ?>>Bueno</option>
                                    <option value="5" <?= isset($_SESSION['proyecto']['idea']['calidadCompetencia1']) && $_SESSION['proyecto']['idea']['calidadCompetencia1'] == 5 ? 'selected' : ''; ?>>Excelente</option>
                                </select></td>
                            <td><select class="select-editable" name="calidadCompetencia2" id="calidadCompetencia2" required>
                                    <option value="1" <?= isset($_SESSION['proyecto']['idea']['calidadCompetencia2']) && $_SESSION['proyecto']['idea']['calidadCompetencia2'] == 1 ? 'selected' : ''; ?>>Terrible</option>
                                    <option value="2" <?= isset($_SESSION['proyecto']['idea']['calidadCompetencia2']) && $_SESSION['proyecto']['idea']['calidadCompetencia2'] == 2 ? 'selected' : ''; ?>>Muy malo</option>
                                    <option value="3" <?= isset($_SESSION['proyecto']['idea']['calidadCompetencia2']) && $_SESSION['proyecto']['idea']['calidadCompetencia2'] == 3 ? 'selected' : ''; ?>>Regular</option>
                                    <option value="4" <?= isset($_SESSION['proyecto']['idea']['calidadCompetencia2']) && $_SESSION['proyecto']['idea']['calidadCompetencia2'] == 4 ? 'selected' : ''; ?>>Bueno</option>
                                    <option value="5" <?= isset($_SESSION['proyecto']['idea']['calidadCompetencia2']) && $_SESSION['proyecto']['idea']['calidadCompetencia2'] == 5 ? 'selected' : ''; ?>>Excelente</option>
                                </select></td>
                            <td><select class="select-editable" name="calidadPropuesta" id="calidadPropuesta" required>
                                    <option value="1" <?= isset($_SESSION['proyecto']['idea']['calidadPropuesta']) && $_SESSION['proyecto']['idea']['calidadPropuesta'] == 1 ? 'selected' : ''; ?>>Terrible</option>
                                    <option value="2" <?= isset($_SESSION['proyecto']['idea']['calidadPropuesta']) && $_SESSION['proyecto']['idea']['calidadPropuesta'] == 2 ? 'selected' : ''; ?>>Muy malo</option>
                                    <option value="3" <?= isset($_SESSION['proyecto']['idea']['calidadPropuesta']) && $_SESSION['proyecto']['idea']['calidadPropuesta'] == 3 ? 'selected' : ''; ?>>Regular</option>
                                    <option value="4" <?= isset($_SESSION['proyecto']['idea']['calidadPropuesta']) && $_SESSION['proyecto']['idea']['calidadPropuesta'] == 4 ? 'selected' : ''; ?>>Bueno</option>
                                    <option value="5" <?= isset($_SESSION['proyecto']['idea']['calidadPropuesta']) && $_SESSION['proyecto']['idea']['calidadPropuesta'] == 5 ? 'selected' : ''; ?>>Excelente</option>
                                </select></td>
                        </tr>
                    </tbody>
                </table>
            </article>
            <article>
                <h3>Proyección de ventas</h3>
                <table>
                    <thead>
                        <th></th>
                        <th>Meta (Año 1)</th>
                        <th>Objetivo (Año 2)</th>
                        <th>Potencial (Año 3)</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Proyección de Ventas</td>
                            <td><input class="input-non-editable" readonly type="text" name="ventasAnio1" id="ventasAnio1" required min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['ventasAnio1']) ? $_SESSION['proyecto']['idea']['ventasAnio1'] : ''; ?>"></td>
                            <td><input class="input-non-editable" readonly type="text" name="ventasAnio2" id="ventasAnio2" required min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['ventasAnio2']) ? $_SESSION['proyecto']['idea']['ventasAnio2'] : ''; ?>"></td>
                            <td><input class="input-non-editable" readonly type="text" name="ventasAnio3" id="ventasAnio3" required min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['ventasAnio3']) ? $_SESSION['proyecto']['idea']['ventasAnio3'] : ''; ?>"></td>
                        </tr>
                        <tr>
                            <td>Costo</td>
                            <td><input class="input-non-editable" readonly  type="text" name="costoAnio1" id="costoAnio1" placeholder="costoPropuesta" min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['costoAnio1']) ? $_SESSION['proyecto']['idea']['costoAnio1'] : ''; ?>"></td>
                            <td><input class="input-non-editable" readonly  type="text" name="costoAnio2" id="costoAnio2" placeholder="costoPropuesta" min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['costoAnio2']) ? $_SESSION['proyecto']['idea']['costoAnio2'] : ''; ?>"></td>
                            <td><input class="input-non-editable" readonly  type="text" name="costoAnio3" id="costoAnio3" placeholder="costoPropuesta" min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['costoAnio3']) ? $_SESSION['proyecto']['idea']['costoAnio3'] : ''; ?>"></td>
                        </tr>
                        <tr>
                            <td>Venta</td>
                            <td><input class="input-non-editable" readonly  type="text" name="ventaAnio1" id="ventaAnio1" placeholder="(Costo * Ventas)" min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['ventaAnio1']) ? $_SESSION['proyecto']['idea']['ventaAnio1'] : ''; ?>"></td>
                            <td><input class="input-non-editable" readonly  type="text" name="ventaAnio2" id="ventaAnio2" placeholder="(Costo * Ventas)" min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['ventaAnio2']) ? $_SESSION['proyecto']['idea']['ventaAnio2'] : ''; ?>"></td>
                            <td><input class="input-non-editable" readonly  type="text" name="ventaAnio3" id="ventaAnio3" placeholder="(Costo * Ventas)" min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['ventaAnio3']) ? $_SESSION['proyecto']['idea']['ventaAnio3'] : ''; ?>"></td>
                        </tr>
                        <tr>
                            <td>Incremento (%)</td>
                            <td><input class="input-non-editable" readonly  type="text" name="incrementoAnio1" id="incrementoAnio1" min="0.00" step="0.05" max="100.00" maxlength="6" placeholder="0" value="<?= isset($_SESSION['proyecto']['idea']['incrementoAnio1']) ? $_SESSION['proyecto']['idea']['incrementoAnio1'] : ''; ?>"></td>
                            <td><input class="input-non-editable" readonly  type="text" name="incrementoAnio2" id="incrementoAnio2" min="0.00" step="0.05" max="100.00" maxlength="6" placeholder="(Año2-Año1)/Año1*100" value="<?= isset($_SESSION['proyecto']['idea']['incrementoAnio2']) ? $_SESSION['proyecto']['idea']['incrementoAnio2'] : ''; ?>"></td>
                            <td><input class="input-non-editable" readonly  type="text" name="incrementoAnio3" id="incrementoAnio3" min="0.00" step="0.05" max="100.00" maxlength="6" placeholder="(Año3-Año2)/Año2*100" value="<?= isset($_SESSION['proyecto']['idea']['incrementoAnio3']) ? $_SESSION['proyecto']['idea']['incrementoAnio3'] : ''; ?>"></td>
                        </tr>
                    </tbody>
                </table>
                <h3>Presupuesto del Negocio</h3>
                <p>Mercado objetivo - Mercado Meta = Presupuesto del Negocio</p>
                <input class="input-non-editable" readonly  type="text" name="presupuestoNegocio" id="presupuestoNegocio" min="0" step="1" value="<?= isset($_SESSION['proyecto']['idea']['presupuestoNegocio']) ? $_SESSION['proyecto']['idea']['presupuestoNegocio'] : ''; ?>">

            </article>
            <button type="submit" class="btn">Siguiente</button>
        </form>
    </main>
</body>

<script>
    //calculos en funciones, cargar funciones al cargar el documento
    document.addEventListener('DOMContentLoaded', function() {
        const demandaMeta = document.getElementById('demandaMeta');
        const demandaMetaPorcentaje = document.getElementById('demandaMetaPorcentaje');
        const demandaObjetivo = document.getElementById('demandaObjetivo');
        const demandaObjetivoPorcentaje = document.getElementById('demandaObjetivoPorcentaje');
        const demandaPotencial = document.getElementById('demandaPotencial');
        const demandaPotencialPorcentaje = document.getElementById('demandaPotencialPorcentaje');

        const costoCompetencia1 = document.getElementById('costoCompetencia1');
        const costoCompetencia2 = document.getElementById('costoCompetencia2');
        const costoPropuesta = document.getElementById('costoPropuesta');

        const ventasAnio1 = document.getElementById('ventasAnio1');
        const ventasAnio2 = document.getElementById('ventasAnio2');
        const ventasAnio3 = document.getElementById('ventasAnio3');

        const costoAnio1 = document.getElementById('costoAnio1');
        const costoAnio2 = document.getElementById('costoAnio2');
        const costoAnio3 = document.getElementById('costoAnio3');

        const ventaAnio1 = document.getElementById('ventaAnio1');
        const ventaAnio2 = document.getElementById('ventaAnio2');
        const ventaAnio3 = document.getElementById('ventaAnio3');

        const incrementoAnio1 = document.getElementById('incrementoAnio1');
        const incrementoAnio2 = document.getElementById('incrementoAnio2');
        const incrementoAnio3 = document.getElementById('incrementoAnio3');

        const presupuestoNegocio = document.getElementById('presupuestoNegocio');

        function calculateDemandaMeta() {
            demandaMetaPorcentaje.value = (demandaMeta.value * 0.50).toFixed(2);
        }

        function calculateDemandaObjetivo() {
            demandaObjetivoPorcentaje.value = (demandaObjetivo.value * 0.50).toFixed(2);
        }

        function calculateDemandaPotencial() {
            demandaPotencialPorcentaje.value = (demandaPotencial.value * 0.50).toFixed(2);
        }

        function calculateCostoPropuesta() {
            costoAnio1.value = costoPropuesta.value;
            costoAnio2.value = costoPropuesta.value;
            costoAnio3.value = costoPropuesta.value;
        }

        function calculateVentaAnio1() {
            ventaAnio1.value = (costoAnio1.value * ventasAnio1.value).toFixed(2);
        }

        function calculateVentaAnio2() {
            ventaAnio2.value = (costoAnio2.value * ventasAnio2.value).toFixed(2);
            incrementoAnio2.value = (((ventasAnio2.value - ventasAnio1.value) / ventasAnio1.value) * 100).toFixed(2);
        }

        function calculateVentaAnio3() {
            ventaAnio3.value = (costoAnio3.value * ventasAnio3.value).toFixed(2);
            incrementoAnio3.value = (((ventasAnio3.value - ventasAnio2.value) / ventasAnio2.value) * 100).toFixed(2);
        }

        function calculatePresupuestoNegocio() {
            presupuestoNegocio.value = (ventaAnio2.value - ventaAnio1.value).toFixed(2);
        }

        //proyeccion de ventas meta, objetivo y potencial es demanda (50%)
        function obtenerDemanda() {
            ventasAnio1.value = demandaMetaPorcentaje.value;
            ventasAnio2.value = demandaObjetivoPorcentaje.value;
            ventasAnio3.value = demandaPotencialPorcentaje.value;
        }

        //propuesta es promedio entre competencia 1 y 2
        function obtenerCostoPropuesta() {
            costoPropuesta.value = ((parseInt(costoCompetencia1.value) + parseInt(costoCompetencia2.value)) / 2).toFixed(2);
        }

        

        demandaMeta.addEventListener('input', calcularTodo);
        demandaObjetivo.addEventListener('input', calcularTodo);
        demandaPotencial.addEventListener('input', calcularTodo);
        costoPropuesta.addEventListener('input', calcularTodo);
        costoCompetencia1.addEventListener('input', calcularTodo);
        costoCompetencia2.addEventListener('input', calcularTodo);
        ventasAnio1.addEventListener('input', calcularTodo);
        ventasAnio2.addEventListener('input', calcularTodo);
        ventasAnio3.addEventListener('input', calcularTodo);
        calculatePresupuestoNegocio();

        function calcularTodo() {
            calculateDemandaMeta();
            calculateDemandaObjetivo();
            calculateDemandaPotencial();
            obtenerDemanda();
            obtenerCostoPropuesta();
            calculateCostoPropuesta();
            calculateVentaAnio1();
            calculateVentaAnio2();
            calculateVentaAnio3();
            calculatePresupuestoNegocio();
            
        }

        calcularTodo();
    });


</script>
    
