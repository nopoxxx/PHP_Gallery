<?php
if (isset($_SESSION['user_id'])) {
    header("Location: ?page=gallery");
    exit();
}

$conn = connectSQL();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: ?page=gallery");
            exit();
        } else {
            $error_message = "Неверный пароль.";
        }
    } else {
        $error_message = "Пользователь не найден.";
    }
}

$conn->close();
?>
<div class="auth">
<h2>Вход</h2>

<?php
if (isset($error_message)) {
    echo "<p class='error-msg'>$error_message</p>";
}
?>

<form action="" method="POST">
    <label for="username">Имя пользователя:</label>
    <input type="text" name="username" required><br><br>

    <label for="password">Пароль:</label>
    <input type="password" name="password" required><br><br>

    <button type="submit">Войти</button>
</form>
</div>