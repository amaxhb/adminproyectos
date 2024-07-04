<header>
    <nav>
        <ul>
            <li><a href="../index/index.php">Inicio</a></li>
            <li><a href="../idea/ideaNegocio.php">Idea Del Negocio</a></li>
            <li><a href="../etapa0/planOperativo.php">Etapa 0</a></li>
            <li><a href="../etapa1/ActaConstitucion.php">Etapa 1</a></li>
            <li><a href="../etapa2/Ejecucion.php">Etapa 2</a></li>
            <li><a href="../etapa3/ChecklistVerificacionCritica.php">Etapa 3</a></li>
            <li><a href="../etapa4/ControlCostos.php">Etapa 4</a></li>
        </ul>
    </nav>

    <!-- div con display para mostrar mensajes -->
    <?php if (isset($_SESSION['message'])) : ?>
        <div class="message_<?php echo $_SESSION['message']['type']; ?>">
            <p><?php echo $_SESSION['message']['text']; ?></p>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <a id="logout" href="../index/logout.php">Cerrar sesión</a>
</header>

