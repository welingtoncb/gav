/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 5.7.21-0ubuntu0.16.04.1 : Database - gav
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`gav` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `gav`;

/*Table structure for table `estoque` */

DROP TABLE IF EXISTS `estoque`;

CREATE TABLE `estoque` (
  `CodRef` int(4) NOT NULL AUTO_INCREMENT,
  `CodProduto` int(4) NOT NULL,
  `QtdEstoque` double(10,2) NOT NULL,
  PRIMARY KEY (`CodRef`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `estoque` */

/*Table structure for table `produtos` */

DROP TABLE IF EXISTS `produtos`;

CREATE TABLE `produtos` (
  `CodProduto` int(4) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(100) NOT NULL,
  `DtEntrada` date NOT NULL,
  `VrCompra` double(15,2) NOT NULL,
  `Descricao` text,
  `Imagem` varchar(100) DEFAULT NULL,
  `DtInsercao` datetime DEFAULT NULL,
  `DtAlteracao` datetime DEFAULT NULL,
  PRIMARY KEY (`CodProduto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `produtos` */

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `CodUsuario` int(3) NOT NULL AUTO_INCREMENT,
  `Login` varchar(100) NOT NULL,
  `Senha` varchar(100) NOT NULL,
  PRIMARY KEY (`CodUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `usuario` */

insert  into `usuario`(`CodUsuario`,`Login`,`Senha`) values (1,'fornecedor','*00A51F3F48415C7D4E8908980D443C29C69B60C9'),(2,'vendedor','*96FE90159FCF83CE735111D15167CCC3D6B0A382'),(3,'admin','*4ACFE3202A5FF5CF467898FC58AAB1D615029441');

/*Table structure for table `vendas` */

DROP TABLE IF EXISTS `vendas`;

CREATE TABLE `vendas` (
  `CodRef` int(4) NOT NULL AUTO_INCREMENT,
  `CodProduto` int(4) NOT NULL,
  `Quantidade` double(9,2) NOT NULL,
  `Valor` double(15,2) NOT NULL,
  `CodUsuario` int(4) NOT NULL,
  `DtVenda` datetime NOT NULL,
  PRIMARY KEY (`CodRef`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `vendas` */

insert  into `vendas`(`CodRef`,`CodProduto`,`Quantidade`,`Valor`,`CodUsuario`,`DtVenda`) values (1,4,1.00,45.90,2,'2018-03-10 17:25:17');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
