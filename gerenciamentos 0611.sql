CREATE DATABASE db_gereciamentos
USE db_gereciamentos

CREATE TABLE tbl_usuarios (
  usu_codigo INT PRIMARY KEY,
  usu_nome VARCHAR(100),
  usu_email VARCHAR(100)
);

CREATE TABLE tbl_tarefas (
  tar_codigo INT PRIMARY KEY,
  usu_codigo INT,
  tar_setor VARCHAR(50),
  tar_prioridade ENUM('baixa','media','alta'),
  tar_descricao TEXT,
  tar_status ENUM('pendente','em andamento','finalizada'),
  FOREIGN KEY (usu_codigo) REFERENCES Tbl_usuarios(usu_codigo)
);

INSERT INTO Tbl_Usuarios (usu_codigo, usu_nome, usu_email) VALUES
(1, 'Ester Sampaio', 'ester.sampaio90@email.com'),
(2, 'Maria Oliveira', 'maria.oliveira@email.com'),
(3, 'Carlos Silva', 'carlos.silva@email.com');

INSERT INTO Tbl_Tarefas (tar_codigo, usu_codigo, tar_setor, tar_prioridade, tar_descricao, tar_status) VALUES
(1, 1, 'TI', 'Alta', 'Desenvolver nova funcionalidade', 'Em andamento'),
(2, 2, 'Marketing', 'Média', 'Criar campanha publicitária', 'Pendente'),
(3, 3, 'Financeiro', 'Baixa', 'Organizar planilhas de orçamento', 'Concluída');
