<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Chamado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, select, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Registrar Novo Chamado</h2>

    <?php
    // Conexão com o banco de dados
    $host = 'localhost';
    $dbname = 'atendimentos_suporte_tecnico_gabriel';
    $user = 'root';
    $password = 'root';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_cliente = $_POST['id_cliente'];
            $descricao_problema = $_POST['descricao_problema'];
            $criticidade = $_POST['criticidade'];

            if (!empty($id_cliente) && !empty($descricao_problema) && !empty($criticidade)) {
                $sql = "INSERT INTO chamados (id_cliente, descricao_problema, criticidade) VALUES (:id_cliente, :descricao_problema, :criticidade)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id_cliente', $id_cliente);
                $stmt->bindParam(':descricao_problema', $descricao_problema);
                $stmt->bindParam(':criticidade', $criticidade);
                $stmt->execute();

                echo "<p style='color: green; text-align: center;'>Chamado registrado com sucesso!</p>";
            } else {
                echo "<p style='color: red; text-align: center;'>Por favor, preencha todos os campos.</p>";
            }
        }

        // Busca clientes para popular o select
        $stmt = $pdo->query("SELECT id_cliente, nome FROM clientes");
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erro na conexão com o banco de dados: " . $e->getMessage() . "</p>";
        die();
    }
    ?>

    <!-- Formulário de registro de chamados -->
    <form method="POST" action="">
        <label for="id_cliente">Cliente:</label>
        <select id="id_cliente" name="id_cliente" required>
            <option value="">Selecione o cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['id_cliente'] ?>"><?= htmlspecialchars($cliente['nome']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="descricao_problema">Descrição do Problema:</label>
        <textarea id="descricao_problema" name="descricao_problema" rows="4" required></textarea>

        <label for="criticidade">Criticidade:</label>
        <select id="criticidade" name="criticidade" required>
            <option value="baixa">Baixa</option>
            <option value="média">Média</option>
            <option value="alta">Alta</option>
        </select>

        <button type="submit">Registrar Chamado</button>
    </form>
</body>
</html>
