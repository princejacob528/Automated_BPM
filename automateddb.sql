-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2021 at 02:40 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `automateddb`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `aid` int(5) NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `stat` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `actdetails`
--

CREATE TABLE `actdetails` (
  `id` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `fname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `lname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `ads1` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `ads2` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `states` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pincode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `pno` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `dest` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `doh` date NOT NULL,
  `idnum` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `utype` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `salary` int(11) NOT NULL,
  `extraduty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `atid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `attdate` date NOT NULL,
  `checkin` time NOT NULL,
  `checkout` time NOT NULL,
  `ahours` float NOT NULL,
  `extraduty` float NOT NULL,
  `attstat` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `astatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attkey`
--

CREATE TABLE `attkey` (
  `aatid` int(11) NOT NULL,
  `attname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `attstr` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custcredit`
--

CREATE TABLE `custcredit` (
  `crid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `dates` date NOT NULL,
  `credit` int(50) NOT NULL,
  `debit` int(50) NOT NULL,
  `ref` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cid` int(5) NOT NULL,
  `cname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cads` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `cemail` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cphone` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial`
--

CREATE TABLE `financial` (
  `fid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `dates` date NOT NULL,
  `salary` int(11) NOT NULL,
  `incentive` int(11) NOT NULL,
  `bonus` int(11) NOT NULL,
  `extraduty` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leavetab`
--

CREATE TABLE `leavetab` (
  `lid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `ltype` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `duration` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `dates` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `fristdate` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `lastdate` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `hoursdate` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `lhours` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `reason` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lstatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notificat`
--

CREATE TABLE `notificat` (
  `nid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `nsub` char(200) COLLATE utf8_unicode_ci NOT NULL,
  `ntitle` char(100) COLLATE utf8_unicode_ci NOT NULL,
  `nstatus` int(11) NOT NULL,
  `ntype` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `nsender` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pdtid` int(11) NOT NULL,
  `pdtname` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `pdtdetails` varchar(60) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productdts`
--

CREATE TABLE `productdts` (
  `ptid` int(11) NOT NULL,
  `pdtid` int(11) NOT NULL,
  `ptsn` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `dp` int(20) NOT NULL,
  `mrp` int(20) NOT NULL,
  `cost` int(20) NOT NULL,
  `discount` int(11) NOT NULL,
  `refp` int(11) NOT NULL,
  `stat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `pcid` int(11) NOT NULL,
  `dates` date NOT NULL,
  `vid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `remarks` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `total` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sid` int(11) NOT NULL,
  `dates` date NOT NULL,
  `aid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `brid` int(11) NOT NULL,
  `remarks` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `incentive` int(20) NOT NULL,
  `total` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `srid` int(11) NOT NULL,
  `srcr` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `brid` int(11) NOT NULL,
  `dates` date NOT NULL,
  `items` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `complaints` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `stat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servicedt`
--

CREATE TABLE `servicedt` (
  `srdid` int(11) NOT NULL,
  `srid` int(11) NOT NULL,
  `svrid` int(11) NOT NULL,
  `srno` int(11) NOT NULL,
  `stat` int(11) NOT NULL,
  `srdetails` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `invdetails` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `incentive` int(20) NOT NULL,
  `cost` int(20) NOT NULL,
  `discount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `serviceinvoice`
--

CREATE TABLE `serviceinvoice` (
  `sriid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `srid` int(11) NOT NULL,
  `dates` date NOT NULL,
  `remarks` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `incentive` int(20) NOT NULL,
  `total` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `shid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `eid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shopexp`
--

CREATE TABLE `shopexp` (
  `exid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `expense` int(11) NOT NULL,
  `details` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dates` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `Chip_Level_Service` int(11) NOT NULL,
  `Advanced_Hardware_Service` int(11) NOT NULL,
  `Software_Service` int(11) NOT NULL,
  `Advanced_Software_Service` int(11) NOT NULL,
  `Surveillance_System_Management` int(11) NOT NULL,
  `Printer_Service` int(11) NOT NULL,
  `Toner_Service` int(11) NOT NULL,
  `Hardware_Service_PC` int(11) NOT NULL,
  `Hardware_Service_Laptop` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `streq`
--

CREATE TABLE `streq` (
  `reid` int(11) NOT NULL,
  `dates` date NOT NULL,
  `requestor` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `pdtid` int(11) NOT NULL,
  `stat` int(11) NOT NULL,
  `istat` int(11) NOT NULL,
  `ref` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE `vendor` (
  `vid` int(11) NOT NULL,
  `vname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `vads` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `vemail` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `vphone` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendorcredit`
--

CREATE TABLE `vendorcredit` (
  `crid` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `dates` date NOT NULL,
  `credit` int(50) NOT NULL,
  `debit` int(50) NOT NULL,
  `ref` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `actdetails`
--
ALTER TABLE `actdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`aid`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`atid`),
  ADD KEY `attendance` (`aid`);

--
-- Indexes for table `attkey`
--
ALTER TABLE `attkey`
  ADD PRIMARY KEY (`aatid`);

--
-- Indexes for table `custcredit`
--
ALTER TABLE `custcredit`
  ADD PRIMARY KEY (`crid`),
  ADD KEY `cutomername` (`cid`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `financial`
--
ALTER TABLE `financial`
  ADD PRIMARY KEY (`fid`),
  ADD KEY `financial_rel` (`aid`);

--
-- Indexes for table `leavetab`
--
ALTER TABLE `leavetab`
  ADD PRIMARY KEY (`lid`),
  ADD KEY `leaveRe` (`aid`);

--
-- Indexes for table `notificat`
--
ALTER TABLE `notificat`
  ADD PRIMARY KEY (`nid`),
  ADD KEY `notification` (`aid`),
  ADD KEY `notification sender` (`nsender`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pdtid`);

--
-- Indexes for table `productdts`
--
ALTER TABLE `productdts`
  ADD PRIMARY KEY (`ptid`),
  ADD KEY `product` (`pdtid`),
  ADD KEY `purchase` (`refp`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`pcid`),
  ADD KEY `vendor` (`vid`),
  ADD KEY `purchaseAdder` (`aid`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sid`),
  ADD KEY `biller` (`aid`),
  ADD KEY `customersss` (`cid`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`srid`),
  ADD KEY `service creator` (`srcr`),
  ADD KEY `service customer` (`cid`),
  ADD KEY `service branch` (`brid`);

--
-- Indexes for table `servicedt`
--
ALTER TABLE `servicedt`
  ADD PRIMARY KEY (`srdid`),
  ADD KEY `service id` (`srid`),
  ADD KEY `servicer id` (`svrid`);

--
-- Indexes for table `serviceinvoice`
--
ALTER TABLE `serviceinvoice`
  ADD PRIMARY KEY (`sriid`),
  ADD KEY `service invoice` (`srid`),
  ADD KEY `service aid` (`aid`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`shid`),
  ADD KEY `shop_manger` (`aid`),
  ADD KEY `shop_employee` (`eid`);

--
-- Indexes for table `shopexp`
--
ALTER TABLE `shopexp`
  ADD PRIMARY KEY (`exid`),
  ADD KEY `expense_aid` (`aid`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skid`),
  ADD KEY `skills` (`aid`);

--
-- Indexes for table `streq`
--
ALTER TABLE `streq`
  ADD PRIMARY KEY (`reid`),
  ADD KEY `request` (`requestor`),
  ADD KEY `productss` (`pdtid`);

--
-- Indexes for table `vendor`
--
ALTER TABLE `vendor`
  ADD PRIMARY KEY (`vid`);

--
-- Indexes for table `vendorcredit`
--
ALTER TABLE `vendorcredit`
  ADD PRIMARY KEY (`crid`),
  ADD KEY `vendorname` (`vid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `aid` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `actdetails`
--
ALTER TABLE `actdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `atid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attkey`
--
ALTER TABLE `attkey`
  MODIFY `aatid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custcredit`
--
ALTER TABLE `custcredit`
  MODIFY `crid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cid` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial`
--
ALTER TABLE `financial`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leavetab`
--
ALTER TABLE `leavetab`
  MODIFY `lid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notificat`
--
ALTER TABLE `notificat`
  MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pdtid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productdts`
--
ALTER TABLE `productdts`
  MODIFY `ptid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `pcid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `srid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servicedt`
--
ALTER TABLE `servicedt`
  MODIFY `srdid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `serviceinvoice`
--
ALTER TABLE `serviceinvoice`
  MODIFY `sriid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `shid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shopexp`
--
ALTER TABLE `shopexp`
  MODIFY `exid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `streq`
--
ALTER TABLE `streq`
  MODIFY `reid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor`
--
ALTER TABLE `vendor`
  MODIFY `vid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendorcredit`
--
ALTER TABLE `vendorcredit`
  MODIFY `crid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actdetails`
--
ALTER TABLE `actdetails`
  ADD CONSTRAINT `account_id` FOREIGN KEY (`aid`) REFERENCES `account` (`aid`);

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance` FOREIGN KEY (`aid`) REFERENCES `account` (`aid`);

--
-- Constraints for table `custcredit`
--
ALTER TABLE `custcredit`
  ADD CONSTRAINT `cutomername` FOREIGN KEY (`cid`) REFERENCES `customer` (`cid`);

--
-- Constraints for table `financial`
--
ALTER TABLE `financial`
  ADD CONSTRAINT `financial_rel` FOREIGN KEY (`aid`) REFERENCES `account` (`aid`);

--
-- Constraints for table `leavetab`
--
ALTER TABLE `leavetab`
  ADD CONSTRAINT `leaveRe` FOREIGN KEY (`aid`) REFERENCES `account` (`aid`);

--
-- Constraints for table `notificat`
--
ALTER TABLE `notificat`
  ADD CONSTRAINT `notification` FOREIGN KEY (`aid`) REFERENCES `account` (`aid`),
  ADD CONSTRAINT `notification sender` FOREIGN KEY (`nsender`) REFERENCES `account` (`aid`);

--
-- Constraints for table `productdts`
--
ALTER TABLE `productdts`
  ADD CONSTRAINT `product` FOREIGN KEY (`pdtid`) REFERENCES `product` (`pdtid`),
  ADD CONSTRAINT `purchase` FOREIGN KEY (`refp`) REFERENCES `purchase` (`pcid`);

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `purchaseAdder` FOREIGN KEY (`aid`) REFERENCES `account` (`aid`),
  ADD CONSTRAINT `vendor` FOREIGN KEY (`vid`) REFERENCES `vendor` (`vid`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `biller` FOREIGN KEY (`aid`) REFERENCES `account` (`aid`),
  ADD CONSTRAINT `customersss` FOREIGN KEY (`cid`) REFERENCES `customer` (`cid`);

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service branch` FOREIGN KEY (`brid`) REFERENCES `account` (`aid`),
  ADD CONSTRAINT `service creator` FOREIGN KEY (`srcr`) REFERENCES `account` (`aid`),
  ADD CONSTRAINT `service customer` FOREIGN KEY (`cid`) REFERENCES `customer` (`cid`);

--
-- Constraints for table `servicedt`
--
ALTER TABLE `servicedt`
  ADD CONSTRAINT `service id` FOREIGN KEY (`srid`) REFERENCES `service` (`srid`),
  ADD CONSTRAINT `servicer id` FOREIGN KEY (`svrid`) REFERENCES `account` (`aid`);

--
-- Constraints for table `serviceinvoice`
--
ALTER TABLE `serviceinvoice`
  ADD CONSTRAINT `service aid` FOREIGN KEY (`aid`) REFERENCES `account` (`aid`),
  ADD CONSTRAINT `service invoice` FOREIGN KEY (`srid`) REFERENCES `service` (`srid`);

--
-- Constraints for table `shop`
--
ALTER TABLE `shop`
  ADD CONSTRAINT `shop_employee` FOREIGN KEY (`eid`) REFERENCES `account` (`aid`),
  ADD CONSTRAINT `shop_manger` FOREIGN KEY (`aid`) REFERENCES `account` (`aid`);

--
-- Constraints for table `shopexp`
--
ALTER TABLE `shopexp`
  ADD CONSTRAINT `expense_aid` FOREIGN KEY (`aid`) REFERENCES `account` (`aid`);

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `skills` FOREIGN KEY (`aid`) REFERENCES `account` (`aid`);

--
-- Constraints for table `streq`
--
ALTER TABLE `streq`
  ADD CONSTRAINT `productss` FOREIGN KEY (`pdtid`) REFERENCES `product` (`pdtid`),
  ADD CONSTRAINT `request` FOREIGN KEY (`requestor`) REFERENCES `account` (`aid`);

--
-- Constraints for table `vendorcredit`
--
ALTER TABLE `vendorcredit`
  ADD CONSTRAINT `vendorname` FOREIGN KEY (`vid`) REFERENCES `vendor` (`vid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
