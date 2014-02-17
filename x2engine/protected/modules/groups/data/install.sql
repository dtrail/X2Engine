DROP TABLE IF EXISTS `x2_groups`;
/*&*/
DROP TABLE IF EXISTS `x2_group_to_user`;
/*&*/
CREATE TABLE x2_groups (
	id						INT				NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name					VARCHAR(250)
) ENGINE InnoDB COLLATE = utf8_general_ci;
/*&*/
CREATE TABLE x2_group_to_user (
	id						INT				NOT NULL AUTO_INCREMENT PRIMARY KEY,
	groupId					INT,
	userId					INT,
	username				VARCHAR(250)
) COLLATE = utf8_general_ci;
/*&*/
INSERT INTO `x2_modules`
			(`name`,			title,			visible, 	menuPosition,	searchable,	editable,	adminOnly,	custom,	toggleable)
	VALUES	("groups",			"Groups",			1,			14,				0,			0,			0,			0,		0);
/*&*/
INSERT INTO `x2_fields` (`modelName`, `fieldName`, `attributeLabel`, `modified`, `custom`, `type`, `required`, `readOnly`, `linkType`, `searchable`, `relevance`, `isVirtual`)
VALUES
('Groups', 'id',    'ID',   0, 0, 'varchar', 0, 0, NULL, 0, NULL, 0),
('Groups', 'name',  'Name', 0, 0, 'varchar', 0, 0, NULL, 0, NULL, 0)
