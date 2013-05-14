-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 19-11-2010 a las 10:57:29
-- Versión del servidor: 5.0.51
-- Versión de PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `mps`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_books`
-- 

CREATE TABLE `prefix_books` (
  `id` bigint(10) NOT NULL auto_increment,
  `isbn` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `format` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- 
-- Volcar la base de datos para la tabla `mps_books`
-- 

INSERT INTO `prefix_books` VALUES (1, '1111111111', 'Llibre scorm remot sense unitat', '1ESO', 'scorm', '1111111111/imsmanifest.xml');
INSERT INTO `prefix_books` VALUES (2, '2222222222', 'Llibre contingut remot sense unitat', '1ESO', 'webcontent', '2222222222/index.php');
INSERT INTO `prefix_books` VALUES (3, '3333333333', 'Llibre scorm remot amb dues unitats', '1ESO', 'scorm', '3333333333/imsmanifest.xml');
INSERT INTO `prefix_books` VALUES (4, '4444444444', 'Llibre contingut remot amb dues unitats', '1ESO', 'webcontent', '4444444444/index.php');
INSERT INTO `prefix_books` VALUES (5, '5555555555', 'Llibre scorm remot amb dues activitats', '2ESO', 'scorm', '5555555555/imsmanifest.xml');
INSERT INTO `prefix_books` VALUES (6, '6666666666', 'Llibre contingut remot amb dues activitats', '2ESO', 'webcontent', '6666666666/index.php');
INSERT INTO `prefix_books` VALUES (7, '7777777777', 'Llibre contingut remot jeràrquic', '1ESO', 'webcontent', '7777777777/index.php');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_books_activities`
-- 

