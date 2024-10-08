-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2022 at 05:52 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bandocat_fieldbookindicesinventory`
--
CREATE DATABASE IF NOT EXISTS `bandocat_fieldbookindicesinventory` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bandocat_fieldbookindicesinventory`;

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `SP_TEMPLATE_FIELDBOOKINDICES_DOCUMENT_CHECK_EXIST_RECORD`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_TEMPLATE_FIELDBOOKINDICES_DOCUMENT_CHECK_EXIST_RECORD` (IN `iLibraryIndex` VARCHAR(200) CHARSET latin1, OUT `oReturnValue` TINYINT(1))  READS SQL DATA
    COMMENT 'GIVEN LIBRARY INDEX, RETURN 1 = EXISTED, 0 = GOOD'
BEGIN
SELECT COUNT(`document`.`documentID`) INTO oReturnValue FROM `document` WHERE `document`.`libraryindex` = iLibraryIndex;
END$$

DROP PROCEDURE IF EXISTS `SP_TEMPLATE_FIELDBOOKINDICES_DOCUMENT_INSERT`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_TEMPLATE_FIELDBOOKINDICES_DOCUMENT_INSERT` (IN `iLibraryIndex` VARCHAR(200) CHARSET latin1, IN `iBookID` INT(11), IN `iPageType` VARCHAR(18) CHARSET latin1, IN `iPageNumber` INT(11), IN `iComments` VARCHAR(300) CHARSET latin1, IN `iNeedsReview` TINYINT(1), IN `iFileName` VARCHAR(100) CHARSET latin1, IN `iFileNamePath` VARCHAR(250) CHARSET latin1)  BEGIN
INSERT INTO `document`(`libraryindex`,`bookID`,`pagetype`,`pagenumber`,`comments`,`needsreview`,`filename`,`filenamepath`) VALUES(iLibraryIndex,iBookID,iPageType,iPageNumber,iComments,iNeedsReview,iFileName,iFileNamePath);
END$$

DROP PROCEDURE IF EXISTS `SP_TEMPLATE_FIELDBOOKINDICES_DOCUMENT_SELECT`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_TEMPLATE_FIELDBOOKINDICES_DOCUMENT_SELECT` (IN `iDocID` INT(11), OUT `oLibraryIndex` VARCHAR(200) CHARSET latin1, OUT `oBookName` VARCHAR(80) CHARSET latin1, OUT `oPageType` VARCHAR(18) CHARSET latin1, OUT `oPageNumber` INT(11), OUT `oComments` VARCHAR(300) CHARSET latin1, OUT `oNeedsReview` TINYINT, OUT `oFileName` VARCHAR(100) CHARSET latin1, OUT `oFileNamePath` VARCHAR(250) CHARSET latin1, OUT `oTranscribed` INT(1))  READS SQL DATA
    COMMENT 'SELECT 1 DOCUMENT FROM DOCUMENT TABLE'
BEGIN
SELECT `document`.`libraryindex`,b.`bookname`,`document`.`pagetype`,`document`.`pagenumber`,`document`.`comments`,`document`.`needsreview`,`document`.`filename`,`document`.`filenamepath`,`document`.`transcribed` INTO oLibraryIndex,oBookName,oPageType,oPageNumber,oComments,oNeedsReview,oFileName,oFileNamePath,oTranscribed FROM `document` LEFT JOIN `book` AS b ON (`document`.`bookID` = b.`bookID`) WHERE `document`.`documentID` = iDocID LIMIT 1;
END$$

DROP PROCEDURE IF EXISTS `SP_TEMPLATE_FIELDBOOKINDICES_DOCUMENT_UPDATE`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_TEMPLATE_FIELDBOOKINDICES_DOCUMENT_UPDATE` (IN `iDocID` INT(11), IN `iLibraryIndex` VARCHAR(200) CHARSET latin1, IN `iBookID` INT(11), IN `iPageType` VARCHAR(18) CHARSET latin1, IN `iPageNumber` INT(11), IN `iComments` VARCHAR(300) CHARSET latin1, IN `iNeedsReview` TINYINT(1))  MODIFIES SQL DATA
    COMMENT 'UPDATE TABLE DOCUMENT IN TEMPLATE INDICES '
