<?php
require_once "../session.php";
// Define variables and initialize with empty values

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validar nombre de usuario
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor, ingrese un nombre de usuario.";
    } else {
        // Leer el archivo JSON
        $data = file_get_contents("users.json");
        $users = json_decode($data, true);

        // Verificar que el nombre de usuario no exista
        $exists = false;
        foreach ($users as $user) {
            if ($user['username'] === trim($_POST["username"])) {
                $exists = true;
                break;
            }
        }

        if ($exists) {
            $username_err = "Este nombre de usuario ya está en uso.";
        } else {
            $username = trim($_POST["username"]);
        }
    }

    // Validar contraseña
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, ingrese su contraseña.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Verificar errores antes de insertar en el archivo
    if (empty($username_err) && empty($password_err)) {
        // Preparar los datos de usuario
        $new_user = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT) // Encriptar la contraseña
        ];

        // Agregar nuevo usuario al array
        $users[] = $new_user;

        // Guardar en archivo JSON
        file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));
        
        // Redirigir al login
        header("location: login.php");
        exit;
    }
}
?>

<?php include_once '../layouts/head.php'; ?>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        <p>Por favor, llene este formulario para crear una cuenta.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Nombre de usuario</label>
                <input class="input-editable" type="text" name="username" value="<?php echo $username; ?>">
                <span><?php echo $username_err; ?></span>
            </div>    
            <div>
                <label>Contraseña</label>
                <input class="input-editable" type="password" name="password">
                <span><?php echo $password_err; ?></span>
            </div>
            <div>
                <button type="submit" class="btn">Registrarse</button>
            </div>
            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
        </form>
    </div>
</body>
</html>