CREATE TABLE `prefix_books_activities` (
  `id` bigint(10) NOT NULL auto_increment,
  `bookid` bigint(10) NOT NULL,
  `unitid` bigint(10) NOT NULL,
  `code` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sortorder` bigint(10) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- 
-- Volcar la base de datos para la tabla `mps_books_activities`
-- 

INSERT INTO `prefix_books_activities` VALUES (1, 5, 5, '1', 'Activitat 1', 1, '5555555555/55555/111/imsmanifest.xml');
INSERT INTO `prefix_books_activities` VALUES (2, 5, 5, '2', 'Activitat 2', 2, '5555555555/55555/222/imsmanifest.xml');
INSERT INTO `prefix_books_activities` VALUES (3, 5, 6, '1', 'Activitat 1', 1, '5555555555/66666/333/imsmanifest.xml');
INSERT INTO `prefix_books_activities` VALUES (4, 5, 6, '2', 'Activitat 2', 2, '5555555555/66666/444/imsmanifest.xml');
INSERT INTO `prefix_books_activities` VALUES (5, 6, 7, '1', 'Activitat 1', 1, '6666666666/77777/555/index.php');
INSERT INTO `prefix_books_activities` VALUES (6, 6, 7, '2', 'Activitat 2', 2, '6666666666/77777/666/index.php');
INSERT INTO `prefix_books_activities` VALUES (7, 6, 8, '1', 'Activitat 1', 1, '6666666666/88888/777/index.php');
INSERT INTO `prefix_books_activities` VALUES (8, 6, 8, '2', 'Activitat 2', 2, '6666666666/88888/888/index.php');
INSERT INTO `prefix_books_activities` VALUES (9, 7, 9, '1', 'Activitat 1', 1, '7777777777/Unit1/Act1/index.php');
INSERT INTO `prefix_books_activities` VALUES (10,7, 9, '2', 'Activitat 2', 2, '7777777777/Unit1/Act2/index.php');
INSERT INTO `prefix_books_activities` VALUES (11, 7, 9, '3', 'Activitat 3', 3, '7777777777/Unit1/Act3/index.php');
INSERT INTO `prefix_books_activities` VALUES (12, 7, 10, '1', 'Activitat 1', 1, '7777777777/Unit2/Act1/index.php');
INSERT INTO `prefix_books_activities` VALUES (13, 7, 10, '2', 'Activitat 2', 2, '7777777777/Unit2/Act2/index.php');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_books_credentials`
-- 

CREATE TABLE `prefix_books_credentials` (
  `id` bigint(10) NOT NULL auto_increment,
  `isbn` varchar(255) NOT NULL,
  `credentials` varchar(255) NULL,
  `success` bigint(1) NOT NULL,
  `code` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

-- 
-- Volcar la base de datos para la tabla `mps_books_credentials`
-- 

INSERT INTO `prefix_books_credentials` VALUES (1, '1111111111', '1', 1, '1', 'URL generada correctament', 'http://www.xtec.cat/llibre.html');
INSERT INTO `prefix_books_credentials` VALUES (2, '1111111111', '0', 0, '0', 'Error inesperat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (3, '1111111111', '-1', 0, '-1', 'Error al realitzar la URL dinàmica', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (4, '1111111111', '-2', 0, '-2', 'El codi de llicencia no es vàlid', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (5, '1111111111', '-4', 0, '-4', 'La llicencia ha expirat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (6, '2222222222', '1', 1, '1', 'URL generada correctament', 'http://www.xtec.cat/llibre.html');
INSERT INTO `prefix_books_credentials` VALUES (7, '2222222222', '0', 0, '0', 'Error inesperat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (8, '2222222222', '-1', 0, '-1', 'Error al realitzar la URL dinàmica', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (9, '2222222222', '-2', 0, '-2', 'El codi de llicencia no es vàlid', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (10, '2222222222', '-4', 0, '-4', 'La llicencia ha expirat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (11, '3333333333', '1', 1, '1', 'URL generada correctament', 'http://www.xtec.cat/llibre.html');
INSERT INTO `prefix_books_credentials` VALUES (12, '3333333333', '0', 0, '0', 'Error inesperat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (13, '3333333333', '-1', 0, '-1', 'Error al realitzar la URL dinàmica', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (14, '3333333333', '-2', 0, '-2', 'El codi de llicencia no es vàlid', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (15, '3333333333', '-4', 0, '-4', 'La llicencia ha expirat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (16, '4444444444', '1', 1, '1', 'URL generada correctament', 'http://www.xtec.cat/llibre.html');
INSERT INTO `prefix_books_credentials` VALUES (17, '4444444444', '0', 0, '0', 'Error inesperat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (18, '4444444444', '-1', 0, '-1', 'Error al realitzar la URL dinàmica', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (19, '4444444444', '-2', 0, '-2', 'El codi de llicencia no es vàlid', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (20, '4444444444', '-4', 0, '-4', 'La llicencia ha expirat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (21, '5555555555', '1', 1, '1', 'URL generada correctament', 'http://www.xtec.cat/llibre.html');
INSERT INTO `prefix_books_credentials` VALUES (22, '5555555555', '0', 0, '0', 'Error inesperat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (23, '5555555555', '-1', 0, '-1', 'Error al realitzar la URL dinàmica', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (24, '5555555555', '-2', 0, '-2', 'El codi de llicencia no es vàlid', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (25, '5555555555', '-4', 0, '-4', 'La llicencia ha expirat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (26, '6666666666', '1', 1, '1', 'URL generada correctament', 'http://www.xtec.cat/llibre.html');
INSERT INTO `prefix_books_credentials` VALUES (27, '6666666666', '0', 0, '0', 'Error inesperat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (28, '6666666666', '-1', 0, '-1', 'Error al realitzar la URL dinàmica', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (29, '6666666666', '-2', 0, '-2', 'El codi de llicencia no es vàlid', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (30, '6666666666', '-4', 0, '-4', 'La llicencia ha expirat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (31, '7777777777', '1', 1, '1', 'URL generada correctament', 'http://www.xtec.cat/llibre.html'); 
INSERT INTO `prefix_books_credentials` VALUES (32, '7777777777', '0', 0, '0', 'Error inesperat', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (33, '7777777777', '-1', 0, '-1', 'Error al realitzar la URL dinàmica', 'http://www.xtec.cat/error.html');
INSERT INTO `prefix_books_credentials` VALUES (34, '7777777777', '-2', 0, '-2', 'El codi de llicencia no es vàlid', 'http://www.xtec.cat/error.html');                                   
INSERT INTO `prefix_books_credentials` VALUES (35, '7777777777', '-4', 0, '-4', 'La llicencia ha expirat', 'http://www.xtec.cat/error.html');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_books_units`
-- 

CREATE TABLE `prefix_books_units` (
  `id` bigint(10) NOT NULL auto_increment,
  `bookid` bigint(10) NOT NULL,
  `code` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sortorder` bigint(10) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- 
-- Volcar la base de datos para la tabla `mps_books_units`
-- 

INSERT INTO `prefix_books_units` VALUES (1, 3, '1', 'Unitat 1', 1, '3333333333/11111/imsmanifest.xml');
INSERT INTO `prefix_books_units` VALUES (2, 3, '2', 'Unitat 2', 2, '3333333333/22222/imsmanifest.xml');
INSERT INTO `prefix_books_units` VALUES (3, 4, '1', 'Unitat 1', 1, '4444444444/33333/index.php');
INSERT INTO `prefix_books_units` VALUES (4, 4, '2', 'Unitat 2', 2, '4444444444/44444/index.php');
INSERT INTO `prefix_books_units` VALUES (5, 5, '1', 'Unitat 1', 1, '5555555555/55555/imsmanifest.xml');
INSERT INTO `prefix_books_units` VALUES (6, 5, '2', 'Unitat 2', 2, '5555555555/66666/imsmanifest.xml');
INSERT INTO `prefix_books_units` VALUES (7, 6, '1', 'Unitat 1', 1, '6666666666/77777/index.php');
INSERT INTO `prefix_books_units` VALUES (8, 6, '2', 'Unitat 2', 2, '6666666666/88888/index.php');
INSERT INTO `prefix_books_units` VALUES (9, 7, 1, 'Unitat 1 (Autoavaluada)', 1, '7777777777/Unit1/index.php');
INSERT INTO `prefix_books_units` VALUES (10, 7, 2, 'Unitat 2 (Avaluada per professor)', 2, '7777777777/Unit2/index.php');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_categories`
-- 

CREATE TABLE `prefix_categories` (
  `id` bigint(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- Volcar la base de datos para la tabla `mps_categories`
-- 

INSERT INTO `prefix_categories` VALUES (1, 'authentication');
INSERT INTO `prefix_categories` VALUES (2, 'bookstructure');
INSERT INTO `prefix_categories` VALUES (3, 'tracking');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_center`
-- 

CREATE TABLE `prefix_center` (
  `id` bigint(10) NOT NULL auto_increment,
  `code` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

-- 
-- Volcar la base de datos para la tabla `mps_center`
-- 

INSERT INTO `prefix_center` VALUES (1, '001', 'Marsupial School Simulator');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_center_books`
-- 

CREATE TABLE `prefix_center_books` (
  `id` bigint(10) NOT NULL auto_increment,
  `bookid` bigint(10) NOT NULL,
  `centerid` bigint(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;

-- 
-- Volcar la base de datos para la tabla `mps_center_books`
-- 

INSERT INTO `prefix_center_books` VALUES (1, 3, 1);
INSERT INTO `prefix_center_books` VALUES (2, 6, 1);

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_config`
-- 

CREATE TABLE `prefix_config` (
  `id` bigint(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15;

-- 
-- Volcar la base de datos para la tabla `mps_config`
-- 

INSERT INTO `prefix_config` VALUES (1, 'theme', 'mpstheme');
INSERT INTO `prefix_config` VALUES (2, 'style', 'default');
INSERT INTO `prefix_config` VALUES (3, 'template', 'default');
INSERT INTO `prefix_config` VALUES (4, 'timezone', '99');
INSERT INTO `prefix_config` VALUES (5, 'country', 'ES');
INSERT INTO `prefix_config` VALUES (6, 'lang', 'ca_utf8');
INSERT INTO `prefix_config` VALUES (7, 'langlist', 'ca');
INSERT INTO `prefix_config` VALUES (8, 'sitename', 'MPS - Marsupial Simulador d''Editorial');
INSERT INTO `prefix_config` VALUES (9, 'session_error_counter', '8');
INSERT INTO `prefix_config` VALUES (10, 'debugmode', '0');
INSERT INTO `prefix_config` VALUES (11, 'limitviewentries', '100');
INSERT INTO `prefix_config` VALUES (12, 'sessiontimeout', '7200');
INSERT INTO `prefix_config` VALUES (13, 'sessioncookie', 'mps');
INSERT INTO `prefix_config` VALUES (14, 'sessioncookiepath', '/');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_lms_ws_credentials`
-- 

CREATE TABLE `prefix_lms_ws_credentials` (
  `id` bigint(10) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `success` bigint(1) NOT NULL,
  `code` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- Volcar la base de datos para la tabla `mps_lms_ws_credentials`
-- 

INSERT INTO `prefix_lms_ws_credentials` VALUES (1, 'S1mul4d0r', 'ed1t0r14l', 1, '1', 'Usuari/contrasenya amb drets');
INSERT INTO `prefix_lms_ws_credentials` VALUES (2, 'USP', 'PUSP', 0, '-102', 'Usuari/contrasenya sense permisos suficients');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_log`
-- 

CREATE TABLE `prefix_log` (
  `id` bigint(10) NOT NULL auto_increment,
  `time` bigint(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `categoryid` text NOT NULL,
  `actionid` varchar(255) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `mps_log`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_log_errors`
-- 

CREATE TABLE `prefix_log_errors` (
  `id` bigint(10) NOT NULL auto_increment,
  `time` bigint(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `actionid` varchar(255) NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `mps_log_errors`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_publishers_credentials`
-- 

CREATE TABLE `prefix_publishers_credentials` (
  `id` bigint(10) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `success` bigint(1) NOT NULL,
  `code` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `mps_publishers_credentials`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_users`
-- 

CREATE TABLE `prefix_users` (
  `id` bigint(10) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `mps_users`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mps_sessions`
-- 

CREATE TABLE `prefix_sessions` (
  `id` bigint(10) NOT NULL auto_increment,
  `token` varchar(255) NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `userid` bigint(10) NOT NULL,
  `nameape` varchar(255) NOT NULL,
  `groupid` bigint(10) NOT NULL,
  `courseid` bigint(10) NOT NULL,
  `centerid` bigint(10) NOT NULL,
  `wsurltracking` varchar(255) NOT NULL,
  `lmscontentid` bigint(10) NOT NULL,
  `unitid` varchar(100) NOT NULL,
  `activityid` varchar(100) NOT NULL,
  `addtime` bigint(10) NOT NULL,
  `expiretime` bigint(10) NOT NULL,
  `urlcontent` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `mps_sessions`
-- 
