<?php
require_once '../session.php';
//guardar datos enviados por el usuario en la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['proyecto']['preguntas'] = $_POST;
    //save image on ../data/usersImages/{username}.png
    $filename = '../data/usersImages/' . $_SESSION['username'] . '.png';
    if (file_exists($filename)) {
        unlink($filename);
    }

    move_uploaded_file($_FILES['foto']['tmp_name'], $filename);
    header('Location: ../idea/ideaNegocio.php');
}


?>

<?php include_once '../layouts/head.php'; ?>

<body>
    <header>
        
    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="../idea/ideaNegocio.php">Idea Del Negocio</a></li>
            <li><a href="../etapa0/planOperativo.php">Etapa 0</a></li>
            <li><a href="../etapa1/ActaConstitucion.php">Etapa 1</a></li>
            <li><a href="../etapa2/Ejecucion.php">Etapa 2</a></li>
            <li><a href="../etapa3/ChecklistVerificacionCritica.php">Etapa 3</a></li>
            <li><a href="../etapa4/ControlCostos.php">Etapa 4</a></li>
        </ul>
    </nav>
    </header>
    
    <main>
        <header>
        <h1>Inicio</h1>
        </header>
        <section>
            <article>
            <h2>¡Bievenido!</h2>
   
            </article>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="planOperativoForm" class="form" enctype="multipart/form-data" >
                <label for="iQue">¿Qué harás?</label>
                <textarea name="iQue" id="iQue"><?php echo $_SESSION['proyecto']['preguntas']['iQue'];?></textarea>
                <label for="iPara">¿Para qué lo harás?</label>
                <textarea name="iPara" id="iPara"><?php echo $_SESSION['proyecto']['preguntas']['iPara'];?></textarea>
                <label for="iCon">¿Con qué lo harás?</label>
                <textarea name="iCon" id="iCon"><?php echo $_SESSION['proyecto']['preguntas']['iCon'];?></textarea>
                <label for="iComo">¿Cómo lo harás?</label>
                <textarea name="iComo" id="iComo"><?php echo $_SESSION['proyecto']['preguntas']['iComo'];?></textarea>
                <button type="submit" class="btn">Inicio</button>
                //foto para logotipo
                <input type="file" name="foto" id="foto" accept="image/*" required>
            </form>
        </section>
    </main>
    <footer>

    </footer>
</body>

</html>
