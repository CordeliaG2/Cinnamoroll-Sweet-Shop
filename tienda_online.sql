-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-12-2024 a las 02:00:27
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_online`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,0) NOT NULL,
  `descuento` tinyint(3) NOT NULL DEFAULT 0,
  `id_categoria` int(11) NOT NULL,
  `activo` int(11) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `descuento`, `id_categoria`, `activo`, `stock`, `imagen`) VALUES
(1, 'Cinnamorroll Milk-Shake', 'Producto original, se trata de una bebida de leche sabor a chicle, Única en su tipo (Por ahora), y lo mejor, trae 3 sellos por exceso de \"chilemorron\".\r\n(500 ml)', 50, 0, 1, 1, 0, 'images/productos/1/principal.png'),
(2, 'Blue eyed Dragon', 'Curiosa figura producto de la colaboración entre sanrio y yu-gi-oh, distribuido por McDonald\'s, fue muy difícil de conseguir en su momento, ahora aparecen en el tianguis a 20 pesos.', 100, 20, 1, 1, 0, 'images/productos/2/principal.png'),
(9, 'Peluche cinnamoroll', '¿Quién no necesita un amigo blandito que siempre esté ahí para abrazarte? Este adorable peluche de Cinnamoroll no solo es el compañero perfecto para largas siestas y noches de películas, sino que también puede aumentar tu felicidad. Algunos dicen que su ternura puede ahuyentar hasta el peor de los días grises. ¡Pruébalo!', 500, 0, 0, 1, 1, 'images/productos/9/principal.png'),
(10, 'Taza', 'Comienza tus mañanas con una taza de café... o de pura magia pastel. Esta taza de Cinnamoroll es más que un recipiente para líquidos calientes; es un catalizador de inspiración. Se rumorea que beber de ella mejora la productividad y te hace más amable con tus vecinos. Aunque claro, ¡eso depende de lo que pongas dentro!', 100, 0, 0, 1, 5, 'images/productos/10/principal.png'),
(11, 'Pin cinnamoroll', '¿Tu atuendo necesita un toque extra de estilo? Este pin de Cinnamoroll no solo es el accesorio más lindo de la temporada, sino que también lleva un poco de la magia del cielo contigo. Colócalo en tu mochila o chaqueta y prepárate para recibir cumplidos y buena suerte en tus aventuras diarias.', 30, 0, 0, 1, 10, 'images/productos/11/principal.png'),
(12, 'Funko pop cinnamoroll', 'Este Funko Pop de Cinnamoroll no es solo una figura coleccionable; es el guardián de la felicidad en tu escritorio. Perfecto para inspirar tu lado más adorable mientras trabajas, estudias o planeas conquistar el mundo de manera amable. Se dice que mirarlo fijamente durante 5 segundos aumenta tu felicidad... ¿Coincidencia? No lo creemos.', 700, 10, 0, 1, 10, 'images/productos/12/principal.png'),
(13, 'tenis', '¡Camina con estilo, comodidad y un toque de magia celestial! Estos tenis de Cinnamoroll son ideales para conquistar el mundo (o al menos el parque más cercano). Cómodos, adorables y con estampados tan lindos que podrían derretir corazones a tu paso. Algunos usuarios reportan que caminar con ellos se siente como flotar en una nube.\"', 700, 50, 0, 1, 3, 'images/productos/13/principal.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('cliente','admin') DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contraseña`, `rol`) VALUES
(1, 'Administrador', 'admin@cinnamoroll.com', '$2y$10$wnTWEnNQ03SKUBpTXU1KguLL2hGPw5wo1.9I9X1fm7lHa.VoXhb2C', 'admin'),
(2, 'Servin', 'cliente@cinnamoroll.com', '$2y$10$XE/SvF2KGRvJS9iTWwiPWeom55NcZZFyP1h38gF3v2okG/30gWDM2', 'cliente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
