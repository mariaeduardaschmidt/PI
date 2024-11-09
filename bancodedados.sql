-- Criando o banco de dados doacoes_fmp
CREATE DATABASE doacoes_fmp;

-- Selecionando o banco de dados para criar as tabelas
USE doacoes_fmp;

-- Tabela de Doações
CREATE TABLE doacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_doador VARCHAR(255) NOT NULL,
    email_doador VARCHAR(255) NOT NULL,
    tipo_produto VARCHAR(255) NOT NULL,
    quantidade INT NOT NULL CHECK (quantidade > 0),
    descricao_produto TEXT NOT NULL,
    data_doacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Histórico de Doações
CREATE TABLE historico_de_doacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doacao_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    data_status TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doacao_id) REFERENCES doacoes(id) ON DELETE CASCADE
);

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    registration_number VARCHAR(100),
    birth_date DATE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Relatórios
CREATE TABLE relatorios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    conteudo TEXT NOT NULL,
    data_relatorio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    autor_id INT NOT NULL,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de Horas Complementares
CREATE TABLE horas_complementares (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    horas INT NOT NULL CHECK (horas > 0),
    descricao TEXT NOT NULL,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
