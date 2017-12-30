/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.1.28-MariaDB : Database - ci_book
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ci_book` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `ci_book`;

/*Table structure for table `ci_board` */

DROP TABLE IF EXISTS `ci_board`;

CREATE TABLE `ci_board` (
  `board_id` int(10) NOT NULL AUTO_INCREMENT,
  `board_pid` int(10) NOT NULL DEFAULT '0' COMMENT '원글번호',
  `user_id` varchar(20) NOT NULL COMMENT '작성자ID',
  `user_name` varchar(20) NOT NULL COMMENT '작성자이름',
  `subject` varchar(50) NOT NULL COMMENT '게시글제목',
  `contents` text NOT NULL COMMENT '게시글내용',
  `hits` int(10) NOT NULL DEFAULT '0' COMMENT '조회수',
  `reg_date` datetime NOT NULL COMMENT '등록일',
  PRIMARY KEY (`board_id`),
  KEY `board_pid` (`board_pid`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='CodeIgniter 게시판';

/*Data for the table `ci_board` */

insert  into `ci_board`(`board_id`,`board_pid`,`user_id`,`user_name`,`subject`,`contents`,`hits`,`reg_date`) values (1,0,'advisor','웅파','안녕하세요','첫글이네요.',6,'2012-06-12 22:23:01'),(2,0,'advisor','웅파','두번째 글입니다.','두번째글이네요.',0,'2012-06-12 22:24:01'),(3,0,'advisor','웅파','세번째 글입니다.','세번째글이네요.',2,'2012-06-12 22:24:01'),(4,0,'advisor','웅파','네번째 글입니다.','네번째글이네요.',6,'2012-06-12 22:24:01'),(5,0,'advisor','웅파','다섯번째 글입니다.','다섯번째글이네요.',6,'2012-06-12 22:24:01'),(8,0,'advisor','웅파','여덞번째 글입니다.2','                                    여덞번째글이네요.2                                ',19,'2012-06-12 22:24:01'),(9,0,'advisor','웅파','아홉번째 글입니다.','                                                                                                            아홉번째글이네요.                                                                                                ',8,'2012-06-12 22:24:01'),(10,0,'advisor','웅파','열번째 글입니다.','                                    열번째글이네요.                                ',16,'2012-06-12 22:24:01'),(11,1,'blumine','웅파1','첫번째 글의 첫번째 댓글입니다.','                                                                        첫번째 댓글이네요.                                                                ',25,'2012-06-12 22:26:01'),(12,1,'blumine','웅파1','첫번째 글의 두번째 댓글입니다.','                                         11111                                두번째 댓글이네요.                                                                                                ',17,'2012-06-12 22:27:01'),(13,2,'blumine','웅파1','두번째 글의 첫번째1111 댓글입니다.','                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      두번째 글의 첫번째 댓글이네요.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ',57,'2012-06-12 22:29:01'),(24,4,'advisor','advisor','','3333',0,'2012-10-09 17:17:22'),(22,4,'advisor','advisor','','1111',0,'2012-10-09 17:17:19'),(25,4,'advisor','advisor','','4444',0,'2012-11-07 14:09:59');

/*Table structure for table `ci_sessions` */

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `ci_sessions` */

insert  into `ci_sessions`(`id`,`ip_address`,`timestamp`,`data`) values ('0c13d07il4m5jaq9f4pkdcsn3tcbq1bs','127.0.0.1',1514625225,'__ci_last_regenerate|i:1514625225;message|s:33:\"로그인에 실패 했습니다.\";__ci_vars|a:1:{s:7:\"message\";s:3:\"old\";}'),('2gbr3g0lcni89dfnqqq3doarmr3gc8cl','127.0.0.1',1514628840,'__ci_last_regenerate|i:1514628840;is_login|b:1;message|s:33:\"로그인에 성공 했습니다.\";__ci_vars|a:1:{s:7:\"message\";s:3:\"old\";}'),('6le5b1mk432g2040hkth9l37hmgoo9ne','127.0.0.1',1514574710,'__ci_last_regenerate|i:1514574710;message|s:33:\"로그인에 실패 했습니다.\";__ci_vars|a:1:{s:7:\"message\";s:3:\"old\";}'),('9balteqhen0vng61njped0ffdfr6o8u4','127.0.0.1',1514627591,'__ci_last_regenerate|i:1514627591;is_login|b:1;message|s:33:\"로그인에 성공 했습니다.\";__ci_vars|a:1:{s:7:\"message\";s:3:\"old\";}'),('em6prsnfffl8qv5am9toqf2a54rgj5ri','127.0.0.1',1514631470,'__ci_last_regenerate|i:1514631470;is_login|b:1;user_name|N;email|s:14:\"sein@gmail.com\";message|s:33:\"로그인에 성공 했습니다.\";__ci_vars|a:1:{s:7:\"message\";s:3:\"old\";}'),('f8sjiom8eao4m8frtn3k92vig02aohvq','127.0.0.1',1514625533,'__ci_last_regenerate|i:1514625533;message|s:37:\"로그인에 실패 했습니다.1234\";__ci_vars|a:1:{s:7:\"message\";s:3:\"old\";}'),('i95pm230ol38ra579mqfgts0ebr3626g','127.0.0.1',1514626368,'__ci_last_regenerate|i:1514626368;is_login|b:1;message|s:33:\"로그인에 성공 했습니다.\";__ci_vars|a:1:{s:7:\"message\";s:3:\"old\";}'),('ij9dq430bleq9530oefuj9103bckm1ql','127.0.0.1',1514571564,'__ci_last_regenerate|i:1514571564;'),('lfe751lfu5nchg7am7qvb4v6n78v3rkt','127.0.0.1',1514573248,'__ci_last_regenerate|i:1514573248;'),('n0hi8uog76crfsljh2r12u9jb94kfj8m','127.0.0.1',1514630299,'__ci_last_regenerate|i:1514630299;'),('o0kir8t89cef0tcb88rig1jfjed5cqu5','127.0.0.1',1514525639,'__ci_last_regenerate|i:1514525621;'),('o3nuvrg4a0pm1l4eptrb17n0n19tpsqh','127.0.0.1',1514631776,'__ci_last_regenerate|i:1514631771;'),('q9eoit9mqpjf3n6mpae3nh4b1jflb24f','127.0.0.1',1514630953,'__ci_last_regenerate|i:1514630953;is_login|b:1;message|s:33:\"로그인에 성공 했습니다.\";__ci_vars|a:1:{s:7:\"message\";s:3:\"old\";}'),('r40cb57i7v9r3s0ntfsvnsn9f04h4mvp','127.0.0.1',1514574937,'__ci_last_regenerate|i:1514574710;message|s:33:\"로그인에 실패 했습니다.\";__ci_vars|a:1:{s:7:\"message\";s:3:\"old\";}'),('u7jo5oeq1ukjm1qcaltf0a1t11s9c2ng','127.0.0.1',1514574222,'__ci_last_regenerate|i:1514574222;message|s:33:\"로그인에 실패 했습니다.\";__ci_vars|a:1:{s:7:\"message\";s:3:\"old\";}'),('uithstgq5avm13jd19eo7n9oljfqiltq','127.0.0.1',1514627897,'__ci_last_regenerate|i:1514627897;message|s:35:\"회원가입에 실패했습니다.\";__ci_vars|a:1:{s:7:\"message\";s:3:\"new\";}');

/*Table structure for table `sns_files` */

DROP TABLE IF EXISTS `sns_files`;

CREATE TABLE `sns_files` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT '0',
  `user_id` varchar(30) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `contents` varchar(200) NOT NULL COMMENT '내용',
  `file_path` varchar(150) NOT NULL,
  `file_name` varchar(100) NOT NULL COMMENT '서버 저장 경로와 변경된 파일명',
  `original_name` varchar(100) NOT NULL COMMENT '서버 저장 경로와 원래 파일명',
  `detail_info` varchar(500) NOT NULL COMMENT '타입, 크기 등의 정보',
  `hit` int(10) NOT NULL DEFAULT '1',
  `reg_date` datetime NOT NULL COMMENT '등록일',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='SNS 프로젝트';

/*Data for the table `sns_files` */

insert  into `sns_files`(`id`,`pid`,`user_id`,`subject`,`contents`,`file_path`,`file_name`,`original_name`,`detail_info`,`hit`,`reg_date`) values (2,0,'advisor','다람쥐','다람쥐 사진 테스트 업로드','F:/xampp/htdocs/sns/uploads/','f3f88649f5f729031baada79d973f651.jpg','다람쥐.jpg','a:4:{s:9:\"file_size\";i:12;s:11:\"image_width\";i:226;s:12:\"image_height\";i:150;s:8:\"file_ext\";s:4:\".jpg\";}',4,'2013-02-16 05:48:25'),(3,0,'advisor','벌 그림','사진','F:/xampp/htdocs/sns/uploads/','7b5dd6cfa920af8476db8ae47f9be405.jpg','thumb_2988812521.jpg','a:4:{s:9:\"file_size\";i:13;s:11:\"image_width\";i:226;s:12:\"image_height\";i:150;s:8:\"file_ext\";s:4:\".jpg\";}',1,'2013-02-17 04:03:44'),(4,0,'advisor','벌 그림','사진','F:/xampp/htdocs/sns/uploads/','6ec103f9fe1ec9bab2cc3d9a9433bc1a.jpg','thumb_2988812521.jpg','a:4:{s:9:\"file_size\";i:13;s:11:\"image_width\";i:226;s:12:\"image_height\";i:150;s:8:\"file_ext\";s:4:\".jpg\";}',1,'2013-02-17 04:04:10'),(5,0,'advisor','양복2','사진2','F:/xampp/htdocs/sns/uploads/','c56ea3b24af2490ae3bc8d9e8605d02b.jpg','small2013877009.jpg','a:4:{s:9:\"file_size\";i:25;s:11:\"image_width\";i:140;s:12:\"image_height\";i:140;s:8:\"file_ext\";s:4:\".jpg\";}',26,'2013-02-17 07:19:40'),(8,5,'advisor','','양복댓글1','','','','',1,'2013-02-17 04:43:39'),(9,5,'advisor','','댓글2','','','','',1,'2013-02-17 04:43:58'),(10,5,'advisor','','댓글3','','','','',1,'2013-02-17 04:44:31'),(11,5,'advisor','','댓글4','','','','',1,'2013-02-17 04:47:00'),(12,5,'advisor','','댓글5','','','','',1,'2013-02-17 04:47:33'),(14,0,'advisor','codeigniter 로고','불꽃','F:/xampp/htdocs/sns/uploads/','c437244b22242e46bc637209ea18aab8.png','logo_ci1.png','a:4:{s:9:\"file_size\";i:2;s:11:\"image_width\";i:48;s:12:\"image_height\";i:70;s:8:\"file_ext\";s:4:\".png\";}',1,'2013-02-17 05:30:54'),(15,0,'advisor','해파리','모질라 로고\r\n\r\n공룡?!!\r\n\r\nhello','F:/xampp/htdocs/sns/uploads/','a9841c30413a85d8f65d597749729bf4.jpg','Jellyfish.jpg','a:4:{s:9:\"file_size\";i:757;s:11:\"image_width\";i:1024;s:12:\"image_height\";i:768;s:8:\"file_ext\";s:4:\".jpg\";}',21,'2013-02-17 11:55:22'),(16,0,'advisor','큰 이미지','폭이 100px 보다 큰  이미지일 경우 썸네일 만듬.','F:/xampp/htdocs/sns/uploads/','e1f017a0c29a4c49ddce0e437b446bd5.jpg','Penguins.jpg','a:4:{s:9:\"file_size\";i:759;s:11:\"image_width\";i:1024;s:12:\"image_height\";i:768;s:8:\"file_ext\";s:4:\".jpg\";}',7,'2013-02-17 11:53:00'),(17,0,'advisor','사막','사막','F:/xampp/htdocs/sns/uploads/','6d6438a28c498f114ff8aa579a0e11e6.jpg','Desert.jpg','a:4:{s:9:\"file_size\";i:826;s:11:\"image_width\";i:1024;s:12:\"image_height\";i:768;s:8:\"file_ext\";s:4:\".jpg\";}',1,'2013-02-17 11:53:53'),(18,0,'advisor','국화','국화','F:/xampp/htdocs/sns/uploads/','e1f4bfe01c582b09a99327d962eabaca.jpg','Chrysanthemum.jpg','a:4:{s:9:\"file_size\";i:858;s:11:\"image_width\";i:1024;s:12:\"image_height\";i:768;s:8:\"file_ext\";s:4:\".jpg\";}',1,'2013-02-17 11:54:04'),(19,0,'advisor','등대','등대','F:/xampp/htdocs/sns/uploads/','59c22ce0a390771c4f6a87e4db012c0e.jpg','Lighthouse.jpg','a:4:{s:9:\"file_size\";i:548;s:11:\"image_width\";i:1024;s:12:\"image_height\";i:768;s:8:\"file_ext\";s:4:\".jpg\";}',3,'2013-02-17 11:54:50'),(20,0,'advisor','코알라','코알라','F:/xampp/htdocs/sns/uploads/','ad1a87a2d52d40e32fc5658bdff05310.jpg','Koala.jpg','a:4:{s:9:\"file_size\";i:762;s:11:\"image_width\";i:1024;s:12:\"image_height\";i:768;s:8:\"file_ext\";s:4:\".jpg\";}',9,'2013-02-17 11:55:08');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) DEFAULT NULL,
  `nickname` varchar(128) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_idx` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`nickname`,`email`,`password`,`created`) values (1,'admin','admin','admin@admin.com','$2y$10$O6LTj4lSdcMugAMThL/2O.PlXn/seK8rcf0MbUN88PoikoDP0xxNu','2017-12-30 18:11:51'),(2,'manager','manager','admin1@admin.com','$2y$10$GiVNF2fzVPX9p2ABR8CNtOCBrftUCgubn1MrAL3uJY80JQ/lkRfa2','2017-12-30 18:59:45'),(3,'1111','sein_prince','sein@gmail.com','$2y$10$uUF9oDun5dsTc2Jp5AMoDem6XUeSqZm1yd9ArtMID2eFLeYJ0zrJK','2017-12-30 19:49:52');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL COMMENT '아이디',
  `password` varchar(50) NOT NULL COMMENT '비밀번호',
  `name` varchar(50) NOT NULL COMMENT '이름',
  `email` varchar(50) DEFAULT NULL COMMENT '이메일',
  `reg_date` datetime NOT NULL COMMENT '가입일',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='회원테이블';

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`password`,`name`,`email`,`reg_date`) values (1,'advisor','1234','웅파','advisor@cikorea.net','2012-07-01 12:54:23');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
