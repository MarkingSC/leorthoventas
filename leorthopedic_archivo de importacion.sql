-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 12-11-2017 a las 00:45:23
-- Versión del servidor: 5.7.19
-- Versión de PHP: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `leorthopedic`
--

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `clasificar`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `clasificar` (`p_codigo` VARCHAR(45), `pid_talla` INT, `p_genero` INT, `p_izq_der` INT, `pid_color` INT)  BEGIN
DECLARE vid_prod INT;
	SELECT id_producto into vid_prod from productos where codigo=p_codigo; #SE OBTIENE EL ID DEL PRODUCTO
	INSERT INTO atributos values (null, vid_prod, pid_talla, p_genero, p_izq_der, pid_color, cantidad); #SE INSERTA UN REGISTRO EN LA TABLA DE ATRIBUTOS
END$$

DROP PROCEDURE IF EXISTS `mod_cantventa`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `mod_cantventa` (`pid_venta` INT, `p_cantidad` INT)  BEGIN
	DECLARE v_disponbles INT;
	DECLARE vid_prod INT;
	DECLARE v_cantantes INT;
	DECLARE v_preciovta FLOAT;
	SELECT cantidad into v_cantantes from ventas where id_venta=pid_venta;
	IF p_cantidad<=0
		then
		SELECT('LA CANTIDAD DE PRODUCTOS NO ES CORRECTA.') as error;
		else
			SELECT id_producto into vid_prod FROM ventas WHERE  ventas.id_venta=pid_venta;
			UPDATE productos set existencias=existencias+v_cantantes where id_producto=vid_prod;
			SELECT existencias into v_disponbles from productos where id_producto=vid_prod;
			IF p_cantidad>v_disponbles
				then
				SELECT('LA CANTIDAD EXCEDE LA DISPONIBILIDAD.') as error;
				else
					UPDATE ventas SET cantidad=p_cantidad WHERE id_venta=pid_venta;
					SELECT precio_venta into v_preciovta FROM productos where id_producto=vid_prod;
					UPDATE ventas set subtotal=p_cantidad*v_preciovta;
					UPDATE productos set existencias=existencias-p_cantidad where id_producto=vid_prod;
			end if;
	end if;
END$$

DROP PROCEDURE IF EXISTS `mod_prod`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `mod_prod` (IN `p_codigo` VARCHAR(45), IN `p_descripcion` VARCHAR(45), IN `p_categoria` VARCHAR(45), IN `p_minimo` INT)  BEGIN
	DECLARE vid_catego INT;
	DECLARE vid_prod INT;
	DECLARE vid_clasificacion INT;
	SELECT id_categoria into vid_catego from categorias WHERE descripcion_c LIKE p_categoria;
	SELECT id_producto into vid_prod FROM productos WHERE  codigo LIKE p_codigo;
		UPDATE productos SET descripcion_p=p_descripcion, id_categoria=vid_catego, minimo=p_minimo WHERE id_producto=vid_prod;
END$$

DROP PROCEDURE IF EXISTS `nva_cancel`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `nva_cancel` ()  BEGIN
DECLARE vid_ticket INT;
 	SELECT id_ticket into vid_ticket from tickets order by id_ticket desc limit 1;
 	DELETE from ventas where id_ticket=v_ticket; #elimina todas las ventas que corresponden al ticket
 	update tickets set procesado="3" where id_ticket=vid_ticket; #pasa el ticket a un estado de cancelación
 	SELECT ("CANCELADO");
END$$

DROP PROCEDURE IF EXISTS `nva_catego`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `nva_catego` (`pdescripcion_c` VARCHAR(45))  BEGIN
	IF pdescripcion_c=''
	then
		SELECT('INGRESE UNA DESCRIPCIÓN PARA LA NUEVA CATEGORIA.') as error;
		else
			INSERT INTO categorias VALUES(NULL, pdescripcion_c);
			SELECT('NUEVA CATEGORIA REGISTRADA.');
	end if;
END$$

DROP PROCEDURE IF EXISTS `nva_devol`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `nva_devol` (`p_codigo` INT, `p_cantidad` INT, `pid_ticket` INT, `p_causa` TEXT(200))  BEGIN
 	DECLARE vid_prod INT;
 	DECLARE v_cantidad INT;
 	DECLARE v_subtotal FLOAT;
