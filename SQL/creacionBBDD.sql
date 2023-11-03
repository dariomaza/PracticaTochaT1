create schema tienda;
use tienda;

create table productos(
	idProducto int auto_increment primary key,
    nombreProducto varchar(40) not null ,
    precioProducto numeric(8,2) not null,
    descProducto varchar(255) not null,
    cantidad int not null
);
CREATE TABLE usuarios (
	idUsuario int auto_increment primary key,
	usuario VARCHAR(12) unique,
    contrasena varchar(255) not null,
    nombre VARCHAR(20) NOT NULL,
    apellidos VARCHAR(40) NOT NULL,
    fechaNacimiento DATE NOT NULL
);

select * from usuarios;

CREATE TABLE cestas (
    idCesta INT AUTO_INCREMENT PRIMARY KEY,
    usuario varchar(12),
    precio_total numeric(8,2) DEFAULT 0,
    constraint pk_cestas_usuario foreign key (usuario) references usuarios(usuario)
);

create table productosCestas (
	idProducto INT,
    idCesta INT,
    cantidad numeric(2),
    constraint pk_productosCestas primary key (idProducto, idCesta),
    constraint fk_productosCestas_productos foreign key (idProducto) references productos(idProducto),
    constraint fk_productosCestas_cestas foreign key (idCesta) references cestas(idCesta)
);