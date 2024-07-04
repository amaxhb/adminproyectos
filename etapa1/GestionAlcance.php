<?php
require_once '../session.php';

//guardar los datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa1']['gestionAlcance'] = $_POST;
    $_SESSION['message'] = [
        'text' => 'La gestión de alcance se ha guardado correctamente',
        'type' => 'success'
    ];
    header('Location: ./ActaConstitucion.php');
    exit;
}

$entregables = array();
foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    if (strpos($key, 'E0_entregable') !== false || strpos($key, 'E1_entregable') !== false || strpos($key, 'E2_entregable') !== false || strpos($key, 'E3_entregable') !== false || strpos($key, 'E4_entregable') !== false) {
        $entregables[] = $value;
    }
}

$tareas = array();
foreach ($_SESSION['proyecto']['etapa0']['planGeneralTrabajo'] as $key => $value) {
    if (strpos($key, 'E0_actividad') !== false || strpos($key, 'E1_actividad') !== false || strpos($key, 'E2_actividad') !== false || strpos($key, 'E3_actividad') !== false || strpos($key, 'E4_actividad') !== false) {
        $tareas[] = $value;
    }
}

$intereados = array('Cliente', 'Patrocinador', 'Líder de Proyecto', 'Director Tecnología', 'Director de Recursos Humanos', 'Director Operativo', 'Director de Finanzas', 'Director Comercial', 'Contratados');


?>


<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
    <?php include_once './header1.php'; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="gestionAlcanceForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
            <article>
                <table>
                    <thead>
                        <th>Proyecto No.</th>
                        <th>Fecha de Envio</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input class="input-editable" type="text" name="proyectoNo" id="proyectoNo" value="<?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['proyectoNo'] : ''; ?>"></td>
                            <td><input class="input-editable" type="date" name="fechaEnvio" id="fechaEnvio" value="<?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['fechaEnvio'] : ''; ?>"></td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <th>
                        Objetivos del Proyecto
                    </th>
                    <tbody>
                        <tr>
                            <td><textarea name="objetivosProyecto" id="objetivosProyecto" placeholder="Redacción..."><?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['objetivosProyecto'] : ''; ?></textarea></td>
                        </tr>
                    </tbody>
                </table>
            </article>

            <article>
                Entregables
                <table>
                    <thead>
                        <th>No. </th>
                        <th>Entregable</th>
                        <th>Descripcion</th>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        foreach ($entregables as $key => $entregable) :
                            if (!empty($entregable)) {
                                $count++;
                        ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    <td><textarea class="input-non-editable" name="entregable_<?php echo $key; ?>" id="entregable_<?php echo $key; ?>"><?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['entregable_' . $key] : $entregable; ?></textarea></td>
                                    <td><textarea name="descripcionEntregable_<?php echo $key; ?>" id="descripcionEntregable_<?php echo $key; ?>" placeholder="Redacción..."><?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['descripcionEntregable_' . $key] : ''; ?></textarea></td>
                                </tr>
                        <?php
                            }
                        endforeach;
                        ?>

                        <?php if ($count === 0) : ?>
                            <tr>
                                <td colspan="2">No hay entregables disponibles.</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </article>

            <article>
                Tareas
                <table>
                    <thead>
                        <th>No. Tarea</th>
                        <th>Tarea</th>
                        <th>Descripcion</th>
                        <th>No. Entregable (select) </th>
                    </thead>

                    <tbody>
                        
                        <?php foreach ($tareas as $key => $tarea) : ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><textarea class="input-non-editable" readonly  type="text" name="tarea_<?php echo $key; ?>" id="tarea_<?php echo $key; ?>"><?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['tarea_' . $key] : $tarea; ?></textarea></td>
                                <td><textarea name="descripcionTarea_<?php echo $key; ?>" id="descripcionTarea_<?php echo $key; ?>" placeholder="Redacción..."><?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['descripcionTarea_' . $key] : ''; ?></textarea></td>
                                <td>
                                    <select class="select-editable" name="entregableTarea_<?php echo $key; ?>" id="entregableTarea_<?php echo $key; ?>">
                                        <option value="">Selecciona una opción</option>
                                        <option value="0">No Aplica</option>
                                        <?php
                                        $count = 0;
                                        foreach ($entregables as $key => $entregable) :
                                            if (!empty($entregable)) {
                                                $count++;
                                        ?>
                                                <option value="<?php echo $key; ?>" <?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) && $_SESSION['proyecto']['etapa1']['gestionAlcance']['entregableTarea_' . $key] == $key ? 'selected' : ''; ?>><?php echo $key + 1; ?></option>
                                        <?php
                                            }
                                        endforeach;
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach;  ?>
                    </tbody>
                </table>
            </article>

            <article>
                Fuera de Alcance
                <table>
                    <tbody>
                        <tr>
                            <td>Este proyecto no lograra lo siguiente</td>
                            <td><textarea name="fueraAlcance" id="fueraAlcance" placeholder="Redacción..."><?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['fueraAlcance'] : ''; ?></textarea></td>
                        </tr>

                    </tbody>
                </table>
            </article>

            <article>
                Supuestos
                <table>
                    <tbody>
                        <tr>
                            <td>Supuestos del Proyecto</td>
                            <td><textarea name="supuestos" id="supuestos" placeholder="Redacción..."><?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['supuestos'] : ''; ?></textarea></td>
                        </tr>
                    </tbody>
                </table>
            </article>

            <article>
                Restricciones
                <table>
                    <tbody>
                        <tr>
                            <td>Restricciones del Proyecto</td>
                            <td><textarea name="restricciones" id="restricciones" placeholder="Redacción..."><?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['restricciones'] : ''; ?></textarea></td>
                        </tr>
                    </tbody>
                </table>

            </article>

            <article>
                Aprobaciones
                <table>
                    <thead>
                        <td>Intereados (Titulo y Nombre)</td>
                        <td>Papel de Interesados (descripción de puesto)</td>
                        <td>Fecha de Presentación para Aprobación</td>
                        <td>Fecha de Recepción de Aprobación</td>
                    </thead>
                    <tbody>
                        <?php foreach ($intereados as $key => $interesado) :?>
                                <tr>
                                    <td>
                                        Título: <input class="input-non-editable" readonly  type="text" name="interesado_<?php echo $key; ?>" id="interesado_<?php echo $key; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['interesado_' . $key] : $interesado; ?>">
                                        <br>
                                        Nombre: <input class="input-editable" type="text" name="nombreInteresado_<?php echo $key; ?>" id="nombreInteresado_<?php echo $key; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['nombreInteresado_' . $key] : ''; ?>">
                                    </td>
                                    <td><textarea class="input-editable" type="text" name="papelInteresado_<?php echo $key; ?>" id="papelInteresado_<?php echo $key; ?>"><?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['papelInteresado_' . $key] : ''; ?></textarea></td>
                                    <td><input class="input-editable" type="date" name="fechaPresentacion_<?php echo $key; ?>" id="fechaPresentacion_<?php echo $key; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['fechaPresentacion_' . $key] : ''; ?>"></td>
                                    <td><input class="input-editable" type="date" name="fechaRecepcion_<?php echo $key; ?>" id="fechaRecepcion_<?php echo $key; ?>" value="<?php echo isset($_SESSION['proyecto']['etapa1']['gestionAlcance']) ? $_SESSION['proyecto']['etapa1']['gestionAlcance']['fechaRecepcion_' . $key] : ''; ?>"></td>
                                </tr>
                        <?php endforeach; ?>

                        <?php if ($count === 0) : ?>
                            <tr>
                                <td colspan="2">No hay interesados disponibles.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </article>

            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>

</body>