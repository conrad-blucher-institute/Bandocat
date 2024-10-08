
-- This script is used to migrate the database from Windows to Containerized Linux environment

-- Update config table in bandocat db to point to correct directory
-- Update bandocatdb database, table `collection`. Field storagedir: change value from M:/{CollectionName}/uploads to ../../Uploads/{CollectionName}/uploads/ for all collections
USE bandocatdb;

UPDATE `collection`
SET
`storagedir` = REPLACE
(`storagedir`, 'M:/', '../../Uploads/')
WHERE `storagedir` LIKE 'M:/%/uploads%';

-- Update georecdir field in collection table from M:/{CollectionName}/georec/ to ../../Uploads/{CollectionName}/georec/
UPDATE `collection`
SET
`georecdir` = REPLACE
(`georecdir`, 'M:/', '../../Uploads/')
WHERE `georecdir` LIKE 'M:/%/georec%';

-- Update dspaceURI and dSpaceID in documents table to have default value (for each db)
-- For bandocat_bluchermapsinventory database
USE bandocat_bluchermapsinventory;

ALTER TABLE `document` CHANGE `dspaceURI` `dspaceURI` VARCHAR
(50) CHARACTER
SET utf8 NULL
DEFAULT NULL;
ALTER TABLE `document` CHANGE `dspaceID` `dspaceID` VARCHAR
(100) CHARACTER
SET utf8 NULL
DEFAULT NULL;

-- For bandocat_greenmapsinventory database
USE bandocat_greenmapsinventory;

ALTER TABLE `document` CHANGE `dspaceURI` `dspaceURI` VARCHAR
(50) CHARACTER
SET utf8 NULL
DEFAULT NULL;
ALTER TABLE `document` CHANGE `dspaceID` `dspaceID` VARCHAR
(100) CHARACTER
SET utf8 NULL
DEFAULT NULL;

-- For bandocat_fieldbookinventory database
USE bandocat_fieldbookinventory;

ALTER TABLE `document` CHANGE `dspaceURI` `dspaceURI` VARCHAR
(50) CHARACTER
SET utf8 NULL
DEFAULT NULL;
ALTER TABLE `document` CHANGE `dspaceID` `dspaceID` VARCHAR
(100) CHARACTER
SET utf8 NULL
DEFAULT NULL;

-- For bandocat_pennyfennerinventory database
USE bandocat_pennyfennerinventory;

ALTER TABLE `document` CHANGE `dspaceURI` `dspaceURI` VARCHAR
(50) CHARACTER
SET utf8 NULL
DEFAULT NULL;
ALTER TABLE `document` CHANGE `dspaceID` `dspaceID` VARCHAR
(100) CHARACTER
SET utf8 NULL
DEFAULT NULL;

-- For bandocat_pennyfennerfieldbookinventory database
USE bandocat_pennyfennerfieldbookinventory;

ALTER TABLE `document` CHANGE `dspaceURI` `dspaceURI` VARCHAR
(50) CHARACTER
SET utf8 NULL
DEFAULT NULL;
ALTER TABLE `document` CHANGE `dspaceID` `dspaceID` VARCHAR
(100) CHARACTER
SET utf8 NULL
DEFAULT NULL;

-- For bandocat_jobfolderinventory database
USE bandocat_jobfolderinventory;

ALTER TABLE `document` CHANGE `dspaceURI` `dspaceURI` VARCHAR
(50) CHARACTER
SET utf8 NULL
DEFAULT NULL;
ALTER TABLE `document` CHANGE `dspaceID` `dspaceID` VARCHAR
(100) CHARACTER
SET utf8 NULL
DEFAULT NULL;

ALTER TABLE `document` CHANGE `dspaceURI` `dspaceURI` VARCHAR
(50) CHARACTER
SET utf8 NULL
DEFAULT NULL;
ALTER TABLE `document` CHANGE `dspaceID` `dspaceID` VARCHAR
(100) CHARACTER
SET utf8 NULL
DEFAULT NULL;

-- TODO: Update template fulldir (bandocatdb.template.fulldir)