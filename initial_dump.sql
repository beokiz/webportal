-- MySQL dump 10.13  Distrib 8.0.21, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: beokiz
-- ------------------------------------------------------
-- Server version	8.0.21

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actions`
--

LOCK TABLES `actions` WRITE;
/*!40000 ALTER TABLE `actions` DISABLE KEYS */;
INSERT INTO `actions` VALUES (1,'2023_04_01_020000_add_permissions',1),(2,'2023_04_01_030000_add_roles',1),(3,'2023_04_01_090508_add_users',1),(4,'2023_09_27_125800_add_domains',1),(5,'2023_09_27_125800_add_subdomains',1),(6,'2023_09_27_203000_add_milestones',1),(7,'2023_10_11_204200_add_kitas',1);
/*!40000 ALTER TABLE `actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domains`
--

DROP TABLE IF EXISTS `domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `domains` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `abbreviation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `daz_dependent` tinyint(1) NOT NULL DEFAULT '0',
  `age_2_red_threshold` smallint unsigned NOT NULL DEFAULT '0',
  `age_2_red_threshold_daz` smallint unsigned NOT NULL DEFAULT '0',
  `age_2_yellow_threshold` smallint unsigned NOT NULL DEFAULT '0',
  `age_2_yellow_threshold_daz` smallint unsigned NOT NULL DEFAULT '0',
  `age_4_red_threshold` smallint unsigned NOT NULL DEFAULT '0',
  `age_4_red_threshold_daz` smallint unsigned NOT NULL DEFAULT '0',
  `age_4_yellow_threshold` smallint unsigned NOT NULL DEFAULT '0',
  `age_4_yellow_threshold_daz` smallint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domains`
--

