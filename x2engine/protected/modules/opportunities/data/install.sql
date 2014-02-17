DROP TABLE IF EXISTS `x2_opportunities`;
/*&*/
CREATE TABLE x2_opportunities(
	id						INT				UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name					VARCHAR(255)	NOT NULL,
	accountName				VARCHAR(100),
	quoteAmount				DECIMAL(18,2),
	salesStage				VARCHAR(20),
	expectedCloseDate		BIGINT,
	probability				FLOAT,
	leadSource				VARCHAR(100),
	description				TEXT,
	assignedTo				TEXT,
	createDate				BIGINT,
    visibility              TINYINT DEFAULT 1,
	associatedContacts		TEXT,
	lastUpdated				BIGINT,
	lastActivity			BIGINT,
	updatedBy				VARCHAR(50)
) COLLATE = utf8_general_ci;
/*&*/
INSERT INTO `x2_modules`
			(`name`,			title,			visible, 	menuPosition,	searchable,	editable,	adminOnly,	custom,	toggleable)
	VALUES	("opportunities",	"Opportunities",	1,			3,				1,			1,			0,			0,		0);
/*&*/
INSERT INTO x2_fields
(modelName,			fieldName,				attributeLabel,	 modified,	custom,	type,		required,	readOnly,  linkType,   searchable,	isVirtual,	relevance, uniqueConstraint, safe)
VALUES
("Opportunity",		"id",					"ID",					0,		0,	"varchar",		0,			0,		NULL,			0,		0,			"",         1,              1),
("Opportunity",		"name",					"Name",					0,		0,	"varchar",		1,			0,		NULL,			1,		0,			"High",     0,              1),
("Opportunity",		"accountName",			"Account",				0,		0,	"link",			0,			0,		"Accounts",	 	0,		0,			"",         0,              1),
("Opportunity",		"quoteAmount",			"Quote Amount",			0,		0,	"currency",		0,			0,		NULL,			0,		0,			"",         0,              1),
("Opportunity",		"salesStage",			"Sales Stage",			0,		0,	"dropdown",		0,			0,		"105",			0,		0,			"",         0,              1),
("Opportunity",		"expectedCloseDate",	"Expected Close Date",	0,		0,	"date",			0,			0,		NULL,			0,		0,			"",         0,              1),
("Opportunity",		"probability",			"Probability",			0,		0,	"percentage",	0,			0,		NULL,			0,		0,			"",         0,              1),
("Opportunity",		"leadSource",			"Lead Source",			0,		0,	"dropdown",		0,			0,		"103",			0,		0,			"",         0,              1),
("Opportunity",		"description",			"Description",			0,		0,	"text",			0,			0,		NULL,			1,		0,			"Medium",   0,              1),
("Opportunity",		"assignedTo",			"Assigned To",			0,		0,	"assignment",	0,			0,		"multiple",		0,		0,			"",         0,              1),
("Opportunity",		"visibility",			"Visibility",			0,		0,	"visibility",	0,			0,		NULL,			0,		0,			"",         0,              1),
("Opportunity",		"createDate",			"Create Date",			0,		0,	"dateTime",		0,			1,		NULL,			0,		0,			"",         0,              1),
("Opportunity",		"lastUpdated",			"Last Updated",			0,		0,	"dateTime",		0,			1,		NULL,			0,		0,			"",         0,              1),
("Opportunity",		"lastActivity",			"Last Activity",		0,		0,	"dateTime",		0,			1,		NULL,			0,		0,			"",         0,              1),
("Opportunity",		"updatedBy",			"Updated By",			0,		0,	"varchar",		0,			1,		NULL,			0,		0,			"",         0,              1);
