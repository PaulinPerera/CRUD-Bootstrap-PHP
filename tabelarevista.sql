SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

SET NAMES utf8;

CREATE DATABASE IF NOT EXISTS banco DEFAULT CHARACTER SET utf8;
USE banco;

CREATE TABLE IF NOT EXISTS tabelarevista (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nome varchar(50) NOT NULL,
  ano int NOT NULL,
  edicao int NOT NULL,
  datacadastro datetime NOT NULL,
  foto varchar(255) NOT NULL,
  descricao text NOT NULL
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=6;

CREATE TABLE IF NOT EXISTS clientes (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nome varchar(50) NOT NULL,
  foto varchar(255) NOT NULL,
  cpf varchar(14) NOT NULL,
  email varchar(100) NOT NULL,
  telefone varchar(15) NOT NULL,
  datacadastro datetime NOT NULL,
  UNIQUE KEY uq_clientes_cpf (cpf),
  UNIQUE KEY uq_clientes_email (email)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=4;

-- Usuários do sistema (a senha é guardada com hash bcrypt, nunca em texto)
CREATE TABLE IF NOT EXISTS usuarios (
  id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nome varchar(50) NOT NULL,
  user varchar(30) NOT NULL,
  password varchar(255) NOT NULL,
  datacadastro datetime NOT NULL,
  UNIQUE KEY uq_usuarios_user (user)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

INSERT IGNORE INTO `tabelarevista` (`id`, `nome`, `ano`, `edicao`, `datacadastro`, `foto`, `descricao`) VALUES
(1, 'O Cavaleiro das Trevas', 1986, 1, '2026-06-11 12:00:00', 'batman_cavaleiro.png', 'Minissérie de Frank Miller que mostra um Batman envelhecido retornando à ativa para combater o crime em Gotham.'),
(2, 'A Piada Mortal', 1988, 1, '2026-06-11 12:05:00', 'piada_mortal.png', 'Graphic novel que explora a relação entre Batman e Coringa, apresentando uma possível origem para o vilão.'),
(3, 'Watchmen', 1986, 1, '2026-06-11 12:10:00', 'watchmen_01.png', 'Obra clássica ambientada em um mundo alternativo onde heróis mascarados influenciam eventos políticos globais.'),
(4, 'Guerra Civil', 2006, 1, '2026-06-11 12:15:00', 'guerra_civil_01.png', 'Evento da Marvel em que os heróis se dividem após a aprovação da Lei de Registro de Super-Humanos.'),
(5, 'Homem-Aranha: A Última Caçada de Kraven', 1987, 1, '2026-06-11 12:20:00', 'spiderman_kraven.png', 'Saga em que Kraven derrota o Homem-Aranha e assume sua identidade em uma das histórias mais marcantes do personagem.');

-- Administrador inicial:  login = admin   |   senha = admin123   (TROQUE a senha depois de entrar: Usuários > Editar)
INSERT IGNORE INTO `usuarios` (`id`, `nome`, `user`, `password`, `datacadastro`) VALUES
(1, 'Administrador', 'admin', '$2b$10$XxDDUHW7cvjc0NbS5O0IZOH9Fz6C8oRBUYpOPY2AjSl4UyE/2Z/rC', '2026-06-11 12:00:00');

-- Clientes de exemplo
INSERT IGNORE INTO `clientes` (`id`, `nome`, `foto`, `cpf`, `email`, `telefone`, `datacadastro`) VALUES
(1, 'Maria Souza', '', '529.982.247-25', 'maria.souza@exemplo.com', '(15) 99876-5432', '2026-06-11 12:30:00'),
(2, 'João Pereira', '', '111.444.777-35', 'joao.pereira@exemplo.com', '(15) 3232-1010', '2026-06-11 12:35:00'),
(3, 'Ana Lima', '', '390.533.447-05', 'ana.lima@exemplo.com', '(11) 98765-4321', '2026-06-11 12:40:00');
