CREATE TABLE books (
	id int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255),
	isbn VARCHAR(250) UNIQUE,
	authors VARCHAR(255),
	filename text,
	path text,
	where_backup VARCHAR(255),
	amazon_link TEXT,
	coverimg VARCHAR(255),
	created DATETIME DEFAULT CURRENT_TIMESTAMP,
	modified DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE collections (
	id int(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255),
	icon VARCHAR(255),
	parent_id int(11) UNSIGNED,
	created DATETIME DEFAULT CURRENT_TIMESTAMP,
	modified DATETIME DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(parent_id) REFERENCES collections(id)
);


CREATE TABLE collection_closure (
	ancestor int(11) UNSIGNED NOT NULL,
	descendant int(11) UNSIGNED NOT NULL,
	depth int(11) UNSIGNED
);

CREATE TABLE books_collections(
	book_id int(11) UNSIGNED NOT NULL,
	collection_id int(11) UNSIGNED NOT NULL,
	PRIMARY KEY(book_id, collection_id),
	FOREIGN KEY(book_id) REFERENCES books(id),
	FOREIGN KEY(collection_id) REFERENCES collections(id)
);


-- INSERT trigger:

delimiter |

CREATE TRIGGER closure_trigger
AFTER INSERT ON collections
	FOR EACH ROW
	BEGIN
		INSERT INTO collection_closure(ancestor, descendant, depth)
			VALUES(NEW.id, NEW.id, 0);
		INSERT INTO collection_closure(ancestor, descendant, depth)
			SELECT p.ancestor, c.descendant, p.depth+c.depth+1
			FROM collection_closure p, collection_closure c
			WHERE p.descendant = NEW.parent_id AND c.ancestor = NEW.id;
	END
	|

delimiter ;


-- DELETE trigger:

delimiter |

CREATE TRIGGER closure_delete_trigger
BEFORE DELETE ON collections
	FOR EACH ROW
	BEGIN
		DELETE link
			FROM collection_closure p, collection_closure link, collection_closure c, collection_closure to_delete
			WHERE p.ancestor = link.ancestor AND c.descendant = link.descendant
			AND p.descendant = to_delete.ancestor AND c.ancestor= to_delete.descendant
			AND (to_delete.ancestor = OLD.id OR to_delete.descendant = OLD.id)
			AND to_delete.depth < 2;
	END
	|

delimiter ;


-- Example select statement:
-- This retrieves all the descendants (at any depth) of a given category
-- (including the category itself)

SELECT id, name, parent_id FROM collections c
	JOIN collection_closure closure
	ON c.id = closure.descendant
WHERE closure.ancestor = CATEGORY_ID;
