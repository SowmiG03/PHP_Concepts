-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2024 at 04:47 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `hall_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `modified_date` date DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `purpose` enum('event','class') NOT NULL,
  `purpose_name` varchar(50) NOT NULL,
  `students_count` int(11) NOT NULL,
  `organiser_name` varchar(105) DEFAULT NULL,
  `organiser_department` varchar(255) DEFAULT NULL,
  `organiser_mobile` varchar(100) DEFAULT NULL,
  `organiser_email` varchar(100) DEFAULT NULL,
  `status` enum('pending','approved','booked','rejected','cancelled') DEFAULT NULL,
  `cancellation_reason` varchar(100) DEFAULT NULL,
  `rejection_reason` varchar(100) DEFAULT NULL,
  `slot_or_session` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `hall_id`, `user_id`, `booking_date`, `modified_date`, `start_date`, `end_date`, `start_time`, `end_time`, `purpose`, `purpose_name`, `students_count`, `organiser_name`, `organiser_department`, `organiser_mobile`, `organiser_email`, `status`, `cancellation_reason`, `rejection_reason`, `slot_or_session`) VALUES
(1, 3, 5, '2024-11-01', NULL, '2024-11-01', '2024-11-01', NULL, NULL, 'event', 'one day workshop', 60, 'Dr. K. S. Kuppusamy', 'department of CS', '9965227891', 'kskuppu@gmail.com', 'pending', NULL, NULL, '1,2,3,4'),
(2, 5, 5, '2024-11-01', NULL, '2024-11-01', '2024-11-01', NULL, NULL, 'class', 'IoT', 60, 'Dr. T. Vengattaraman', 'department of CS', '9965227891', 'vengattaraman.t@pondiuni.ac.in', 'approved', NULL, NULL, '5,6'),
(3, 4, 6, '2024-11-04', NULL, '2024-11-04', '2024-11-05', NULL, NULL, 'event', 'seminar on electronics', 100, 'Dr. K. Anusudha', 'department of electronics', '9965227891', 'anusudhak@yahoo.co.in', 'pending', NULL, NULL, '5,6,7,8'),
(4, 4, 6, '2024-11-01', NULL, '2024-11-06', '2024-11-06', NULL, NULL, 'class', 'electronics', 60, 'Dr. P. Samundiswary', 'department of electronics', '9965227891', 'sam.dee@pondiuni.edu.in', 'cancelled', 'class removed due to some reason', NULL, '1,2,3,4'),
(5, 3, 5, '2024-11-10', NULL, '2024-11-10', '2024-11-10', NULL, NULL, 'event', 'webinar', 60, 'Dr. V. Uma', 'department of CS', '9965227891', 'umabskr@gmail.com', 'pending', NULL, NULL, '5,6,7,8'),
(6, 5, 5, '2024-11-12', NULL, '2024-11-13', '2024-11-14', NULL, NULL, 'event', 'seminar on networks', 60, 'Dr. K. S. Kuppusamy', 'department of CS', '9965227891', 'kskuppu@gmail.com', 'rejected', NULL, NULL, '1,2,3,4'),
(7, 3, 5, '2024-11-16', NULL, '2024-11-16', '2024-11-16', NULL, NULL, 'class', 'web technology', 60, 'Dr. V. Uma', 'department of CS', '9965227891', 'umabskr@gmail.com', 'cancelled', NULL, NULL, '4,5'),
(9, 1, 4, '2024-11-01', NULL, '2024-11-01', '2024-11-01', NULL, NULL, 'class', 'Silapathikaram', 60, 'Dr. M. Jeeva', 'department of Tamil', '9965227891', 'ustadjeeva@gmail.com', 'cancelled', NULL, NULL, '6,7'),
(10, 2, 4, '2024-11-01', NULL, '2024-11-01', '2024-11-01', NULL, NULL, 'event', 'one day workshop', 60, 'Dr. B. RaviKumar', 'department of Tamil', '9965227891', 'ravikumar.tam@pondiuni.edu.in', 'cancelled', NULL, NULL, '1,2,3,4'),
(12, 1, NULL, '2024-10-18', NULL, '2024-11-02', '2024-11-02', NULL, NULL, 'event', 'Webinar event', 50, 'AnuRadha', 'Centre for Pollution Control and Environmental Engineering', '8925359648', 'subhashsb00007@gmail.com', 'cancelled', NULL, NULL, '1,2,3,4'),
(13, 1, NULL, '2024-10-18', NULL, '2024-11-01', '2024-11-01', NULL, NULL, 'event', 'webinar event', 55, 'Radha', 'Dept of Computer Science', '08778807523', 'thirivikram007@gmail.com', 'pending', NULL, NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(10) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `department_name` varchar(150) NOT NULL,
  `hod_name` varchar(255) NOT NULL,
  `hod_contact_mobile` varchar(11) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `hod_contact_email` varchar(255) NOT NULL,
  `hod_intercom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `school_id`, `department_name`, `hod_name`, `hod_contact_mobile`, `designation`, `hod_contact_email`, `hod_intercom`) VALUES
