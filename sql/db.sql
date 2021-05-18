CREATE DATABASE IF NOT EXISTS diario;
USE diario;
CREATE TABLE IF NOT EXISTS receita (
	receita_id INT PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(150) NOT NULL UNIQUE,
	descricao VARCHAR(255) NOT NULL,
	image_url VARCHAR(160) NOT NULL
);
CREATE TABLE IF NOT EXISTS ingrediente (
	ingrediente_id INT PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(150) NOT NULL UNIQUE
);
CREATE TABLE IF NOT EXISTS receita_ingrediente (
	receita_id INT NOT NULL,
	ingrediente_id INT NOT NULL,
	FOREIGN KEY (receita_id) REFERENCES receita (receita_id) ON DELETE RESTRICT ON UPDATE CASCADE,
	FOREIGN KEY (ingrediente_id) REFERENCES ingrediente (ingrediente_id) ON DELETE RESTRICT ON UPDATE CASCADE,
	PRIMARY KEY (receita_id, ingrediente_id)
);
