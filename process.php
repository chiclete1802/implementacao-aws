<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = htmlspecialchars($_POST["nome"]);
    $email = htmlspecialchars($_POST["email"]);

    $conn = new mysqli("localhost", "root", "root", "aws_db");

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome, $email);

    if ($stmt->execute()) {
        echo "<h1>Dados salvos com sucesso!</h1>";
        echo "<p><strong>Nome:</strong> $nome</p>";
        echo "<p><strong>Email:</strong> $email</p>";
    } else {
        echo "Erro ao salvar: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Acesso inválido!";
}
?>
