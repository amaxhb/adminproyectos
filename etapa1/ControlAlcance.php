<?php
require_once '../session.php';

//guardar los datos del formulario en la sesión


?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <?php include_once './header1.php'; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="controlAlcanceForm" class="form">
        <?php include_once '../layouts/plantillaLogo.php'; ?>


            <button type="submit" class="btn">Guardar</button>
        </form>

    </main>

</body>