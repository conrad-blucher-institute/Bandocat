-- This script is used to grant privileges to the user on the databases so the application does not use root credentials to access the databases

-- Grant privileges to the user on other databases
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE ON bandocat_bluchermapsinventory.* TO 'bandocat_user'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE ON bandocat_fieldbookindicesinventory.* TO 'bandocat_user'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE ON bandocat_fieldbookinventory.* TO 'bandocat_user'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE ON bandocat_greenmapsinventory.* TO 'bandocat_user'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE ON bandocat_indicesinventory.* TO 'bandocat_user'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE ON bandocat_jobfolderinventory.* TO 'bandocat_user'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE ON bandocat_pennyfennerfieldbookinventory.* TO 'bandocat_user'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE ON bandocat_pennyfennerinventory.* TO 'bandocat_user'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE ON bandocatdb.* TO 'bandocat_user'@'%';

-- Don't forget to apply the changes
FLUSH PRIVILEGES;
