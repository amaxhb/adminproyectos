<?php
require_once '../session.php';

//guardar los datos del formulario en la sesión


?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <?php include_once '../layouts/header.php'; ?>
    <main>
        <header>
            <h1>Verificación del Alcance</h1>
            <nav>
                <ul>
                    <li><a href="./ActaConstitucion.php">Acta Constitución</a></li>
                    <li><a href="./GestionInteresados.php">Gestión de Interesados</a></li>
                    <li><a href="./PlanDireccionProyecto.php">Plan de Dirección de Proyecto</a></li>
                    <li><a href="./GestionAlcance.php">Gestión de Alcance</a></li>
                </ul><ul>
                    <li><a href="./Requisitos.php">Requisitos</a></li>
                    <li><a href="./Cronograma.php">Cronograma</a></li>
            </nav>
        </header>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="verificacionAlcanceForm" class="form">


<button type="submit" class="btn">Guardar</button>
</form>
