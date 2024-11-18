<?php
// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'atendimentos_suporte_tecnico_gabriel';
$user = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Verifica se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    // Validações básicas
    if (!empty($nome) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $sql = "INSERT INTO colaboradores (nome, email) VALUES (:nome, :email)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            echo "Colaborador cadastrado com sucesso!";
        } catch (PDOException $e) {
            echo "Erro ao cadastrar colaborador: " . $e->getMessage();
        }
    } else {
        echo "Preencha todos os campos corretamente.";
    }
}
?>