SELECT id_producto into vid_prod FROM productos, ventas, tickets WHERE  productos.codigo=p_codigo and productos.id_producto=ventas.id_producto and ventas.id_ticket=pid_ticket;
SELECT cantidad into v_cantidad FROM productos, ventas, tickets WHERE  productos.id_producto=vid_prod and productos.id_producto=ventas.id_producto and ventas.id_ticket=pid_ticket;
	IF vid_prod=NULL
		then
			SELECT('DATOS DE CÓDIGO O FOLIO INCORRECTOS, VERIFIQUE.') as error;
		else
			IF v_cantidad<=p_cantidad
			then
				UPDATE ventas set cantidad=cantidad-v_cantidad where id_ticket=pid_ticket and id_producto=vid_prod; #E RESTA LA CANTIDAD DE´PRODUCTOS DEVUELTOS A LA VENTA QUE SE REALIZO
				UPDATE productos set existencias=existencias+p_cantidad where  productos.id_producto=vid_prod; # SE AGREGAN LA CANTIDAD DE ARTICULOS AL IVENTARIO
				SELECT precio_venta*p_cantidad into v_subtotal from productos, ventas, tickets where   productos.codigo=p_codigo and productos.id_producto=ventas.id_producto and ventas.id_ticket=pid_ticket; #SE OBTIENE CUÁNTO SE LE HA DE REGRESAR AL CLIENTE
				SELECT ('DEVOLUCIÓN PROCESADA, ENTREGUE AL CLIENTE LA CANTIDAD DE $'+v_subtotal+' PESOS.');
			else
				SELECT('ESA CANTIDAD DE PRODUCTOS A DEVOLVER NO ES CORRECTA.') as error;
			end if;
	end if;
END$$

DROP PROCEDURE IF EXISTS `nva_ent`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `nva_ent` (IN `p_codigo` VARCHAR(45), IN `p_cant` INT, IN `p_costo` FLOAT, IN `p_observ` TEXT)  BEGIN
DECLARE vid_prod INT;
	SELECT id_producto into vid_prod FROM productos WHERE  productos.codigo=p_codigo;
	IF p_cant<=0
		then
		SELECT('LA CANTIDAD DE PRODUCTOS NO ES CORRECTA.') as error;
		else
			IF p_costo<=0
				then
				SELECT( 'EL COSTO DE PRODUCTO NO ES CORRECTO.') as error;
				else
				IF vid_prod=NULL
					then
					SELECT('ESE PRODUCTO NO ESTÁ REGISTRADO.') as error;
					else
						  INSERT INTO entradas VALUES(NULL, vid_prod, p_cant, p_costo, p_observ, CURRENT_TIMESTAMP);
						  UPDATE productos set existencias=existencias+p_cant WHERE productos.codigo=p_codigo;
						  SELECT ('NUEVA ENTRADA REGISTRADA.');
				end if;
		end if;
	end if;
END$$

DROP PROCEDURE IF EXISTS `nva_talla`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `nva_talla` (`pdesc_talla` VARCHAR(45))  BEGIN
	IF pdesc_talla=''
	then
		SELECT('INGRESE UNA DESCRIPCIÓN PARA LA NUEVA TALLA.') as error;
		else
			INSERT INTO tallas VALUES(NULL, pdesc_talla);
	end if;
END$$

DROP PROCEDURE IF EXISTS `nva_venta`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `nva_venta` (`p_codigo` VARCHAR(45), `p_cantidad` INT)  BEGIN
DECLARE vid_prod INT;
DECLARE v_prod INT;
DECLARE v_precio FLOAT;
DECLARE v_subtotal FLOAT;
DECLARE v_ticket INT;
DECLARE v_disponibles INT;
DECLARE v_codigo INT;
SET vid_prod=0;
SET v_precio=0;
SET v_subtotal=0;
SET v_ticket=0;
SET v_disponibles=0;
SET v_codigo=0;
	SELECT id_producto into vid_prod FROM productos WHERE  productos.codigo=p_codigo;

	SELECT COUNT(ventas.id_producto) as v_codigo from productos, ventas where ventas.id_producto=productos.id_producto AND productos.codigo=p_codigo and id_ticket=v_ticket;
	IF v_codigo>0
	then
		UPDATE ventas set cantidad=cantidad+p_cantidad where id_producto=vid_prod;
	else
		IF vid_prod=NULL
			then
			SELECT('ESE PRODUCTO NO ESTÁ REGISTRADO.') as error;
			else
				IF p_cantidad<=0
					then
					SELECT('LA CANTIDAD DE PRODUCTOS NO ES CORRECTA.') as error;
					else
						SELECT existencias into v_disponibles from productos where id_producto=vid_prod;
						IF p_cantidad>v_disponibles
						then
						SELECT('NO HAY SUFICIENTES PRODUCTOS.') as error;
						else
							
							SELECT precio_venta into v_precio from productos where vid_prod=productos.id_producto;
							SET v_subtotal=v_precio*p_cantidad;
						  	SELECT id_ticket into v_ticket from tickets order by id_ticket desc LIMIT 1; #SE OBTIENE EL TIQUET, QUE PÓR LÓGICA ES EL ÚLTIMO AGREGADO
						  	INSERT INTO ventas VALUES(NULL, vid_prod, p_cantidad, v_ticket, v_subtotal); #SE INSERTA EL REGISTRO EN LA TABLA DE VENTAS
						  	UPDATE productos set existencias=existencias-p_cantidad where id_producto=vid_prod; #SE RESTA LA EXISTENCIA EN LA TABLA DE PRODUCTOS
		  					SELECT ('VENTA GENERADA CON ÉXITO.');	
		  				end if;
	  			end if;
	  	end if;
	 end if;
