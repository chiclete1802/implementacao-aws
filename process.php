<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = htmlspecialchars($_POST["nome"]);
    $email = htmlspecialchars($_POST["email"]);

    echo "<h1>Dados Recebidos:</h1>";
    echo "<p><strong>Nome:</strong> $nome</p>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<a href='index.html'>Voltar</a>";
} else {
    echo "Acesso inválido!";
}
?>
