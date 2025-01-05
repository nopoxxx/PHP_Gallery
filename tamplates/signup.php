<?php

$conn = connectSQL();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ?page=sign-in");
    } else {
        echo $conn->error;
    }
}

$conn->close();
?>
<div class="auth">
<h2>Регистрация</h2>
<form action="" method="POST">
    <label for="username">Имя пользователя:</label>
    <input type="text" name="username" required><br><br>

    <label for="password">Пароль:</label>
    <input type="password" name="password" required><br><br>

    <button type="submit">Зарегистрироваться</button>
</form>
</div>