BEGIN
UPDATE `document`
SET `libraryindex` = iLibraryIndex,`bookID` = iBookID,`pagetype` = iPageType,`pagenumber` = iPageNumber, `comments` = iComments, `needsreview` = iNeedsReview WHERE `documentID` = iDocID;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
CREATE TABLE `book` (
  `bookID` int(11) NOT NULL,
  `bookname` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
CREATE TABLE `document` (
  `documentID` int(11) NOT NULL COMMENT 'Document Index',
  `libraryindex` varchar(200) NOT NULL COMMENT 'Document''s Library Index',
  `bookID` int(11) NOT NULL COMMENT 'Foreign Key - Book which document belongs to (see `book` table)',
  `pagetype` varchar(18) NOT NULL DEFAULT 'General Index' COMMENT 'Table of Contents or General Index',
  `pagenumber` int(11) DEFAULT NULL,
  `comments` varchar(300) DEFAULT NULL,
  `needsreview` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Value = 1 if document needs to be reviewed',
  `filename` varchar(100) NOT NULL COMMENT 'document''s image name',
  `filenamepath` varchar(250) NOT NULL,
  `transcribed` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'VALUE 1 if Document is fully transcribed'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mapkind`
--

DROP TABLE IF EXISTS `mapkind`;
CREATE TABLE `mapkind` (
  `mapkindID` int(11) NOT NULL,
  `mapkindname` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transcription`
--

DROP TABLE IF EXISTS `transcription`;
CREATE TABLE `transcription` (
  `documentID` int(11) NOT NULL COMMENT 'Part of composite keys along with x1,y1,x2,y2',
  `x1` int(11) NOT NULL COMMENT 'x coordinate of top-left corner of rectangle',
  `y1` int(11) NOT NULL COMMENT 'y coordinate of top-left corner of rectangle',
  `x2` int(11) NOT NULL COMMENT 'x coordinate of bottom-right corner of rectangle',
  `y2` int(11) NOT NULL COMMENT 'y coordinate of bottom-right corner of rectangle',
  `surveyorsection` varchar(100) DEFAULT NULL,
  `blockortract` varchar(100) DEFAULT NULL,
  `lotoracres` varchar(100) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `client` varchar(900) DEFAULT NULL,
  `fieldbookinfo` varchar(900) DEFAULT NULL COMMENT 'Stores multiple tuples of (Book Number, Page Number) in JSON array',
  `relatedpapersfileno` varchar(400) DEFAULT NULL,
  `mapinfo` varchar(800) DEFAULT NULL COMMENT 'Stores multiple tuples of (Map Number, Map Kind) in JSON array',
  `date` varchar(800) NOT NULL DEFAULT '' COMMENT 'Format: MM/DD/YYYY',
  `jobnumber` varchar(500) DEFAULT NULL,
  `comments` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`bookID`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`documentID`),
  ADD UNIQUE KEY `library_index` (`libraryindex`);

--
-- Indexes for table `mapkind`
--
ALTER TABLE `mapkind`
  ADD PRIMARY KEY (`mapkindID`),
  ADD UNIQUE KEY `mp_name` (`mapkindname`),
  ADD KEY `mp_id` (`mapkindID`);

--
-- Indexes for table `transcription`
--
ALTER TABLE `transcription`
  ADD UNIQUE KEY `unique_index` (`documentID`,`x1`,`y1`,`x2`,`y2`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `bookID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `documentID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Document Index';

--
-- AUTO_INCREMENT for table `mapkind`
--
ALTER TABLE `mapkind`
  MODIFY `mapkindID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