(1, 1, 'Department of Tamil', 'Dr. M. Karunanidhi', '', 'Head Of Department', 'nidhikaruna.tam@pondiuni.ac.in', '+91-413-2654516'),
(2, 2, 'Department of Management Studies', 'Dr. R. Kasilingam', '', 'Head Of Department', 'head.dms@pondiuni.ac.in', '+914132654399'),
(3, 2, 'Department of Management Studies – Karaikal Campus', 'Dr. C. Madhavaiah', '', 'Head Of Department', 'head.kcm@pondiuni.ac.in', '+914368231029'),
(4, 2, 'Department of Commerce', 'Dr. P. Natarajan', '', 'Head Of Department', 'head.com@pondiuni.ac.in', '+914132654694'),
(5, 2, 'Department of Commerce – Karaikal Campus', 'Dr. V. Arulmurugan', '', 'Head Of Department', 'arulmurugan.kcm@pondiuni.edu.in', '+914132654364'),
(6, 2, 'Department of Economics', 'Dr. Prasant Kumar Panda', '', 'Head Of Department', 'head.eco@pondiuni.ac.in', '+914132654669'),
(7, 2, 'Department of Tourism Studies', 'Dr. R. C. Anu Chandran', '', ' Head of Department', 'anu.chandran48@gmail.com', '+914132654729\n'),
(8, 2, 'Department of Banking Technology', 'Dr. V. Mariappan', '', 'Head of Department', 'vmaris.btm@pondiuni.edu.in', '+914132654536'),
(9, 2, 'Department of International Business', 'Dr. P. G. Arul', '', 'Head of Department', 'head.ibm@pondiuni.ac.in', '+914132654643'),
(10, 2, 'Department of Management Studies Port Blair Campus', 'Dr. T. Ganesh', '', 'Head of Department', 'coordinatormbapb@pondiuni.ac.in', '03192295544'),
(11, 3, 'Department of Statistics', 'Dr. Navin Chandra', '', 'Head of Department', 'nc.stat@pondiuni.ac.in', '+914132654390'),
(12, 3, 'Department of Mathematics', 'Dr. A. Joseph Kennedy', '', 'Head of Department', 'kennedy.pondi@gmail.com', '+914132654702'),
(13, 4, 'Department of Coastal Disaster Management', 'Dr. S. Balaji', '', 'Head of Department', 'hodcdm@gmail.com', '03192261520'),
(14, 4, 'Department of Applied Psychology', 'Dr. Sibnath Deb', '', 'Head of Department', 'sibnath23@gmail.com', '03192261520'),
(15, 4, 'Department of Earth Sciences', 'Dr. K. Srinivasamoorthy', '', 'Head of Department', 'moorthy.esc@pondiuni.edu.in', '+914132654490'),
(16, 4, 'Department of Chemistry', 'Dr. Bala. Manimaran', '', 'Head of Department', 'head.che@pondiuni.edu.in', '+914132654410'),
(17, 4, 'Department of Physics', 'Dr. R. Sivakumar', '', 'Head of Department', 'head.phy@pondiuni.ac.in', '+914132654402 /609'),
(18, 5, 'Department of Bioinformatics', 'Dr. P. T. V. Lakshmi', '', 'Head of Department', 'head@bicpu.edu.in', '+914132654589'),
(19, 5, 'Department of Microbiology', 'Dr. Maheswaran Mani', '', 'Head of Department', 'mahes.mib@pondiuni.edu.in', ' +91-413-2654-868 / 870'),
(20, 5, 'Department of Food Science and Technology', 'Dr. S. Haripriya', '', 'Head of Department', 'head.fst@pondiuni.ac.in', '+914132654625'),
(21, 5, 'Department of Ocean Studies and Marine Biology', 'Dr. Gadi Padmavati', '', 'Head of Department', 'padma190@rediffmail.com', '3192262307'),
(22, 5, 'Department of Ecology and Environmental Sciences', 'Dr. S. M. Sundarapandian', '', 'Head of Department', 'head.ees@pondiuni.ac.in', '+91413265432020'),
(23, 5, 'Department of Biotechnology', 'Dr. B. Sudhakar', '', 'Head of Department', 'baluchamy@yahoo.com', '+914132654788'),
(24, 5, 'Department of Biochemistry and Molecular Biology', 'Dr. C. Thirunavukkarasu', '', 'Head of Department', 'head.bmb@pondiuni.ac.in', '+914132654972'),
(25, 6, 'Centre for Foreign Language', '', '', 'Head of Department', '', '0'),
(26, 6, 'Department of Physical Education and Sports', 'Dr. G. Vinod Kumar', '', 'Head of Department', 'head.pes@pondiuni.ac.in', '+914132654845'),
(27, 6, 'Department of Philosophy', 'Dr. Velmurugan. K', '', 'Head of Department', 'velmurugank@pondiuni.ac.in', '+914132654340'),
(28, 6, 'Department of Sanskrit', 'Dr. J. Krishnan', '', 'Head of Department', 'jkrishnan63@yahoo.co.in', '+914132654358'),
(29, 6, 'Department of Hindi', 'Dr. C. Jaya Sankar Babu', '', 'Head of Department', 'dept.of.hindi.12@gmail.com', '+914132654352'),
(30, 6, 'Department of French', 'Dr. Sarmila Acharif', '', 'Head of Department', 'm_sharmi@yahoo.com', '+914132654352'),
(31, 6, 'Department of English', 'Dr. T. Marx', '', 'Head of Department', 'drtmarx@gmail.com', '+914132654803'),
(32, 6, 'Escande Chair in Asian Christian Studies', '', '', 'Head of Department', '', ''),
(33, 7, 'Centre for Maritime Studies', 'Prof. A. Subramanyam Raju', '', 'Head of Department', 'adluriraju@rediffmail.com', '+914132654587'),
(34, 7, 'Centre for European Studies', 'Dr. Kamalaveni', '', 'Head of Department', 'kamalaveni@pondiuni.ac.in', '+914132654'),
(35, 7, 'Centre for Study of Social Exclusion & Inclusive Policy', 'Dr. A. Chidambaram', '', 'Head of Department', 'balajasst@gmail.com', '+914132654380'),
(36, 7, 'UMISARC – Centre for South Asian Studies', 'Prof. A. Subramanyam Raju', '', 'Head of Department', 'adluriraju@rediffmail.com', '+914132654587'),
(37, 7, 'Centre for Women’s Studies', 'Dr. Aashita', '', 'Head of Department', 'aashita.pu@pondiuni.ac.in', '+914132654820'),
(38, 7, 'Department of Social Work', 'Dr. K. Anbu', '', 'Head of Department', 'anbucovai@gmail.com', '+914132654956, 9486313164'),
(39, 7, 'Department of Politics and International Studies', 'Dr. Nanda Kishor M.S', '', 'Head of Department', 'head.pol@pondiuni.ac.in', '+914132654333'),
(40, 7, 'Department of History', 'Dr. N. Chandramouli', '', 'Head of Department', 'c.navuluri@gmail.com', '+914132654384'),
(41, 7, 'Department of Sociology', 'Dr. C. Aruna', '', 'Head of Department', 'mathivanan.pu@gmail.com', '+914132654384'),
(42, 7, 'Department of Anthropology', 'Dr. Valerie Dkhar', '', 'Head of Department', 'valz2203@pondiuni.ac.in', '+914132654765'),
(43, 8, 'Centre for Pollution Control and Environmental Engineering', 'Dr. S. Gajalakshmi', '', 'Head of Department', 'office.cpee@pondiuni.ac.in', '+914132654362'),
(44, 8, 'Department of Computer Science – Karaikal Campus', 'Dr. S. Bhuvaneswari', '', 'Head of Department', 'arafatbegam@gmail.com', '+914368231030'),
(45, 8, 'Department of Electronics Engineering', 'Dr. T. Shanmuganantham', '', 'Head of Department', 'shanmuga.dee@pondiuni.edu.in', '+914132654992'),
(46, 8, 'Department of Computer Science', 'Dr. S. K. V. Jayakumar', '', 'Head of Department', 'hodcspu@gmail.com', '+9104132654990'),
(47, 9, 'Centre for Adult and Continuing Education', '', '', 'Head of Department', 'default@default.in', '0'),
(48, 10, 'Department of Performing Arts', 'Dr. P. Sridharan', '', 'Head of Department', 'drsridharpu@gmail.com', '+914132654646 '),
(49, 11, 'School of Law', 'Dr. S. Victor Anandkumar', '', 'Head of Department', 'schooloflawpu@gmail.com', '+914132654910'),
(50, 12, 'Department of Electronic Media and Mass Communication', 'Dr. Radhika Khanna', '', 'Head of Department', 'office.demmc@pondiuni.ac.in', '+914132654680 '),
(51, 12, 'Department of Library and Information Science', '', '', '', '', '0'),
(52, 13, 'Centre for Nano Sciences & Technology', '', '', '', '', '0'),
(53, 13, 'Department of Green Energy Technology', '', '', '', '', '0'),
(54, 1, 'Subramania Bharathi School of Tamil Language & Literature', 'Dr. S. Sudalai Muthu', '', 'Dean', 'dean.tam@pondiuni.ac.in', '+914132654483'),
(55, 2, 'Department Of Management Studies', 'Dr. R. Kasilingam', '', ' Head of Department', '\r\nhead.dms@pondiuni.ac.in', '+914132654399'),
(56, 2, 'Department of Commerce', 'Dr. P. Natarajan', '', 'Head of Department', 'head.com@pondiuni.ac.in', '\r\n+91-413-2654-694'),
(57, 2, 'Department of Economics', 'Dr. Prasant Kumar Panda', '', 'Head of Department', 'head.eco@pondiuni.ac.in', '+914132654669'),
(58, 2, 'Department of Tourism Studies', 'Dr. R. C. Anu Chandran', '', 'Head of Department', 'anu.chandran48@gmail.com', '+914132654729');

