DROP TABLE IF EXISTS x2_ob_b;
/*&*/
CREATE TABLE x2_ob_b(
		id INT NOT NULL AUTO_INCREMENT primary key,
		assignedTo VARCHAR(250),
		name VARCHAR(250) NOT NULL,
		description TEXT,
		createDate INT,
		lastUpdated INT,
		lastActivity BIGINT,
		updatedBy VARCHAR(250)
) COLLATE = utf8_general_ci;
/*&*/
INSERT INTO `x2_modules`
			(`name`,			title,			visible, 	menuPosition,	searchable,	editable,	adminOnly,	custom,	toggleable)
	VALUES	('ob_b',		'Ob_b',	1,			1,				1,			1,			0,			1,		1);
/*&*/
INSERT INTO x2_fields
(modelName,		fieldName,			attributeLabel,		custom,		type,		required,	readOnly, 		linkType,   searchable,	isVirtual,	relevance, uniqueConstraint, safe)
VALUES
("Ob_b",	"id",				"ID",					0,		"int",			0,			0,			NULL,		0,			0,			"",			1,                  1),
("Ob_b",	"name",				"Name",					0,		"varchar",		0,			0,			NULL,		0,			0,			"High",		0,                  1),
("Ob_b",	"assignedTo",		"Assigned To",			0,		"assignment",	0,			0,			NULL,		0,			0,			"",			0,                  1),
("Ob_b",	"description",		"Description",			0,		"text",			0,			0,			NULL,		0,			0,			"Medium",	0,                  1),
("Ob_b",	"createDate",		"Create Date",			0,		"dateTime",		0,			0,			NULL,		0,			0,			"",			0,                  1),
("Ob_b",	"lastUpdated",		"Last Updated",			0,		"dateTime",		0,			0,			NULL,		0,			0,			"",			0,                  1),
("Ob_b",	"lastActivity",		"Last Activity",		0,		"dateTime",		0,			0,			NULL,		0,			0,			"",			0,                  1),
("Ob_b",	"updatedBy",		"Updated By",			0,		"assignment",	0,			0,			NULL,		0,			0,			"",			0,                  1);