LOCK TABLES `domains` WRITE;
/*!40000 ALTER TABLE `domains` DISABLE KEYS */;
INSERT INTO `domains` VALUES (1,'GM','Grobmotorik',1,0,10,10,20,20,15,15,30,30,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(2,'FM','Feinmotorik',2,0,15,15,25,25,20,20,35,35,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(3,'KG','Kognitive Grundfunktion',3,0,10,10,20,20,15,15,30,30,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(4,'D','Denken',4,0,10,10,20,20,15,15,30,30,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(5,'HF','Höhere kognitive Funktionen',5,0,10,10,20,20,15,15,30,30,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(6,'SV','Schulische Vorläuferfähigkeiten',6,0,10,10,20,20,15,15,30,30,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(7,'S1','Sprache 1',7,0,40,40,60,60,12,12,40,40,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(8,'S2','Sprache 2',8,0,10,10,20,20,15,15,30,30,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(9,'S3','Sprache 3',9,0,10,10,20,20,15,15,30,30,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(10,'SB','Soziale Beziehungen',10,0,10,10,20,20,15,15,30,30,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(11,'SE','Soziale Entwicklung',11,0,10,10,20,20,15,15,30,30,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(12,'SR','Selbstregulation',12,0,10,10,20,20,15,15,30,30,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(13,'G','Gefühle',13,0,10,10,20,20,15,15,30,30,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL);
/*!40000 ALTER TABLE `domains` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evaluations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `kita_id` bigint unsigned DEFAULT NULL,
  `age` enum('2.5','4.5') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_daz` tinyint(1) NOT NULL DEFAULT '0',
  `data` json DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluations_user_id_foreign` (`user_id`),
  KEY `evaluations_kita_id_foreign` (`kita_id`),
  CONSTRAINT `evaluations_kita_id_foreign` FOREIGN KEY (`kita_id`) REFERENCES `kitas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `evaluations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluations`
--

LOCK TABLES `evaluations` WRITE;
/*!40000 ALTER TABLE `evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `evaluations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kita_has_users`
--

DROP TABLE IF EXISTS `kita_has_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kita_has_users` (
  `kita_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`kita_id`,`user_id`),
  KEY `kita_has_users_user_id_foreign` (`user_id`),
  CONSTRAINT `kita_has_users_kita_id_foreign` FOREIGN KEY (`kita_id`) REFERENCES `kitas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `kita_has_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kita_has_users`
--

LOCK TABLES `kita_has_users` WRITE;
/*!40000 ALTER TABLE `kita_has_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `kita_has_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kitas`
--

DROP TABLE IF EXISTS `kitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kitas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kitas`
--

LOCK TABLES `kitas` WRITE;
/*!40000 ALTER TABLE `kitas` DISABLE KEYS */;
/*!40000 ALTER TABLE `kitas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0000_00_00_000000_create_websockets_statistics_entries_table',1),(2,'2014_01_17_104421_create_jobs_table',1),(3,'2014_01_17_104649_create_job_batches_table',1),(4,'2014_01_17_104829_create_failed_jobs_table',1),(5,'2014_01_18_083944_create_webhook_calls_table',1),(6,'2014_10_12_000000_create_users_table',1),(7,'2014_10_12_100000_create_password_resets_table',1),(8,'2019_12_14_000001_create_personal_access_tokens_table',1),(9,'2022_08_18_180137_change_migration_actions_table',1),(10,'2023_01_21_172923_rename_migrations_actions_table_to_actions',1),(11,'2023_04_01_090833_create_permission_tables',1),(12,'2023_09_27_094200_create_domains_table',1),(13,'2023_09_27_125400_create_subdomains_table',1),(14,'2023_09_27_195100_create_milestones_table',1),(15,'2023_10_10_094400_update_domains_table',1),(16,'2023_10_11_192500_create_kitas_table',1),(17,'2023_10_11_202100_create_kita_has_users_table',1),(18,'2023_10_25_111500_create_evaluations_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `milestones`
--

DROP TABLE IF EXISTS `milestones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `milestones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subdomain_id` bigint unsigned NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `emphasis` double NOT NULL,
  `emphasis_daz` double NOT NULL,
  `age` enum('2.5','4.5') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `milestones_subdomain_id_foreign` (`subdomain_id`),
  CONSTRAINT `milestones_subdomain_id_foreign` FOREIGN KEY (`subdomain_id`) REFERENCES `subdomains` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `milestones`
--

LOCK TABLES `milestones` WRITE;
/*!40000 ALTER TABLE `milestones` DISABLE KEYS */;
INSERT INTO `milestones` VALUES (1,1,'GM.12','Rückwärtsschritte machen','Kind steht frei im Raum und bewegt sich mit mind. 3 Schritten hintereinander rückwärts, ohne sich irgendwo festzuhalten oder abzustützen.',1,1,1,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(2,1,'GM.13','Treppensteigen','Kind geht in aufrechter Position und ohne fremde Hilfe (aber möglicherweise mit Abstützen an der Wand oder am Geländer) mind. 3 Treppenstufen aufwärts und 3 Treppenstufen abwärts. (Eine Richtung genügt nicht.)',2,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(3,1,'GM.15','Frei auf einem Bein stehen','Kind hebt im freien Stand (ohne sich irgendwo festzuhalten) ein Bein vom Boden und hält in dieser Position mind. 3 Sekunden die Balance, ohne den zweiten Fuß wieder abstellen zu müssen.',3,3,3,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(4,1,'GM.16','Ohne Festhalten auf der Stelle hüpfen','Kind hüpft mit beiden Beinen gleichzeitig hoch, so dass die Füße nicht mehr den Boden berühren, und landet wieder sicher im Stand.',4,1,1,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(5,2,'GM.20','Alleine von einer Stufe / einem Absatz springen und sicher landen','Kind springt mit beiden Beinen gleichzeitig hoch, so dass die Füße nicht mehr den Boden berühren, überwindet im Sprung einen (kleinen) Absatz oder eine Stufe und landet wieder sicher im Stand.',5,1,1,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(6,3,'GM.21','Werfen eines Gegenstandes','Kind hält ein Objekt über der Schulter (Nicht vor der Brust! Nicht neben dem Körper), streckt den Arm ganz nach vorne aus, um das Objekt gezielt und mit Schwung in eine bestimmte Richtung zu werfen. Das Objekt landet mind. einen halben Meter entfernt.',6,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(7,3,'GM.22','Ball mit den Armen fangen','Kind fängt einen Ball mittlerer Größe, der ihm aus kurzer Entfernung (mind. 1 m) zugeworfen wird, mit beiden Armen auf, ohne dass er auf den Boden fällt.',7,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(8,4,'GM.29','Ohne fremde Hilfe auf einer Schaukel Schwung holen','Kind holt auf der Schaukel sitzend selbstständig Schwung und schaukelt sich nach oben.',17,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(9,5,'GM.37','Von der Höhe einer 2. Treppenstufe springen','Kind springt von der Höhe einer 2. Treppenstufe oder einer anderen Ebene ähnlicher Höhe und landet sicher, ohne das Gleichgewicht zu verlieren (kein Ausfallschritt, kein Abfangen mit der Hand).',18,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(10,5,'GM.39','Vorwärtssprung mit beiden Beinen','Kind springt mit beiden Beinen gleichzeitig vorwärts und landet auch wieder auf beiden Beinen, ohne das Gleichgewicht zu verlieren (kein Ausfallschritt, kein Abfangen mit der Hand).',19,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(11,6,'GM.40','Sprung mit Gegenbewegung von Armen und Beinen','Kind zeigt mind. zweimal eine Sprungfigur, bei der sich Arme und Beine jeweils in entgegengesetzte Richtung bewegen (z.B. Hampelmann-Sprung).',20,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(12,7,'GM.44','Plötzliches Losrennen','Kind rennt nach Start-Signal sofort los und beschleunigt schnell.',21,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(13,7,'GM.45','Plötzlicher Bewegungsstop','Kind stoppt sich nach Stopp-Signal sofort aus dem Laufen ab und kommt zum Stehen.',22,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(14,8,'FM.10','Mindestens drei Gegenstände stapeln','Kind legt 3 Bauklötze oder andere Gegenstände gezielt aufeinander, so dass ein Turm entsteht, ohne dass etwas herunterfällt.',8,4,4,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(15,9,'FM.11','Alleine aus einem offenen Trinkgefäß trinken','Kind hält ein mind. zur Hälfte gefülltes Trinkgefäß ohne Deckel, führt es zum Mund und trinkt daraus, ohne dass etwas daneben geht. Es zeigt dieses Verhalten mind. 3x.',9,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(16,9,'FM.12','Mit dem Löffel essen','Kind hält einen mit halbfester Nahrung (z.B. Reis, Pudding, Kartoffelbrei...) beladenen Löffel gerade, führt ihn zum Mund führen, schiebt ihn in den Mund und nimmt die Nahrung mit dem Mund auf, ohne dass etwas daneben geht. Es zeigt dieses Verhalten mind. 3x.',10,1,1,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(17,10,'FM.18','Mit Stift kritzeln','Kind hält einen Stift stabil und zeichnet/malt damit Striche oder Punkte.',24,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(18,10,'FM.19','Gezielt Linien und Formen zeichnen','Kind hält einen Stift in richtiger Haltung (nicht in der Faust) und zeichnet damit gezielt mind. 3 unterschiedliche Linien oder Formen (z.B. senkrechte oder waagerechte mind. 2.5 cm lange Linien, Kringel, Kreise).',25,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(19,11,'FM.17','Getränk ohne fremde Hilfe einschenken','Kind füllt selbstständig Flüssigkeit aus einer Flasche oder einer Kanne in ein Glas, ohne dass etwas daneben geht.',23,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(20,12,'FM.20','Mit Stift im Dreipunktgriff zeichnen','Kind hält Stift im Dreipunktgriff (zwischen Daumen-, Zeige- und Mittelfinger) und zeichnet damit.',26,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(21,13,'FM.24','Kleidungsstücke selbst ausziehen','Kind zieht Hemd, T-Shirt, Pulli, Jacke oder Hose alleine aus, wenn dafür keine Verschlüsse geöffnet werden müssen. Die Mütze vom Kopf ziehen oder eine Socke oder einen Hand-schuh ausziehen, genügt nicht.',27,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(22,13,'FM.25','Kleidungsstücke selbst anziehen','Kind zieht Hemd, T-Shirt, Pulli, Jacke oder Hose alleine an. Dabei dürfen vorne und hinten oder rechts und links ver-tauscht sein, aber alle Körperteile müssen in passenden Öff-nungen stecken. Knöpfe und Reißverschlüsse müssen nicht zu sein.',28,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(23,13,'FM.26','Reißverschluss der eigenen Jacke auf/ zu ziehen','Kind zieht Reißverschlüsse an Jacken alleine auf- und zu (eine Richtung genügt nicht), wenn es dafür beide Hände braucht: eine zum Ziehen am Zipper und eine zum Gegenziehen am Stoff. (Beim Einfädeln darf geholfen werden. Reißverschlüsse sollen grob sein).',29,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(24,13,'FM.31','Langärmeliges Oberteil ohne Hilfe an- oder ausziehen','Kind zieht ein langärmeliges Oberteil selbständig über Kopf an oder aus.',30,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(25,14,'FM.35','Runde Formen ausschneiden','Kind schneidet selbstständig eine vorgezeichnete runde Form mit der Schere aus und weicht kaum von der Linie ab.',31,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(26,14,'FM.36','Komplexe eckige Formen ausschneiden','Kind schneidet selbstständig eine komplexe Form (z.B. Tannenbaum, Stern) mit Schere so aus, dass die Form eindeutig erkennbar ist.',32,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(27,14,'FM.38','Eine Kugel formen','Kind formt (z.B. aus Knete, Teig oder Ton) mit beiden Händen eine Kugel.',33,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(28,15,'FM.43','Einfachen Knoten machen','Kind macht alleine einen einfachen Knoten (z.B. beim Ge-schenk packen, Schuhe binden).',34,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(29,16,'FM.48','Schraubverschluss öffnen und schließen','Kind öffnet und schließt selbständig ein Gefäß mit schmalem Durchmesser und Schraubdeckel.',35,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(30,17,'FM.49','Puzzleteile zusammenfügen','Kind verbindet ohne fremde Hilfe mind. 3 große Puzzle-Teile nahtlos miteinander.',36,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(31,18,'KG.03','Aufmerksam betrachten','Kind betrachtet konzentriert ca. 1 Minute oder länger einen unbewegten Gegenstand (z.B. ein Bild, eine Pflanze).',11,3,3,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(32,18,'KG.04','Gezielte visuelle Suche','Kind sucht ca. 1 Minute oder länger ganz gezielt bestimmte Objekte unter vielen anderen (z.B. auf Wimmelbildern, oder in einer Spielkiste).',12,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(33,19,'KG.08','Aufmerksamkeitsdauer beim Zuhören','Kind lauscht mind. 3 Minuten ruhig und aufmerksam einem Musikstück, einer Geschichte oder einem Gespräch (in einer Sprache, die dem Kind vertraut ist).',13,4,4,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(34,19,'KG.09','Gezieltes Hören','Kind hört gezielt aus einem Lied oder aus einem Gespräch ein bestimmtes Wort heraus. Das Wort sollte kein Personen-Name sein.',14,1,1,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(35,20,'KG.11','Konzentration bei selbstständiger Aktivität','Kind beschäftigt sich selbstständig mind. 10 Minuten mit dem gleichen Material und verfolgt dabei ein Ziel, ohne sich ablenken zu lassen (Handy-, Tablet-, oder Computerspiele ausgeschlossen).',15,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(36,21,'KG.14','Gezielter Wechsel beim Kategorisieren','Kind sortiert Objekte gleicher Art (z.B. Legosteine, Perlen, Bauklötze) auf Bitte hin erst nach Form und anschließend nach der Farbe, ohne sich zu vertun.',37,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(37,22,'KG.19','Memory spielen','Kind findet bei Memory-Spielen (28 Teile) zielsicher 2 gleiche Teile unter mind. 10 verbleibenden, wenn diese vorher bereits einmal oder mehrmals aufgedeckt wurden.',38,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(38,23,'KG.23','Akustisches Kurzzeitgedächtnis II','Das Kind kann ein vier-silbiges Fantasiewörter (z.B. ri-so-la-mi), oder eine zufällige Zahlenreihe (z.B. 9-3-7-1) nach einmaligem Hören richtig nachsprechen.',39,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(39,23,'KG.25','Akustisches Langzeitgedächtnis II','Kind gibt Teile von bekannten Liedern, Reimen, Sprüchen oder Sprachspielen von sich aus (ohne Erinnerungshilfe) wie-der.',40,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(40,24,'D.06','Gezielte Verkettung von Teilhandlungen','Kind führt geplant 2 unabhängige Teilhandlungen nachein-ander aus, um ein Ziel zu erreichen (z.B. Becher holen und Getränk einschenken, um zu trinken). Wichtig ist das Erkennen eines Handlungsplans aus mindestens 2 abgrenzbaren Teil-schritten.',43,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(41,24,'D.07','Verwendung von Gegenständen als Hilfsmitteln','Kind hat kein geeignetes Werkzeug, um ein bestimmtes Ziel zu erreichen (z.B. keine Schaufel, um Sand zu graben) und verwendet dann spontan (ohne Hinweis von anderen) ein Hilfsmittel, das sonst nicht in dieser Funktion verwendet wird (z.B. Löffel).',44,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(42,24,'D.08','Verschiedene Lösungswege ausprobieren','´Kind steht vor einem Problem, für das es die Lösung nicht gleich findet. Auch wenn es zunächst scheitert, denkt es er-kennbar über die Lösung nach und probiert mind. 2 Möglichkeiten aus, um sein Ziel doch noch zu erreichen (Frage nach Lösungsweg zählt nicht).',45,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(43,25,'D.03','Als-ob-Spiele','Kind weist Gegenständen (nicht Personen!) im Spiel eine Bedeutung zu, die nicht zu ihrem Aussehen und/oder zu ihrer normalen Funktion passen (Zweckentfremdung). Es spielt Als-ob-Spiele (z.B. Besen als Pferd, Kiste als Boot).',41,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(44,26,'D.05','Beginnendes Sortierverhalten','Kind legt Gegenstände verschiedener Art (z.B. Murmeln und Bauklötze) in getrennte Behälter. Die Behälter sind bereits mit Objekten der jeweiligen Kategorie befüllt. Es werden mind. 2 neue Objekte jeder Art vom Kind richtig einsortiert.',42,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(45,27,'KF.09','Fantasie-Figuren oder -Objekte erfinden','Kind erfindet eine eigene Fantasie-Figur / ein Fantasie-Objekt und stellt sie/ es genauer dar (verbal oder auf andere Weise).',48,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(46,27,'KF.11','Sinnvolle Begründungen geben','Das Kind formuliert eigene, in sich plausible Erklärungen für eine Beobachtung.',49,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(47,28,'KF.01','Planvoll und strukturiert handeln','Kind führt spontan mind. 3 Handlungsschritte durch, die alle zusammenhängen und auf ein übergeordnetes Ziel hinführen (z.B. erst Einzelteile basteln, dann alles zusammenfügen).',46,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(48,29,'KF.04','Gezielt Experimentieren','Beim Experimentieren verändert das Kind von sich aus ganz bewusst einzelne Aspekte einer Situation, um herauszufin-den, welche Auswirkungen das hat (z.B. was passiert, wenn man etwas dazu tut, wegnimmt, verschiebt, erwärmt).',47,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(49,30,'SV.03','Ein geschriebenes Wort erkennen','Kind erkennt seinen eigenen Namen oder ein anderes Wort mit mind. 3 Buchstaben am Schriftbild wieder.',50,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(50,31,'SV.17','Menge von drei Elementen einzeln abzählen','Kind zählt eine Menge von 3 Gegenständen ab (z.B. drei ei-gene Finger oder 3 Bonbons). Es zeigt auf jedes Objekt einmal und nennt dabei die passende Zahl.',51,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(51,32,'SV.20','Gefäße ineinander stapeln','Kind stapelt mind. 3 unterschiedlich große Gefäße ineinan-der (z.B. Plastiktöpfchen, Becher).',52,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(52,33,'S1.08','Mindestens 50 unterschiedliche Wörter aktiv verwenden','Kind hat einen aktiven Wortschatz von mind. 50 verschiedenen Wörtern. Die gleiche Lautfolge wird in verschiedenen Situationen zur Beschreibung des gleichen Sachverhaltes verwendet. Machen Sie vor der Bewertung eine Liste der gesprochenen Worte (ein passendes Formular finden Sie auf Seite X im BeoTool (kulturspezifisch abfragen \"arabisch für Hund).',16,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(53,34,'S2.01','Verwendung von Finalwörtern','Kind benutzt Worte wie „auf“, „zu“, „ab“, „aus“ oder „weg“, die das Ende eines Vorgangs beschreiben. Das gleiche Wort wird in verschiedenen Situationen wiederholt passend verwendet.',53,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(54,34,'S2.02','Verwendung von Mehrzahlwörtern','Kind benennt Objekte in Mehrzahl und verändert dafür das Wortende (z.B. „Autos“ statt „Auto“). Wenn es dabei zu lusti-gen Formbildungen (z.B. „Ananässe“, „Mädchens“) kommt, gilt das auch.',54,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(55,34,'S2.03','Verwendung von Farbwörtern','Kind benennt die Farbe eines Gegenstandes in verschiedenen Situationen richtig.',55,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(56,34,'S2.04','Verwendung der Wörter „ich“ und „du“','Kind spricht von sich selbst als „ich“ und benennt sein Gegenüber als „du“. Dieses Verhalten wird in unterschiedlichen Situationen gezeigt.',56,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(57,35,'S2.05','Einfache Sätze verstehen','Kind zeigt durch sein Verhalten, dass es einen Satz versteht, obwohl sich die Bedeutung nicht aus der Situation erschließen lässt und keine Gesten (z.B. Zeigen) zur Verständigung eingesetzt werden.',57,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(58,35,'S2.06','Zweiwortsätze bilden','Kind bezieht 2 verschiedene Wörter sinnvoll aufeinander (z.B. „Puppe weg“, „Mama laufen“, „Lilo groß“, „Papa Auto“), sodass sie eine Bedeutungseinheit bilden.',58,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(59,35,'S2.07','Drei- und Mehrwortsätze bilden','Kind bildet einfache Sätze, die aus 3 oder mehr Wörtern bestehen (z.B. „Papa Auto fahren“, „Mama, Bonbon geben“).',59,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(60,36,'S3.01','Konkrete Bezeichnungen für Objekte verwenden','Kind benennt Personen, Tiere, Gegenstände etc.',60,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(61,36,'S3.02','Bezeichnungen für abstrakte Dinge verwenden','Kind benennt Dinge, die man nicht anfassen kann (z.B. Woche, Lied, Gefühl).',61,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(62,36,'S3.04','Konkrete Tätigkeitsbeschreibungen verwenden','Kind nutzt Wörter, die konkrete und direkt beobachtbare Handlungen beschreiben (z.B. malen, laufen, essen).',62,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(63,36,'S3.06','Zeitbegriffe benennen und unterscheiden','Kind zeigt Verständnis für Zeitbegriffe (gestern, heute, morgen, oder morgens, mittags, abends). Es verwendet mehrere dieser Worte in passenden Zusammenhängen.',63,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(64,36,'S3.07','Raumbeziehungen benennen und unterscheiden','Kind zeigt Verständnis für Lagebezeichnungen (vor, hinter, unter, über, zwischen, neben). Es verwendet mehrere dieser Worte in passenden Zusammenhängen.',64,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(65,36,'S3.08','Mentale Aktivitäten benennen und unterscheiden','Kind verwendet Wörter, die sich auf nicht direkt sichtbare Prozesse beziehen, wie denken, wissen, meinen, glauben, fühlen.',65,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(66,37,'S3.12','Mehrzahlbildung','Kind bildet die Mehrzahlform richtig (z.B. Bäum-e, Gräse-r, Blume-n, Auto-s).',66,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(67,38,'S3.19','Nebensätze bilden','Kind bildet Sätze, die aus mind. 2 Satzteilen bestehen (z.B. „Ich frage mich, was Du in der Tasche hast?“ „Gib mir den Teddy, den ich so mag!“).',67,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(68,38,'S3.23','Flexibler Satzbau','Kind verwendet unterschiedliche Satzanfänge. Es beginnt z.B. mit einer Zeit- oder Ortsangabe („Abends gehe ich ins Bett“; „Zuhause lese ich immer“).',68,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(69,39,'S3.30','Entscheidungsfragen stellen','Kind stellt ein Verb an den Anfang seiner Frage (z.B. „Teilst Du mit mir?“ „Kannst Du das schon?“).',69,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(70,39,'S3.31','W-Fragen verstehen','Kind versteht die Bedeutung unterschiedlicher W-Worte (z.B. wer, warum, wie, wann, wo).',70,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(71,39,'S3.32','W-Frage stellen','Kind nutzt mind. 3 unterschiedliche Fragewörter wie \"wer, wie, was, wo, wieso, weshalb, warum, wohin, wozu\", um mehr Informationen über einen Sachverhalt zu erhalten.',71,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(72,39,'S3.33','Fragen ausführlich beantworten','Das Kind antwortet auf differenzierte Fragen ausführlich, genau und in richtig formulierten Sätzen. Die Antwort besteht aus mind. 3 sinnvoll aufeinander bezogenen Sätzen.',72,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(73,40,'S3.34','In verständlicher Weise über Erfahrungen berichten','Kind berichtet von einer Erfahrung oder einem Erlebnis so, dass man verstehen kann, was passiert ist (z.B. von einem Ausflug, einem Fest, vom Alltag).',73,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(74,40,'S3.36','Geschichten sprachlich komplex wiedergeben','Beim Geschichten nacherzählen nutzt das Kind Fürwörter (er, sie, es) und Bindewörter (z.B. und, weil, aber). Außerdem macht es konkrete Zeitangaben (danach, jetzt, plötzlich).',74,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(75,40,'S3.37','Kurze Aufträge verstehen','Das Kind versteht Aufträge, die aus 1-2 verschiedenen Tätigkeiten bestehen (z.B. „Zieh‘ dir die Jacke aus und komm dann zu mir.“).',75,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(76,41,'SB.14','Emotionale Rückversicherung','Wenn das Kind unsicher ist, was es von einer neuen Situation, einem unvertrauten Gegenstand oder einer unbekannten Person halten soll, schaut es zur Bezugsperson, um in ihrem Gesicht ablesen zu können, wie sie zu der Sache steht.',76,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(77,42,'SB.15','Teilen','Kind teilt Nahrung, Getränke oder Spielmaterial freiwillig mit einem anderen Menschen, wenn es sieht, dass die andere Person etwas davon haben möchte.',77,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(78,42,'SB.17','Freiwilliges Helfen','Kind zeigt spontan Bemühen, andere zu unterstützen. Es hilft Erwachsenen (z.B. bei der Hausarbeit) oder anderen Kindern im Spiel bei der Umsetzung eines Vorhabens, ohne dazu auf-gefordert worden zu sein.',78,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(79,43,'SB.18','Assoziatives Spiel','Kind beobachtet beim eigenen Spiel, was ein anderes Kind gerade tut. Es greift nicht in das Spiel des anderen ein, nimmt aber Anregungen auf (z.B. Imitation von Handlungen anderer Kinder im Sandkasten, Kommentieren des Spiels eines anderen Kindes).',79,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(80,43,'SB.19','Bewegungsspiel','Kind spielt ein Spiel, für das es andere Kinder braucht und sich bewegen muss (z.B. Fangen, Ballspiele, Verstecken). Das Spiel dauert länger als 3 Minuten.',80,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(81,43,'SB.20','Konstruktionsspiel','Kind baut mehr als 3 Minuten lang etwas auf (z.B. Sandburg, Höhle). Das Bauziel, die Aufgabenteilungen oder der Spielverlauf werden mit den Spielpartnern (älteren Kindern oder Erwachsenen) gemeinsam besprochen.',81,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(82,43,'SB.21','Rollenspiel','Kind versetzt sich in eine Rolle (z.B. Verkäufer, Doktor, Kapitän) und spielt länger als 3 Minuten, diese Person zu sein. Es können Verkleidungen oder Spielfiguren zum Einsatz kommen.',82,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(83,44,'SE.07','Gefühle darstellen','Kind gibt vor, bestimmte Gefühle zu haben (z.B. Schmerz, Traurigkeit, Furcht, Freude), etwa im Rollenspiel.',83,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(84,44,'SE.08','Flunkern','Kind versucht, einen anderen Menschen von etwas zu überzeugen, das nicht der Wahrheit entspricht.',84,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(85,44,'SE.09','Perspektivenübernahme','Kind versteht, dass jeder Mensch anders über die gleiche Sache denken kann. Auch wenn die Perspektive eines anderen von seiner eigenen abweicht, kann es sie erfassen.',85,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(86,45,'SE.13','Wünsche und Vorlieben anderer berücksichtigen','Das Kind berücksichtigt die Wünsche und Vorlieben anderer Kinder (z.B. bei der Auswahl von Spielen oder Spielmaterial) im eigenen Handeln oder es fragt nach den Wünschen und Vorlieben seines Spielpartners.',86,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(87,46,'SE.19','Spontanes Teilen von Ressourcen','Kind teilt öfters von sich aus etwas, für das sich außer ihm noch ein anderes Kind interessiert (z.B. Zeit auf der Schaukel, Spiel-Material, Kekse).',87,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(88,47,'SE.23','Gemeinsames Konstruktionsspiel mit Plan','Kind baut mit einem anderen Kind gemeinsam ein Spielszenario auf und spricht seine Pläne dabei ab.',88,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(89,48,'SE.30','Um Erlaubnis fragen','Wenn das Kind sich nicht sicher ist, ob eine Handlung erlaubt ist, fragt es erst bei Erwachsenen nach (verbal oder nonverbal).',89,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(90,48,'SE.32','Ungerechtigkeiten benennen','Kind teilt es einem Erwachsenen mit, wenn es dessen Entscheidung oder Handlung ungerecht findet.',90,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(91,48,'SE.33','Regeln und Verbote hinterfragen','Das Kind hinterfragt den Sinn und Zweck von Verboten und Regeln Erwachsenen gegenüber.',91,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(92,49,'SE.37','Gemeinschaftsaufgaben freiwillig arbeitsteilig erledigen','Kind beteiligt sich bereitwillig an der Erledigung von Pflichten gegenüber der Gemeinschaft (z.B. Tisch decken, Aufräumen).',92,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(93,50,'SR.04','Impulse eigenständig kontrollieren','Kind hemmt ein eigenes Bedürfnis oder Gefühl von sich aus (z.B. nicht sofort schreien, wenn es sich wehgetan hat; nicht hauen, wenn es sich geärgert hat; nicht essen, solange noch nicht alle am Tisch sitzen).',93,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(94,51,'SR.07','Auf das Töpfchen oder die Toilette gehen wollen','Kind macht deutlich, dass es auf Toilette oder auf das Töpfchen muss, bevor die Hose voll ist oder es folgt bereitwillig einem Erwachsenen, der einen Gang zur Toilette / zum Töpfchen anbietet.',94,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(95,52,'SR.12','Zielorientierung halten','Wenn das Kind etwas schaffen möchte, zeigt es Vertrauen in seine eigenen Fähigkeiten, indem es trotz mehrerer Fehlversuche länger versucht, doch noch ans Ziel zu kommen.',95,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(96,53,'SR.15','Warten, bis man an der Reihe ist','Kind wartet bereitwillig, bis es dran ist und drängelt sich nicht vor.',96,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(97,54,'SR.19','Selbstregulation im Umgang mit Ärger und Wut','Wenn Kind Ärger oder Wut fühlt, gelingt es ihm, sich selbst zu beruhigen oder es drückt seine Gefühle aus, ohne andere körperlich oder mit Worten anzugreifen.',97,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(98,55,'G.10','Stolz','Kind zeigt deutliche Freude, Bewusstheit und Selbstbewusstsein über eigene Leistungen (z.B. über etwas, das es gemacht hat). Andere sollen sehen/hören, was das Kind kann/produziert hat.',98,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(99,55,'G.13','Trotz','Kind erkennt, dass die Bezugsperson es dazu bringen will, etwas Bestimmtes zu tun oder zu lassen. Es reagiert mit heftigem Widerstand und beharrt auf der Durchsetzung seiner eigenen Ziele.',99,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(100,55,'G.14','Mitgefühl','Kind zeigt durch sein Verhalten, dass es die Gefühle anderer versteht und ihnen helfen möchte. Es reagiert empathisch auf einen Menschen (z.B. indem es ein anderes Kind tröstet oder verteidigt).',100,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(101,56,'G.16','Über eigene Körperzustände reden','Kind teilt anderen Menschen sprachlich etwas über seine eigenen Körperzustände mit (z.B. Müdigkeit, Hunger, Schmerzen, Temperaturempfindungen).',101,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(102,56,'G.17','Über eigene Gefühle und Gefühlsäußerungen reden','Kind teilt anderen Menschen sprachlich etwas über seine eigenen Gefühle mit. Es benutzt Be-/Umschreibungen, die sich auf eigene Gefühlsäußerungen beziehen (z.B. weinen, lachen) oder direkt auf die Gefühle selbst (z.B. böse / traurig sein).',102,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(103,56,'G.18','Über Körperzustände, Gefühle und Gefühlsäußerungen anderer reden','Kind spricht über die Körperzustände oder Gefühle eines anderen Menschen. Es verwendet Wörter, die entsprechende Zustände oder Gefühle beschreiben und ordnet sie anderen Personen zu (z.B. „Kind hat Aua/ ist böse“).',103,2,2,'2.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(104,57,'G.19','Erkennen und benennen, warum ein anderes Kind weint','Kind erkennt und benennt den Grund für das Weinen eines anderen Kindes (z.B. vor Schmerzen, Ärger, Traurigkeit).',104,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(105,58,'G.25','Grundgefühle im eigenen Erleben unterscheiden','Kind gibt auf Nachfrage an, ob es sich freut, traurig, ärgerlich bzw. wütend oder ängstlich ist. Es unterscheidet beim Sprechen zwischen diesen Emotionen.',105,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(106,59,'G.27','Gründe für eigene Empfindungen benennen','Kind erklärt, was bei ihm ein bestimmtes Gefühl ausgelöst hat (z.B. „Der hat mich nicht in Ruhe gelassen und da bin ich wütend geworden“).',106,2,2,'4.5','2024-02-13 13:27:26','2024-02-13 13:27:26',NULL);
/*!40000 ALTER TABLE `milestones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES ('beokiznimda@gmail.com','$2y$10$8B8Cm9m4zCQZqvQ5Qgrypurnqd2kEC1BrTCsN9rmBBL4YX/7F8hgG','2024-02-13 13:27:18');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `human_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_institution_role` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'superadmin','Superadmin','web',0,'2024-02-13 13:27:17','2024-02-13 13:27:17'),(2,'admin','Admin','web',0,'2024-02-13 13:27:17','2024-02-13 13:27:17'),(3,'monitor','Monitor','web',0,'2024-02-13 13:27:17','2024-02-13 13:27:17'),(4,'monitor-oe','Monitor oE','web',0,'2024-02-13 13:27:17','2024-02-13 13:27:17'),(5,'manager','Manager','web',1,'2024-02-13 13:27:17','2024-02-13 13:27:17'),(6,'user-multiplikator','User-Multiplikator','web',1,'2024-02-13 13:27:17','2024-02-13 13:27:17'),(7,'mitarbeiter','Mitarbeiter','web',1,'2024-02-13 13:27:17','2024-02-13 13:27:17');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subdomains`
--

DROP TABLE IF EXISTS `subdomains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subdomains` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subdomains_domain_id_foreign` (`domain_id`),
  CONSTRAINT `subdomains_domain_id_foreign` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subdomains`
--

LOCK TABLES `subdomains` WRITE;
/*!40000 ALTER TABLE `subdomains` DISABLE KEYS */;
INSERT INTO `subdomains` VALUES (1,1,'Im Stehen',1,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(2,1,'Hüpfen',2,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(3,1,'Werfen/ Fangen',3,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(4,1,'Hängen und Schaukeln',4,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(5,1,'Sprungarten',5,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(6,1,'Körperkoordination beim Turnen',6,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(7,1,'Zeitliche Steuerung von Bewegungen',7,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(8,2,'Gegenstände manipulieren',1,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(9,2,'Essen & Trinken',2,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(10,2,'Zeichnen',3,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(11,2,'Handgeschicklichkeit beim Essen und Trinken',4,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(12,2,'Mit Stiften umgehen',5,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(13,2,'An- und Ausziehen',6,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(14,2,'Basteln und Werken',7,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(15,2,'Mit Nadel und Faden / Schnur umgehen',8,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(16,2,'Schrauben und Schließen',9,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(17,2,'Handgeschicklichkeit beim Umgang mit Spielmaterial',10,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(18,3,'Visuelle Aufmerksamkeit',1,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(19,3,'Akustische Aufmerksamkeit',2,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(20,3,'Konzentration',3,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(21,3,'Kategorisierung und geistige Flexibilität',4,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(22,3,'Visuelle Merkfähigkeit',5,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(23,3,'Akustische Merkfähigkeit',6,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(24,4,'Planen',1,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(25,4,'Darstellen und Symbolisieren',2,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(26,4,'Räumlich ordnen',3,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(27,5,'Logik und Argumentation',1,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(28,5,'Planen und Problemlösen',2,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(29,5,'Experimentieren und Forschen',3,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(30,6,'Lesen',1,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(31,6,'Zahlenreihe bilden und abzählen',2,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(32,6,'Ordnen, Messen, Vergleichen',3,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(33,7,'Worte',1,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(34,8,'Besondere Wörter',2,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(35,8,'Sätze',3,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(36,9,'Wortschatz',1,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(37,9,'Grammatik auf Wortebene',2,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(38,9,'Grammatik auf Satzebene',3,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(39,9,'Pragmatik auf Sprachgebrauch',4,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(40,9,'Längere sprachliche Äußerungen verstehen und produzieren',5,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(41,10,'Fremde und vertraute Personen unterscheiden',1,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(42,10,'Kooperation im Alltag',2,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(43,10,'Gemeinsam Spielen',3,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(44,11,'Theory of Mind',1,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(45,11,'Freundschaften bilden und halten',2,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(46,11,'Kooperation mit anderen Kindern',3,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(47,11,'Spielverhalten in der Gemeinschaft',4,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(48,11,'Umgang mit Erwachsenen (Eltern, Fachkräften)',5,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(49,11,'Sozialverhalten in Gruppen',6,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(50,12,'Impulse',1,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(51,12,'Ausscheidungen',2,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(52,12,'Umgang mit Zielfrustration',3,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(53,12,'Umgang mit sozialen Wartesituationen',4,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(54,12,'Selbstregulation eigener Gefühlszustände',5,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(55,13,'Komplexe Gefühle zeigen',1,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(56,13,'Über Gefühle reden',2,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(57,13,'Gefühle erkennen',3,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(58,13,'Gefühle unterscheiden',4,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL),(59,13,'Austausch über Gefühle mit anderen Menschen',5,'2024-02-13 13:27:26','2024-02-13 13:27:26',NULL);
/*!40000 ALTER TABLE `subdomains` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_auth_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_login_at` timestamp NULL DEFAULT NULL,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','Super','beokiznimda@gmail.com',NULL,'$2y$10$U3Da2rmDrEtZ/rJyg8SkJuj9XYAj7xzyfbAeMbWGrACm9hAx6/zz6',0,NULL,NULL,NULL,'2024-02-13 13:27:18','2024-02-13 13:27:18',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webhook_calls`
--

DROP TABLE IF EXISTS `webhook_calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `webhook_calls` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` json DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `exception` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webhook_calls`
--

LOCK TABLES `webhook_calls` WRITE;
/*!40000 ALTER TABLE `webhook_calls` DISABLE KEYS */;
/*!40000 ALTER TABLE `webhook_calls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `websockets_statistics_entries`
--

DROP TABLE IF EXISTS `websockets_statistics_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `websockets_statistics_entries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `app_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peak_connection_count` int NOT NULL,
  `websocket_message_count` int NOT NULL,
  `api_message_count` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `websockets_statistics_entries`
--

LOCK TABLES `websockets_statistics_entries` WRITE;
/*!40000 ALTER TABLE `websockets_statistics_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `websockets_statistics_entries` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-02-13 17:27:44