END$$

DROP PROCEDURE IF EXISTS `nvo_prod`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `nvo_prod` (IN `p_codigo` VARCHAR(45), IN `p_desc` VARCHAR(100), IN `p_categoria` VARCHAR(45), IN `p_precio` FLOAT, IN `p_minimo` INT)  BEGIN
DECLARE vid_prod INT;
DECLARE v_codigo VARCHAR(45);
DECLARE vid_catego INT;
	SELECT id_categoria into vid_catego from categorias where descripcion_c LIKE p_categoria;
	IF vid_catego=NULL
		then
		SELECT('LA CATEGORIA NO ES CORRECTA.') AS error;
		else
			IF p_desc=''
				then
				SELECT('LA DESCRIPCIÓN NO ES CORRECTA.') AS error;
				else
					IF p_precio=0
						then
						SELECT('EL COSTO NO PUEDE SER NULO') AS error;
						else
							SELECT count(codigo) into v_codigo from productos where p_codigo=codigo;
							IF v_codigo>0
								then
								SELECT('ESE CÓDIGO YA EXISTE.') AS error;
								else
									IF p_codigo is null
										then
										SELECT('DEBE EXISTIR UN CÓDIGO PARA EL PRODUCTO.') AS error;
										else
											INSERT INTO productos VALUES(NULL, p_codigo, p_desc, vid_catego, p_precio, 0, p_minimo, 0);
											SELECT ('NUEVO PRODUCTO REGISTRADO.');
											
									end if;
							end if;
					end if;
			end if;
	end if;
END$$

DROP PROCEDURE IF EXISTS `nvo_ticket`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `nvo_ticket` (`p_cliente` TEXT)  BEGIN
  INSERT INTO tickets VALUES(NULL, p_cliente, 0, NULL, 0);
  SELECT ('OK');
END$$

DROP PROCEDURE IF EXISTS `prod_tallas`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `prod_tallas` (`pid_prod` INT, `pid_talla` INT)  BEGIN
DECLARE vid_talla INT;
DECLARE vid_prod INT;
	SELECT count(id_talla) into vid_talla from tallas where id_talla=pid_talla;
	IF vid_talla<=0 
	then
		SELECT('LA TALLA ESPECIFICADA NO EXISTE.') as error;
		else
			SELECT count(id_producto) into vid_prod from productos where id_producto=pid_prod;
			IF vid_prod<=0
			then
				SELECT('EL PRODUCTO ESPECIFICADO NO EXISTE.') as error;
				else
					INSERT INTO tallasprods VALUES(NULL, pid_prod, pid_talla);
					SELECT ('PRODUCTO EN TALLA REGISTRADO');
			end if;
	end if;
END$$

