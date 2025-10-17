-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2022 at 11:14 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pdf`
--

-- --------------------------------------------------------

--
-- Table structure for table `student_registrations`
--

CREATE TABLE `student_registrations` (
  `id` int(11) NOT NULL,
  `matric_number` text NOT NULL,
  `study_center` text NOT NULL,
  `study_center_code` text NOT NULL,
  `course` text NOT NULL,
  `exam_day` text NOT NULL,
  `exam_session` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_registrations`
--

INSERT INTO `student_registrations` (`id`, `matric_number`, `study_center`, `study_center_code`, `course`, `exam_day`, `exam_session`) VALUES
(2, 'nou040075712', 'Lagos Study Centre ', 'LA01', 'ENG113_22', '1', '8:00AM'),
(3, 'nou060036048', 'Uyo Study Centre ', 'AK01', 'MTH101_22', '1', '8:00AM'),
(4, 'nou060217554', 'Maiduguri Study Centre  ', 'BO01', 'PPL423_22', '1', '8:00AM'),
(5, 'nou060219523', 'Yenagoa Study Centre  ', 'BY01', 'POL121_22', '1', '8:00AM'),
(6, 'nou060220188', 'Isulo Community Study Centre ', 'AN03', 'JIL447_22', '1', '8:00AM'),
(7, 'nou060336051', 'Abuja Model Study Centre  ', 'FC01', 'PHY102_22', '1', '8:00AM'),
(8, 'nou070047455', 'Nigeria Navy Special Study Centre Apapa Lagos ', 'LA04', 'STT205_22', '1', '8:00AM'),
(9, 'nou070104304', 'Osogbo Study Centre ', 'OS02', 'STT205_22', '1', '8:00AM'),
(10, 'nou070120570', 'Gombe Study Centre  ', 'GM01', 'CSS112_22', '1', '8:00AM'),
(11, 'nou070194650', 'Ilorin Study Centre  ', 'KW01', 'CIT381_22', '1', '8:00AM'),
(12, 'nou070195975', 'National Union Of Road Transport Workers Special Study Centre Garki Abuja ', 'FC03', 'CIT891_22', '2', '8:00AM'),
(13, 'nou070197483', 'Abeokuta Study Centre ', 'OG01', 'BUS322_22', '3', '8:00AM'),
(14, 'nou070385528', 'Ikom Community Study Centre ', 'CR02', 'GST302_22', '4', '8:00AM'),
(15, 'nou070398330', 'Nigeria Navy Special Study Centre Apapa Lagos ', 'LA04', 'GST103_22', '5', '8:00AM'),
(16, 'nou070408940', 'Lagos Study Centre ', 'LA01', 'PUL341_22', '6', '8:00AM'),
(17, 'nou090058257', 'Nigeria Navy Special Study Centre Apapa Lagos ', 'LA04', 'CLL533_22', '7', '8:00AM'),
(18, 'nou090075682', 'Emevor Community Study Centre ', 'DE02', 'PPL323_22', '8', '11:00AM'),
(19, 'nou090077214', 'Abuja Model Study Centre  ', 'FC01sd', 'ACC204_22', '9', '11:00AM'),
(20, 'nou090125454', 'Katsina Study Centre  ', 'KT02', 'CIT412_22', '10', '11:00AM'),
(21, 'nou090149763', 'Minna Study Centre  ', 'NG01', 'TSM403_22', '11', '11:00AM'),
(22, 'nou090219379', 'Umudike Study Centre  ', 'AB01', 'CHM101_22', '2', '11:00AM'),
(23, 'nou090241813', 'Minna Study Centre  ', 'NG01', 'NSC312_22', '3', '11:00AM'),
(24, 'nou090252488', 'Kaduna Study Centre ', 'KD01', 'CSS331_22', '4', '11:00AM'),
(25, 'nou090267191', 'Isulo Community Study Centre ', 'AN03', 'NSC412_22', '5', '11:00AM'),
(26, 'nou090268519', 'Kaduna Study Centre ', 'KD01', 'MKT402_22', '6', '11:00AM'),
(27, 'nou090318480', 'Uyo Study Centre ', 'AK01', 'MKT411_22', '7', '11:00AM'),
(28, 'nou100065214', 'Isulo Community Study Centre ', 'AN03', 'NSC316_22', '8', '11:00AM'),
(29, 'nou100092528', 'Maiduguri Study Centre  ', 'BO01', 'NSC217_22', '9', '11:00AM'),
(30, 'nou100118178', 'Abuja Model Study Centre  ', 'FC01', 'CSS132_22', '10', '11:00AM'),
(31, 'nou100119179', 'Uyo Study Centre ', 'AK01', 'JIL212_22', '11', '11:00AM'),
(32, 'nou100122285', 'Isulo Community Study Centre ', 'AN03', 'CLL534_22', '3', '11:00AM'),
(33, 'nou100125918', 'Benin  Study Centre  ', 'ED01', 'CLL232_22', '3', '11:00AM'),
(34, 'nou100131498', 'Lagos Study Centre ', 'LA01', 'TSM444_22', '3', '11:00AM'),
(35, 'nou100159082', 'Makurdi Study Centre  ', 'BN01', 'BUS424_22', '4', '2:00PM'),
(36, 'nou100166418', 'Owhrode Community Study Centre ', 'DE03', 'PUL443_22', '5', '2:00PM'),
(37, 'nou100224402', 'Lagos Study Centre ', 'LA01', 'PUL445_22', '6', '2:00PM'),
(38, 'nou100224839', 'Isulo Community Study Centre ', 'AN03', 'JIL447_22', '7', '2:00PM'),
(39, 'nou100225245', 'Owhrode Community Study Centre ', 'DE03', 'CIT104_22', '8', '2:00PM'),
(40, 'nou100225537', 'Awgu Community Study Centre ', 'EN01', 'CLL332_22', '9', '2:00PM'),
(41, 'nou100239913', 'Asaba Study Centre  ', 'DE01', 'EDU332_22', '10', '2:00PM'),
(42, 'nou100248048', 'Lagos Study Centre ', 'LA01', 'PUL341_22', '11', '2:00PM'),
(43, 'nou100248193', 'Awka Study Centre ', 'AN01', 'CLL332_22', '3', '2:00PM'),
(44, 'nou110005769', 'Lagos Study Centre ', 'LA01', 'CLL231_22', '4', '2:00PM'),
(45, 'nou110008010', 'Nigerian Correctional Service Special Study Centre Port-Harcourt ', 'RV01', 'CRD430_22', '5', '2:00PM'),
(46, 'nou110023007', 'Awka Study Centre ', 'AN01', 'INR212_22', '6', '2:00PM'),
(47, 'nou110040094', 'Lagos Study Centre ', 'LA01', 'CLL332_22', '7', '2:00PM'),
(48, 'nou110048328', 'Nigerian Air Force Special Study Centre Kaduna ', 'KD03', 'CSS152_22', '8', '2:00PM'),
(49, 'nou110073842', 'Isulo Community Study Centre ', 'AN03', 'EDU711_22', '9', '2:00PM'),
(50, 'nou110092944', 'Wuse II Study Centre Abuja ', 'FC09', 'GST302_22', '10', '2:00PM'),
(51, 'nou110094957', 'Owhrode Community Study Centre ', 'DE03', 'PHY101_22', '11', '2:00PM'),
(52, 'nou110106064', 'Lagos Study Centre ', 'LA01', 'MAC411_22', '1', '2:00PM'),
(53, 'nou110134480', 'Kagoro Study Centre ', 'KD02', 'CLL534_22', '1', '2:00PM'),
(54, 'nou110155620', 'Nigerian Correctional Service Special Study Centre Enugu ', 'EN03', 'JIL447_22', '2', '2:00PM'),
(55, 'nou110173472', 'Enugu Study Centre  ', 'EN02', 'PPL323_22', '3', '2:00PM'),
(56, 'nou110193823', 'Owhrode Community Study Centre ', 'DE03', 'CLL233_22', '4', '2:00PM'),
(57, 'nou110196905', 'Owerri Study Centre  ', 'IM01', 'CSS111_22', '5', '2:00PM'),
(58, 'nou110242548', 'Nigeria Immigration Service Special Study Centre Abuja ', 'FC04', 'MTH101_22', '6', '2:00PM'),
(59, 'nou110268210', 'Enugu Study Centre  ', 'EN02', 'PHS210_22', '7', '2:00PM'),
(60, 'nou110295444', 'Iwo Study Centre ', 'OS01', 'MTH103_22', '8', '2:00PM'),
(61, 'nou110314458', 'Lagos Study Centre ', 'LA01', 'NSC221_22', '9', '2:00PM'),
(62, 'nou110354967', 'Osogbo Study Centre ', 'OS02', 'BFN421_22', '10', '8:00AM'),
(63, 'nou110412962', 'Lagos Study Centre ', 'LA01', 'MTH102_22', '11', '8:00AM'),
(64, 'nou110437914', 'Isulo Community Study Centre ', 'AN03', 'BUS428_22', '2', '8:00AM'),
(65, 'nou110457387', 'Lagos Study Centre ', 'LA01', 'GST302_22', '3', '8:00AM'),
(66, 'nou110493971', 'Isulo Community Study Centre ', 'AN03', 'NSC402_22', '4', '8:00AM'),
(67, 'nou110494096', 'Lagos Study Centre ', 'LA01', 'NSC301_22', '5', '8:00AM'),
(68, 'nou110497426', 'Umudike Study Centre  ', 'AB01', 'JIL511_22', '6', '8:00AM'),
(69, 'nou110508452', 'Lagos Study Centre ', 'LA01', 'CLL232_22', '7', '8:00AM'),
(70, 'nou110516697', 'Calabar Study Centre ', 'CR01', 'EDU423_22', '8', '8:00AM'),
(71, 'nou110518543', 'Dutse Study Centre  ', 'JG01', 'PHY101_22', '1', '11:00AM'),
(72, 'nou110525776', 'Nigerian Correctional Service Special Study Centre Port-Harcourt ', 'RV01', 'CRD122_22', '1', '11:00AM'),
(73, 'nou110562408', 'Nigerian Correctional Service Special Study Centre Enugu ', 'EN03', 'ECO121_22', '2', '11:00AM'),
(74, 'nou110586581', 'Uyo Study Centre ', 'AK01', 'CHM201_22', '3', '11:00AM'),
(75, 'nou110594918', 'Mccarthy Study Centre Lagos ', 'LA02', 'ECO332_22', '4', '11:00AM'),
(76, 'nou110600142', 'Ado-Ekiti Study Centre ', 'EK01', 'JIL447_22', '5', '11:00AM'),
(77, 'nou110638478', 'Lagos Study Centre ', 'LA01', 'BUS105_22', '6', '11:00AM'),
(78, 'nou110645274', 'Abeokuta Study Centre ', 'OG01', 'ACC306_22', '7', '11:00AM'),
(79, 'nou110708713', 'Abuja Model Study Centre  ', 'FC01', 'BIO101_22', '8', '11:00AM'),
(80, 'nou110710996', 'Minna Study Centre  ', 'NG01', 'PUL341_22', '9', '11:00AM'),
(81, 'nou110720369', 'Lagos Study Centre ', 'LA01', 'PHS318_22', '10', '11:00AM'),
(82, 'nou110727848', 'Maiduguri Study Centre  ', 'BO01', 'EHS204_22', '11', '11:00AM'),
(83, 'nou110764816', 'Wuse II Study Centre Abuja ', 'FC09', 'GST107_22', '2', '11:00AM'),
(84, 'nou110786180', 'Portharcourt Study Centre ', 'RV02', 'CLL534_22', '3', '11:00AM'),
(85, 'nou110795561', 'Awka Study Centre ', 'AN01', 'CSS121_22', '4', '11:00AM'),
(86, 'nou110797058', 'Portharcourt Study Centre ', 'RV02', 'PPL421_22', '5', '11:00AM'),
(87, 'nou110799778', 'Owhrode Community Study Centre ', 'DE03', 'CLL534_22', '6', '11:00AM'),
(88, 'nou110800313', 'Lagos Study Centre ', 'LA01', 'CIT202_22', '7', '2:00PM'),
(89, 'nou110827647', 'Owhrode Community Study Centre ', 'DE03', 'ECO121_22', '8', '2:00PM'),
(90, 'nou110830507', 'Abuja Model Study Centre  ', 'FC01', 'EDA825_22', '1', '2:00PM'),
(91, 'nou110848213', 'Kaduna Study Centre ', 'KD01', 'CIT102_22', '1', '2:00PM'),
(92, 'nou110878945', 'Owhrode Community Study Centre ', 'DE03', 'ENG113_22', '2', '2:00PM'),
(93, 'nou110879261', 'Nigeria Navy Special Study Centre Apapa Lagos ', 'LA04', 'HCM433_22', '3', '2:00PM'),
(94, 'nou110883076', 'Jalingo Study Centre  ', 'TR01', 'CIT425_22', '4', '2:00PM'),
(95, 'nou110918907', 'Benin  Study Centre  ', 'ED01', 'CRS423_22', '5', '2:00PM'),
(96, 'nou110941071', 'Yenagoa Study Centre  ', 'BY01', 'PUL445_22', '6', '2:00PM'),
(97, 'nou110948056', 'Kaduna Study Centre ', 'KD01', 'CSS111_22', '7', '2:00PM'),
(98, 'nou110984976', 'Ilorin Study Centre  ', 'KW01', 'CSS121_22', '8', '2:00PM'),
(99, 'nou120027235', 'Jos Study Centre ', 'PL01', 'PHY101_22', '9', '2:00PM'),
(100, 'nou120113196', 'Nigeria Navy Special Study Centre Apapa Lagos ', 'LA04', 'PHY101_22', '10', '2:00PM'),
(101, 'nou120132019', 'Osogbo Study Centre ', 'OS02', 'CIT237_22', '11', '2:00PM'),
(102, 'nou120160288', 'Abuja Model Study Centre  ', 'OG01', 'BUS322_22', '2', '2:00PM'),
(103, 'nou120201038', 'Isulo Community Study Centre ', 'AN03', 'PUL445_22', '3', '2:00PM'),
(104, 'nou120236483', 'Abeokuta Study Centre ', 'OG01', 'MKT206_22', '4', '2:00PM'),
(105, 'nou120309058', 'Danbatta Study Centre ', 'KN09', 'CIT802_22', '5', '2:00PM'),
(106, 'nou120325135', 'Owerri Study Centre  ', 'IM01', 'PUL445_22', '6', '8:00AM'),
(107, 'nou120334943', 'Ado-Ekiti Study Centre ', 'EK01', 'PPL522_22', '7', '8:00AM'),
(108, 'nou120340872', 'Lagos Study Centre ', 'LA01', 'GST103_22', '8', '8:00AM'),
(109, 'nou120386580', 'Owhrode Community Study Centre ', 'DE03', 'PUL244_22', '9', '8:00AM'),
(110, 'nou120395164', 'Lagos Study Centre ', 'LA01', 'PHY101_22', '10', '8:00AM'),
(111, 'nou120422570', 'Calabar Study Centre ', 'CR01', 'PPL422_22', '11', '8:00AM'),
(112, 'nou120428824', 'Lagos Study Centre ', 'LA01', 'PHY101_22', '12', '8:00AM'),
(113, 'nou120464677', 'Jos Study Centre ', 'PL01', 'BUS419_22', '13', '8:00AM'),
(114, 'nou120472206', 'Benin  Study Centre  ', 'ED01', 'EDU720_22', '14', '8:00AM'),
(115, 'nou120521289', 'Abuja Model Study Centre  ', 'FC01', 'CIT212_22', '15', '11:00AM'),
(116, 'nou120575882', 'Owerri Study Centre  ', 'IM01', 'INR212_22', '16', '11:00AM'),
(117, 'nou120599822', 'Owhrode Community Study Centre ', 'DE03', 'CIT322_22', '11', '11:00AM'),
(118, 'nou120604684', 'Benin  Study Centre  ', 'ED01', 'GST203_22', '2', '11:00AM'),
(119, 'nou120618400', 'Akure Study Centre ', 'ON01', 'PAD747_22', '3', '11:00AM'),
(120, 'nou120637523', 'Lagos Study Centre ', 'LA01', 'MKT205_22', '4', '11:00AM'),
(121, 'nou120652059', 'Ilorin Study Centre  ', 'KW01', 'GST104_22', '5', '11:00AM'),
(122, 'nou120669326', 'Owhrode Community Study Centre ', 'DE03', 'MAC313_22', '6', '11:00AM'),
(123, 'nou120674983', 'Portharcourt Study Centre ', 'RV02', 'JIL511_22', '7', '11:00AM'),
(124, 'nou120682300', 'Nigerian Air Force Special Study Centre Kaduna ', 'KD03', 'MKT411_22', '8', '11:00AM'),
(125, 'nou120691680', 'Bauchi Study Centre ', 'BA02', 'CLL307_22', '9', '11:00AM'),
(126, 'nou120740035', 'Kano Study Centre  ', 'KN01', 'ESM308_22', '10', '11:00AM'),
(127, 'nou120803786', 'Owhrode Community Study Centre ', 'DE03', 'DAM301_22', '11', '11:00AM'),
(128, 'nou120812619', 'Nigeria Army Special Study Centre Ilorin ', 'KW02', 'MTH103_22', '12', '11:00AM'),
(129, 'nou120813350', 'Owhrode Community Study Centre ', 'DE03', 'MBF805_22', '13', '11:00AM'),
(130, 'nou130193769', 'Abuja Model Study Centre  ', 'FC01', 'ACC419_22', '14', '11:00AM'),
(131, 'nou130290985', 'Portharcourt Study Centre ', 'RV02', 'GST302_22', '15', '11:00AM'),
(132, 'nou130347928', 'Abuja Model Study Centre  ', 'FC01', 'PCR112_22', '16', '2:00PM'),
(133, 'nou130478625', 'Lagos Study Centre ', 'LA01', 'CLL533_22', '11', '2:00PM'),
(134, 'nou130566160', 'Owhrode Community Study Centre ', 'DE03', 'BIO102_22', '2', '2:00PM'),
(135, 'nou130671846', 'Lagos Study Centre ', 'LA01', 'ACC419_22', '3', '2:00PM'),
(136, 'nou130715509', 'Abuja Model Study Centre  ', 'FC01', 'PCR422_22', '4', '2:00PM'),
(137, 'nou130859207', 'Benin  Study Centre  ', 'ED01', 'GST302_22', '5', '2:00PM'),
(138, 'nou130916200', 'Minna Study Centre  ', 'NG01', 'BUS727_22', '6', '2:00PM'),
(139, 'nou131017506', 'Lagos Study Centre ', 'LA01', 'GST302_22', '7', '2:00PM'),
(140, 'nou131073739', 'Owhrode Community Study Centre ', 'DE03', 'ENG311_22', '8', '2:00PM'),
(141, 'nou131074489', 'Owhrode Community Study Centre ', 'DE03', 'POL441_22', '9', '2:00PM'),
(142, 'nou131136053', 'Kagoro Study Centre ', 'KD02', 'CSS354_22', '10', '2:00PM'),
(143, 'nou131156024', 'Awa-Ijebu Community Study Centre ', 'OG02', 'EDU732_22', '11', '2:00PM'),
(144, 'nou131249829', 'Mccarthy Study Centre Lagos ', 'LA02', 'POL312_22', '12', '2:00PM'),
(145, 'nou131343375', 'Lagos Study Centre ', 'LA01', 'GST104_22', '13', '2:00PM'),
(146, 'nou131384427', 'Portharcourt Study Centre ', 'RV02', 'MAC444_22', '14', '2:00PM'),
(147, 'nou131430370', 'Awka Study Centre ', 'AN01', 'ACC407_22', '15', '2:00PM'),
(148, 'nou131452851', 'Osogbo Study Centre ', 'OS02', 'NSC509_22', '16', '2:00PM'),
(149, 'nou131455801', 'Awa-Ijebu Community Study Centre ', 'OG02', 'PUL342_22', '11', '2:00PM'),
(150, 'nou131465112', 'Awa-Ijebu Community Study Centre ', 'OG02', 'JIL447_22', '2', '8:00AM'),
(151, 'nou131623711', 'Abeokuta Study Centre ', 'OG01', 'BUS205_22', '3', '8:00AM'),
(152, 'nou131660383', 'Owhrode Community Study Centre ', 'DE03', 'PHY101_22', '4', '8:00AM'),
(153, 'nou131691095', 'Minna Study Centre  ', 'NG01', 'BUS325_22', '5', '8:00AM'),
(154, 'nou131703285', 'Nigeria Immigration Service Special Study Centre Abuja ', 'FC04', 'PHY101_22', '6', '8:00AM'),
(155, 'nou131832386', 'Abakaliki Study Centre  ', 'EB01', 'PPL436_22', '7', '8:00AM'),
(156, 'nou131850510', 'Abuja Model Study Centre  ', 'FC01', 'BUS427_22', '8', '8:00AM'),
(157, 'nou131897337', 'Abeokuta Study Centre ', 'OG01', 'CLL533_22', '9', '8:00AM'),
(158, 'nou131909777', 'Ibadan Study Centre ', 'OY01', 'GST103_22', '10', '8:00AM'),
(159, 'nou131938061', 'Nigeria Navy Special Study Centre Apapa Lagos ', 'LA04', 'NSC206_22', '11', '11:00AM'),
(160, 'nou131959360', 'Osogbo Study Centre ', 'OS02', 'ENT330_22', '12', '11:00AM'),
(161, 'nou131993972', 'Mccarthy Study Centre Lagos ', 'LA02', 'STT205_22', '13', '11:00AM'),
(162, 'nou132036718', 'Sokoto Study Centre  ', 'SO01', 'PHS210_22', '14', '11:00AM'),
(163, 'nou132128570', 'Mccarthy Study Centre Lagos ', 'LA02', 'MTH103_22', '15', '11:00AM'),
(164, 'nou132144048', 'Lagos Study Centre ', 'LA01', 'MTH101_22', '16', '11:00AM'),
(165, 'nou132147811', 'Benin  Study Centre  ', 'ED01', 'MAC242_22', '11', '11:00AM'),
(166, 'nou132150226', 'Abuja Model Study Centre  ', 'FC01', 'CHM191_22', '2', '11:00AM'),
(167, 'nou132169323', 'Ibadan Study Centre ', 'OY01', 'JIL212_22', '3', '11:00AM'),
(168, 'nou132180986', 'Lagos Study Centre ', 'LA01', 'POL324_22', '4', '11:00AM'),
(169, 'nou132218755', 'Iwo Study Centre ', 'OS01', 'CSS212_22', '5', '11:00AM'),
(170, 'nou132232714', 'Offa Community Study Centre  ', 'KW04', 'ENT302_22', '6', '11:00AM'),
(171, 'nou132297187', 'Ibadan Study Centre ', 'OY01', 'BIO191_22', '7', '11:00AM'),
(172, 'nou132299620', 'Asaga Community Study Centre ', 'AB03', 'POL223_22', '8', '11:00AM'),
(173, 'nou132300833', 'Enugu Study Centre  ', 'EN02', 'POL121_22', '9', '11:00AM'),
(174, 'nou132324328', 'Ikom Community Study Centre ', 'CR02', 'BFN421_22', '10', '11:00AM'),
(175, 'nou132336822', 'Ilorin Study Centre  ', 'KW01', 'GST302_22', '11', '11:00AM'),
(176, 'nou132384512', 'Lagos Study Centre ', 'LA01', 'CIT381_22', '12', '2:00PM'),
(177, 'nou132409295', 'Nigeria Navy Special Study Centre Apapa Lagos ', 'LA04', 'CSS742_22', '13', '2:00PM'),
(178, 'nou132416477', 'Lagos Study Centre ', 'LA01', 'PUL303_22', '14', '2:00PM'),
(179, 'nou132416911', 'Awa-Ijebu Community Study Centre ', 'OG02', 'PHY101_22', '15', '2:00PM'),
(180, 'nou132468422', 'Benin  Study Centre  ', 'ED01', 'PUL844_22', '16', '2:00PM'),
(181, 'nou132510180', 'Abuja Model Study Centre  ', 'FC01', 'PAD410_22', '11', '2:00PM'),
(182, 'nou132577275', 'Lagos Study Centre ', 'LA01', 'ESM328_22', '2', '2:00PM'),
(183, 'nou132746230', 'Wuse II Study Centre Abuja ', 'FC09', 'PAD410_22', '3', '2:00PM'),
(184, 'nou132752975', 'Iwo Study Centre ', 'OS01', 'HCM347_22', '4', '2:00PM'),
(185, 'nou132758616', 'Abuja Model Study Centre  ', 'FC01', 'ENT407_22', '5', '2:00PM'),
(186, 'nou132863522', 'Abuja Model Study Centre  ', 'FC01', 'GST202_22', '6', '2:00PM'),
(187, 'nou132864887', 'Akure Study Centre ', 'ON01', 'POL223_22', '7', '2:00PM'),
(188, 'nou132879331', 'Kaduna Study Centre ', 'KD01', 'GST105_22', '8', '2:00PM'),
(189, 'nou132926545', 'Minna Study Centre  ', 'NG01', 'MTH103_22', '9', '2:00PM'),
(190, 'nou132950307', 'Minna Study Centre  ', 'NG01', 'BFN805_22', '10', '2:00PM'),
(191, 'nou132983619', 'Owhrode Community Study Centre ', 'DE03', 'ESM444_22', '11', '2:00PM'),
(192, 'nou133030529', 'Lokoja Study Centre  ', 'KG03', 'MTH103_22', '12', '2:00PM'),
(193, 'nou133055993', 'Ibadan Study Centre ', 'OY01', 'CIT383_22', '13', '2:00PM'),
(194, 'nou133064243', 'Abuja Model Study Centre  ', 'FC01', 'MPA855_22', '14', '8:00AM'),
(195, 'nou133085214', 'Jos Study Centre ', 'PL01', 'CIT237_22', '15', '8:00AM'),
(196, 'nou133186056', 'Ilorin Study Centre  ', 'KW01', 'STT205_22', '16', '8:00AM'),
(197, 'nou133213508', 'Portharcourt Study Centre ', 'RV02', 'PUL241_22', '11', '8:00AM'),
(198, 'nou133220294', 'Abeokuta Study Centre ', 'OG01', 'PAD410_22', '2', '8:00AM'),
(199, 'nou133276205', 'Ilorin Study Centre  ', 'KW01', 'ACC419_22', '3', '8:00AM'),
(200, 'nou133278799', 'Makurdi Study Centre  ', 'BN01', 'BUS325_22', '4', '8:00AM'),
(201, 'nou133337509', 'Abeokuta Study Centre ', 'OG01', 'ENT303_22', '5', '8:00AM'),
(202, 'nou133391683', 'Calabar Study Centre ', 'CR01', 'MTH103_22', '6', '8:00AM'),
(203, 'nou133439343', 'Awka Study Centre ', 'AN01', 'ENT322_22', '7', '11:00AM'),
(204, 'nou133576993', 'Benin  Study Centre  ', 'ED01', 'GST302_22', '8', '11:00AM'),
(205, 'nou133577893', 'Kisi Community Study Centre ', 'OY02', 'PPL422_22', '9', '11:00AM'),
(206, 'nou133580792', 'Nigerian Correctional Service Special Study Centre Port-Harcourt ', 'RV01', 'EDU214_22', '10', '11:00AM'),
(207, 'nou133588103', 'Uyo Study Centre ', 'AK01', 'GST201_22', '11', '11:00AM'),
(208, 'nou133632862', 'Abuja Model Study Centre  ', 'FC01', 'FMS731_22', '12', '11:00AM'),
(209, 'nou133725596', 'Osogbo Study Centre ', 'OS02', 'BUS207_22', '13', '11:00AM'),
(210, 'nou133825466', 'Kaduna Study Centre ', 'KD01', 'CIT237_22', '14', '11:00AM'),
(211, 'nou133848127', 'Lafia Study Centre  ', 'NS01', 'NSC509_22', '15', '11:00AM'),
(212, 'nou133866971', 'Lagos Study Centre ', 'LA01', 'BUS428_22', '16', '11:00AM'),
(213, 'nou133892553', 'Abuja Model Study Centre  ', 'FC01', 'STT205_22', '11', '11:00AM'),
(214, 'nou133893701', 'Ilorin Study Centre  ', 'KW01', 'CLL533_22', '2', '11:00AM'),
(215, 'nou133911910', 'Owerri Study Centre  ', 'IM01', 'PUL303_22', '3', '11:00AM'),
(216, 'nou133965515', 'Ikom Community Study Centre ', 'CR02', 'CIT208_22', '4', '11:00AM'),
(217, 'nou133980854', 'Awka Study Centre ', 'AN01', 'STT205_22', '5', '11:00AM'),
(218, 'nou133993845', 'Owhrode Community Study Centre ', 'DE03', 'BFN421_22', '6', '11:00AM'),
(219, 'nou133998977', 'Nigerian Air Force Special Study Centre Kaduna ', 'KD03', 'PHY101_22', '7', '11:00AM'),
(220, 'nou134017478', 'Abuja Model Study Centre  ', 'FC01', 'CIT427_22', '8', '2:00PM'),
(221, 'nou134053916', 'Lagos Study Centre ', 'LA01', 'MTH102_22', '9', '2:00PM'),
(222, 'nou134133326', 'Kaduna Study Centre ', 'KD01', 'ACC419_22', '10', '2:00PM');

