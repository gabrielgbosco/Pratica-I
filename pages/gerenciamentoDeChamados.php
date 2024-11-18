<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Chamados</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Gerenciamento de Chamados</h2>

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
        die("<p style='color: red;'>Erro na conexão com o banco de dados: " . $e->getMessage() . "</p>");
    }

    // Tratamento dos filtros
    $status = $_GET['status'] ?? '';
    $criticidade = $_GET['criticidade'] ?? '';
    $colaborador = $_GET['colaborador'] ?? '';

    // Construção dinâmica da query com filtros
    $query = "SELECT chamados.id_chamado, chamados.descricao_problema, chamados.criticidade, chamados.status, 
                     chamados.data_abertura, colaboradores.nome AS colaborador_nome 
              FROM chamados
              LEFT JOIN colaboradores ON chamados.id_colaborador = colaboradores.id_colaborador
              WHERE 1=1";

    if (!empty($status)) {
        $query .= " AND chamados.status = :status";
    }

    if (!empty($criticidade)) {
        $query .= " AND chamados.criticidade = :criticidade";
    }

    if (!empty($colaborador)) {
        $query .= " AND chamados.id_colaborador = :colaborador";
    }

    $stmt = $pdo->prepare($query);

    // Bind de parâmetros dinâmicos
    if (!empty($status)) {
        $stmt->bindParam(':status', $status);
    }
    if (!empty($criticidade)) {
        $stmt->bindParam(':criticidade', $criticidade);
    }
    if (!empty($colaborador)) {
        $stmt->bindParam(':colaborador', $colaborador);
    }

    try {
        $stmt->execute();
        $chamados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("<p style='color: red;'>Erro ao buscar chamados: " . $e->getMessage() . "</p>");
    }

    // Busca colaboradores para preencher o filtro
    $colaboradoresStmt = $pdo->query("SELECT id_colaborador, nome FROM colaboradores");
    $colaboradores = $colaboradoresStmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!-- Formulário de Filtros -->
    <form method="GET" action="">
        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="">Todos</option>
            <option value="aberto" <?= $status == 'aberto' ? 'selected' : '' ?>>Aberto</option>
            <option value="em andamento" <?= $status == 'em andamento' ? 'selected' : '' ?>>Em andamento</option>
            <option value="resolvido" <?= $status == 'resolvido' ? 'selected' : '' ?>>Resolvido</option>
        </select>

        <label for="criticidade">Criticidade:</label>
        <select name="criticidade" id="criticidade">
            <option value="">Todas</option>
            <option value="baixa" <?= $criticidade == 'baixa' ? 'selected' : '' ?>>Baixa</option>
            <option value="média" <?= $criticidade == 'média' ? 'selected' : '' ?>>Média</option>
            <option value="alta" <?= $criticidade == 'alta' ? 'selected' : '' ?>>Alta</option>
        </select>

        <label for="colaborador">Colaborador:</label>
        <select name="colaborador" id="colaborador">
            <option value="">Todos</option>
            <?php foreach ($colaboradores as $colab): ?>
                <option value="<?= $colab['id_colaborador'] ?>" <?= $colaborador == $colab['id_colaborador'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($colab['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filtrar</button>
    </form>

    <br>

    <!-- Tabela de Chamados -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Descrição</th>
                <th>Criticidade</th>
                <th>Status</th>
                <th>Data de Abertura</th>
                <th>Colaborador</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($chamados)): ?>
                <?php foreach ($chamados as $chamado): ?>
                    <tr>
                        <td><?= $chamado['id_chamado'] ?></td>
                        <td><?= htmlspecialchars($chamado['descricao_problema']) ?></td>
                        <td><?= $chamado['criticidade'] ?></td>
                        <td><?= $chamado['status'] ?></td>
                        <td><?= $chamado['data_abertura'] ?></td>
                        <td><?= htmlspecialchars($chamado['colaborador_nome'] ?? 'Não atribuído') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Nenhum chamado encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
