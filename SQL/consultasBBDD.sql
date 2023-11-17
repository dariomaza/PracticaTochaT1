select * from productosCestas;
SELECT 
    pc.cantidad * p.precioProducto AS total
FROM 
    productosCestas pc
JOIN 
    productos p ON pc.idProducto = p.idProducto where pc.idProducto = 1;
    
    SELECT pc.cantidad * p.precioProducto AS total FROM productosCestas pc JOIN productos p ON pc.idProducto = p.idProducto where pc.idProducto = 1 and pc.idCesta = 2;
    
SELECT p.imagen,  p.nombreProducto, p.precioProducto, pc.cantidad, pc.idProducto FROM productos p INNER JOIN productosCestas pc ON p.idProducto = pc.idProducto where pc.idCesta = 1;

select * from cestas;
UPDATE productosCestas SET 4 WHERE idProducto = 1;
SELECT p.imagen,  p.nombreProducto, p.precioProducto, pc.cantidad FROM productos p INNER JOIN productosCestas pc ON p.idProducto = pc.idProducto;

select * from usuarios;

select * from cestas;

SELECT idCesta FROM cestas where usuario = 'dario19';

SELECT * FROM productosCestas  productos where idCesta = 1;
SELECT EXISTS(SELECT 1 FROM productosCestas WHERE idProducto = '1' and idCesta = '1');
select * from fotosUsuarios;

SELECT p.idProducto, p.precioProducto, pc.cantidad FROM productos AS p JOIN productosCestas AS pc ON p.idProducto = pc.idProducto WHERE pc.idCesta = 2;

select * from pedidos join lineaspedidos;