use tienda;





create table productos(
	idProducto int auto_increment primary key,
    nombreProducto varchar(40) not null ,
    precioProducto numeric(8,2) not null,
    descProducto varchar(255) not null,
    cantidad int not null,
    imagen varchar(200) not null
);

select * from usuarios;

CREATE TABLE usuarios (
	idUsuario int auto_increment primary key,productos
	usuario VARCHAR(12) unique,
    contrasena varchar(255) not null,
    nombre VARCHAR(20) NOT NULL,
    apellidos VARCHAR(40) NOT NULL,
    fechaNacimiento DATE NOT NULL,
    Rol varchar(20) default 'cliente'
);

create table fotosUsuarios (
	idFoto int auto_increment primary key,
    usuario varchar(12),
    ruta varchar(100),
    constraint pk_fotosUsuarios_usuario foreign key (usuario) references usuarios(usuario)
);



CREATE TABLE cestas (
    idCesta INT AUTO_INCREMENT PRIMARY KEY,
    usuario varchar(12),
    precio_total numeric(8,2) DEFAULT 0,
    constraint pk_cestas_usuario foreign key (usuario) references usuarios(usuario)
);
use tienda;
create table productosCestas (
	idProducto INT,
    idCesta INT,
    cantidad numeric(2),
    constraint pk_productosCestas primary key (idProducto, idCesta),
    constraint fk_productosCestas_productos foreign key (idProducto) references productos(idProducto),
    constraint fk_productosCestas_cestas foreign key (idCesta) references cestas(idCesta)
);

CREATE TABLE pedidos (
    idPedido INT AUTO_INCREMENT PRIMARY KEY,
    usuario varchar(12),
    precioTotal FLOAT(10,2),
    fechaPedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario) REFERENCES usuarios(usuario)
);

CREATE TABLE LineasPedidos (
    lineaPedido INT,
    idProducto INT,
    precioUnitario FLOAT(10,2),
    cantidad INT,
    idPedido INT,
    FOREIGN KEY (idProducto) REFERENCES productos(idProducto),
    FOREIGN KEY (idPedido) REFERENCES pedidos(idPedido)
);



