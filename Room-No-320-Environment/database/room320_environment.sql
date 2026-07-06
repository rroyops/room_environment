-- Room No. 320 Environment Database SQL Dump
-- Database: `room320_environment`
-- Created on: 2026-07-06

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `room320_environment`
--
CREATE DATABASE IF NOT EXISTS `room320_environment` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `room320_environment`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('user', 'admin') NOT NULL DEFAULT 'user',
  `fullname` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'default_avatar.png',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

-- Default admin: username 'admin', email 'admin@room320.com', password 'admin123'
-- Default user: username 'john', email 'john@room320.com', password 'password123'
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `fullname`, `bio`, `avatar`, `created_at`) VALUES
(1, 'admin', 'admin@room320.com', '$2y$10$w859.uU0pP1M9DCHXW/Xieq8oXU8eZ9rAunxbe3/v4eCofF.D/Bia', 'admin', 'Administrator (Room 320)', 'Head Coordinator of Room No. 320 Environment and Lead Developer.', 'admin_avatar.png', CURRENT_TIMESTAMP),
(2, 'john', 'john@room320.com', '$2y$10$T8Zf3eEbyf7qW9RAtiJd8OidvA7Z0w.M.Y.63iUqfO7S0yB4z8E5m', 'user', 'John Doe', 'Active resident member and environment research enthusiast.', 'default_avatar.png', CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `bio` text NOT NULL,
  `photo` varchar(255) DEFAULT 'default_member.png',
  `joined_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `role`, `email`, `phone`, `bio`, `photo`, `joined_date`, `created_at`) VALUES
(1, 'Dr. Sarah Jenkins', 'Chief Advisor / Professor', 'sarah.jenkins@room320.com', '+1234567890', 'Specializes in Environmental Science and Urban Planning with 15+ years of academic research.', 'member_sarah.png', '2024-01-15', CURRENT_TIMESTAMP),
(2, 'Alex Rivera', 'Project Manager', 'alex.rivera@room320.com', '+1234567891', 'Lead coordinator of green initiatives, carbon auditing, and sustainable campus integration projects.', 'member_alex.png', '2024-03-10', CURRENT_TIMESTAMP),
(3, 'Emily Watson', 'Lead Eco-Researcher', 'emily.watson@room320.com', '+1234567892', 'Focuses on Indoor Air Quality (IAQ) and sensory-stimulating microclimates inside university environments.', 'member_emily.png', '2024-05-01', CURRENT_TIMESTAMP),
(4, 'Tariq Al-Mansoor', 'IoT Systems Architect', 'tariq.al@room320.com', '+1234567893', 'Designs sensor arrays for automated tracking of humidity, carbon dioxide, and temperature within Room 320.', 'member_tariq.png', '2024-06-15', CURRENT_TIMESTAMP),
(5, 'Jessica Lin', 'Community Outreach Lead', 'jessica.lin@room320.com', '+1234567894', 'Fosters collaboration between environmental bodies and university campuses through interactive workshops.', 'member_jessica.png', '2024-09-20', CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `uploaded_by` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'General',
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `description`, `image_path`, `uploaded_by`, `category`, `is_approved`, `created_at`) VALUES
(1, 'Eco-Lab Setup', 'Our primary testing lab inside Room 320 with air-monitoring configurations.', 'eco_lab.png', 'Admin', 'Research', 1, CURRENT_TIMESTAMP),
(2, 'Indoor Vertical Forest', 'A high-efficiency visual layout of indoor bio-walls for active oxygenation.', 'vertical_forest.png', 'Admin', 'Initiatives', 1, CURRENT_TIMESTAMP),
(3, 'Sensor Deployment Site', 'Tariq deploying the new IoT humidity multi-sensor grid on the northern balcony.', 'sensor_deployment.png', 'Tariq Al-Mansoor', 'Research', 1, CURRENT_TIMESTAMP),
(4, 'Student Awareness Seminar', 'A packed room during the sustainable space design workshop hosted by Jessica.', 'seminar.png', 'Jessica Lin', 'Events', 1, CURRENT_TIMESTAMP),
(5, 'Smart Irrigation System', 'A custom drip irrigation module running automatically based on soil health sensors.', 'smart_irrigation.png', 'Admin', 'Initiatives', 1, CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `activity_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `title`, `description`, `image_path`, `activity_date`, `created_at`) VALUES
(1, 'Carbon Ingress Audits', 'A rigorous diagnostic review measuring internal university spaces against international ecological benchmarks.', 'activity_carbon.png', '2026-05-12', CURRENT_TIMESTAMP),
(2, 'Air Filtration Hackathon', 'An intense multi-disciplinary coding and hardware hackathon to build self-cleaning ventilation concepts.', 'activity_hackathon.png', '2026-06-04', CURRENT_TIMESTAMP),
(3, 'Community Tree Planting Drive', 'Planting over 200 air-purifying saplings around the research annex building to establish microclimates.', 'activity_tree_planting.png', '2026-06-25', CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `created_at`) VALUES
(1, 'New Air Sensor API Release', 'The Room No. 320 IoT sensor stream is now accessible in beta format for researchers. Please generate an API token inside your custom user dashboard or request access via the chief systems architect.', CURRENT_TIMESTAMP),
(2, 'Annual Eco Summit 2026 Rescheduled', 'Please note that our upcoming Summit has been rescheduled to October 12, 2026, due to logistical enhancements. Registration is free and remains open for all verified university students.', CURRENT_TIMESTAMP),
(3, 'Volunteers Needed: Balcony Greens', 'We are looking for enthusiastic hands to help maintain the balcony garden beds and automated solar-drip feeds. 2-hour shifts are available on Wednesdays and Fridays.', CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `is_read`) VALUES
(1, 'Robert Foster', 'robert.f@example.com', 'Collaboration Proposal', 'Hello, I represent the GreenTech Initiative and we are highly interested in integrating our carbon tracker modules with the sensor arrays in Room 320. Can we schedule a virtual call?', CURRENT_TIMESTAMP, 0),
(2, 'Amara Patel', 'amara.p@example.com', 'Student Internships', 'Are there any available student internship positions for undergraduate developers focusing on environmental IoT dashboarding? Thank you!', CURRENT_TIMESTAMP, 1);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
