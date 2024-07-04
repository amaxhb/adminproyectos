<?php
require_once '../session.php';

//guardar los datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa1']['gestionInteresados'] = $_POST;
    $_SESSION['message'] = [
        'text' => 'La gestión de interesados se ha guardado correctamente.',
        'type' => 'success'
    ];

    header('Location: ../etapa1/PlanDireccionProyecto.php');
    exit;
}



const interesados = array('Líder del proyecto', 'Director Operativo', 'Director de Tecnología', 'Director de Recursos Humanos', 'Director de Finanzas', 'Director Comercial');
const nivelCompromiso = array('Desconoce', 'Se resiste', 'Neutral', 'Apoya', 'Lidera');
const poderInfluencia = array(
    "B" => "Bajo",
    "A" => "Alto"
);
const Interes = array(
    "B" => "Bajo",
    "A" => "Alto"
);

const Estrategia = array(
    "A+A" => "Gestionar de Cerca",
    "A+B" => "Mantener satisfecho",
    "B+A" => "Informar",
    "B+B" => "Monitorear"
);


$selectRange = range(1, 10);
?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <?php include_once './header1.php'; ?>


        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="gestionInteresadosForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
            
            <?php $name = 'interesadoSelect'; ?>
            <select class="select-editable" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
                <option value="">Selecciona un interesado</option>
                <?php for ($i = 1; $i < count($selectRange); $i++) : ?>
                    <option value="<?php echo $i; ?>" <?php if (isset($_SESSION['proyecto']['etapa1']['gestionInteresados'])) {
                                                                        if ($_SESSION['proyecto']['etapa1']['gestionInteresados'][$name] == $i) {
                                                                            echo 'selected';
                                                                        } 
                                                                    } ?>><?php echo $i; ?> </option>

                <?php endfor; ?>
            </select>
            </div>
            <table>
                <thead>
                    <th rowspan="2">Interesado</th>
                    <th rowspan="2">Descripcion</th>
                    <th colspan="5">Compromiso</th>
                    <th rowspan="2">Poder / Influencia</th>
                    <th rowspan="2">Interés</th>
                    <th rowspan="2">Estrategia</th>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <?php foreach (nivelCompromiso as $compromiso) : ?>
                            <td><?php echo $compromiso; ?></td>
                        <?php endforeach; ?>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php $i = 0; ?>
                    <?php foreach (interesados as $interesado) : ?>
                        <tr>
                            <td><?php echo $interesado; ?> <br>
                                Nombre: <input class="input-editable" type="text" name="<?php echo $i; ?>Nombre" id="<?php echo $i; ?>Nombre" value="<?php if (isset($_SESSION['proyecto']['etapa1']['gestionInteresados'])) {
                                                                                                                                                            echo $_SESSION['proyecto']['etapa1']['gestionInteresados']["$i" . 'Nombre'];
                                                                                                                                                        } ?>">
                            </td>
                            <td>
                                <textarea name="<?php echo $i; ?>Descripcion" id="<?php echo $i; ?>Descripcion"><?php if (isset($_SESSION['proyecto']['etapa1']['gestionInteresados'])) {
                                                                                                                    echo $_SESSION['proyecto']['etapa1']['gestionInteresados']["$i" . 'Descripcion'];
                                                                                                                } ?> </textarea> <br>
                            </td>
                            <?php foreach (nivelCompromiso as $compromiso) : ?>
                                <td><input type="radio" name="<?php echo $i; ?>Compromiso" id="<?php echo $i; ?>Compromiso" value="<?php echo $compromiso; ?>"></td>
                            <?php endforeach; ?>
                            <td>
                                <select class="select-editable" name="<?php echo $i; ?>Poder" id="<?php echo $i; ?>Poder">
                                    <option value="">Seleccionar</option>
                                    <?php foreach (poderInfluencia as $key => $influencia) : ?>
                                        <option value="<?php echo $key; ?>" <?php if (isset($_SESSION['proyecto']['etapa1']['gestionInteresados'])) {
                                                                                if ($_SESSION['proyecto']['etapa1']['gestionInteresados']["$i" . 'Poder'] === $key) {
                                                                                    echo 'selected';
                                                                                }
                                                                            } ?>><?php echo $key; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select class="select-editable" name="<?php echo $i; ?>Interes" id="<?php echo $i; ?>Interes">
                                    <option value="">Seleccionar</option>
                                    <?php foreach (Interes as $key => $interes) : ?>
                                        <option value="<?php echo $key; ?>" <?php if (isset($_SESSION['proyecto']['etapa1']['gestionInteresados'])) {
                                                                                if ($_SESSION['proyecto']['etapa1']['gestionInteresados']["$i" . 'Interes'] === $key) {
                                                                                    echo 'selected';
                                                                                }
                                                                            } ?>><?php echo $key; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select class="select-non-editable" name="<?php echo $i; ?>Estrategia" id="<?php echo $i; ?>Estrategia">
                                    <option value="">*</option>
                                    <?php foreach (Estrategia as $key => $estrategia) : ?>
                                        <option value="<?php echo $key; ?>" <?php if (isset($_SESSION['proyecto']['etapa1']['gestionInteresados'])) {
                                                                                if ($_SESSION['proyecto']['etapa1']['gestionInteresados']["$i" . 'Estrategia'] === $key) {
                                                                                    echo 'selected';
                                                                                }
                                                                            } ?>><?php echo $estrategia; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn">Guardar</button>
        </form>

        <?php include_once '../layouts/footer.php'; ?>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                //autoseleccionar estrategia de acuerdo al seleccionar poder e interes
                const selectPoder = document.querySelectorAll('select[name$="Poder"]');
                const selectInteres = document.querySelectorAll('select[name$="Interes"]');

                selectPoder.forEach((select) => {
                    select.addEventListener('change', () => {
                        const interesado = select.name.replace('Poder', '');
                        const estrategiaSelect = document.querySelector(`select[name="${interesado}Estrategia"]`);
                        const poderValue = select.value;
                        const interesSelect = document.querySelector(`select[name="${interesado}Interes"]`);
                        const interesValue = interesSelect.value;

                        if (poderValue === 'A' && interesValue === 'A') {
                            estrategiaSelect.value = 'A+A';
                        } else if (poderValue === 'A' && interesValue === 'B') {
                            estrategiaSelect.value = 'A+B';
                        } else if (poderValue === 'B' && interesValue === 'A') {
                            estrategiaSelect.value = 'B+A';
                        } else if (poderValue === 'B' && interesValue === 'B') {
                            estrategiaSelect.value = 'B+B';
                        } else {
                            estrategiaSelect.value = '';
                        }
                    });
                });

                selectInteres.forEach((select) => {
                    select.addEventListener('change', () => {
                        const interesado = select.name.replace('Interes', '');
                        const estrategiaSelect = document.querySelector(`select[name="${interesado}Estrategia"]`);
                        const interesValue = select.value;
                        const poderSelect = document.querySelector(`select[name="${interesado}Poder"]`);
                        const poderValue = poderSelect.value;

                        if (poderValue === 'A' && interesValue === 'A') {
                            estrategiaSelect.value = 'A+A';
                        } else if (poderValue === 'A' && interesValue === 'B') {
                            estrategiaSelect.value = 'A+B';
                        } else if (poderValue === 'B' && interesValue === 'A') {
                            estrategiaSelect.value = 'B+A';
                        } else if (poderValue === 'B' && interesValue === 'B') {
                            estrategiaSelect.value = 'B+B';
                        } else {
                            estrategiaSelect.value = '';
                        }
                    });
                });
            });
        </script>

    </main>

</body>