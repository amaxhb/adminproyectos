<?php
#login.php

require_once "../session.php";
// Verifica si el usuario ya está logueado
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php'); 
    exit;
}

$username = $password = "";
$username_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor, introduce tu usuario.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, introduce tu contraseña.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Valida credenciales
    if (empty($username_err) && empty($password_err)) {
        $user_data = file_get_contents("users.json");
        $users = json_decode($user_data, true);

        foreach ($users as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password'])) {
                // Iniciar sesion
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $username;
                header("location: index.php");
                exit;
            }
        }

        // Si el usuario no se encuentra o la contraseña no coincide
        $login_err = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<?php include_once '../layouts/head.php'; ?>    
<body>
    <div class="container">
        <h2>Iniciar sesión</h2>
        <p>Rellena tus credenciales para iniciar sesión.</p>
        <?php 
        if (!empty($login_err)) {
            echo '<div class="error">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Usuario</label>
                <input class="input-editable" type="text" name="username" value="<?php echo $username; ?>">
                <span><?php echo $username_err; ?></span>
            </div>    
            <div>
                <label>Contraseña</label>
                <input class="input-editable" type="password" name="password">
                <span><?php echo $password_err; ?></span>
            </div>
            <div>
                <button type="submit" class="btn">Iniciar sesión</button>
            </div>
            <p>No tienes una cuenta? <a href="register.php">Registrate ahora</a>.</p>
        </form>
    </div>
</body>
</html>
