CREATE TABLE ezshipping_pricegroup (
  id int(11) NOT NULL auto_increment,
  name varchar(255) DEFAULT NULL,
  identifier varchar(50) DEFAULT NULL,
  PRIMARY KEY (id)
);

INSERT INTO ezshipping_pricegroup (id, name, identifier) VALUES (1, 'First Value', 'first_value');
INSERT INTO ezshipping_pricegroup (id, name, identifier) VALUES (2, 'Additional value', 'additional_value');

CREATE TABLE ezshipping_group (
  id int(11) NOT NULL auto_increment,
  name varchar(255) DEFAULT NULL,
  data_text longtext DEFAULT NULL,
  PRIMARY KEY (id)
);

ALTER TABLE ezshipping_pricegroup TYPE = innodb;
ALTER TABLE ezshipping_group TYPE = innodb;
