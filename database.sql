-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 01, 2012 at 12:36 PM
-- Server version: 5.5.25
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `money`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `AccountID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `AccountName` varchar(100) NOT NULL,
  `ReconciledTotal` float NOT NULL,
  `Archived` tinyint(4) NOT NULL,
  PRIMARY KEY (`AccountID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

CREATE TABLE `labels` (
  `LabelID` int(11) NOT NULL AUTO_INCREMENT,
  `LabelName` varchar(100) NOT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`LabelID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `PaymentID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `AccountID` int(11) NOT NULL,
  `Timestamp` int(11) NOT NULL,
  `PaymentName` varchar(100) NOT NULL,
  `PaymentDesc` varchar(200) NOT NULL,
  `PaymentAmount` float NOT NULL,
  `PaymentType` varchar(100) NOT NULL,
  `LabelID` int(11) NOT NULL,
  `Reconciled` tinyint(1) NOT NULL,
  `RepeatID` int(11) NOT NULL,
  `Deleted` tinyint(4) NOT NULL,
  `ToAccount` int(11) NOT NULL,
  `PairedID` int(11) NOT NULL,
  PRIMARY KEY (`PaymentID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `repeats`
--

CREATE TABLE `repeats` (
  `RepeatID` int(11) NOT NULL AUTO_INCREMENT,
  `Frequency` varchar(10) NOT NULL,
  `Times` int(11) NOT NULL,
  `ExpireTime` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `AccountID` int(11) NOT NULL,
  `Timestamp` int(11) NOT NULL,
  `PaymentName` varchar(100) NOT NULL,
  `PaymentDesc` varchar(200) NOT NULL,
  `PaymentAmount` float NOT NULL,
  `PaymentType` varchar(100) NOT NULL,
  `LabelID` int(11) NOT NULL,
  `Reconciled` tinyint(4) NOT NULL,
  `ToAccount` int(11) NOT NULL,
  `PairedID` int(11) NOT NULL,
  PRIMARY KEY (`RepeatID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `SessionID` int(11) NOT NULL AUTO_INCREMENT,
  `SessionKey` varchar(64) NOT NULL,
  `UserID` int(11) NOT NULL,
  `SessionTimeout` int(11) NOT NULL,
  `IP` varchar(40) NOT NULL,
  PRIMARY KEY (`SessionID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(64) NOT NULL,
  `Salt` varchar(16) NOT NULL,
  `Validated` tinyint(1) NOT NULL,
  `ValidationKey` varchar(64) NOT NULL,
  `ValidatedTimeout` int(11) NOT NULL,
  `ResetKey` varchar(64) NOT NULL,
  `ResetTimeout` int(11) NOT NULL,
  `PrefCurrency` varchar(10) NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