DROP PROCEDURE IF EXISTS `ter_venta`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ter_venta` ()  BEGIN
DECLARE vid_ticket INT;
DECLARE v_total FLOAT;
	SELECT id_ticket into vid_ticket from tickets order by id_ticket desc LIMIT 1; #SE OBTIENE EL ÚLTIMO TICKET
	SELECT SUM(subtotal) into v_total FROM ventas WHERE id_ticket=vid_ticket; #SE OBTIENE EL TOTAL DE LA VENTA QUE ESTÁ REGISRADA CON ESE TICKET
	UPDATE tickets set total=v_total where id_ticket=vid_ticket; # SE AGREGA EL TOTAL DE LA VENTA AL TICKET
	UPDATE tickets set fecha=NULL where id_ticket=vid_ticket; # SE ACTUALIZA LA FECHA DE EMISIÓN DEL TICKET
	UPDATE tickets set procesado=1 where id_ticket=vid_ticket; # INDICA QUE EL TICKET HA SIDO PROCESADO.
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion_c` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `descripcion_c`) VALUES
(1, 'Cabeza'),
(2, 'Brazos'),
(3, 'Miembro inferior');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colores`
--

DROP TABLE IF EXISTS `colores`;
CREATE TABLE IF NOT EXISTS `colores` (
  `id_color` int(11) NOT NULL AUTO_INCREMENT,
  `desc_color` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_color`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cortes`
--

DROP TABLE IF EXISTS `cortes`;
CREATE TABLE IF NOT EXISTS `cortes` (
  `id_corte` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `supuesto` float NOT NULL,
  `realidad` float NOT NULL,
  `ubicacion` tinytext,
  PRIMARY KEY (`id_corte`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

DROP TABLE IF EXISTS `entradas`;
CREATE TABLE IF NOT EXISTS `entradas` (
  `id_entrada` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `costo` float DEFAULT NULL,
  `observaciones` tinytext,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_entrada`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`id_entrada`, `id_producto`, `cantidad`, `costo`, `observaciones`, `fecha`) VALUES
