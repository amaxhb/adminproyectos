<?php
require_once '../session.php';


//guardar datos enviados por el usuario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa4']['Riesgos'] = $_POST;
    $_SESSION['message'] = array(
        'type' => 'success',
        'text' => 'Riesgos  guardada correctamente.'
    );

    header('Location: ../etapa4/ActaCierreProyecto.php');
    exit;
}

const tableHeader = array('Riesgo', 'Impacto', 'Plan de Accion', 'Tiempo', 'Costo');
const riesgos = array('Tecnologico', 'Economico', 'Politico', 'Social', 'Ambiental');
?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <header>
            <h1>Riesgos</h1>
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


        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="riesgosForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
            <article>
                <table>
                    <thead>
                        <?php foreach (tableHeader as $header) : ?>
                            <th><?php echo $header; ?></th>
                        <?php endforeach; ?>
                    </thead>
                
                <tbody>
                    <?php $i = 0; ?>
                    <?php foreach (riesgos as $riesgo) : ?>
                        <tr>
                            <td>
                                <input class="input-non-editable" readonly type="text" name="riesgo<?php echo $i; ?>" value="<?php echo $riesgo; ?>">
                            </td>
                        <td><textarea name="impacto<?php echo $i; ?>" id="impacto<?php echo $i; ?>"><?php if (isset($_SESSION['proyecto']['etapa4']['Riesgos']["impacto$i"])) {
                                                                                                                echo $_SESSION['proyecto']['etapa4']['Riesgos']["impacto$i"];
                                                                                                            } ?></textarea></td>
                            <td><textarea name="planAccion<?php echo $i; ?>" id="planAccion<?php echo $i; ?>"><?php if (isset($_SESSION['proyecto']['etapa4']['Riesgos']["planAccion$i"])) {
                                                                                                                    echo $_SESSION['proyecto']['etapa4']['Riesgos']["planAccion$i"];
                                                                                                                } ?></textarea></td>
                            <td><input class="input-editable" type="text" name="tiempo<?php echo $i; ?>" id="tiempo<?php echo $i; ?>" value="<?php if (isset($_SESSION['proyecto']['etapa4']['Riesgos']["tiempo$i"])) {
                                                                                                                        echo $_SESSION['proyecto']['etapa4']['Riesgos']["tiempo$i"];
                                                                                                                    } ?>"></td>
                            <td><input class="input-editable" type="text" name="costo<?php echo $i; ?>" id="costo<?php echo $i; ?>" value="<?php if (isset($_SESSION['proyecto']['etapa4']['Riesgos']["costo$i"])) {
                                                                                                                        echo $_SESSION['proyecto']['etapa4']['Riesgos']["costo$i"];
                                                                                                                    } ?>"></td>

                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td>Total de Tiempo y costo</td>
                        <td></td>
                        <td></td>
                        <td><input class="input-non-editable" readonly type="text" name="totalTiempo" id="totalTiempo" value="<?php if (isset($_SESSION['proyecto']['etapa4']['Riesgos']['totalTiempo'])) {
                                                                                                                            echo $_SESSION['proyecto']['etapa4']['Riesgos']['totalTiempo'];
                                                                                                                        } ?>"></td>
                        <td><input class="input-non-editable" readonly type="text" name="totalCosto" id="totalCosto" value="<?php if (isset($_SESSION['proyecto']['etapa4']['Riesgos']['totalCosto'])) {
                                                                                                                            echo $_SESSION['proyecto']['etapa4']['Riesgos']['totalCosto'];
                                                                                                                        } ?>">

                    </tr>
                </tbody>
                </table>
            </article>
            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tiempo = document.querySelectorAll('input[name^="tiempo"]');
            const costo = document.querySelectorAll('input[name^="costo"]');
            const totalTiempo = document.getElementById('totalTiempo');
            const totalCosto = document.getElementById('totalCosto');

            tiempo.forEach((element, index) => {
                element.addEventListener('input', () => {
                    let sum = 0;
                    tiempo.forEach((element, index) => {
                        sum += parseInt(element.value) || 0;
                    });
                    totalTiempo.value = sum;
                });
            });

            costo.forEach((element, index) => {
                element.addEventListener('input', () => {
                    let sum = 0;
                    costo.forEach((element, index) => {
                        sum += parseInt(element.value) || 0;
                    });
                    totalCosto.value = sum;
                });
            });

            

            evento = new Event('input');
        });
    </script>

</body>