-- --------------------------------------------------------

--
-- Table structure for table `study_centers`
--

CREATE TABLE `study_centers` (
  `id` int(11) NOT NULL,
  `study_center` text DEFAULT NULL,
  `study_center_code` text DEFAULT NULL,
  `study_centre_email` text DEFAULT NULL,
  `phone_number` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `study_centers`
--

INSERT INTO `study_centers` (`id`, `study_center`, `study_center_code`, `study_centre_email`, `phone_number`) VALUES
(1, 'Yola Study Centre ', 'AD01asd', 'yolastudycentre@noun.edu.ng', '2348098101085'),
(2, 'Makurdi Study Centre ', 'BN01', 'makurdistudycentre@noun.edu.ng', '2348098101086'),
(3, 'Mushin Study Centre Lagos', 'LA03', 'mushinstudycentrelagos@noun.edu.ng', '2348098101087'),
(4, 'Abuja Model Study Centre ', 'FC01', 'abujamodelstudycentre@noun.edu.ng', '2348098101088'),
(5, 'Katsina Study Centre ', 'KT02', 'katsinastudycentre@noun.edu.ng', '2348098101089'),
(6, 'Nigeria Navy Special Study Centre Apapa Lagos', 'LA04', 'nigerianavyspecialstudycentreapapalagos@noun.edu.ng', '2348098101090'),
(7, 'Benin  Study Centre ', 'ED01', 'beninstudycentre@noun.edu.ng', '2348098101091'),
(8, 'Asaba Study Centre ', 'DE01', 'asabastudycentre@noun.edu.ng', '2348098101092'),
(9, 'Special Study Centre Nigerian Correctional Service National Headquarters Abuja', 'FC07', 'specialstudycentrenigeriancorrectionalservicenationalheadquartersabuja@noun.edu.ng', '2348098101093'),
(10, 'Nigeria Immigration Service Special Study Centre Abuja', 'FC04', 'nigeriaimmigrationservicespecialstudycentreabuja@noun.edu.ng', '2348098101094'),
(11, 'Ado-Ekiti Study Centre', 'EK01', 'ado-ekitistudycentre@noun.edu.ng', '2348098101095'),
(12, 'Ibadan Study Centre', 'OY01', 'ibadanstudycentre@noun.edu.ng', '2348098101096'),
(13, 'Nigerian Correctional Service Special Study Centre Enugu', 'EN03', 'nigeriancorrectionalservicespecialstudycentreenugu@noun.edu.ng', '2348098101097'),
(14, 'Portharcourt Study Centre', 'RV02', 'portharcourtstudycentre@noun.edu.ng', '2348098101098'),
(15, 'Wuse II Study Centre Abuja', 'FC09', 'wuseiistudycentreabuja@noun.edu.ng', '2348098101099'),
(16, 'Umudike Study Centre ', 'AB01', 'umudikestudycentre@noun.edu.ng', '2348098101100'),
(17, 'Mccarthy Study Centre Lagos', 'LA02', 'mccarthystudycentrelagos@noun.edu.ng', '2348098101101'),
(18, 'Jos Study Centre', 'PL01', 'josstudycentre@noun.edu.ng', '2348098101102'),
(19, 'Sokoto Study Centre ', 'SO01', 'sokotostudycentre@noun.edu.ng', '2348098101103'),
(20, 'Maiduguri Study Centre ', 'BO01', 'maiduguristudycentre@noun.edu.ng', '2348098101104'),
(21, 'Bogoro Community Study Centre', 'BA03', 'bogorocommunitystudycentre@noun.edu.ng', '2348098101105'),
(22, 'Emevor Community Study Centre', 'DE02', 'emevorcommunitystudycentre@noun.edu.ng', '2348098101106'),
(23, 'Kaduna Study Centre', 'KD01', 'kadunastudycentre@noun.edu.ng', '2348098101107'),
(24, 'Kano Study Centre ', 'KN01', 'kanostudycentre@noun.edu.ng', '2348098101108'),
(25, 'Akure Study Centre', 'ON01', 'akurestudycentre@noun.edu.ng', '2348098101109'),
(26, 'Bauchi Study Centre', 'BA02', 'bauchistudycentre@noun.edu.ng', '2348098101110'),
(27, 'Lafia Study Centre ', 'NS01', 'lafiastudycentre@noun.edu.ng', '2348098101111'),
(28, 'Lokoja Study Centre ', 'KG03', 'lokojastudycentre@noun.edu.ng', '2348098101112'),
(29, 'Dutse Study Centre ', 'JG01', 'dutsestudycentre@noun.edu.ng', '2348098101113'),
(30, 'Gusau Study Centre ', 'ZM01', 'gusaustudycentre@noun.edu.ng', '2348098101114'),
(31, 'National Union Of Road Transport Workers Special Study Centre Garki Abuja', 'FC03', 'nationalunionofroadtransportworkersspecialstudycentregarkiabuja@noun.edu.ng', '2348098101115'),
(32, 'Uyo Study Centre', 'AK01', 'uyostudycentre@noun.edu.ng', '2348098101116'),
(33, 'Ilorin Study Centre ', 'KW01', 'ilorinstudycentre@noun.edu.ng', '2348098101117'),
(34, 'Owerri Study Centre ', 'IM01', 'owerristudycentre@noun.edu.ng', '2348098101118'),
(35, 'Nigerian Air Force Special Study Centre Kaduna', 'KD03', 'nigerianairforcespecialstudycentrekaduna@noun.edu.ng', '2348098101119'),
(36, 'Gombe Study Centre ', 'GM01', 'gombestudycentre@noun.edu.ng', '2348098101120'),
(37, 'Calabar Study Centre', 'CR01', 'calabarstudycentre@noun.edu.ng', '2348098101121'),
(38, 'Abeokuta Study Centre', 'OG01', 'abeokutastudycentre@noun.edu.ng', '2348098101122'),
(39, 'Lagos Study Centre', 'LA01', 'lagosstudycentre@noun.edu.ng', '2348098101123'),
(40, 'Osogbo Study Centre', 'OS02', 'osogbostudycentre@noun.edu.ng', '2348098101124'),
(41, 'Nigeria Security And Civil Defence Special Study Centre Sauka Abuja', 'FC06', 'nigeriasecurityandcivildefencespecialstudycentresaukaabuja@noun.edu.ng', '2348098101125'),
(42, 'Abakaliki Study Centre ', 'EB01', 'abakalikistudycentre@noun.edu.ng', '2348098101126'),
(43, 'Enugu Study Centre ', 'EN02', 'enugustudycentre@noun.edu.ng', '2348098101127'),
(44, 'Masari Community Study Centre', 'KT03', 'masaricommunitystudycentre@noun.edu.ng', '2348098101128'),
(45, 'Owhrode Community Study Centre', 'DE03', 'owhrodecommunitystudycentre@noun.edu.ng', '2348098101129'),
(46, 'Nigeria Correctional Service Special Study Centre Ikoyi', 'LA09', 'nigeriacorrectionalservicespecialstudycentreikoyi@noun.edu.ng', '2348098101130'),
(47, 'Nigerian Correctional Service Special Study Centre Port-Harcourt', 'RV01', 'nigeriancorrectionalservicespecialstudycentreport-harcourt@noun.edu.ng', '2348098101131'),
(48, 'Awka Study Centre', 'AN01', 'awkastudycentre@noun.edu.ng', '2348098101132'),
(49, 'Ikom Community Study Centre', 'CR02', 'ikomcommunitystudycentre@noun.edu.ng', '2348098101133'),
(50, 'Damaturu Study Centre', 'YB01', 'damaturustudycentre@noun.edu.ng', '2348098101134'),
(51, 'Wukari Study Centre ', 'TR02', 'wukaristudycentre@noun.edu.ng', '2348098101135'),
(52, 'Uromi Community Study Centre', 'ED03', 'uromicommunitystudycentre@noun.edu.ng', '2348098101136'),
(53, 'Nigeria Police Special Study Centre Kubwa Abuja', 'FC05', 'nigeriapolicespecialstudycentrekubwaabuja@noun.edu.ng', '2348098101137'),
(54, 'Yenagoa Study Centre ', 'BY01', 'yenagoastudycentre@noun.edu.ng', '2348098101138'),
(55, 'Awgu Community Study Centre', 'EN01', 'awgucommunitystudycentre@noun.edu.ng', '2348098101139'),
(56, 'Azare Community Study Centre', 'BA01', 'azarecommunitystudycentre@noun.edu.ng', '2348098101140'),
(57, 'Minna Study Centre ', 'NG01', 'minnastudycentre@noun.edu.ng', '2348098101141'),
(58, 'Gulak Community Study Centre ', 'AD02', 'gulakcommunitystudycentre@noun.edu.ng', '2348098101142'),
(59, 'Kebbi Study Centre', 'KB01', 'kebbistudycentre@noun.edu.ng', '2348098101143'),
(60, 'Idah Community Study Centre ', 'KG01', 'idahcommunitystudycentre@noun.edu.ng', '2348098101144'),
(61, 'Fugar Community Study Centre ', 'ED02', 'fugarcommunitystudycentre@noun.edu.ng', '2348098101145'),
(62, 'Awa-Ijebu Community Study Centre', 'OG02', 'awa-ijebucommunitystudycentre@noun.edu.ng', '2348098101146'),
(63, 'Kagoro Study Centre', 'KD02', 'kagorostudycentre@noun.edu.ng', '2348098101147'),
(64, 'Jalingo Study Centre ', 'TR01', 'jalingostudycentre@noun.edu.ng', '2348098101148'),
(65, 'Viite Special Study Centre Abuja', 'FC08', 'viitespecialstudycentreabuja@noun.edu.ng', '2348098101149'),
(66, 'Nigeria Correctional Service Special Study Centre Keffi', 'NS02', 'nigeriacorrectionalservicespecialstudycentrekeffi@noun.edu.ng', '2348098101150'),
(67, 'Isulo Community Study Centre', 'AN03', 'isulocommunitystudycentre@noun.edu.ng', '2348098101151'),
(68, 'Nigeria Army Special Study Centre Ilorin', 'KW02', 'nigeriaarmyspecialstudycentreilorin@noun.edu.ng', '2348098101152'),
(69, 'Otukpo Study Centre ', 'BN02', 'otukpostudycentre@noun.edu.ng', '2348098101153'),
(70, 'Offa Community Study Centre ', 'KW04', 'offacommunitystudycentre@noun.edu.ng', '2348098101154'),
(71, 'Asaga Community Study Centre', 'AB03', 'asagacommunitystudycentre@noun.edu.ng', '2348098101155'),
(72, 'Kisi Community Study Centre', 'OY02', 'kisicommunitystudycentre@noun.edu.ng', '2348098101156'),
(73, 'Ogori Community Study Centre', 'KG04', 'ogoricommunitystudycentre@noun.edu.ng', '2348098101157'),
(74, 'Opi Community Study Centre Nsuka', 'EN04', 'opicommunitystudycentrensuka@noun.edu.ng', '2348098101158'),
(75, 'Hadejia Study Centre', 'JG02', 'hadejiastudycentre@noun.edu.ng', '2348098101159'),
(76, 'Kwaciri Study Centre', 'KN17', 'kwaciristudycentre@noun.edu.ng', '2348098101160'),
(77, 'Kabo Study Centre', 'KN06', 'kabostudycentre@noun.edu.ng', '2348098101161'),
(78, 'Iwo Study Centre', 'OS01', 'iwostudycentre@noun.edu.ng', '2348098101162'),
(79, 'Sapele Community Study Centre', 'DE04', 'sapelecommunitystudycentre@noun.edu.ng', '2348098101163'),
(80, 'Fagge Study Centre', 'KN16', 'faggestudycentre@noun.edu.ng', '2348098101164'),
(81, 'Iyara Community Study Centre', 'KG02', 'iyaracommunitystudycentre@noun.edu.ng', '2348098101165'),
(82, 'Tofa Study Centre', 'KN07', 'tofastudycentre@noun.edu.ng', '2348098101166'),
(83, 'Kuje Correctional Service Study Centre', 'FC11', 'kujecorrectionalservicestudycentre@noun.edu.ng', '2348098101167'),
(84, 'Nigerian Maximum Security Correctional Service Special Study Centre Kirikiri', 'LA07', 'nigerianmaximumsecuritycorrectionalservicespecialstudycentrekirikiri@noun.edu.ng', '2348098101168'),
(85, 'Isua-Akoko Community Study Centre', 'ON02', 'isua-akokocommunitystudycentre@noun.edu.ng', '2348098101169'),
(86, 'Kaduna Correctional Service Study Centre', 'KD04', 'kadunacorrectionalservicestudycentre@noun.edu.ng', '2348098101170'),
(87, 'Otan-Ayegbaju Community Study Centre', 'OS03', 'otan-ayegbajucommunitystudycentre@noun.edu.ng', '2348098101171'),
(88, 'Danbatta Study Centre', 'KN09', 'danbattastudycentre@noun.edu.ng', '2348098101172'),
(89, 'Gwarzo Study Centre', 'KN10', 'gwarzostudycentre@noun.edu.ng', '2348098101173'),
(90, 'Ugbokolo Study Centre ', 'BN03', 'ugbokolostudycentre@noun.edu.ng', '2348098101174'),
(91, 'Benin Study Centre ', 'ED01', 'beninstudycentre@noun.edu.ng', '2348098101175'),
(92, 'Gabasawa Study Centre', 'KN13', 'gabasawastudycentre@noun.edu.ng', '2348098101176'),
(93, 'Dawakin Kudu Study Centre', 'KN15', 'dawakinkudustudycentre@noun.edu.ng', '2348098101177'),
(94, 'Nigerian Medium Security Correctional Service Special Study Centre Kirikiri Lagos', 'LA05', 'nigerianmediumsecuritycorrectionalservicespecialstudycentrekirikirilagos@noun.edu.ng', '2348098101178'),
(95, 'Dawakin Tofa Study Centre', 'KN05', 'dawakintofastudycentre@noun.edu.ng', '2348098101179'),
(96, 'Bagwai Study Centre', 'KN03', 'bagwaistudycentre@noun.edu.ng', '2348098101180'),
(97, 'Rimi Gado Study Centre', 'KN04', 'rimigadostudycentre@noun.edu.ng', '2348098101181'),
(98, 'Makoda Study Centre', 'KN12', 'makodastudycentre@noun.edu.ng', '2348098101182'),
(99, 'Abeokuta Correctional Service  Study Centre', 'OG03', 'abeokutacorrectionalservicestudycentre@noun.edu.ng', '2348098101183'),
(100, 'Bichi Study Centre', 'KN08', 'bichistudycentre@noun.edu.ng', '2348098101184'),
(101, 'Lafia Correctional Service Study Centre', 'NS03', 'lafiacorrectionalservicestudycentre@noun.edu.ng', '2348098101185'),
(102, 'Nigeria Correctional Service Special Study Centre Awka', 'AN02', 'nigeriacorrectionalservicespecialstudycentreawka@noun.edu.ng', '2348098101186'),
(103, 'Nigerian Female Correctional Service Special Study Centre Kirikiri', 'LA08', 'nigerianfemalecorrectionalservicespecialstudycentrekirikiri@noun.edu.ng', '2348098101187'),
(104, 'Unknown	', 'UU00', 'unknown	@noun.edu.ng', '2348098101188'),
(105, 'Shanono Study Centre', 'KN02', 'shanonostudycentre@noun.edu.ng', '2348098101189'),
(106, 'Umuahia Correctional Service Study Centre', 'AB02', 'umuahiacorrectionalservicestudycentre@noun.edu.ng', '2348098101190'),
(107, 'Kunchi Study Centre', 'KN11', 'kunchistudycentre@noun.edu.ng', '2348098101191'),
(108, 'Ilesha Correctional Service Special Study Centre', 'OS05', 'ileshacorrectionalservicespecialstudycentre@noun.edu.ng', '2348098101192'),
(109, 'Tsanyawa Study Centre', 'KN14', 'tsanyawastudycentre@noun.edu.ng', '2348098101193');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student_registrations`
--
ALTER TABLE `student_registrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `study_centers`
--
ALTER TABLE `study_centers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student_registrations`
--
ALTER TABLE `student_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `study_centers`
--
ALTER TABLE `study_centers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