(1, 1, 5, 80, '', '2017-11-09 03:08:00'),
(2, 1, 3, 58, '', '2017-11-09 03:12:22'),
(3, 1, 10, 160, '', '2017-11-09 15:37:20'),
(4, 2, 6, 1100, '', '2017-11-09 16:26:56'),
(5, 2, 4, 2000, '', '2017-11-09 16:30:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lapsos`
--

DROP TABLE IF EXISTS `lapsos`;
CREATE TABLE IF NOT EXISTS `lapsos` (
  `id_lapso` int(11) NOT NULL AUTO_INCREMENT,
  `desc_lapso` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_lapso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(45) NOT NULL,
  `descripcion_p` varchar(100) DEFAULT NULL,
  `id_categoria` int(11) NOT NULL,
  `precio_venta` float NOT NULL,
  `precio_adq` float NOT NULL,
  `minimo` int(11) NOT NULL,
  `existencias` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_producto`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `codigo`, `descripcion_p`, `id_categoria`, `precio_venta`, `precio_adq`, `minimo`, `existencias`) VALUES
(3, '12345', 'CollarÃ­n Beige', 1, 280, 0, 4, 0),
(2, '22222', 'Muleta', 3, 270, 0, 2, 1),
(4, '11111', 'Andadera aluminio', 3, 500, 0, 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

DROP TABLE IF EXISTS `reportes`;
CREATE TABLE IF NOT EXISTS `reportes` (
  `id_reporte` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_i` date DEFAULT NULL,
  `fecha_f` date DEFAULT NULL,
  `id_lapso` int(11) NOT NULL,
  `ubicacion` tinytext,
  PRIMARY KEY (`id_reporte`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `show_ventas`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `show_ventas`;
CREATE TABLE IF NOT EXISTS `show_ventas` (
`codigo` varchar(45)
,`producto` varchar(100)
,`precio` float
,`cantidad` int(11)
,`subtotal` float
,`id_venta` int(11)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

DROP TABLE IF EXISTS `tallas`;
CREATE TABLE IF NOT EXISTS `tallas` (
  `id_talla` int(11) NOT NULL AUTO_INCREMENT,
  `desc_talla` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_talla`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
  `id_ticket` int(11) NOT NULL AUTO_INCREMENT,
  `cliente` tinytext,
  `total` float DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `procesado` int(11) NOT NULL,
  PRIMARY KEY (`id_ticket`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`id_ticket`, `cliente`, `total`, `fecha`, `procesado`) VALUES
(1, 'Marco', 0, NULL, 0),
(2, 'Marco', 0, NULL, 0),
(3, 'MArco', 0, NULL, 0),
(4, 'Cari', 0, NULL, 0),
(5, 'Marco', 0, NULL, 0),
(6, 'Marco', 0, NULL, 0),
(7, 'Marco', 0, NULL, 0),
(8, 'Marco', 0, NULL, 0),
(9, 'Marco', 23, NULL, 1),
(10, 'Marco', 0, NULL, 0),
(11, 'Gaby', 540, NULL, 1),
(12, 'gaby', 0, NULL, 0),
(13, 'Gaby', 540, NULL, 1),
(14, 'Marco', 810, NULL, 1),
(15, 'Marco', 0, NULL, 0),
(16, 'Marco', 69, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos`
--

DROP TABLE IF EXISTS `tipos`;
CREATE TABLE IF NOT EXISTS `tipos` (
  `id_tipo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

DROP TABLE IF EXISTS `ventas`;
CREATE TABLE IF NOT EXISTS `ventas` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT '1',
  `id_ticket` int(11) NOT NULL,
  `subtotal` float NOT NULL,
  PRIMARY KEY (`id_venta`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_producto`, `cantidad`, `id_ticket`, `subtotal`) VALUES
(1, 1, 2, 3, 46),
(2, 1, 2, 4, 46),
(3, 1, 2, 6, 46),
(4, 1, 1, 7, 23),
(5, 1, 1, 8, 23),
(6, 1, 1, 9, 23),
(7, 2, 2, 11, 540),
(8, 2, 1, 12, 270),
(9, 2, 1, 12, 270),
(10, 2, 2, 13, 540),
(11, 2, 3, 14, 810),
(12, 1, 2, 16, 46),
(13, 1, 1, 16, 23);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_editproductos`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_editproductos`;
CREATE TABLE IF NOT EXISTS `vista_editproductos` (
`codigo` varchar(45)
,`producto` varchar(100)
,`categoria` varchar(45)
,`minimo` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_entradas`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_entradas`;
CREATE TABLE IF NOT EXISTS `vista_entradas` (
`producto` varchar(100)
,`cantidad` int(11)
,`fecha` timestamp
,`observaciones` tinytext
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_infoprods`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_infoprods`;
CREATE TABLE IF NOT EXISTS `vista_infoprods` (
`id_producto` int(11)
,`codigo` varchar(45)
,`producto` varchar(100)
,`categoria` varchar(45)
,`minimo` int(11)
,`existencias` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_pagot`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_pagot`;
CREATE TABLE IF NOT EXISTS `vista_pagot` (
`id_producto` int(11)
,`descripcion` varchar(100)
,`existencias` int(11)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `show_ventas`
--
DROP TABLE IF EXISTS `show_ventas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `show_ventas`  AS  (select `productos`.`codigo` AS `codigo`,`productos`.`descripcion_p` AS `producto`,`productos`.`precio_venta` AS `precio`,`ventas`.`cantidad` AS `cantidad`,`ventas`.`subtotal` AS `subtotal`,`ventas`.`id_venta` AS `id_venta` from (`productos` join `ventas`) where ((`productos`.`id_producto` = `ventas`.`id_producto`) and (`ventas`.`id_ticket` = (select `tickets`.`id_ticket` from `tickets` order by `tickets`.`id_ticket` desc limit 1)))) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_editproductos`
--
DROP TABLE IF EXISTS `vista_editproductos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_editproductos`  AS  (select `productos`.`codigo` AS `codigo`,`productos`.`descripcion_p` AS `producto`,`categorias`.`descripcion_c` AS `categoria`,`productos`.`minimo` AS `minimo` from (`productos` join `categorias`) where (`productos`.`id_categoria` = `categorias`.`id_categoria`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_entradas`
--
DROP TABLE IF EXISTS `vista_entradas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_entradas`  AS  (select `productos`.`descripcion_p` AS `producto`,`entradas`.`cantidad` AS `cantidad`,`entradas`.`fecha` AS `fecha`,`entradas`.`observaciones` AS `observaciones` from (`productos` join `entradas`) where (`productos`.`id_producto` = `entradas`.`id_producto`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_infoprods`
--
DROP TABLE IF EXISTS `vista_infoprods`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_infoprods`  AS  (select `productos`.`id_producto` AS `id_producto`,`productos`.`codigo` AS `codigo`,`productos`.`descripcion_p` AS `producto`,`categorias`.`descripcion_c` AS `categoria`,`productos`.`minimo` AS `minimo`,`productos`.`existencias` AS `existencias` from (`productos` join `categorias`) where (`productos`.`id_categoria` = `categorias`.`id_categoria`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_pagot`
--
DROP TABLE IF EXISTS `vista_pagot`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_pagot`  AS  (select `productos`.`id_producto` AS `id_producto`,`productos`.`descripcion_p` AS `descripcion`,`productos`.`existencias` AS `existencias` from `productos` where (`productos`.`existencias` <= `productos`.`minimo`)) ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
