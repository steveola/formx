-- Adminer 3.6.3 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `sms`;
CREATE DATABASE `sms` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `sms`;

DROP TABLE IF EXISTS `academic_session`;
CREATE TABLE `academic_session` (
  `academic_session_id` int(10) NOT NULL AUTO_INCREMENT,
  `academic_session_name` varchar(70) NOT NULL,
  `academic_session_start` varchar(70) NOT NULL,
  `academic_session_end` varchar(70) NOT NULL,
  `academic_session_lastupdate` varchar(70) NOT NULL,
  `institution_id` int(11) NOT NULL,
  `academic_session_description` varchar(70) NOT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`academic_session_id`),
  KEY `institution_id` (`institution_id`),
  CONSTRAINT `academic_session_ibfk_1` FOREIGN KEY (`institution_id`) REFERENCES `institution` (`institution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `course_id` int(12) NOT NULL AUTO_INCREMENT,
  `course_code` varchar(20) NOT NULL,
  `course_fullname` varchar(200) NOT NULL,
  `course_shortname` varchar(200) NOT NULL,
  `course_description` text NOT NULL,
  `course_summary` text NOT NULL,
  `course_creditload` int(3) NOT NULL,
  `department_id` int(5) NOT NULL,
  `course_coordinator_name` varchar(200) NOT NULL,
  `course_ass_coordinator_name` varchar(200) NOT NULL,
  `course_level` varchar(200) NOT NULL,
  `semester` int(2) NOT NULL,
  `time_added` varchar(200) NOT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`course_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `course_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `course_catergories`;
CREATE TABLE `course_catergories` (
  `course_catergory_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_option_shortname` varchar(200) NOT NULL,
  `course_option_fullname` int(11) NOT NULL,
  `course_option_parent` int(11) NOT NULL,
  `department_id` int(5) NOT NULL,
  `course_option_description` longtext NOT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`course_catergory_id`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `course_catergories_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='course options';


DROP TABLE IF EXISTS `course_reg`;
CREATE TABLE `course_reg` (
  `course_reg_id` int(13) NOT NULL AUTO_INCREMENT,
  `course_id` int(12) NOT NULL,
  `student_id` int(11) NOT NULL,
  `c_assesment_score` varchar(3) NOT NULL,
  `exams_score` varchar(3) NOT NULL,
  `approved` varchar(20) NOT NULL DEFAULT '0' COMMENT 'approval from the course coordinator',
  `time` varchar(20) NOT NULL COMMENT 'time of course regisrtation',
  `comment` text NOT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`course_reg_id`),
  KEY `course_id` (`course_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `course_reg_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_info` (`student_id`),
  CONSTRAINT `course_reg_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `department_id` int(5) NOT NULL AUTO_INCREMENT,
  `faculty_id` int(11) NOT NULL,
  `department_fullname` varchar(200) NOT NULL,
  `department_shortname` varchar(200) NOT NULL,
  `department_type_id` int(4) NOT NULL,
  `department_created_year` varchar(200) NOT NULL,
  `department_created_month` varchar(200) NOT NULL,
  `department_created_day` varchar(200) NOT NULL,
  `department_decription` text NOT NULL,
  `department_addtime_todb` varchar(20) NOT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`department_id`),
  KEY `faculty_id` (`faculty_id`),
  KEY `department_type_id` (`department_type_id`),
  CONSTRAINT `department_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`),
  CONSTRAINT `department_ibfk_2` FOREIGN KEY (`department_type_id`) REFERENCES `department_type` (`department_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `department` (`department_id`, `faculty_id`, `department_fullname`, `department_shortname`, `department_type_id`, `department_created_year`, `department_created_month`, `department_created_day`, `department_decription`, `department_addtime_todb`, `deleted`) VALUES
(1,	1,	'aihdaasdsn',	'asdad',	3,	'232',	'2',	'2',	'asndknd',	'123313',	'0'),
(4,	1,	'chemisty',	'CHM',	3,	'2000',	'2',	'2',	'aksjiasjo oiaj dias',	'1477250050',	'0');

DROP TABLE IF EXISTS `department_type`;
CREATE TABLE `department_type` (
  `department_type_id` int(4) NOT NULL AUTO_INCREMENT,
  `department_type_name` varchar(200) NOT NULL,
  `department_type_abbr` varchar(200) NOT NULL,
  `department_type_description` text NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`department_type_id`),
  KEY `faculty_id` (`faculty_id`),
  CONSTRAINT `department_type_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `department_type` (`department_type_id`, `department_type_name`, `department_type_abbr`, `department_type_description`, `faculty_id`, `deleted`) VALUES
(3,	'Academic',	'ACAD',	'asdadasd',	1,	'0');

DROP TABLE IF EXISTS `faculty`;
CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL AUTO_INCREMENT,
  `faculty_fullname` varchar(200) DEFAULT NULL,
  `faculty_shortname` varchar(200) NOT NULL,
  `faculty_description` text NOT NULL,
  `faculty_year_added` int(5) NOT NULL,
  `faculty_month_added` varchar(20) NOT NULL,
  `faculty_day_added` int(2) NOT NULL,
  `institution_id` int(11) NOT NULL,
  `faculty_time_todb` varchar(20) NOT NULL,
  `faculty_logo` varchar(400) NOT NULL,
  `faculty_text` varchar(400) NOT NULL,
  `faculty_campus` varchar(400) NOT NULL,
  `faculty_files` varchar(400) NOT NULL,
  `faculty_code` varchar(400) DEFAULT NULL,
  `faculty_note` varchar(400) DEFAULT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`faculty_id`),
  UNIQUE KEY `faculty_shortname` (`faculty_shortname`),
  KEY `institution_id` (`institution_id`),
  CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`institution_id`) REFERENCES `institution` (`institution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `faculty` (`faculty_id`, `faculty_fullname`, `faculty_shortname`, `faculty_description`, `faculty_year_added`, `faculty_month_added`, `faculty_day_added`, `institution_id`, `faculty_time_todb`, `faculty_logo`, `faculty_text`, `faculty_campus`, `faculty_files`, `faculty_code`, `faculty_note`, `deleted`) VALUES
(1,	'ajjbjs',	'adasd',	'asdasd',	2,	'2',	2,	2,	'0',	'',	'',	'',	'',	'',	NULL,	'0'),
(4,	'ggaa',	'asf',	'asfa',	3,	'2',	2,	2,	'1477164504',	'',	'',	'',	'',	'',	NULL,	'0'),
(10,	'111',	'222',	'asdnl',	444,	'2',	3,	1,	'78792',	'',	'',	'',	'',	'',	NULL,	'0'),
(60,	'#000000',	'372',	'all',	2,	'3',	2,	2,	'1503506177',	'1503506177falculty.jpg',	'11-12-8432',	'1503506177falcultycampus.JPG',	'1503506177falculty_1.jpg',	NULL,	NULL,	'0'),
(70,	'#c0c0c0',	'2',	'sdafssa',	3,	'1',	2,	2,	'1503506874',	'1503506874falculty.gif',	'11-12-8432',	'',	'1503506874falculty_1.jpg',	NULL,	NULL,	'0'),
(1222,	'#000000',	'43',	'lions',	2,	'3',	3,	2,	'1502808512',	'1502808512falculty.jpg',	'1-3-4',	'1502808512falcultycampus.jpg',	'',	NULL,	NULL,	'0'),
(12334,	'1507884831',	'1',	'0\'\';0\'09\r\n',	3,	'1@2@3',	8,	2,	'1508576706',	'1507904051falculty.jpg',	'4-87-3920',	'1507408134falcultycampus.jpg',	'1507904952falculty_1.jpg,1507904952falculty_2.jpg',	'',	'try this',	'0');

DROP TABLE IF EXISTS `institution`;
CREATE TABLE `institution` (
  `institution_id` int(11) NOT NULL AUTO_INCREMENT,
  `institution_shortname` varchar(200) NOT NULL,
  `institution_fullname` varchar(200) NOT NULL,
  `institution_address` varchar(2000) NOT NULL,
  `institution_latitude` varchar(200) NOT NULL,
  `institution_longitude` varchar(200) NOT NULL,
  `institution_description` text NOT NULL,
  `institution_time_added` varchar(200) NOT NULL,
  `institution_history` text NOT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`institution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `institution` (`institution_id`, `institution_shortname`, `institution_fullname`, `institution_address`, `institution_latitude`, `institution_longitude`, `institution_description`, `institution_time_added`, `institution_history`, `deleted`) VALUES
(1,	'uam',	'university of agriculture makurdi',	'makurdi ajbjkdbj',	'12.874',	'123.7788',	'Very address of uam has dhihdf',	'',	'',	'0'),
(2,	'adf',	'BSU',	'asfas',	'asfa',	'asfdas',	'asdfafd',	'asfsa',	'asasdfsa asdf sa',	'0'),
(3,	'nnn',	'FUTA',	'nnn',	'nnn',	'institution_longitude',	'aaa',	'1477158807',	'nnn',	'0');

DROP TABLE IF EXISTS `level`;
CREATE TABLE `level` (
  `level_id` int(9) NOT NULL AUTO_INCREMENT,
  `level_shortname` varchar(400) NOT NULL,
  `level_longname` varchar(400) NOT NULL,
  `institution_id` int(11) NOT NULL,
  `level_comment` text NOT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`level_id`),
  KEY `institution_id` (`institution_id`),
  CONSTRAINT `level_ibfk_1` FOREIGN KEY (`institution_id`) REFERENCES `institution` (`institution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `othertr`;
CREATE TABLE `othertr` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `ffn` varchar(200) NOT NULL,
  `insid` varchar(200) NOT NULL,
  `time` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `othertr` (`id`, `ffn`, `insid`, `time`) VALUES
(73,	'12334',	'2',	'1508576706');

DROP TABLE IF EXISTS `othertr2`;
CREATE TABLE `othertr2` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `ffn` varchar(200) NOT NULL,
  `insid` varchar(200) NOT NULL,
  `time` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `othertr2` (`id`, `ffn`, `insid`, `time`) VALUES
(82,	'12334',	'1',	'1508576706'),
(83,	'12334',	'2',	'1508576706'),
(84,	'12334',	'3',	'1508576706');

DROP TABLE IF EXISTS `othertr3`;
CREATE TABLE `othertr3` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `ffn` varchar(200) NOT NULL,
  `insid` text NOT NULL,
  `time` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `othertr4`;
CREATE TABLE `othertr4` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `ffn` varchar(200) NOT NULL,
  `insid` text NOT NULL,
  `time` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `othertr4` (`id`, `ffn`, `insid`, `time`) VALUES
(710,	'12334',	'bill and mark - Copy.jpg',	'1508098940');

DROP TABLE IF EXISTS `required_course`;
CREATE TABLE `required_course` (
  `required_course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_catergories_id` int(11) NOT NULL,
  `required_course_type` int(2) NOT NULL COMMENT '1 compulsory, 2 optional, 3 elective',
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`required_course_id`),
  KEY `course_catergories_id` (`course_catergories_id`),
  CONSTRAINT `required_course_ibfk_1` FOREIGN KEY (`course_catergories_id`) REFERENCES `course_catergories` (`course_catergory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `school_fee`;
CREATE TABLE `school_fee` (
  `fee_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `semester_id` int(12) NOT NULL,
  `school_fee_type_id` int(10) NOT NULL,
  `payment_receiver_name` varchar(300) NOT NULL COMMENT 'Recorder of payment e.g cashier',
  `method` varchar(300) NOT NULL COMMENT '1 is cash, 2 is bank, 3 transfer, 4 cheque',
  `pin` varchar(300) NOT NULL,
  `time` varchar(300) NOT NULL,
  `approved` varchar(300) NOT NULL,
  `comment` text NOT NULL,
  `deteleted` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 is no, 1 is yes',
  PRIMARY KEY (`fee_id`),
  KEY `student_id` (`student_id`),
  KEY `semester_id` (`semester_id`),
  KEY `school_fee_type_id` (`school_fee_type_id`),
  CONSTRAINT `school_fee_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student_info` (`student_id`),
  CONSTRAINT `school_fee_ibfk_3` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`semester_id`),
  CONSTRAINT `school_fee_ibfk_4` FOREIGN KEY (`school_fee_type_id`) REFERENCES `semester_type` (`semester_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `school_fee_type`;
CREATE TABLE `school_fee_type` (
  `school_fee_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `institution_id` int(11) NOT NULL,
  `school_fee_type_shortname` varchar(200) NOT NULL,
  `school_fee_type_fullname` varchar(200) NOT NULL,
  `school_fee_type_purpose` text NOT NULL,
  `school_fee_type_description` text NOT NULL,
  `school_fee_type_lastedited` varchar(20) NOT NULL,
  `school_fee_type_status` int(2) NOT NULL DEFAULT '0' COMMENT '0 is active, 1 is inactive, 2 is deleted',
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`school_fee_type_id`),
  KEY `institution_id` (`institution_id`),
  CONSTRAINT `school_fee_type_ibfk_1` FOREIGN KEY (`institution_id`) REFERENCES `institution` (`institution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `semester`;
CREATE TABLE `semester` (
  `semester_id` int(12) NOT NULL AUTO_INCREMENT,
  `semester_year` int(5) NOT NULL,
  `semester_type_id` int(10) NOT NULL,
  `academic_session_id` int(10) NOT NULL,
  `semester_begin` varchar(20) NOT NULL,
  `semester_end` varchar(20) NOT NULL,
  `semester_comment` longtext NOT NULL,
  `semester_status` int(1) NOT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`semester_id`),
  KEY `semester_type_id` (`semester_type_id`),
  KEY `academic_session_id` (`academic_session_id`),
  CONSTRAINT `semester_ibfk_1` FOREIGN KEY (`semester_type_id`) REFERENCES `semester_type` (`semester_type_id`),
  CONSTRAINT `semester_ibfk_2` FOREIGN KEY (`academic_session_id`) REFERENCES `academic_session` (`academic_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='For add openning and closing the portal';


DROP TABLE IF EXISTS `semester_creditload`;
CREATE TABLE `semester_creditload` (
  `semester_creditload` int(11) NOT NULL AUTO_INCREMENT,
  `course_catergory_id` int(11) NOT NULL,
  `semester_creditload_value_min` int(4) NOT NULL,
  `semester_creditload_value_max` int(4) NOT NULL,
  `semester_type_id` int(10) NOT NULL,
  `semester_creditload_description` int(10) NOT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`semester_creditload`),
  KEY `course_catergory_id` (`course_catergory_id`),
  KEY `semester_type_id` (`semester_type_id`),
  KEY `semester_creditload_description` (`semester_creditload_description`),
  CONSTRAINT `semester_creditload_ibfk_1` FOREIGN KEY (`course_catergory_id`) REFERENCES `course_catergories` (`course_catergory_id`),
  CONSTRAINT `semester_creditload_ibfk_2` FOREIGN KEY (`semester_type_id`) REFERENCES `semester_type` (`semester_type_id`),
  CONSTRAINT `semester_creditload_ibfk_3` FOREIGN KEY (`semester_creditload_description`) REFERENCES `semester_type` (`semester_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `semester_type`;
CREATE TABLE `semester_type` (
  `semester_type_id` int(10) NOT NULL AUTO_INCREMENT,
  `semester_type_fullname` varchar(300) NOT NULL,
  `semester_type_shortname` varchar(300) NOT NULL,
  `institution_id` int(11) NOT NULL,
  `semester_type_description` varchar(300) NOT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`semester_type_id`),
  KEY `institution_id` (`institution_id`),
  CONSTRAINT `semester_type_ibfk_1` FOREIGN KEY (`institution_id`) REFERENCES `institution` (`institution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `student_info`;
CREATE TABLE `student_info` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(5) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `middlename` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `login_password` varchar(200) NOT NULL,
  `reg_no1` varchar(200) NOT NULL,
  `reg_no2` varchar(200) NOT NULL,
  `state_of_origin` varchar(200) NOT NULL,
  `gender` varchar(200) NOT NULL,
  `photo` varchar(400) NOT NULL,
  `graduation` varchar(400) NOT NULL,
  `current_class` varchar(400) NOT NULL,
  `time` varchar(200) NOT NULL,
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `reg_no1` (`reg_no1`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `student_info_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `system_user`;
CREATE TABLE `system_user` (
  `system_user` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `portfolio` varchar(30) NOT NULL,
  `note` text NOT NULL,
  `picture` varchar(300) NOT NULL,
  `registered_time` varchar(20) NOT NULL,
  `last_login` varchar(20) NOT NULL,
  `reistered_byuserid` varchar(10) NOT NULL DEFAULT '0' COMMENT '0 is registered by self',
  `user_status` int(1) NOT NULL DEFAULT '0' COMMENT '0 is inactive, 1 is active, 2 is blocked',
  `deleted` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`system_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `test`;
CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `test` (`id`, `name`) VALUES
(1,	'steve'),
(2,	'john'),
(3,	'celvin');

DROP TABLE IF EXISTS `test_renamed`;
CREATE TABLE `test_renamed` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(200) NOT NULL,
  `level` varchar(200) NOT NULL,
  `class` varchar(200) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `test_renamed` (`id`, `student_id`, `level`, `class`, `subject`, `description`, `time`) VALUES
(8,	'98',	'sdg',	'dsg',	'sdsdg',	'sdgsg\r\n',	'00:00:00'),
(78,	'67',	'aaaa',	'2222',	'6666',	'11111111111111111',	'00:00:00');

DROP TABLE IF EXISTS `test_table`;
CREATE TABLE `test_table` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `int` int(10) NOT NULL,
  `decimal` decimal(10,3) NOT NULL,
  `double` float NOT NULL,
  `text` text NOT NULL,
  `bool` tinyint(1) DEFAULT NULL,
  `enum` enum('old','new') NOT NULL,
  `set` set('previous','current','next') NOT NULL,
  `varchar` varchar(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `test_table` (`ID`, `int`, `decimal`, `double`, `text`, `bool`, `enum`, `set`, `varchar`) VALUES
(1,	10,	10.000,	10,	'Soadadad',	1,	'old',	'previous,current,next',	'adadad');

DROP TABLE IF EXISTS `test_users`;
CREATE TABLE `test_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `gender` enum('M','F') NOT NULL,
  `password` char(32) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `test_users` (`ID`, `username`, `email`, `firstname`, `lastname`, `gender`, `password`) VALUES
(1,	'user1',	'user1@domain.com',	'username',	'user_lastname',	'M',	'd41d8cd98f00b204e9800998ecf8427e'),
(2,	'user2',	'user2@domain.com',	'username',	'user_lastname',	'M',	'd41d8cd98f00b204e9800998ecf8427e'),
(3,	'sfhf',	'sdfsd@gmail.com',	'sdfg',	'gsd',	'F',	'202cb962ac59075b964b07152d234b70'),
(4,	'sdfgs',	'sdfgds@gmail.com',	'dsf',	'fsf',	'M',	'6512bd43d9caa6e02c990b0a82652dca'),
(5,	'sdfgs',	'sdfgds@gmail.com',	'dsf',	'fsf',	'F',	'6512bd43d9caa6e02c990b0a82652dca'),
(6,	'sdfgs',	'sdfgds@gmail.com',	'dsf',	'fsf',	'F',	'6512bd43d9caa6e02c990b0a82652dca'),
(7,	'sdfgs',	'sdfgds@gmail.com',	'dsf',	'fsf',	'F',	'6512bd43d9caa6e02c990b0a82652dca'),
(8,	'sdfgs',	'sdfgds@gmail.com',	'dsf',	'fsf',	'F',	'6512bd43d9caa6e02c990b0a82652dca'),
(9,	'634',	'sdfsd@gmail.com',	'dada',	'dfdda',	'F',	'f7177163c833dff4b38fc8d2872f1ec6'),
(10,	'634',	'sdfsd@gmail.com',	'dada',	'dfdda',	'F',	'f7177163c833dff4b38fc8d2872f1ec6');

-- 2017-11-01 17:45:52
