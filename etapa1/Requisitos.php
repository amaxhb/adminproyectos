<?php
require_once '../session.php';

//guardar los datos del formulario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['etapa1']['requisitos'] = $_POST;
    header('Location: ../etapa1/VerificacionAlcance.php');
    exit;
}

const headerTable = array('Actividad', 'Requisito', 'Descripcion', 'Responsables');

?>
<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <?php include_once './header1.php'; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="requisitosForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>
            <article>
            <table>
                <thead></thead>
            </table>
            </article>
            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>

</body>