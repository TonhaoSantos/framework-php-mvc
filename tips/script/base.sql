/*
 *
 * Criando a base de dados
 *
 */
/* Criando Banco de Dados */
CREATE DATABASE homolog_ourbrazil
    DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;



/*
 *
 * Usando a base de dados
 *
 */
USE homolog_ourbrazil;



/*
 *
 * Criando as tabelas
 *
 */
/* Categoria */
/* Nome Tabela */
CREATE TABLE TB_NOT_NOME_TABELA (
	not_id INT NOT NULL AUTO_INCREMENT,
	not_nomeCampo TEXT NOT NULL,
	not_nomeCampo VARCHAR(250) NOT NULL,
	not_nomeCampo VARCHAR(100),
	not_nomeCampo INT NOT NULL,
	not_nomeCampo INT(200),
	not_nomeCampo ENUM('Sim','Não') DEFAULT 'Não',
	PRIMARY KEY (not_id),
	CONSTRAINT uni_not UNIQUE (not_id, not_nomeCampo)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;




/*
 * Inserts
 *
 */
/* Categoria */
/* Nome Tabela */
INSERT INTO TB_NOT_NOME_TABELA (campos)
	values ('valores'),
         ('valores');


/*
 * Foreign Key
 *
 */
/* Categoria */
/* Nome Tabela */
	ALTER TABLE TB_NOT_NOME_TABELA
	ADD CONSTRAINT fk_not_nomeCampo
	FOREIGN KEY (nomeCampo) REFERENCES TB_NOT_NOME_TABELA_REFERENCIA (nomeCampoReferencia);
