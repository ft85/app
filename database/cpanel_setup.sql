-- ============================================================
-- I-Solutions POS — cPanel Production Setup SQL
-- Run this in phpMyAdmin or MySQL terminal on your cPanel
-- ============================================================

-- 1. Create unspec table (OBR item class codes)
CREATE TABLE IF NOT EXISTS `unspec` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `unspec_code` VARCHAR(191) NOT NULL,
    `item_class` VARCHAR(191) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unspec_code_unique` (`unspec_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Add unspec column to categories (if not exists)
ALTER TABLE `categories` ADD COLUMN IF NOT EXISTS `unspec` VARCHAR(191) NULL DEFAULT NULL;

-- 3. Seed all 57 OBR item class codes
INSERT IGNORE INTO `unspec` (`unspec_code`, `item_class`) VALUES
('A1001', 'Boissons alcoolisees'),
('A1002', 'Boissons non alcoolisees'),
('A1003', 'Eaux minerales et eaux gazeuses'),
('B2001', 'Produits alimentaires et epicerie'),
('B2002', 'Produits laitiers'),
('B2003', 'Viandes et charcuteries'),
('B2004', 'Poissons et fruits de mer'),
('B2005', 'Pain, patisseries et cereales'),
('B2006', 'Fruits et legumes'),
('B2007', 'Huiles et graisses alimentaires'),
('B2008', 'Condiments, epices et sauces'),
('B2009', 'Sucre, confiseries et chocolat'),
('C3001', 'Tabac et produits du tabac'),
('D4001', 'Medicaments et produits pharmaceutiques'),
('D4002', 'Materiel et equipements medicaux'),
('D4003', 'Produits de sante et cosmetiques'),
('E5001', 'Vetements et textiles'),
('E5002', 'Chaussures et maroquinerie'),
('E5003', 'Accessoires vestimentaires'),
('F6001', 'Electronique grand public'),
('F6002', 'Telephones et accessoires'),
('F6003', 'Informatique et peripheriques'),
('F6004', 'Appareils electromenagers'),
('G7001', 'Materiaux de construction'),
('G7002', 'Quincaillerie et outillage'),
('G7003', 'Peintures et revetements'),
('H8001', 'Meubles et ameublement'),
('H8002', 'Literie et textiles maison'),
('H8003', 'Articles de menage et vaisselle'),
('I9001', 'Carburants et lubrifiants'),
('I9002', 'Pieces automobiles et accessoires'),
('J1001', 'Services de restauration'),
('J1002', 'Services hoteliers et hebergement'),
('J1003', 'Services de transport'),
('J1004', 'Services de telecommunication'),
('J1005', 'Services informatiques et technologie'),
('J1006', 'Services financiers et bancaires'),
('J1007', 'Services de sante et medicaux'),
('J1008', 'Services educatifs et formation'),
('J1009', 'Services de construction et genie civil'),
('J1010', 'Services de securite'),
('J1011', 'Services de nettoyage et entretien'),
('J1012', 'Services juridiques et comptables'),
('K1101', 'Papeterie et fournitures de bureau'),
('K1102', 'Livres, journaux et publications'),
('L1201', 'Produits agricoles et semences'),
('L1202', 'Engrais et produits phytosanitaires'),
('L1203', 'Animaux et produits elevage'),
('M1301', 'Equipements industriels et machines'),
('M1302', 'Matieres premieres industrielles'),
('N1401', 'Jouets et articles de sport'),
('N1402', 'Articles de loisirs et culture'),
('O1501', 'Bijoux et montres'),
('O1502', 'Produits de luxe'),
('P1601', 'Energie electrique'),
('P1602', 'Gaz et energie'),
('Q1701', 'Autres produits non classes');

-- 4. Add injonge_code to products (if not exists)
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `injonge_code` VARCHAR(191) NULL DEFAULT NULL;

-- 5. Add res_tables columns for table management
ALTER TABLE `res_tables` ADD COLUMN IF NOT EXISTS `assigned_waiter_id` INT UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `res_tables` ADD COLUMN IF NOT EXISTS `is_table_open` TINYINT(1) NOT NULL DEFAULT 0;

-- 6. Create missing OBR tables
CREATE TABLE IF NOT EXISTS `stockmaster_obr` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `business_id` INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NULL,
    `item_code` VARCHAR(191) NULL,
    `item_name` VARCHAR(191) NULL,
    `quantity` DECIMAL(22,4) NULL DEFAULT 0.0000,
    `unit_price` DECIMAL(22,4) NULL DEFAULT 0.0000,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `devices` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `business_id` INT UNSIGNED NULL,
    `device_id` VARCHAR(191) NULL,
    `device_name` VARCHAR(191) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `injonge_items` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `business_id` INT UNSIGNED NULL,
    `injonge_code` VARCHAR(191) NULL,
    `item_name` VARCHAR(191) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `account_eucl` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `business_id` INT UNSIGNED NULL,
    `account_number` VARCHAR(191) NULL,
    `account_name` VARCHAR(191) NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `vwbalance` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `business_id` INT UNSIGNED NULL,
    `balance` DECIMAL(22,4) NULL DEFAULT 0.0000,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `stock_adjustments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `business_id` INT UNSIGNED NOT NULL,
    `location_id` INT UNSIGNED NULL,
    `transaction_id` INT UNSIGNED NULL,
    `product_id` INT UNSIGNED NULL,
    `variation_id` INT UNSIGNED NULL,
    `quantity` DECIMAL(22,4) NULL DEFAULT 0.0000,
    `unit_price` DECIMAL(22,4) NULL DEFAULT 0.0000,
    `note` TEXT NULL,
    `created_by` INT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
