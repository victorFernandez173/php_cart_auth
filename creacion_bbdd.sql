DROP DATABASE IF EXISTS almacen;
CREATE DATABASE almacen;

/* creamos el usuario para el manejo de la base de datos y le otorgamos permisos */
-- DROP user IF EXISTS 'xxxxxx'@'%';
-- CREATE user 'xxxxxx'@'%' identified by 'Xxxxxx123%';
-- GRANT all privileges ON almacen.* to 'xxxxxx'@'%';

USE almacen;

CREATE table usuario(
    idUsuario INT UNSIGNED AUTO_INCREMENT,
    nombre VARCHAR(20) NOT NULL,
    clave VARCHAR(128) NOT NULL,
    CONSTRAINT usuario_pk PRIMARY KEY(idUsuario)
);

CREATE table datosUsu(
    idDatosUsu INT UNSIGNED,
    email VARCHAR(50) NOT NULL, 
    telf VARCHAR(9) NOT NULL,
    CONSTRAINT datosUsu_pk PRIMARY KEY(idDatosUsu),
    CONSTRAINT datosUsu_fk_usuario FOREIGN KEY (idDatosUsu) REFERENCES usuario(idUsuario) ON DELETE CASCADE
); 


/* PRODUCTOS */
CREATE table producto(
    idProducto INT UNSIGNED AUTO_INCREMENT,
    descripcion VARCHAR(70) NOT NULL,
    precio DECIMAL(5, 2) NOT NULL,
    talla enum('S', 'M', 'L') NOT NULL,
    tipo enum('CAMI', 'PANT') NOT NULL,
    CONSTRAINT producto_pk PRIMARY KEY(idProducto),
    CONSTRAINT producto_uq UNIQUE(idProducto, talla, tipo)
);

CREATE table stock(
    idProducto INT UNSIGNED,
    unidades INT UNSIGNED NOT NULL,
    CONSTRAINT stock_pk PRIMARY KEY(idProducto),
    CONSTRAINT stock_fk_producto FOREIGN KEY (idProducto) REFERENCES producto(idProducto) ON DELETE CASCADE
);

/* De primeras para el ejemplo solo añado camisetas de tres modelos, eso si
 diferentes tallas */
INSERT INTO producto (descripcion, precio, talla, tipo) VALUES ('Modelo QuickSilver', 45.95, 'S', 'CAMI'), ('Modelo QuickSilver', 45.95, 'M', 'CAMI'), ('Modelo QuickSilver', 45.95, 'L', 'CAMI'), ('Modelo Patagonia', 35.95, 'S', 'CAMI'), ('Modelo Patagonia', 35.95, 'M', 'CAMI'), ('Modelo Patagonia', 35.95, 'L', 'CAMI'), ('Modelo Adidas', 29.95, 'S', 'CAMI'), ('Modelo Adidas', 29.95, 'M', 'CAMI'), ('Modelo Adidas', 29.95, 'L', 'CAMI');

INSERT INTO stock VALUES (1, 10), (2, 10), (3, 10), (4, 15), (5, 15), (6, 15), (7, 20), (8, 20), (9, 20);

