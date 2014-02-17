/*&*/ALTER TABLE x2_zv ADD COLUMN c_zv_customer VARCHAR(255);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('zv', 'c_zv_customer', 'Kunden/Kontakt', '1', '1', 'link', 'Contacts');/*&*/ALTER TABLE x2_zv ADD COLUMN c_zv_name VARCHAR(255);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('zv', 'c_zv_name', 'Bezeichnung der Liste', '1', '1', 'varchar', '');/*&*/ALTER TABLE x2_zv ADD COLUMN c_zv_place TEXT;/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('zv', 'c_zv_place', 'Orte/Bereiche (zB Bundesländer)', '1', '1', 'text', '');/*&*/INSERT INTO x2_form_layouts (model, version, scenario, layout, defaultView, defaultForm, createDate, lastUpdated) VALUES ('Zv', 'Defaul', 'Default', '{"version":"1.2","sections":[{"collapsible":false,"title":"Bitte hier die Bezeichnung und die betreffenden Orte eintragen. Die Liste dann nach Erstellen des Eintrages als Datei anhängen.","rows":[{"cols":[{"width":589,"items":[{"name":"formItem_name","labelType":"left","readOnly":"0","height":"22","width":"154","tabindex":"undefined"},{"name":"formItem_c_zv_name","labelType":"left","readOnly":"0","height":"22","width":"154","tabindex":"undefined"},{"name":"formItem_c_zv_place","labelType":"left","readOnly":"0","height":"242","width":"249","tabindex":"undefined"}]}]}]},{"collapsible":false,"title":"Für welchen Kontakt wird dieser Auftrag bearbeitet?","rows":[{"cols":[{"width":589,"items":[{"name":"formItem_c_zv_customer","labelType":"left","readOnly":"0","height":"22","width":"154","tabindex":"undefined"}]}]}]},{"collapsible":false,"title":"Von welchem Mitarbeiter stammt diese Liste?","rows":[{"cols":[{"width":589,"items":[{"name":"formItem_updatedBy","labelType":"left","readOnly":"1","height":"21","width":"60","tabindex":"undefined"},{"name":"formItem_createDate","labelType":"left","readOnly":"0","height":"22","width":"154","tabindex":"undefined"}]}]}]},{"collapsible":false,"title":"Einem bestimmten Mitarbeiter oder allen zuordnen","rows":[{"cols":[{"width":589,"items":[{"name":"formItem_assignedTo","labelType":"left","readOnly":"0","height":"21","width":"60","tabindex":"undefined"}]}]}]}]}', '1', '1', '1389611468', '1389611468');