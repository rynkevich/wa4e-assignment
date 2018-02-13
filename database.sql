CREATE DATABASE ResumeRegistry DEFAULT CHARACTER SET utf8;
USE ResumeRegistry;

GRANT ALL ON ResumeRegistry.* TO 'application'@'localhost' IDENTIFIED BY 'Pa$$w0rD';
GRANT ALL ON ResumeRegistry.* TO 'application'@'127.0.0.1' IDENTIFIED BY 'Pa$$w0rD';

CREATE TABLE users (
	user_id INTEGER NOT NULL AUTO_INCREMENT,
	name VARCHAR(128),
	email VARCHAR(128),
	`password` VARCHAR(128),

	PRIMARY KEY(user_id),
	INDEX(email),
    INDEX(`password`)
) ENGINE = InnoDB;

CREATE TABLE profile (
    profile_id INTEGER NOT NULL AUTO_INCREMENT,
    user_id INTEGER NOT NULL,
    first_name TEXT,
    last_name TEXT,
    email TEXT,
    headline TEXT,
    summary TEXT,

    PRIMARY KEY(profile_id),
    CONSTRAINT profile_ibfk_2 FOREIGN KEY (user_id)
        REFERENCES users (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

INSERT INTO users (name, email, `password`)
    VALUES ('UMSI', 'umsi@umich.edu', '1a52e17fa899cf40fb04cfc42e6352f1');
INSERT INTO users (name, email, `password`)
    VALUES ('Chuck', 'csev@umich.edu', '1a52e17fa899cf40fb04cfc42e6352f1');
