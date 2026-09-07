-- ATENÇÃO: UTILIZE O COMANDO ABAIXO SOZINHO PARA CRIAR O BANCO DE DADOS, POIS O MESMO NÃO EXISTE AINDA. CASO O BANCO DE DADOS JÁ EXISTA, COMENTE A LINHA ABAIXO E DESCOMENTE A LINHA SEGUINTE.
CREATE DATABASE livrariafai;

USE livrariafai;

CREATE TABLE  idiomas
(
    cod_idioma INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

CREATE TABLE  autores
(
    cod_autor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

CREATE TABLE  editoras
(
    cod_editora INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

CREATE TABLE  cidades
(
    cod_cidade INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    uf CHAR(2) NOT NULL
);

CREATE TABLE cat_usuarios
(
    cod_cat INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL,
    n_dias INT NOT NULL,
    n_exemplares INT NOT NULL
);

CREATE TABLE usuarios
(
    cod_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cod_cat INT,
    cod_cidade INT,
    CONSTRAINT fk_usuarios_cat
        FOREIGN KEY (cod_cat) REFERENCES cat_usuarios(cod_cat),
    CONSTRAINT fk_usuarios_cidade
        FOREIGN KEY (cod_cidade) REFERENCES cidades(cod_cidade)
);

CREATE TABLE livros (
    cod_livro INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    paginas INT NOT NULL,
    ano INT NOT NULL,
    edicao INT NOT NULL,
    cod_editora INT NOT NULL,
    CONSTRAINT fk_livros_editoras
        FOREIGN KEY (cod_editora) REFERENCES editoras(cod_editora),
    esta_emprestado BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE LI (
    cod_livro INT NOT NULL,
    cod_idioma INT NOT NULL,
    PRIMARY KEY (cod_livro, cod_idioma),
    CONSTRAINT fk_LI_livros
        FOREIGN KEY (cod_livro) REFERENCES livros(cod_livro),
    CONSTRAINT fk_LI_idiomas
        FOREIGN KEY (cod_idioma) REFERENCES idiomas(cod_idioma)
);

CREATE TABLE LA (
    cod_livro INT NOT NULL,
    cod_autor INT NOT NULL,
    PRIMARY KEY (cod_livro, cod_autor),
    CONSTRAINT fk_LA_livros
        FOREIGN KEY (cod_livro) REFERENCES livros(cod_livro),
    CONSTRAINT fk_LA_autores
        FOREIGN KEY (cod_autor) REFERENCES autores(cod_autor)
);

CREATE TABLE emprestimos (
    cod_emprestimo INT AUTO_INCREMENT PRIMARY KEY,
    cod_usuario INT NOT NULL,
    cod_livro INT NOT NULL,
    data_emprestimo DATE NOT NULL,
    data_devolucao DATE,
    CONSTRAINT fk_emprestimos_usuarios
        FOREIGN KEY (cod_usuario) REFERENCES usuarios(cod_usuario),
    CONSTRAINT fk_emprestimos_livros
        FOREIGN KEY (cod_livro) REFERENCES livros(cod_livro)
);
