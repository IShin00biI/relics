CREATE TABLE relic (
    relic_id INTEGER(7) PRIMARY KEY,
    relic_name TEXT NOT NULL,
    relic_dating TEXT,
    relic_street TEXT,
		relic_desc TEXT,
    relic_reg_no TEXT NOT NULL,
    place_id INTEGER(7) NOT NULL
);

CREATE TABLE place (
    place_id NUMBER(7) PRIMARY KEY,
    place_name TEXT NOT NULL,
    commune_name TEXT NOT NULL,
    district_name TEXT NOT NULL,
    voivodeship_name TEXT NOT NULL
);

CREATE TABLE visit (
    visit_id INTEGER(7) PRIMARY KEY,
    user_id INTEGER(7) NOT NULL,
    relic_id INTEGER(7) NOT NULL,
    visit_time DATE
);

CREATE TABLE user (
    user_id NUMBER(7) PRIMARY KEY,
    user_name TEXT NOT NULL UNIQUE
);
