ALTER TABLE psa_group ADD COLUMN custom_col1 varchar(50) NULL;
ALTER TABLE psa_group ADD COLUMN custom_col2 varchar(50) NULL;
ALTER TABLE psa_group ADD COLUMN custom_col3 varchar(50) NULL;

UPDATE psa_group SET name='TestGroup', custom_col1='aaa',  custom_col2='bbb', custom_col3='ccc' WHERE id=1;
