-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2026 at 01:02 PM
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
-- Database: `online_exam_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `time_limit` int(11) NOT NULL COMMENT 'Time in minutes',
  `negative_marking` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: No, 1: Yes',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `teacher_id`, `subject_id`, `title`, `total_questions`, `time_limit`, `negative_marking`, `status`, `created_at`) VALUES
(6, 28, 6, 'test 1', 5, 15, 1, 'active', '2026-09-26 10:38:26');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_answer` enum('A','B','C','D') NOT NULL,
  `difficulty` enum('easy','medium','hard') NOT NULL DEFAULT 'medium'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `subject_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `difficulty`) VALUES
(26, 6, 'Which protocol is primarily used to transfer web pages over the Internet?', 'FTP', 'HTTP', 'SMTP', 'SSH', 'B', 'medium'),
(27, 6, 'Which layer of the OSI model is responsible for logical addressing and routing?', 'Data Link Layer', 'Transport Layer', 'Network Layer', 'Session Layer', 'C', 'medium'),
(28, 6, 'Which device forwards data packets between different networks?', 'Switch', 'Hub', 'Router', 'Repeater', 'C', 'medium'),
(29, 6, 'What is the main purpose of DNS?', 'To encrypt data', 'To translate domain names into IP addresses', 'To transfer files', 'To manage databases', 'B', 'medium'),
(30, 6, 'Which IP address belongs to the private IPv4 address range?', '8.8.8.8', '172.16.10.5', '1.1.1.1', '169.254.1.1', 'B', 'medium'),
(31, 6, 'Which protocol is commonly used for sending email?', 'SMTP', 'HTTP', 'FTP', 'SNMP', 'A', 'medium'),
(32, 6, 'Which protocol is used to securely access a remote computer through a command-line interface?', 'FTP', 'SSH', 'HTTP', 'Telnet', 'B', 'medium'),
(33, 6, 'Which technology is used to assign IP addresses automatically to devices on a network?', 'DNS', 'DHCP', 'HTTP', 'ARP', 'B', 'medium'),
(34, 6, 'What does HTTPS provide in addition to HTTP?', 'Data compression only', 'Encrypted communication', 'Faster processing', 'File compression', 'B', 'medium'),
(35, 6, 'Which device operates primarily at Layer 2 of the OSI model?', 'Router', 'Switch', 'Gateway', 'Modem', 'B', 'medium'),
(36, 6, 'Which data structure follows the Last In, First Out principle?', 'Queue', 'Array', 'Stack', 'Linked List', 'C', 'medium'),
(37, 6, 'Which data structure follows the First In, First Out principle?', 'Stack', 'Queue', 'Tree', 'Graph', 'B', 'medium'),
(38, 6, 'Which programming concept allows a class to acquire properties and methods from another class?', 'Encapsulation', 'Inheritance', 'Abstraction', 'Compilation', 'B', 'medium'),
(39, 6, 'Which OOP concept hides internal implementation details from the user?', 'Inheritance', 'Polymorphism', 'Abstraction', 'Recursion', 'C', 'medium'),
(40, 6, 'Which language is primarily used to structure the content of web pages?', 'CSS', 'HTML', 'JavaScript', 'SQL', 'B', 'medium'),
(41, 6, 'Which technology is mainly responsible for the visual presentation and layout of a web page?', 'HTML', 'CSS', 'SQL', 'PHP', 'B', 'medium'),
(42, 6, 'Which language is commonly used to add interactivity to web pages?', 'HTML', 'CSS', 'JavaScript', 'SQL', 'C', 'medium'),
(43, 6, 'Which HTTP status code indicates that a requested web page was not found?', '200', '301', '404', '500', 'C', 'medium'),
(44, 6, 'Which HTTP status code generally indicates a successful request?', '200', '301', '403', '404', 'A', 'medium'),
(45, 6, 'Which database language is used to retrieve and manipulate relational data?', 'HTML', 'SQL', 'CSS', 'XML', 'B', 'medium'),
(46, 6, 'Which SQL command is used to retrieve records from a database?', 'INSERT', 'UPDATE', 'SELECT', 'DELETE', 'C', 'medium'),
(47, 6, 'Which SQL constraint uniquely identifies each record in a table?', 'FOREIGN KEY', 'PRIMARY KEY', 'CHECK', 'DEFAULT', 'B', 'medium'),
(48, 6, 'What is the purpose of a foreign key in a relational database?', 'To encrypt a column', 'To establish a relationship between tables', 'To delete duplicate records', 'To create a database backup', 'B', 'medium'),
(49, 6, 'Which database normalization form removes repeating groups and ensures atomic values?', 'First Normal Form', 'Second Normal Form', 'Third Normal Form', 'Fourth Normal Form', 'A', 'medium'),
(50, 6, 'Which command is used to modify existing records in an SQL table?', 'UPDATE', 'ALTER', 'INSERT', 'CREATE', 'A', 'medium'),
(51, 6, 'Which command is used to remove records from a database table?', 'REMOVE', 'DELETE', 'DROP', 'CLEAR', 'B', 'medium'),
(52, 6, 'What is the main purpose of an operating system?', 'To design websites', 'To manage computer hardware and software resources', 'To create databases only', 'To provide Internet service', 'B', 'medium'),
(53, 6, 'Which operating system component manages the interaction between hardware and software?', 'Browser', 'Kernel', 'Compiler', 'Shell Script', 'B', 'medium'),
(54, 6, 'Which memory is volatile and loses its contents when power is turned off?', 'ROM', 'SSD', 'RAM', 'Hard Disk', 'C', 'medium'),
(55, 6, 'Which storage technology generally uses flash memory and has no moving mechanical parts?', 'HDD', 'SSD', 'DVD', 'Floppy Disk', 'B', 'medium'),
(56, 6, 'What is the primary function of a compiler?', 'To execute web pages', 'To translate source code into machine or target code', 'To manage network traffic', 'To store databases', 'B', 'medium'),
(57, 6, 'Which type of software is designed to detect and remove malicious programs?', 'Compiler', 'Antivirus', 'Debugger', 'Text Editor', 'B', 'medium'),
(58, 6, 'What is phishing?', 'A method of compressing files', 'A fraudulent attempt to obtain sensitive information', 'A database backup technique', 'A network routing protocol', 'B', 'medium'),
(59, 6, 'Which type of malware can replicate itself and spread across networks without requiring a host file?', 'Trojan', 'Worm', 'Spyware', 'Adware', 'B', 'medium'),
(60, 6, 'What is the primary purpose of a firewall?', 'To increase monitor resolution', 'To filter and control network traffic', 'To create spreadsheets', 'To compress files', 'B', 'medium'),
(61, 6, 'Which security principle requires users to receive only the permissions necessary for their tasks?', 'Open Access', 'Least Privilege', 'Full Control', 'Public Access', 'B', 'medium'),
(62, 6, 'Which encryption approach uses the same key for encryption and decryption?', 'Asymmetric encryption', 'Symmetric encryption', 'Hashing', 'Digital signing', 'B', 'medium'),
(63, 6, 'Which technology is commonly used to provide a secure private connection over a public network?', 'LAN', 'VPN', 'DNS', 'DHCP', 'B', 'medium'),
(64, 6, 'What is cloud computing?', 'Using computers without an operating system', 'Delivering computing resources over the Internet', 'Storing files only on DVDs', 'Using only local networks', 'B', 'medium'),
(65, 6, 'Which cloud service model provides virtual machines, storage, and networking resources?', 'SaaS', 'PaaS', 'IaaS', 'DaaS', 'C', 'medium'),
(66, 6, 'Which cloud service model provides ready-to-use software applications over the Internet?', 'IaaS', 'PaaS', 'SaaS', 'LAN', 'C', 'medium'),
(67, 6, 'What is virtualization?', 'Deleting an operating system', 'Creating virtual versions of computing resources', 'Encrypting every file', 'Connecting two printers', 'B', 'medium'),
(68, 6, 'Which network topology connects every device to a central device?', 'Bus', 'Ring', 'Star', 'Mesh', 'C', 'medium'),
(69, 6, 'Which topology provides a dedicated connection between every pair of devices?', 'Bus', 'Star', 'Ring', 'Mesh', 'D', 'medium'),
(70, 6, 'What is the main purpose of a MAC address?', 'To identify a network interface at the data-link level', 'To identify a website', 'To encrypt passwords', 'To identify a database', 'A', 'medium'),
(71, 6, 'Which protocol is commonly used to securely transfer files over an SSH connection?', 'SFTP', 'SMTP', 'SNMP', 'HTTP', 'A', 'medium'),
(72, 6, 'Which version of IP uses 128-bit addresses?', 'IPv4', 'IPv5', 'IPv6', 'IPv8', 'C', 'medium'),
(73, 6, 'What is the purpose of an IP address?', 'To uniquely identify a device or network interface for network communication', 'To store passwords', 'To compress files', 'To format hard drives', 'A', 'medium'),
(74, 6, 'Which technology allows multiple devices in a private network to share a single public IP address?', 'NAT', 'DNS', 'FTP', 'ARP', 'A', 'medium'),
(75, 6, 'Which protocol is used to automatically synchronize time between networked computers?', 'NTP', 'FTP', 'SMTP', 'POP3', 'A', 'medium'),
(76, 6, 'What is the main purpose of a backup system?', 'To increase CPU speed', 'To restore data after loss or corruption', 'To reduce screen brightness', 'To replace an operating system', 'B', 'medium'),
(77, 6, 'Which practice helps protect an online account from unauthorized access by requiring more than one verification method?', 'Defragmentation', 'Multi-factor authentication', 'Disk formatting', 'File compression', 'B', 'medium'),
(78, 6, 'Which technology is commonly used to connect devices wirelessly to a local network?', 'Ethernet only', 'Wi-Fi', 'SATA', 'PCIe', 'B', 'medium'),
(79, 7, 'If 3x + 5 = 20, what is the value of x?', '3', '5', '7', '10', 'B', 'medium'),
(80, 7, 'What is the HCF of 36 and 48?', '6', '8', '12', '16', 'C', 'medium'),
(81, 7, 'What is the LCM of 12 and 18?', '24', '30', '36', '48', 'C', 'medium'),
(82, 7, 'If 20% of a number is 50, what is the number?', '200', '250', '300', '350', 'B', 'medium'),
(83, 7, 'A number is increased by 25% and becomes 500. What was the original number?', '375', '400', '425', '450', 'B', 'medium'),
(84, 7, 'What is the average of 15, 20, 25, 30 and 35?', '20', '22', '25', '28', 'C', 'medium'),
(85, 7, 'A shopkeeper buys an article for ₹800 and sells it for ₹960. What is the profit percentage?', '15%', '20%', '25%', '30%', 'B', 'medium'),
(86, 7, 'An article marked at ₹1,000 is sold at a discount of 15%. What is its selling price?', '₹825', '₹850', '₹875', '₹900', 'B', 'medium'),
(87, 7, 'If the ratio of two numbers is 3:5 and their sum is 64, what is the smaller number?', '18', '21', '24', '27', 'C', 'medium'),
(88, 7, 'If 5 workers complete a work in 12 days, how many days will 10 workers take, assuming the same efficiency?', '4 days', '5 days', '6 days', '8 days', 'C', 'medium'),
(89, 7, 'A train travels 360 km in 6 hours. What is its average speed?', '50 km/h', '55 km/h', '60 km/h', '65 km/h', 'C', 'medium'),
(90, 7, 'How much time is required to travel 240 km at a speed of 80 km/h?', '2 hours', '3 hours', '4 hours', '5 hours', 'B', 'medium'),
(91, 7, 'What is the simple interest on ₹5,000 at 8% per annum for 2 years?', '₹600', '₹700', '₹800', '₹900', 'C', 'medium'),
(92, 7, 'What is the amount obtained when ₹10,000 is invested at 10% simple interest for 3 years?', '₹12,000', '₹13,000', '₹14,000', '₹15,000', 'B', 'medium'),
(93, 7, 'What is the square root of 1764?', '36', '40', '42', '44', 'C', 'medium'),
(94, 7, 'What is the value of 15² - 10²?', '100', '125', '150', '175', 'B', 'medium'),
(95, 7, 'If x² = 144 and x is positive, what is x?', '10', '12', '14', '16', 'B', 'medium'),
(96, 7, 'What is the value of 2³ × 2⁴?', '32', '64', '128', '256', 'C', 'medium'),
(97, 7, 'Simplify: (3² + 4²).', '20', '25', '30', '35', 'B', 'medium'),
(98, 7, 'What is 0.75 expressed as a fraction in simplest form?', '1/2', '2/3', '3/4', '4/5', 'C', 'medium'),
(99, 7, 'What is the value of 3/5 + 2/5?', '1/5', '1/2', '1', '2', 'C', 'medium'),
(100, 7, 'What is 7/8 - 3/8?', '1/4', '1/2', '5/8', '3/4', 'B', 'medium'),
(101, 7, 'If 4x - 7 = 21, what is x?', '5', '6', '7', '8', 'C', 'medium'),
(102, 7, 'If x/3 = 9, what is the value of x?', '18', '21', '27', '30', 'C', 'medium'),
(103, 7, 'What is the perimeter of a rectangle with length 15 cm and breadth 8 cm?', '38 cm', '40 cm', '46 cm', '48 cm', 'C', 'medium'),
(104, 7, 'What is the area of a rectangle measuring 12 cm × 7 cm?', '72 cm²', '84 cm²', '96 cm²', '108 cm²', 'B', 'medium'),
(105, 7, 'What is the area of a square whose side is 14 cm?', '168 cm²', '182 cm²', '196 cm²', '224 cm²', 'C', 'medium'),
(106, 7, 'The circumference of a circle is approximately 44 cm. What is its radius? (Use π = 22/7)', '5 cm', '6 cm', '7 cm', '8 cm', 'C', 'medium'),
(107, 7, 'What is the area of a triangle with base 12 cm and height 10 cm?', '50 cm²', '60 cm²', '72 cm²', '120 cm²', 'B', 'medium'),
(108, 7, 'What is the volume of a cube with side 5 cm?', '25 cm³', '75 cm³', '100 cm³', '125 cm³', 'D', 'medium'),
(109, 7, 'What is the volume of a cuboid measuring 10 cm × 6 cm × 4 cm?', '180 cm³', '200 cm³', '240 cm³', '260 cm³', 'C', 'medium'),
(110, 7, 'The angles of a triangle are 50° and 60°. What is the third angle?', '60°', '70°', '80°', '90°', 'B', 'medium'),
(111, 7, 'What is the complement of an angle of 35°?', '45°', '50°', '55°', '65°', 'C', 'medium'),
(112, 7, 'What is the supplement of an angle of 120°?', '30°', '45°', '60°', '90°', 'C', 'medium'),
(113, 7, 'If two angles are vertically opposite, then they are always:', 'Complementary', 'Supplementary', 'Equal', 'Unequal', 'C', 'medium'),
(114, 7, 'What is the value of sin 30°?', '1/2', '√2/2', '√3/2', '1', 'A', 'medium'),
(115, 7, 'What is the value of cos 60°?', '0', '1/2', '√3/2', '1', 'B', 'medium'),
(116, 7, 'What is the value of tan 45°?', '0', '1/2', '1', '√3', 'C', 'medium'),
(117, 7, 'If the probability of an event is 0, the event is:', 'Certain', 'Likely', 'Impossible', 'Equally likely', 'C', 'medium'),
(118, 7, 'A die is rolled once. What is the probability of getting an even number?', '1/6', '1/3', '1/2', '2/3', 'C', 'medium'),
(119, 7, 'A coin is tossed twice. What is the probability of getting two heads?', '1/2', '1/3', '1/4', '3/4', 'C', 'medium'),
(120, 7, 'What is the median of 5, 8, 12, 15 and 20?', '8', '10', '12', '15', 'C', 'medium'),
(121, 7, 'What is the mode of 3, 5, 5, 7, 8, 5 and 9?', '3', '5', '7', '8', 'B', 'medium'),
(122, 7, 'If the mean of 5 numbers is 18, what is their total?', '72', '80', '90', '100', 'C', 'medium'),
(123, 7, 'What is the next number in the sequence 2, 6, 12, 20, 30, ?', '36', '40', '42', '44', 'C', 'medium'),
(124, 7, 'If a = 5 and b = 3, what is the value of a² + b²?', '25', '30', '34', '36', 'C', 'medium'),
(125, 7, 'Simplify: 5(2x + 3) when x = 4.', '45', '50', '55', '60', 'C', 'medium'),
(126, 7, 'If the roots of an equation are 3 and 5, what is their sum?', '2', '8', '15', '18', 'B', 'medium'),
(127, 7, 'What is the distance between the points (0,0) and (3,4)?', '3 units', '4 units', '5 units', '7 units', 'C', 'medium'),
(128, 7, 'What is the slope of the line y = 3x + 5?', '2', '3', '5', '8', 'B', 'medium'),
(129, 7, 'If the radius of a circle is doubled, its area becomes:', 'Two times', 'Three times', 'Four times', 'Eight times', 'C', 'medium'),
(130, 7, 'A number is divisible by both 6 and 8. What is the smallest possible positive number?', '12', '18', '24', '48', 'C', 'medium'),
(131, 8, 'Which organelle is known as the powerhouse of the cell?', 'Nucleus', 'Mitochondria', 'Ribosome', 'Golgi apparatus', 'B', 'medium'),
(132, 8, 'Which part of the plant cell is responsible for photosynthesis?', 'Mitochondria', 'Chloroplast', 'Nucleus', 'Vacuole', 'B', 'medium'),
(133, 8, 'Which gas is released during photosynthesis?', 'Carbon dioxide', 'Nitrogen', 'Oxygen', 'Hydrogen', 'C', 'medium'),
(134, 8, 'What is the basic structural and functional unit of life?', 'Tissue', 'Organ', 'Cell', 'Organ system', 'C', 'medium'),
(135, 8, 'Which blood cells are primarily responsible for fighting infections?', 'Red blood cells', 'White blood cells', 'Platelets', 'Plasma cells', 'B', 'medium'),
(136, 8, 'Which component of blood is mainly responsible for clotting?', 'Red blood cells', 'White blood cells', 'Platelets', 'Plasma', 'C', 'medium'),
(137, 8, 'Which organ pumps blood throughout the human body?', 'Lungs', 'Liver', 'Heart', 'Kidney', 'C', 'medium'),
(138, 8, 'Which organ is mainly responsible for filtering waste from the blood?', 'Heart', 'Kidney', 'Stomach', 'Lungs', 'B', 'medium'),
(139, 8, 'Which part of the human respiratory system is the main site of gas exchange?', 'Trachea', 'Bronchi', 'Alveoli', 'Nasal cavity', 'C', 'medium'),
(140, 8, 'Which enzyme begins the digestion of starch in the mouth?', 'Pepsin', 'Amylase', 'Trypsin', 'Lipase', 'B', 'medium'),
(141, 8, 'Which vitamin is mainly produced in the skin in the presence of sunlight?', 'Vitamin A', 'Vitamin B12', 'Vitamin C', 'Vitamin D', 'D', 'medium'),
(142, 8, 'Which mineral is essential for the formation of haemoglobin?', 'Calcium', 'Iron', 'Sodium', 'Potassium', 'B', 'medium'),
(143, 8, 'Which hormone regulates blood glucose levels?', 'Insulin', 'Adrenaline', 'Thyroxine', 'Estrogen', 'A', 'medium'),
(144, 8, 'Which part of the brain controls balance and coordination?', 'Cerebrum', 'Cerebellum', 'Medulla', 'Hypothalamus', 'B', 'medium'),
(145, 8, 'Which system controls and coordinates most activities of the human body?', 'Digestive system', 'Nervous system', 'Respiratory system', 'Skeletal system', 'B', 'medium'),
(146, 8, 'Which type of reproduction involves only one parent?', 'Sexual reproduction', 'Asexual reproduction', 'Cross reproduction', 'External reproduction', 'B', 'medium'),
(147, 8, 'What is the process by which green plants prepare food using sunlight?', 'Respiration', 'Transpiration', 'Photosynthesis', 'Digestion', 'C', 'medium'),
(148, 8, 'Which structure controls the movement of substances into and out of a cell?', 'Cell wall', 'Cell membrane', 'Nucleus', 'Cytoplasm', 'B', 'medium'),
(149, 8, 'Which tissue transports water and minerals from roots to other parts of a plant?', 'Phloem', 'Xylem', 'Epidermis', 'Cambium', 'B', 'medium'),
(150, 8, 'Which tissue transports food from leaves to other parts of a plant?', 'Xylem', 'Phloem', 'Parenchyma', 'Collenchyma', 'B', 'medium'),
(151, 8, 'What is the SI unit of force?', 'Joule', 'Newton', 'Watt', 'Pascal', 'B', 'medium'),
(152, 8, 'What is the SI unit of work?', 'Newton', 'Joule', 'Watt', 'Pascal', 'B', 'medium'),
(153, 8, 'What is the SI unit of power?', 'Joule', 'Newton', 'Watt', 'Volt', 'C', 'medium'),
(154, 8, 'Which law states that every action has an equal and opposite reaction?', 'Newton’s First Law', 'Newton’s Second Law', 'Newton’s Third Law', 'Law of Gravitation', 'C', 'medium'),
(155, 8, 'What happens to the acceleration of an object if the applied force increases while mass remains constant?', 'It decreases', 'It increases', 'It becomes zero', 'It remains unchanged', 'B', 'medium'),
(156, 8, 'Which form of energy is possessed by an object due to its motion?', 'Potential energy', 'Kinetic energy', 'Chemical energy', 'Nuclear energy', 'B', 'medium'),
(157, 8, 'Which form of energy is stored in a stretched rubber band?', 'Kinetic energy', 'Potential energy', 'Sound energy', 'Light energy', 'B', 'medium'),
(158, 8, 'Which device converts electrical energy into mechanical energy?', 'Electric motor', 'Generator', 'Transformer', 'Battery', 'A', 'medium'),
(159, 8, 'Which device converts mechanical energy into electrical energy?', 'Motor', 'Generator', 'Battery', 'Resistor', 'B', 'medium'),
(160, 8, 'What is the approximate acceleration due to gravity on Earth?', '5.8 m/s²', '8.9 m/s²', '9.8 m/s²', '12.5 m/s²', 'C', 'medium'),
(161, 8, 'Which material is generally a good conductor of electricity?', 'Rubber', 'Glass', 'Copper', 'Plastic', 'C', 'medium'),
(162, 8, 'Which instrument is used to measure electric current?', 'Voltmeter', 'Ammeter', 'Barometer', 'Thermometer', 'B', 'medium'),
(163, 8, 'Which instrument is used to measure potential difference?', 'Ammeter', 'Voltmeter', 'Galvanometer', 'Barometer', 'B', 'medium'),
(164, 8, 'What is the unit of electrical resistance?', 'Volt', 'Ampere', 'Ohm', 'Watt', 'C', 'medium'),
(165, 8, 'Which phenomenon explains the bending of light when it passes from one medium to another?', 'Reflection', 'Refraction', 'Diffraction', 'Dispersion', 'B', 'medium'),
(166, 8, 'Which mirror is commonly used as a rear-view mirror in vehicles?', 'Plane mirror', 'Concave mirror', 'Convex mirror', 'Cylindrical mirror', 'C', 'medium'),
(167, 8, 'Which lens is used to correct myopia?', 'Convex lens', 'Concave lens', 'Bifocal lens', 'Cylindrical lens', 'B', 'medium'),
(168, 8, 'Which lens is generally used to correct hypermetropia?', 'Concave lens', 'Convex lens', 'Plane lens', 'Cylindrical lens', 'B', 'medium'),
(169, 8, 'What is the chemical formula of water?', 'CO₂', 'H₂O', 'O₂', 'H₂O₂', 'B', 'medium'),
(170, 8, 'What is the pH of a neutral solution at room temperature?', '0', '5', '7', '14', 'C', 'medium'),
(171, 8, 'Which gas is mainly responsible for the acidic nature of rain when dissolved in water?', 'Oxygen', 'Sulfur dioxide', 'Hydrogen', 'Nitrogen', 'B', 'medium'),
(172, 8, 'Which substance is commonly used to neutralize an acidic soil?', 'Vinegar', 'Quicklime', 'Common salt', 'Sugar', 'B', 'medium'),
(173, 8, 'Which of the following is a chemical change?', 'Melting of ice', 'Boiling of water', 'Rusting of iron', 'Breaking of glass', 'C', 'medium'),
(174, 8, 'Which gas is produced when an acid reacts with a metal such as zinc?', 'Oxygen', 'Carbon dioxide', 'Hydrogen', 'Nitrogen', 'C', 'medium'),
(175, 8, 'Which element has the chemical symbol Na?', 'Nitrogen', 'Sodium', 'Neon', 'Nickel', 'B', 'medium'),
(176, 8, 'Which element has the chemical symbol Fe?', 'Fluorine', 'Iron', 'Francium', 'Fermium', 'B', 'medium'),
(177, 8, 'Which layer of the Earth is directly beneath the crust?', 'Inner core', 'Outer core', 'Mantle', 'Atmosphere', 'C', 'medium'),
(178, 8, 'Which instrument is used to measure atmospheric pressure?', 'Thermometer', 'Barometer', 'Hygrometer', 'Ammeter', 'B', 'medium'),
(179, 8, 'Which gas is present in the largest proportion in Earth’s atmosphere?', 'Oxygen', 'Carbon dioxide', 'Nitrogen', 'Hydrogen', 'C', 'medium'),
(180, 8, 'Which process converts water vapour into liquid water?', 'Evaporation', 'Condensation', 'Sublimation', 'Freezing', 'B', 'medium'),
(181, 8, 'Which renewable source of energy uses moving air to generate electricity?', 'Solar energy', 'Wind energy', 'Geothermal energy', 'Biomass energy', 'B', 'medium');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `score` decimal(10,2) NOT NULL,
  `total_marks` int(11) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `status` enum('pass','fail') NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `time_taken` int(11) NOT NULL DEFAULT 0 COMMENT 'Time taken in seconds'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `student_id`, `exam_id`, `score`, `total_marks`, `percentage`, `status`, `completed_at`, `time_taken`) VALUES
(1, 29, 6, 2.50, 5, 50.00, 'pass', '2026-09-26 10:41:56', 56),
(2, 30, 6, 2.50, 5, 50.00, 'pass', '2026-09-26 10:46:34', 87);

-- --------------------------------------------------------

--
-- Table structure for table `student_answers`
--

CREATE TABLE `student_answers` (
  `id` int(11) NOT NULL,
  `result_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `student_answer` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_answers`
--

INSERT INTO `student_answers` (`id`, `result_id`, `question_id`, `student_answer`) VALUES
(1, 1, 67, 'B'),
(2, 1, 28, 'A'),
(3, 1, 44, 'D'),
(4, 1, 48, 'B'),
(5, 1, 38, 'B'),
(6, 2, 33, 'A'),
(7, 2, 62, 'B'),
(8, 2, 72, 'A'),
(9, 2, 32, 'B'),
(10, 2, 75, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `assigned_teacher_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `assigned_teacher_id`, `created_at`) VALUES
(6, 'Information Technology', 28, '2026-09-26 10:17:48'),
(7, 'Mathematics', 31, '2026-09-26 10:52:16'),
(8, 'Science', 32, '2026-09-26 10:59:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `school_name` varchar(255) DEFAULT NULL,
  `academic_details` varchar(255) DEFAULT NULL,
  `teaching_subject` varchar(255) DEFAULT NULL,
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `school_name`, `academic_details`, `teaching_subject`, `otp_code`, `otp_expires_at`, `created_at`) VALUES
(27, 'Admin User', 'admin@example.com', '$2y$10$7FZK8U6yBVg2TzrV8Rl8G.vR7uki6hdvrWONDQVm12BCTQmEQitOa', 'admin', 'active', NULL, NULL, NULL, NULL, NULL, '2026-09-13 18:50:29'),
(28, 'Bishal Paul', 'bishal.teacher@exampro.com', '$2y$10$ETZ6IuvaK0r8nlqYqF6SXOuWJcUVw2w.W0Mhobu246XWpJ4xWi8WO', 'teacher', 'active', 'ExamPro', NULL, 'Information Technology', NULL, NULL, '2026-09-26 08:20:10'),
(29, 'Bishal Paul', 'bishal@gmail.com', '$2y$10$e5PAwmznNk550yhs.dGjQOxcfPyZbzECSiu4aWnkqbMy6VXMRbZym', 'student', 'active', 'IGNOU', 'MCA 6th sem', NULL, NULL, NULL, '2026-09-26 10:12:19'),
(30, 'Manish Pal', 'manish@gmail.com', '$2y$10$YIvrJgSQ23.bpvueDVAIq.QAAGY/9qJY2XkRWqkpxSjio0GQMMCn2', 'student', 'active', 'MUJ', 'BCA 5th sem', NULL, NULL, NULL, '2026-09-26 10:44:49'),
(31, 'Sudip Deb', 'sudip.teacher@exampro.com', '$2y$10$4TmSqwOAATepKLLDGhIc7.ZM0Axwqit8Nn.LVVNEAFIA1p.cSDrqW', 'teacher', 'active', 'DMSMHSS', NULL, 'Mathematics', NULL, NULL, '2026-09-26 10:50:23'),
(32, 'Sudipta Paul', 'sudipta.teacher@exampro.com', '$2y$10$JRGHFasRtc46rO016SjG0.If0svJB2hCquyUgg0eYLVdwBWbYn3LW', 'teacher', 'active', 'PHSS', NULL, 'Science', NULL, NULL, '2026-09-26 10:58:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_id` (`result_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_teacher_id` (`assigned_teacher_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exams_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`result_id`) REFERENCES `results` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`assigned_teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
