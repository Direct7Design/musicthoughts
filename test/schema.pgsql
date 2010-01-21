BEGIN;
CREATE SCHEMA musicthoughts;
SET search_path = musicthoughts;

CREATE TABLE categories (
	id serial primary key,
	description varchar(64) UNIQUE
);

CREATE TABLE authors (
	id serial primary key,
	name varchar(127) UNIQUE,
	url varchar(255)
);

CREATE TABLE contributors (
	id serial primary key,
	shared_id integer UNIQUE, 
	name varchar(127),
	email varchar(127) UNIQUE,
	url varchar(255),
	place varchar(255)
);

CREATE TABLE thoughts (
	id serial primary key,
	approved boolean default false not null,
	author_id integer not null REFERENCES authors(id),
	contributor_id integer not null REFERENCES contributors(id),
	created_at date not null default CURRENT_DATE,
	as_rand boolean not null default false,
	source_url varchar(255)
);

CREATE TABLE thought_translations (
	id serial primary key,
	thought_id integer not null REFERENCES thoughts(id),
	lang char(2) not null default 'en',
	thought text,
	UNIQUE (thought_id, lang)
);

CREATE TABLE categories_thoughts (
	thought_id integer not null REFERENCES thoughts(id),
	category_id integer not null REFERENCES categories(id),
	PRIMARY KEY (thought_id, category_id)
);
CREATE INDEX ctti ON categories_thoughts(thought_id);
CREATE INDEX ctci ON categories_thoughts(category_id);

COMMIT;
