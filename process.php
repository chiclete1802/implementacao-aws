<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];

    $servername = "127.0.0.1";
    $username   = "root";
    $password   = "root";
    $dbname     = "aws_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $nome, $email);

    if ($stmt->execute()) {
        echo "<h1>Dados Recebidos e Salvos:</h1>";
        echo "<p><strong>Nome:</strong> " . htmlspecialchars($nome) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
    } else {
        echo "Erro ao salvar: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    echo "<br><a href='index.html'>Voltar</a>";
} else {
    echo "Acesso inválido!";
}
?>
