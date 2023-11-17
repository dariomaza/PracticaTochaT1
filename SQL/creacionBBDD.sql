create schema tienda;
use tienda;

create table productos(
	idProducto int auto_increment primary key,
    nombreProducto varchar(40) not null ,
    precioProducto numeric(8,2) not null,
    descProducto varchar(255) not null,
    cantidad int not null,
    imagen varchar(200) not null
);

select * from productos;

CREATE TABLE usuarios (
	idUsuario int auto_increment primary key,
	usuario VARCHAR(12) unique,
    contrasena varchar(255) not null,
    nombre VARCHAR(20) NOT NULL,
    apellidos VARCHAR(40) NOT NULL,
    fechaNacimiento DATE NOT NULL,
    Rol varchar(20) default 'cliente'
);

select * from usuarios;
create table fotosUsuarios (
	idFoto int auto_increment primary key,
    usuario varchar(12),
    ruta varchar(100),
    constraint pk_fotosUsuarios_usuario foreign key (usuario) references usuarios(usuario)
);

select * from fotosUsuarios;

CREATE TABLE cestas (
    idCesta INT AUTO_INCREMENT PRIMARY KEY,
    usuario varchar(12),
    precio_total numeric(8,2) DEFAULT 0,
    constraint pk_cestas_usuario foreign key (usuario) references usuarios(usuario)
);
select * from cestas;



create table productosCestas (
	idProducto INT,
    idCesta INT,
    cantidad numeric(2),
    constraint pk_productosCestas primary key (idProducto, idCesta),
    constraint fk_productosCestas_productos foreign key (idProducto) references productos(idProducto),
    constraint fk_productosCestas_cestas foreign key (idCesta) references cestas(idCesta)
);
select * from productosCestas;
SELECT 
    pc.cantidad * p.precioProducto AS total
FROM 
    productosCestas pc
JOIN 
    productos p ON pc.idProducto = p.idProducto where pc.idProducto = 1;
    
SELECT p.imagen,  p.nombreProducto, p.precioProducto, pc.cantidad, pc.idProducto FROM productos p INNER JOIN productosCestas pc ON p.idProducto = pc.idProducto;

select * from cestas;
UPDATE productosCestas SET 4 WHERE idProducto = 1;
SELECT p.imagen,  p.nombreProducto, p.precioProducto, pc.cantidad FROM productos p INNER JOIN productosCestas pc ON p.idProducto = pc.idProducto;