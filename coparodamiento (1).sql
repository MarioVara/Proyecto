-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-04-2024 a las 22:15:17
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `coparodamiento`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `administrador` varchar(15) NOT NULL,
  `pass` varchar(15) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`administrador`, `pass`) VALUES
('Mario', '14733');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `id` int(11) NOT NULL,
  `circuito` varchar(55) DEFAULT NULL,
  `pais` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`id`, `circuito`, `pais`) VALUES
(1, 'Barcelona', 'España'),
(2, 'Bakú', 'Azerbaiyán'),
(3, 'Monza', 'Italia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras_temporada`
--

CREATE TABLE `carreras_temporada` (
  `id_temporada` int(11) NOT NULL,
  `id_carrera` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion_carrera`
--

CREATE TABLE `clasificacion_carrera` (
  `id` int(11) NOT NULL,
  `posicion` int(11) DEFAULT NULL,
  `puntos` int(11) DEFAULT NULL,
  `pole` tinyint(1) DEFAULT 0,
  `vuelta_rapida` tinyint(1) DEFAULT 0,
  `sancion` int(11) DEFAULT 0,
  `sprint` tinyint(1) DEFAULT 0,
  `puntos_sprint` int(11) DEFAULT 0,
  `id_piloto` int(11) DEFAULT NULL,
  `id_carrera` int(11) DEFAULT NULL,
  `temporada` int(11) DEFAULT NULL,
  `id_equipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clasificacion_carrera`
--

INSERT INTO `clasificacion_carrera` (`id`, `posicion`, `puntos`, `pole`, `vuelta_rapida`, `sancion`, `sprint`, `puntos_sprint`, `id_piloto`, `id_carrera`, `temporada`, `id_equipo`) VALUES
(11, 1, 0, 0, 0, 0, 0, 0, 1, 2, 1, 2),
(12, 2, 0, 0, 0, 0, 0, 0, 2, 2, 1, 3),
(13, 3, 0, 0, 0, 0, 0, 0, 3, 2, 1, 1);

--
-- Disparadores `clasificacion_carrera`
--
DELIMITER $$
CREATE TRIGGER `clasificacion_equipos_insert` AFTER INSERT ON `clasificacion_carrera` FOR EACH ROW BEGIN
	DECLARE existe_equipo INT;
    DECLARE puntos_ganados INT;
    DECLARE vueltarapida INT;
    DECLARE poles INT;
    DECLARE equipo INT;
    
 -- Mira si hay algún registro con el equipo y la temporada que acabamos de introducir
    SET existe_equipo = (SELECT COUNT(*) FROM clasifica_equipos
        								WHERE id_equipo = (SELECT id_equipo FROM compite 
                                                           WHERE id_piloto = NEW.id_piloto 
                                                            AND id_temporada = NEW.temporada));
    IF existe_equipo = 0 THEN     
	-- Si el equipo no existe, crea un registro que pone el equipo y la temporada que acabamos de meter, pero los puntos y las vueltas rápidas a 0
    	INSERT INTO clasifica_equipos (id_equipo, id_temporada, puntos_totales, total_poles, vueltas_rapidas)
        VALUES ((SELECT id_equipo FROM compite WHERE id_piloto = NEW.id_piloto AND id_temporada = NEW.temporada), NEW.temporada, 0, 0, 0);
    END IF;
    
    -- Guardo el equipo del piloto
    SELECT id_equipo INTO equipo 
    FROM clasificacion_carrera 
    	WHERE id_piloto = NEW.id_piloto
   		AND temporada = NEW.temporada;
        
	-- Si existe un registro, entonces se actualiza el resultado
    SELECT SUM(puntos + puntos_sprint)
    	INTO puntos_ganados
               FROM clasificacion_carrera
                       WHERE id_equipo = equipo
   						AND temporada = NEW.temporada;


        -- Seleccionas los puntos y los puntos sprint en la clasificación_carrera y los sumas.
    UPDATE clasifica_equipos
    SET puntos_totales = puntos_ganados
    WHERE id_equipo = (SELECT id_equipo 
                       FROM compite
                       WHERE id_piloto = NEW.id_piloto
   						AND id_temporada = NEW.temporada);
                        
 -- Recoge todas las vueltas rápidas del equipo
    SELECT COALESCE(SUM(vuelta_rapida),0) INTO vueltarapida
     FROM clasificacion_carrera
                       WHERE id_equipo = equipo
   						AND temporada = NEW.temporada;
	UPDATE clasifica_equipos
        SET vueltas_rapidas = vueltarapida
        WHERE id_equipo = (SELECT id_equipo 
                           FROM compite 
                           WHERE id_piloto = NEW.id_piloto
        					AND id_temporada = NEW.temporada);
	
-- Recoge todas las poles rápidas del equipo
        SELECT COALESCE(SUM(pole),0) INTO poles
     FROM clasificacion_carrera
                       WHERE id_equipo = equipo
   						AND temporada = NEW.temporada;
	UPDATE clasifica_equipos
        SET total_poles = poles
        WHERE id_equipo = (SELECT id_equipo 
                           FROM compite 
                           WHERE id_piloto = NEW.id_piloto
        					AND id_temporada = NEW.temporada);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `clasificacion_equipos_update` AFTER UPDATE ON `clasificacion_carrera` FOR EACH ROW BEGIN
	DECLARE existe_equipo INT;
    DECLARE puntos_ganados INT;
    DECLARE vueltarapida INT;
    DECLARE poles INT;
    DECLARE equipo INT;
    
 -- Mira si hay algún registro con el equipo y la temporada que acabamos de introducir
    SET existe_equipo = (SELECT COUNT(*) FROM clasifica_equipos
        								WHERE id_equipo = (SELECT id_equipo FROM compite 
                                                           WHERE id_piloto = NEW.id_piloto 
                                                            AND id_temporada = NEW.temporada));
    IF existe_equipo = 0 THEN     
	-- Si el equipo no existe, crea un registro que pone el equipo y la temporada que acabamos de meter, pero los puntos y las vueltas rápidas a 0
    	INSERT INTO clasifica_equipos (id_equipo, id_temporada, puntos_totales, total_poles, vueltas_rapidas)
        VALUES ((SELECT id_equipo FROM compite WHERE id_piloto = NEW.id_piloto AND id_temporada = NEW.temporada), NEW.temporada, 0, 0, 0);
    END IF;
    
    -- Guardo el equipo del piloto
    SELECT id_equipo INTO equipo 
    FROM clasificacion_carrera 
    	WHERE id_piloto = NEW.id_piloto
   		AND temporada = NEW.temporada;
        
	-- Si existe un registro, entonces se actualiza el resultado
    SELECT SUM(puntos + puntos_sprint)
    	INTO puntos_ganados
               FROM clasificacion_carrera
                       WHERE id_equipo = equipo
   						AND temporada = NEW.temporada;


        -- Seleccionas los puntos y los puntos sprint en la clasificación_carrera y los sumas.
    UPDATE clasifica_equipos
    SET puntos_totales = puntos_ganados
    WHERE id_equipo = (SELECT id_equipo 
                       FROM compite
                       WHERE id_piloto = NEW.id_piloto
   						AND id_temporada = NEW.temporada);
                        
 -- Recoge todas las vueltas rápidas del equipo
    SELECT COALESCE(SUM(vuelta_rapida),0) INTO vueltarapida
     FROM clasificacion_carrera
                       WHERE id_equipo = equipo
   						AND temporada = NEW.temporada;
	UPDATE clasifica_equipos
        SET vueltas_rapidas = vueltarapida
        WHERE id_equipo = (SELECT id_equipo 
                           FROM compite 
                           WHERE id_piloto = NEW.id_piloto
        					AND id_temporada = NEW.temporada);
	
-- Recoge todas las poles rápidas del equipo
        SELECT COALESCE(SUM(pole),0) INTO poles
     FROM clasificacion_carrera
                       WHERE id_equipo = equipo
   						AND temporada = NEW.temporada;
	UPDATE clasifica_equipos
        SET total_poles = poles
        WHERE id_equipo = (SELECT id_equipo 
                           FROM compite 
                           WHERE id_piloto = NEW.id_piloto
        					AND id_temporada = NEW.temporada);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `clasificacion_pilotos_insert` AFTER INSERT ON `clasificacion_carrera` FOR EACH ROW BEGIN
	DECLARE existe_piloto INT;
    DECLARE puntos_ganados INT;
    DECLARE vueltarapida INT;
    DECLARE poles INT;
    
 -- Mira si hay algún registro con el piloto y la temporada que acabamos de introducir
    SET existe_piloto = (SELECT COUNT(*) FROM clasificacion_piloto
        								WHERE id_piloto = NEW.id_piloto 
                                        AND id_temporada = NEW.temporada);
-- Si el piloto no existe, crea un registro que pone el piloto y la temporada que acabamos de meter, pero los puntos, las vueltas rápidas y las poles a 0
    IF existe_piloto = 0 THEN     
    	INSERT INTO clasificacion_piloto (id_piloto, id_temporada, total_poles, total_puntos, total_rapidas)
        VALUES (NEW.id_piloto, NEW.temporada, 0, 0, 0);
    END IF;
    
-- Si existe un registro, entonces se actualiza el resultado
-- Suma todos los puntos ganados por el piloto
   SELECT SUM(puntos + puntos_sprint) INTO puntos_ganados
    						FROM clasificacion_carrera
                            WHERE id_piloto = NEW.id_piloto
         					 AND temporada = NEW.temporada; 
                             
-- Actualiza los puntos totales
    UPDATE clasificacion_piloto
        SET total_puntos = puntos_ganados
        WHERE id_piloto = NEW.id_piloto
         AND id_temporada = NEW.temporada;
         
-- Recoge todas las vueltas rápidas y las actualiza.
    SELECT COALESCE(SUM(vuelta_rapida),0) INTO vueltarapida
    	FROM clasificacion_carrera
        WHERE id_piloto = NEW.id_piloto
        	 AND temporada = NEW.temporada;
    
    	UPDATE clasificacion_piloto
        SET total_rapidas = vueltarapida
        	WHERE id_piloto = NEW.id_piloto
        	 AND id_temporada = NEW.temporada;
    
-- Recoge todas las poles y las actualiza
    SELECT COALESCE(SUM(pole),0) INTO poles
    	FROM clasificacion_carrera
        WHERE id_piloto = NEW.id_piloto
        	 AND temporada = NEW.temporada;
    
    	UPDATE clasificacion_piloto
        SET total_poles = poles
        	WHERE id_piloto = NEW.id_piloto
        	 AND id_temporada = NEW.temporada;
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `clasificacion_pilotos_update` AFTER UPDATE ON `clasificacion_carrera` FOR EACH ROW BEGIN
	DECLARE existe_piloto INT;
    DECLARE puntos_ganados INT;
    DECLARE vueltarapida INT;
    DECLARE poles INT;
 -- Mira si hay algún registro con el piloto y la temporada que acabamos de introducir
    SET existe_piloto = (SELECT COUNT(*) FROM clasificacion_piloto
        								WHERE id_piloto = NEW.id_piloto 
                                        AND id_temporada = NEW.temporada);
    IF existe_piloto = 0 THEN     
	-- Si el piloto no existe, crea un registro que pone el piloto y la temporada que acabamos de meter, pero los puntos y las vueltas rápidas a  
    	INSERT INTO clasificacion_piloto (id_piloto, id_temporada, total_poles, total_puntos, total_rapidas)
        VALUES (NEW.id_piloto, NEW.temporada, 0, 0, 0);
    END IF;
	-- Si existe un registro, entonces se actualiza el resultado
	
   SELECT SUM(puntos + puntos_sprint) INTO puntos_ganados
    						FROM clasificacion_carrera
                            WHERE id_piloto = NEW.id_piloto
         					 AND temporada = NEW.temporada; 


        -- Seleccionas los puntos y los puntos sprint que acabas de meter en la clasificación_carrera y los sumas.
    UPDATE clasificacion_piloto
        SET total_puntos = puntos_ganados
        WHERE id_piloto = NEW.id_piloto
         AND id_temporada = NEW.temporada;
         
	-- Recoge todas las vueltas rápidas y las actualiza.
    SELECT COALESCE(SUM(vuelta_rapida),0) INTO vueltarapida
    	FROM clasificacion_carrera
        WHERE id_piloto = NEW.id_piloto
        	 AND temporada = NEW.temporada;
    
    	UPDATE clasificacion_piloto
        SET total_rapidas = vueltarapida
        	WHERE id_piloto = NEW.id_piloto
        	 AND id_temporada = NEW.temporada;
    
-- Recoge todas las poles y las actualiza
    SELECT COALESCE(SUM(pole),0) INTO poles
    	FROM clasificacion_carrera
        WHERE id_piloto = NEW.id_piloto
        	 AND temporada = NEW.temporada;
    
    	UPDATE clasificacion_piloto
        SET total_poles = poles
        	WHERE id_piloto = NEW.id_piloto
        	 AND id_temporada = NEW.temporada;
    
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificacion_piloto`
--

CREATE TABLE `clasificacion_piloto` (
  `id_piloto` int(11) NOT NULL,
  `id_temporada` int(11) NOT NULL,
  `total_puntos` int(11) DEFAULT NULL,
  `total_rapidas` int(11) DEFAULT NULL,
  `total_poles` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clasificacion_piloto`
--

INSERT INTO `clasificacion_piloto` (`id_piloto`, `id_temporada`, `total_puntos`, `total_rapidas`, `total_poles`) VALUES
(1, 1, 0, 0, 0),
(2, 1, 0, 0, 0),
(3, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasifica_equipos`
--

CREATE TABLE `clasifica_equipos` (
  `id_equipo` int(11) NOT NULL,
  `id_temporada` int(11) NOT NULL,
  `puntos_totales` int(11) DEFAULT 0,
  `vueltas_rapidas` int(11) DEFAULT 0,
  `total_poles` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clasifica_equipos`
--

INSERT INTO `clasifica_equipos` (`id_equipo`, `id_temporada`, `puntos_totales`, `vueltas_rapidas`, `total_poles`) VALUES
(1, 1, 0, 0, 0),
(2, 1, 0, 0, 0),
(3, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compite`
--

CREATE TABLE `compite` (
  `id_piloto` int(11) NOT NULL,
  `id_temporada` int(11) NOT NULL,
  `id_equipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compite`
--

INSERT INTO `compite` (`id_piloto`, `id_temporada`, `id_equipo`) VALUES
(1, 1, 2),
(1, 4, 2),
(2, 1, 3),
(2, 4, 2),
(3, 1, 1),
(3, 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`id`, `nombre`) VALUES
(1, 'Ferrari'),
(2, 'Aston Martin'),
(3, 'Haas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `piloto`
--

CREATE TABLE `piloto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(55) DEFAULT NULL,
  `nickname` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `piloto`
--

INSERT INTO `piloto` (`id`, `nombre`, `nickname`) VALUES
(1, 'Mario Garcia', 'Mario_Vara'),
(2, 'Adrian Olmedo', 'AdriOlmedo5'),
(3, 'Jesus', 'Chuchi');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `temporada`
--

CREATE TABLE `temporada` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `temporada`
--

INSERT INTO `temporada` (`id`) VALUES
(1),
(2),
(3),
(4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `carreras_temporada`
--
ALTER TABLE `carreras_temporada`
  ADD PRIMARY KEY (`id_temporada`,`id_carrera`),
  ADD KEY `id_carrera` (`id_carrera`);

--
-- Indices de la tabla `clasificacion_carrera`
--
ALTER TABLE `clasificacion_carrera`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_piloto` (`id_piloto`),
  ADD KEY `id_carrera` (`id_carrera`),
  ADD KEY `temporada` (`temporada`);

--
-- Indices de la tabla `clasificacion_piloto`
--
ALTER TABLE `clasificacion_piloto`
  ADD PRIMARY KEY (`id_piloto`,`id_temporada`),
  ADD KEY `id_temporada` (`id_temporada`);

--
-- Indices de la tabla `clasifica_equipos`
--
ALTER TABLE `clasifica_equipos`
  ADD PRIMARY KEY (`id_equipo`,`id_temporada`),
  ADD KEY `id_temporada` (`id_temporada`);

--
-- Indices de la tabla `compite`
--
ALTER TABLE `compite`
  ADD PRIMARY KEY (`id_piloto`,`id_temporada`,`id_equipo`),
  ADD KEY `id_temporada` (`id_temporada`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `piloto`
--
ALTER TABLE `piloto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `temporada`
--
ALTER TABLE `temporada`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrera`
--
ALTER TABLE `carrera`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clasificacion_carrera`
--
ALTER TABLE `clasificacion_carrera`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `piloto`
--
ALTER TABLE `piloto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `temporada`
--
ALTER TABLE `temporada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carreras_temporada`
--
ALTER TABLE `carreras_temporada`
  ADD CONSTRAINT `carreras_temporada_ibfk_1` FOREIGN KEY (`id_temporada`) REFERENCES `temporada` (`id`),
  ADD CONSTRAINT `carreras_temporada_ibfk_2` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id`);

--
-- Filtros para la tabla `clasificacion_carrera`
--
ALTER TABLE `clasificacion_carrera`
  ADD CONSTRAINT `clasificacion_carrera_ibfk_1` FOREIGN KEY (`id_piloto`) REFERENCES `piloto` (`id`),
  ADD CONSTRAINT `clasificacion_carrera_ibfk_2` FOREIGN KEY (`id_carrera`) REFERENCES `carrera` (`id`),
  ADD CONSTRAINT `temporada` FOREIGN KEY (`temporada`) REFERENCES `temporada` (`id`);

--
-- Filtros para la tabla `clasificacion_piloto`
--
ALTER TABLE `clasificacion_piloto`
  ADD CONSTRAINT `clasificacion_piloto_ibfk_1` FOREIGN KEY (`id_piloto`) REFERENCES `piloto` (`id`),
  ADD CONSTRAINT `clasificacion_piloto_ibfk_2` FOREIGN KEY (`id_temporada`) REFERENCES `temporada` (`id`);

--
-- Filtros para la tabla `clasifica_equipos`
--
ALTER TABLE `clasifica_equipos`
  ADD CONSTRAINT `clasifica_equipos_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id`),
  ADD CONSTRAINT `clasifica_equipos_ibfk_2` FOREIGN KEY (`id_temporada`) REFERENCES `temporada` (`id`);

--
-- Filtros para la tabla `compite`
--
ALTER TABLE `compite`
  ADD CONSTRAINT `compite_ibfk_1` FOREIGN KEY (`id_piloto`) REFERENCES `piloto` (`id`),
  ADD CONSTRAINT `compite_ibfk_2` FOREIGN KEY (`id_temporada`) REFERENCES `temporada` (`id`),
  ADD CONSTRAINT `compite_ibfk_3` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
