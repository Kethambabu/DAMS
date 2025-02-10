-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2025 at 04:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: damsmsdb
--

-- --------------------------------------------------------

--
-- Table structure for table feedbacks
--

CREATE TABLE feedbacks (
  id int(11) NOT NULL,
  feedback text DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table feedbacks
--

INSERT INTO feedbacks (id, feedback, created_at) VALUES
(1, 'acha bahut lucha', '2025-02-03 06:58:46'),
(2, 'Good Monitoring by the doctors, and the pathology doctor is good to treat(Anil).', '2025-02-03 17:00:19'),
(3, 'Anil is the best doctor for pathology.,', '2025-02-03 17:00:39'),
(4, 'Appoint doctors like Anilpatnala good for society welfare.', '2025-02-03 17:00:58');

-- --------------------------------------------------------

--
-- Table structure for table tblappointment
--

CREATE TABLE tblappointment (
  ID int(10) NOT NULL,
  AppointmentNumber int(10) DEFAULT NULL,
  Name varchar(250) DEFAULT NULL,
  MobileNumber bigint(20) DEFAULT NULL,
  Email varchar(250) DEFAULT NULL,
  AppointmentDate date DEFAULT NULL,
  AppointmentTime time DEFAULT NULL,
  Specialization varchar(250) DEFAULT NULL,
  Doctor int(10) DEFAULT NULL,
  Message mediumtext DEFAULT NULL,
  ApplyDate timestamp NULL DEFAULT current_timestamp(),
  Remark varchar(250) DEFAULT NULL,
  Status varchar(250) DEFAULT NULL,
  UpdationDate timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table tblappointment
--

INSERT INTO tblappointment (ID, AppointmentNumber, Name, MobileNumber, Email, AppointmentDate, AppointmentTime, Specialization, Doctor, Message, ApplyDate, Remark, Status, UpdationDate) VALUES
(1, 141561395, 'Rajesh Kaur', 989, 'raj@gmail.com', '2025-01-31', '12:41:00', '2', 2, 'Thanks', '2022-11-10 06:11:50', 'Cancelled due to incorrect mobile number', 'Cancelled', '2025-02-07 16:27:13'),
(2, 499219152, 'Mukesh Yadav', 7977797979, 'mukesh@gmail.com', '2025-01-31', '12:30:00', '2', 2, 'bmnbmngfugwakJDiowhfdgr', '2022-11-10 07:08:58', 'Your appointment has been approved, kindly came at mention time', 'Approved', '2025-02-07 16:27:20'),
(3, 485109480, 'Tina Yadav', 4654564464, 'tina@gmail.com', '2025-01-31', '13:00:00', '1', 1, 'bjnbjh', '2022-11-10 12:08:51', 'Appointment request has been approved', 'Approved', '2025-02-07 16:27:30'),
(4, 611388102, 'Jyoti', 7897987987, 'jyoti@gmail.com', '2025-01-31', '02:00:00', '1', 1, 'Thanks', '2022-11-10 14:31:17', 'k', 'Cancelled', '2025-02-07 16:27:39'),
(5, 607441873, 'Anuj kumar', 1425362514, 'anujkkk@hmak.com', '2022-11-16', '20:50:00', '1', 1, 'NA', '2022-11-11 01:19:37', 'll', 'Cancelled', '2025-02-02 16:18:03'),
(6, 667282012, 'Rahul', 1425251414, 'rk@gmail.com', '2022-11-15', '18:31:00', '2', 2, 'NA', '2022-11-11 01:48:52', 'Approved', 'Approved', '2022-11-11 07:24:25'),
(7, 599829368, 'Anita', 4563214563, 'anta@test.com', '2022-11-25', '15:20:00', '2', 2, 'NA', '2022-11-11 01:49:54', NULL, NULL, NULL),
(8, 940019123, 'Amit Kumar', 1425362514, 'amitkr123@test.com', '2022-11-15', '12:30:00', '13', 4, 'NA', '2022-11-11 01:56:17', 'Your appointment has been approved.', 'Approved', '2022-11-11 01:56:55'),
(9, 978020992, 'Anu', 798789798, 'anu@gmail.com', '1980-02-03', '21:50:00', '10', 5, '', '2025-02-02 16:20:54', NULL, NULL, '2025-02-07 16:28:55'),
(10, 713614317, 'gheee', 9969455945, 'gheer@gmail.com', '2025-02-20', '12:01:00', '1', 1, 'anu is great', '2025-02-07 04:29:15', 'Patient is good.', 'Approved', '2025-02-07 04:30:21'),
(11, 712424591, 'pavani', 9959674723, 'anilpatnala123@gmail.com', '2025-02-19', '10:10:00', '1', 1, '', '2025-02-07 04:36:57', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table tbldoctor
--

CREATE TABLE tbldoctor (
  ID int(5) NOT NULL,
  FullName varchar(250) DEFAULT NULL,
  MobileNumber bigint(10) DEFAULT NULL,
  Email varchar(250) DEFAULT NULL,
  Specialization varchar(250) DEFAULT NULL,
  Password varchar(259) DEFAULT NULL,
  CreationDate timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table tbldoctor
--

INSERT INTO tbldoctor (ID, FullName, MobileNumber, Email, Specialization, Password, CreationDate) VALUES
(1, 'Anu', 9787979798, 'anu@gmail.com', '1', 'f925916e2754e5e03f75dd58a5733251', '2022-11-09 15:01:11'),
(2, 'Dr. Pradeep Chauhan', 6464654646, 'pra@gmail.com', '2', '202cb962ac59075b964b07152d234b70', '2022-11-09 15:01:59'),
(3, 'Garima Singh', 14253625, 'gs123@test.com', '7', 'f925916e2754e5e03f75dd58a5733251', '2022-11-11 01:28:44'),
(4, 'Shiv Kumar Singh', 1231231230, 'skmr123@test.com', '4', 'f925916e2754e5e03f75dd58a5733251', '2022-11-11 01:54:44'),
(5, 'Patnala Anil', 7989318985, 'anilpatnala123@gmail.com', '10', '25d55ad283aa400af464c76d713c07ad', '2025-02-02 16:19:45'),
(6, 'Anil', 9959674723, 'n210432@rguktn.ac.in', '12', 'dae25370b4b2cd9c9d8483059950cdf4', '2023-02-03 16:57:49');

-- --------------------------------------------------------

--
-- Table structure for table tblpage
--

CREATE TABLE tblpage (
  ID int(10) NOT NULL,
  PageType varchar(200) DEFAULT NULL,
  PageTitle mediumtext DEFAULT NULL,
  PageDescription mediumtext DEFAULT NULL,
  Email varchar(200) DEFAULT NULL,
  MobileNumber bigint(10) DEFAULT NULL,
  UpdationDate date DEFAULT NULL,
  Timing varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table tblpage
--

INSERT INTO tblpage (ID, PageType, PageTitle, PageDescription, Email, MobileNumber, UpdationDate, Timing) VALUES
(1, 'aboutus', 'About Us', '<div><font color=\"#202124\" face=\"arial, sans-serif\"><b>Health is Wealth, Cancer not to transfer.  Stay strong stay Healthy.</b></font></div><div><font color=\"#202124\" face=\"arial, sans-serif\"><b><br></b></font></div><div><font color=\"#202124\" face=\"arial, sans-serif\"><b>To give a best treatment with great specialists like anil patnala (pathology, MA ,FASIFIAS, MBBS). HIV special sugenon(Ketham babai) ,cancer, lukemia, and cancer, pyschiartist pawan vaddi best moral speeches, Best Gyanocolgist(Venkatalaksmi Maddu), and Sagar (best physician with 20 year experience)</b></font></div>', NULL, NULL, NULL, ''),
(2, 'contactus', 'Contact Us', '532401, Nuzviduu, Eluru District, Andhra Pradesh.', 'anipatnala@123gmail.com', 9848075162, NULL, '10:30 am to 7:30 pm');

-- --------------------------------------------------------

--
-- Table structure for table tblspecialization
--

CREATE TABLE tblspecialization (
  ID int(5) NOT NULL,
  Specialization varchar(250) DEFAULT NULL,
  CreationDate timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table tblspecialization
--

INSERT INTO tblspecialization (ID, Specialization, CreationDate) VALUES
(1, 'Orthopedics', '2025-01-13 14:22:33'),
(2, 'Internal Medicine', '2025-01-09 14:23:42'),
(3, 'Obstetrics and Gynecology', '2025-01-31 14:24:14'),
(4, 'Dermatology', '2025-01-31 14:24:14'),
(5, 'Pediatrics', '2025-01-31 14:25:06'),
(6, 'Radiology', '2025-01-31 14:25:31'),
(7, 'General Surgery', '2025-01-31 14:25:52'),
(8, 'Ophthalmology', '2025-01-31 14:27:18'),
(9, 'Family Medicine', '2025-01-31 14:27:52'),
(10, 'Chest Medicine', '2025-01-31 14:28:32'),
(11, 'Anesthesia', '2025-01-31 14:29:12'),
(12, 'Pathology', '2025-01-31 14:29:51'),
(13, 'ENT', '2025-01-31 14:30:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table feedbacks
--
ALTER TABLE feedbacks
  ADD PRIMARY KEY (id);

--
-- Indexes for table tblappointment
--
ALTER TABLE tblappointment
  ADD PRIMARY KEY (ID);

--
-- Indexes for table tbldoctor
--
ALTER TABLE tbldoctor
  ADD PRIMARY KEY (ID);

--
-- Indexes for table tblpage
--
ALTER TABLE tblpage
  ADD PRIMARY KEY (ID);

--
-- Indexes for table tblspecialization
--
ALTER TABLE tblspecialization
  ADD PRIMARY KEY (ID);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table feedbacks
--
ALTER TABLE feedbacks
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table tblappointment
--
ALTER TABLE tblappointment
  MODIFY ID int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table tbldoctor
--
ALTER TABLE tbldoctor
  MODIFY ID int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table tblpage
--
ALTER TABLE tblpage
  MODIFY ID int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table tblspecialization
--
ALTER TABLE tblspecialization
  MODIFY ID int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;