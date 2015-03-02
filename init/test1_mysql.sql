ALTER TABLE psa_user ADD COLUMN custom_col1 varchar(50) NULL;
ALTER TABLE psa_user ADD COLUMN custom_col2 varchar(50) NULL;
ALTER TABLE psa_user ADD COLUMN custom_col3 varchar(50) NULL;

UPDATE psa_user SET username='TestUser', custom_col1='aaa',  custom_col2='bbb', custom_col3='ccc' WHERE id=1;
