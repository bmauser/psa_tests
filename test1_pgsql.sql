ALTER TABLE psa_user ADD COLUMN custom_col1 character varying(50);
ALTER TABLE psa_user ADD COLUMN custom_col2 character varying(50);
ALTER TABLE psa_user ADD COLUMN custom_col3 character varying(50);

UPDATE psa_user SET username='TestUser', custom_col1='aaa',  custom_col2='bbb', custom_col3='ccc' WHERE id=1;