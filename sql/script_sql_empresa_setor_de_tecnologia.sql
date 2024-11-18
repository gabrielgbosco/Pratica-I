CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(20)
);

CREATE TABLE colaboradores (
    id_colaborador INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

CREATE TABLE chamados (
    id_chamado INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_colaborador INT NULL,
    descricao_problema TEXT NOT NULL,
    criticidade ENUM('baixa', 'média', 'alta') DEFAULT 'baixa',
    status ENUM('aberto', 'em andamento', 'resolvido') DEFAULT 'aberto',
    data_abertura TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    FOREIGN KEY (id_colaborador) REFERENCES colaboradores(id_colaborador)
);
