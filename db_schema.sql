CREATE TABLE books (
	id int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255),
	isbn VARCHAR(250) UNIQUE,
	authors VARCHAR(255),
	filename text,
	path text,
	amazon_link TEXT,
	coverimg VARCHAR(255),
	created DATETIME,
	modified DATETIME
);

CREATE TABLE collections (
	id int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255),
	icon VARCHAR(255),
	created DATETIME,
	modified DATETIME
);

CREATE TABLE books_collections(
	book_id int(11) UNSIGNED NOT NULL,
	collection_id int(11) UNSIGNED NOT NULL,
	PRIMARY KEY(book_id, collection_id),
	FOREIGN KEY(book_id) REFERENCES books(id),
	FOREIGN KEY(collection_id) REFERENCES collections(id)
);

CREATE INDEX isbn_index ON books(isbn);