-- --------------------------------------------------------

--
-- Table structure for table `hall_details`
--

CREATE TABLE `hall_details` (
  `hall_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `hall_name` varchar(255) NOT NULL,
  `capacity` varchar(255) NOT NULL,
  `wifi` varchar(255) NOT NULL,
  `ac` varchar(255) NOT NULL,
  `projector` varchar(255) NOT NULL,
  `computer` varchar(255) NOT NULL,
  `audio_system` varchar(255) NOT NULL,
  `podium` varchar(255) NOT NULL,
  `ramp` varchar(255) NOT NULL,
  `smart_board` varchar(255) NOT NULL,
  `lift` varchar(255) NOT NULL,
  `white_board` varchar(255) NOT NULL,
  `blackboard` varchar(255) NOT NULL,
  `floor` enum('Ground Floor','First Floor','Second Floor','') NOT NULL,
  `zone` varchar(255) NOT NULL,
  `cost` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `availability` varchar(255) NOT NULL,
  `belongs_to` enum('Department','School','Administration','') NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `section` varchar(25) NOT NULL,
  `incharge_name` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `incharge_email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `from_date` date NOT NULL,
  `updated_date` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hall_details`
--

INSERT INTO `hall_details` (`hall_id`, `type_id`, `hall_name`, `capacity`, `wifi`, `ac`, `projector`, `computer`, `audio_system`, `podium`, `ramp`, `smart_board`, `lift`, `white_board`, `blackboard`, `floor`, `zone`, `cost`, `image`, `availability`, `belongs_to`, `department_id`, `school_id`, `section_id`, `section`, `incharge_name`, `designation`, `incharge_email`, `phone`, `from_date`, `updated_date`) VALUES
(1, 1, 'SH-1', '70', 'WIFI', 'AC', 'Projector', 'No', 'AudioSystem', 'Podium', 'No', 'Smartboard', 'No', 'No', 'No', 'Ground Floor', 'South', '', 'image/travel-adventure-with-baggage.jpg', 'yes', 'Department', 43, 8, NULL, '', 'Dr. S. Gajalakshmi', 'HoD', 'office.cpee@pondiuni.ac.in', '914132654362', '2024-10-18', ''),
(2, 1, 'SH-2', '100', 'WIFI', 'AC', 'Projector', 'No', 'AudioSystem', 'Podium', 'No', 'Smartboard', 'No', 'No', 'No', 'First Floor', 'North', '', 'image/WhatsApp Image 2024-10-08 at 8.17.59 AM.jpeg', 'yes', 'Department', 43, 8, NULL, '', 'Dr. S. Gajalakshmi', 'HoD', 'office.cpee@pondiuni.ac.in', '914132654362', '2024-10-18', ''),
(3, 1, 'SH-1', '60', 'No', 'AC', 'Projector', 'Computer', 'AudioSystem', 'Podium', 'No', 'Smartboard', 'No', 'Whiteboard', 'Blackboard', 'Second Floor', 'North', '', 'image/UNI_IMG (1).png', 'yes', 'Department', 45, 8, NULL, '', 'Dr. T. Shanmuganantham', 'HoD', 'shanmuga.dee@pondiuni.edu.in', '914132654992', '2024-10-18', ''),
(4, 1, 'SH-2', '90', 'No', 'AC', 'No', 'Computer', 'AudioSystem', 'No', 'Ramp', 'Smartboard', 'No', 'Whiteboard', 'Blackboard', 'Second Floor', 'North', '', 'image/WhatsApp Image 2024-10-09 at 4.59.26 PM.jpeg', 'yes', 'Department', 45, 8, NULL, '', 'Dr. T. Shanmuganantham', 'HoD', 'shanmuga.dee@pondiuni.edu.in', '914132654992', '2024-10-18', ''),
(5, 1, 'SH-1', '50', 'No', 'AC', 'No', 'Computer', 'No', 'Podium', 'No', 'Smartboard', 'No', 'Whiteboard', 'Blackboard', 'Ground Floor', 'North', '', 'image/WhatsApp Image 2024-09-14 at 2.28.11 PM.jpeg', 'yes', 'Department', 46, 8, NULL, '', 'Dr. S.K.V Jayakumar', 'HoD', 'hodcspu@gmail.com', '9104132654990', '2024-10-18', ''),
(6, 1, 'SH-2', '80', 'No', 'AC', 'Projector', 'Computer', 'AudioSystem', 'Podium', 'No', 'Smartboard', 'No', 'Whiteboard', 'Blackboard', 'Second Floor', 'North', '', 'image/WhatsApp Image 2024-10-08 at 8.17.59 AM.jpeg', 'yes', 'Department', 46, 8, NULL, '', 'Dr. S.K.V Jayakumar', 'HoD', 'hodcspu@gmail.com', '9104132654990', '2024-10-18', ''),
(7, 1, 'SH-1', '70', 'WIFI', 'No', 'Projector', 'Computer', 'No', 'Podium', 'Ramp', 'Smartboard', 'No', 'No', 'No', 'First Floor', 'North', '', 'image/UNI_IMG (1).png', 'yes', 'Department', 11, 3, NULL, '', ' Dr. Navin Chandra', 'HoD', 'nc.stat@pondiuni.ac.in', '914132654390', '2024-10-18', ''),
(8, 1, 'SH-2', '100', 'WIFI', 'No', 'Projector', 'Computer', 'No', 'Podium', 'No', 'Smartboard', 'No', 'Whiteboard', 'No', 'First Floor', 'North', '', 'image/travel-adventure-with-baggage.jpg', 'yes', 'Department', 11, 3, NULL, '', 'Dr. Navin Chandra', 'HoD', 'nc.stat@pondiuni.ac.in', '914132654390', '2024-10-18', ''),
(9, 1, 'SH-1', '120', 'No', 'No', 'Projector', 'No', 'AudioSystem', 'Podium', 'Ramp', 'Smartboard', 'No', 'No', 'No', 'First Floor', 'North', '', 'image/UNI_IMG (1).png', 'yes', 'Department', 12, 3, NULL, '', 'Dr. A. Joseph Kennedy', 'HoD', 'kennedy.pondi@gmail.com', '914132654702', '2024-10-18', ''),
(10, 1, 'SH-2', '100', 'No', 'AC', 'Projector', 'No', 'AudioSystem', 'Podium', 'Ramp', 'Smartboard', 'No', 'No', 'No', 'Second Floor', 'North', '', 'image/WhatsApp Image 2024-10-08 at 8.17.59 AM.jpeg', 'yes', 'Department', 12, 3, NULL, '', 'Dr. A. Joseph Kennedy', 'HoD', 'kennedy.pondi@gmail.com', '914132654702', '2024-10-18', '');

-- --------------------------------------------------------

--
-- Table structure for table `hall_type`
--

CREATE TABLE `hall_type` (
  `type_id` int(11) NOT NULL,
  `type_name` enum('Seminar Hall','Auditorium','Lecture Hall Room','Conference Hall') NOT NULL,
  `updated_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hall_type`
--

INSERT INTO `hall_type` (`type_id`, `type_name`, `updated_date`) VALUES
(1, 'Seminar Hall', '0000-00-00'),
(2, 'Auditorium', '0000-00-00'),
(3, 'Lecture Hall Room', '0000-00-00'),
(4, 'Conference Hall', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `school_id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `dean_name` varchar(255) NOT NULL,
  `dean_contact_number` int(255) NOT NULL,
  `dean_email` varchar(255) NOT NULL,
  `dean_intercome` varchar(255) NOT NULL,
  `dean_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`school_id`, `school_name`, `dean_name`, `dean_contact_number`, `dean_email`, `dean_intercome`, `dean_status`) VALUES
(1, 'Subramania Bharathi School of Tamil Language & Literature', 'Dr. S. Sudalai Muthu', 0, 'dean.tam@pondiuni.ac.in', ' +91-413-2654 483', 'permanent'),
(2, 'School of Management', 'Dr. Malabika Deo', 0, 'dean.mgt@pondiuni.edu.in', '+91-413-2654664', 'permanent'),
(3, 'Ramanujan School of Mathematical Sciences', 'Dr. Rajeswari Seshadri', 0, '\" dean.mcs@pondiuni.edu.in\"', '+91-413-2654-647', 'permanent'),
(4, 'School of Physical, Chemical and Applied Sciences', 'Dr. K. Anbalagan', 0, 'dean.pca@pondiuni.ac.in', ' +91-413-2654-859', 'permanent'),
(5, 'School of Life Science', 'Dr. H. Prathap Kumar Shetty', 0, 'puslsdean@gmail.com', ' +91-413-2654-568', 'permanent'),
(6, 'School of Humanities', 'Prof. Clement Sagayaradja Lourdes', 0, 'dean.hum@pondiuni.edu.in', '+91-413-2654-596', 'permanent'),
(7, 'School of Social Sciences and International Studies', 'Dr. G. Chandhrika', 0, 'dean.sss@pondiuni.ac.in', '+91-413-2654-815', 'permanent'),
(8, 'School of Engineering and Technology', 'Dr. S. Sivasathya', 0, 'dean.set@pondiuni.ac.in', '+91-413-2654-309', 'permanent'),
(9, 'School of Education', 'Dr. E. Sreekala', 0, 'dean.edu@pondiuni.ac.in', '91-413-2654-613', 'permanent'),
(10, 'School of Performing Arts', 'Dr. P. Sridharan', 0, '\" dean.spa@pondiuni.edu.in\"', '+91-413-2654-800', 'permanent'),
(11, 'School of Law', 'Dr. S. Victor Anandkumar', 0, 'dean.sol@pondiuni.edu.in', '+91-413-2654-911', 'permanent'),
(12, 'School of Media and Communication', 'Dr. R. Sevukan', 0, 'dean.smc@pondiuni.edu.in', '+91-413-2654-47', 'permanent'),
(13, 'Madanjeet School of Green Energy Technologies', 'Dr. A. Subramania', 0, 'dean.get@pondiuni.edu.in', '+91-413-2654-939', 'permanent');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `section_id` int(11) NOT NULL,
  `section_name` varchar(255) NOT NULL,
  `from_date` date NOT NULL,
  `updated_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`section_id`, `section_name`, `from_date`, `updated_date`) VALUES
