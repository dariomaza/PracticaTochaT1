<?php 
    class Product{
        public int $idProducto;
        public string $nombreProducto;
        public int $precio;
        public string $descripcion;
        public int $cantidad;
        public string $imagen;

        function __construct(int $idProducto,
                            string $nombreProducto,
                            int $precio,
                            string $descripcion,
                            int $cantidad,
                            string $imagen){
            $this -> idProducto = $idProducto;
            $this -> nombreProducto = $nombreProducto;
            $this -> precio = $precio;
            $this -> descripcion = $descripcion;
            $this -> cantidad = $cantidad;
            $this -> imagen = $imagen;
        }
    }
?>