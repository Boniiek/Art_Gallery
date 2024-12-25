CREATE TABLE users(
user_id SERIAL PRIMARY KEY,
u_name VARCHAR(30),
u_email VARCHAR(75),
u_password VARCHAR(255),
u_date DATE,
author INT,
date_created TIMESTAMP DEFAULT (date_trunc('second', now()::TIMESTAMP)),
u_status int DEFAULT 0);

CREATE TABLE category(
category_id SERIAL PRIMARY KEY,
category_name VARCHAR(30));

CREATE TABLE genre(
genre_id SERIAL PRIMARY KEY,
genre_name VARCHAR(30));

CREATE TABLE pictures(
pic_id SERIAL PRIMARY KEY,
pic_name VARCHAR(30),
author_id INT,
category_id INT,
genre_id INT,
year_taken INT DEFAULT 0,
date_posted TIMESTAMP DEFAULT (date_trunc('second', now()::TIMESTAMP)),
FOREIGN KEY (author_id) REFERENCES users (user_id) ON UPDATE CASCADE ON DELETE RESTRICT,
FOREIGN KEY (category_id) REFERENCES category (category_id) ON UPDATE CASCADE ON DELETE RESTRICT,
FOREIGN KEY (genre_id) REFERENCES genre (genre_id) ON UPDATE CASCADE ON DELETE RESTRICT);


INSERT INTO category(category_name)
VALUES('Photo'), ('Painting'), ('Drawing');

INSERT INTO genre(genre_name)
VALUES('People'), ('Nature'), ('Animals'), ('Characters'), ('History'),
('Architecture'), ('Other');



CREATE TABLE administrators(
adm_id SERIAL PRIMARY KEY,
adm_name VARCHAR(30),
adm_password VARCHAR(255));


CREATE TABLE tmp_users(
user_id SERIAL PRIMARY KEY,
u_name VARCHAR(30),
u_email VARCHAR(75),
u_password VARCHAR(255),
u_date DATE,
author INT,
act_code VARCHAR(255),
act_exp TIMESTAMP DEFAULT (date_trunc('second', now()::TIMESTAMP) + interval '1 hour' * 6));