(1, 'Engineering', '2024-10-17', '0000-00-00'),
(2, 'Examination Wing', '2024-10-17', '0000-00-00'),
(3, 'Library', '2024-10-17', '0000-00-00'),
(44, 'Guest House', '2024-10-17', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','hod','dean','incharge') NOT NULL,
  `school_id` varchar(10) DEFAULT NULL,
  `department_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `role`, `school_id`, `department_id`) VALUES
(1, 'Admin', '$2y$10$r2Q5BKGjLBTb5Rxez5roOu6CnkFJ82Hq75TU.tgX5p6vHF4dfyw.m', 'admin@gmail.com', 'admin', NULL, NULL),
(2, 'Dr. M. Sathya', '$2y$10$mdDtJh8UwwgCHqR.0afMeugJUah00v/3LB1gGn9lxpy3JHbLLpL9G', 'msathya.csc@pondiuni.ac.in', 'dean', '8', '46'),
(3, 'Dr. S. Sudalai Muthu', '$2y$10$15zXp2GPHgGbGw6E2JmFJe3S5zAneHwIKhrDSn0vOlHQEaivsNS7e', 'dean.tam@pondiuni.ac.in', 'dean', '1', '1'),
(4, 'Dr. M. Karunanidhi', '$2y$10$PrfeVeMixrZTmt1lkKhPsuK5CVke3uVkQkcP8vWJNbT2evSdEAiEG', 'nidhikaruna.tam@pondiuni.ac.in', 'hod', '1', '1'),
(5, 'Dr. S. K. V. Jayakumar', '$2y$10$BUrJ8a5w9mhy7GcItx4xBOYV0gYhrzDAdYdtFOLS/5R9hkgt/QkoW', 'hodcspu@gmail.com', 'hod', '8', '46'),
(6, 'Dr. T. Shanmuganantham', '$2y$10$IzU39bqoqPAuKHzdgN0PFu0./c3fDVGrPpsPnjFfjwpV1FTDQ/HVO', 'shanmuga.dee@pondiuni.edu.in', 'hod', '8', '45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `hall_details`
--
ALTER TABLE `hall_details`
  ADD PRIMARY KEY (`hall_id`),
  ADD KEY `hall_id` (`type_id`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `hall_type`
--
ALTER TABLE `hall_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`school_id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `hall_details`
--
ALTER TABLE `hall_details`
  MODIFY `hall_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hall_type`
--
ALTER TABLE `hall_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `school_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hall_details`
--
ALTER TABLE `hall_details`
  ADD CONSTRAINT `hall_details_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `section` (`section_id`),
  ADD CONSTRAINT `hall_details_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `hall_type` (`type_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
