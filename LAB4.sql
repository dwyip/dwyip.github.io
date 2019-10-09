DROP TABLE IF EXISTS lab5.school;

CREATE TABLE lab5.school (
    NAME VARCHAR,
    BOARD VARCHAR,
    TYPE VARCHAR,
    NAME_AB VARCHAR,
    ADDRESS_AB VARCHAR,
    CITY VARCHAR,
    PROVINCE VARCHAR,
    GRADES VARCHAR,
    POSTSECOND VARCHAR,
    DATA_SOURCE VARCHAR,
    PROVINCIAL_CODE_NUM FLOAT,
    ECS VARCHAR,
    ELEM VARCHAR,
    JUNIOR_H VARCHAR,
    SENIOR_H VARCHAR,
    PROCESS_DT DATE,
    POSTAL_COD VARCHAR,
    STRUCTURE_ID FLOAT,
    longitude FLOAT,
    latitude FLOAT,
    location POINT
);

COPY lab5.school
FROM 'C:\Users\dwyip\Downloads\School_Locations.csv' DELIMITER ',' CSV HEADER;

SELECT AddGeometryColumn('lab5', 'school', 'geom4326', 4326, 'POINT', 2);
UPDATE lab5.school SET geom4326 = ST_SetSRID(ST_MakePoint(longitude,latitude),4326);

ALTER TABLE lab5.school
ADD COLUMN community VARCHAR;

UPDATE lab5.community SET geom = ST_SetSRID(geom,4326);


UPDATE lab5.school
SET community = c.name FROM lab5.community as c WHERE ST_Contains(c.geom, geom4326);

SELECT * from lab5.school



