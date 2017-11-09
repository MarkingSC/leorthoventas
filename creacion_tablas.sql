CREATE TABLE entradas (
	id_entrada INT PRIMARY KEY AUTO_INCREMENT, 
	id_producto INT NOT NULL, 
	cantidad INT, 
	costo FLOAT, 
	observaciones TEXT(100), 
	fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP);

CREATE TABLE cortes(
	id_corte INT PRIMARY KEY AUTO_INCREMENT,
	fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	supuesto FLOAT NOT NULL,
	realidad FLOAT NOT NULL, 
	ubicacion TEXT(200));

CREATE TABLE reportes(
	id_reporte INT PRIMARY KEY AUTO_INCREMENT,
	fecha_i DATE,
	fecha_f DATE,
	id_lapso INT NOT NULL,
	ubicacion TEXT(200));

CREATE TABLE lapsos (
 	id_lapso INT PRIMARY KEY AUTO_INCREMENT,
 	desc_lapso VARCHAR(45));

CREATE TABLE productos (
	id_producto INT PRIMARY KEY AUTO_INCREMENT, 
	codigo VARCHAR(45) UNIQUE NOT NULL,
	descripcion_p VARCHAR(45), 
	id_categoria INT NOT NULL, 
	precio_venta FLOAT NOT NULL, 
	precio_adq FLOAT NOT NULL, 
	minimo INT NOT NULL,
	existencias INT,
	ruta_img TEXT(200));


CREATE TABLE colores(
	id_color INT PRIMARY KEY AUTO_INCREMENT,
	desc_color VARCHAR(45));

CREATE TABLE categorias (
	id_categoria INT PRIMARY KEY AUTO_INCREMENT, 
	descripcion_c VARCHAR(45));

CREATE TABLE tickets (
	id_ticket INT PRIMARY KEY AUTO_INCREMENT, 
	cliente TEXT(100), 
	total FLOAT, 
	fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	procesado INT NOT NULL);

CREATE TABLE tallas (
	id_talla INT PRIMARY KEY AUTO_INCREMENT, 
	desc_talla VARCHAR(45));

CREATE TABLE ventas (
	id_venta INT PRIMARY KEY AUTO_INCREMENT, 
	id_producto INT NOT NULL, 
	cantidad INT DEFAULT 1, 
	id_ticket INT NOT NULL, 
	subtotal FLOAT NOT NULL);

CREATE TABLE clasificaciones(
	id_clasificacion INT PRIMARY KEY AUTO_INCREMENT,
	id_producto INT NOT NULL,
	tallas INT,
	generos INT,
	izqder INT,
	colores INT);

CREATE TABLE assign_tallas(
	id_assign_talla INT PRIMARY KEY AUTO_INCREMENT,
	id_clasificacion INT NOT NULL,
	id_talla INT NOT NULL,
	cantidad INT);

CREATE TABLE assign_colores(
	id_assign_color INT PRIMARY KEY AUTO_INCREMENT,
	id_clasificacion INT NOT NULL,
	id_color INT NOT NULL,
	cantidad INT);

CREATE TABLE assign_generos(
	id_assign_genero INT PRIMARY KEY AUTO_INCREMENT,
	id_clasificacion INT NOT NULL,
	genero INT NOT NULL,
	cantidad INT);

CREATE TABLE assign_izqder(
	id_assign_izqder INT PRIMARY KEY AUTO_INCREMENT,
	id_clasificacion INT NOT NULL,
	lado INT NOT NULL,
	cantidad INT);


#:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:= BITÁCORAS :=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=:=


#despues de haber agregado un nuevo producto
CREATE TABLE bit_nvosprods (
	idbit_np INT PRIMARY KEY AUTO_INCREMENT, 
	cuando TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
	id_producto INT NOT NULL, 
	preciou FLOAT NOT NULL, 
	cantidad INT, 
	subtotal FLOAT);

#Antes de una eliminación en la tabla de productos
CREATE TABLE bit_bajas(
	idbit_baja INT PRIMARY KEY AUTO_INCREMENT,
	cuando TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	producto VARCHAR(45) NOT NULL,
	por_que VARCHAR(100) NOT NULL DEFAULT "NA",
	valor FLOAT);

#Después de hacer un corte de caja
CREATE TABLE bit_cortesmal(
	idbit_cortemal INT PRIMARY KEY AUTO_INCREMENT,
	cuando TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	faltante FLOAT NOT NULL,
	justificado INT NOT NULL DEFAULT 0);

#Ates de realizar la cancelación
CREATE TABLE bit_cancelaciones(
	idbit_cancv INT PRIMARY KEY AUTO_INCREMENT,
	cuando TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	producto VARCHAR(45),
	cuantos INT NOT NULL,
	valor FLOAT NOT NULL,
	cliente VARCHAR(45));

#Ates de realizar la devolucion
CREATE TABLE bit_devoluciones(
	idbit_dev INT PRIMARY KEY AUTO_INCREMENT,
	cuando TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	producto VARCHAR(45),
	cuantos INT NOT NULL,
	valor FLOAT NOT NULL,
	cliente VARCHAR(45),
	por_que VARCHAR(100));

CREATE TRIGGER bit_nvosprods
	after INSERT on productos
	for each row
	BEGIN
	DECLARE v_subtotal;
	SET v_subtotal=(new.precio_adq)*(new.existencias);
	insert into bit_nvosprods VALUES(NULL, NULL, new.id_producto, new.precio_adq, new.existencias, v_subtotal);
	END;

CREATE TRIGGER bbp #BITACORA DE BAJA DE PRODUCTOS
	before DELETE on productos
	for each row
	BEGIN
	SET v_valor=old.precio_adq*old.existencias;
	insert into bit_bajas VALUES(NULL, NULL, old.descripcion_p, NULL, v_valor);
	END bbp;

CREATE TRIGGER bcm #BITACORA DE CORTES DE CAJA MAL EQUILIBRADOS ********************************
	after INSERT on cortes
	for each row
	BEGIN
	insert into bit_cortesmal VALUES(NULL, NULL, old.descripcion_p, NULL, old.precio_adq);
	END bcm;

CREATE TRIGGER bcanc{ #BITACORA DE CANCELACIONES
	before delete on ventas
	for each row
	BEGIN
}

CREATE TRIGGER bcanc #BITACORA DE DEVOLUCIONES
	before DELETE on ventas
	for each row
	BEGIN
	DECLARE vid_prod INT;
 	DECLARE v_cantidad INT;
 	SELECT cantidad into v_cantidad from ventas WHERE id_venta=old.id_venta;
 	SELECT id_producto into vid_prod FROM productos, ventas WHERE productos.id_producto=ventas.id_producto and ventas.id_venta=old.id_venta;
	UPDATE productos set existencias=existencias+v_cantidad where productos.id_producto=vid_prod; # SE AGREGAN LA CANTIDAD DE ARTICULOS AL IVENTARIO
end;

