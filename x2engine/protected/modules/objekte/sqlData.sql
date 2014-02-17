/*&*/ALTER TABLE x2_objekte ADD COLUMN c_an_adress TEXT;/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_an_adress', 'Anbieter-Adresse', '1', '1', 'text', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_an_fax VARCHAR(255);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_an_fax', 'Anbieter-Fax', '1', '1', 'varchar', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_an_mail VARCHAR(255);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_an_mail', 'Anbieter-Mail', '1', '1', 'email', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_an_mobile VARCHAR(255);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_an_mobile', 'Anbieter-Handy', '1', '1', 'varchar', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_an_name VARCHAR(255);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_an_name', 'Anbieter-Name', '1', '1', 'link', 'Contacts');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_an_phone VARCHAR(255);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_an_phone', 'Telefon', '1', '1', 'varchar', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_descr TEXT;/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_descr', 'Beschreibung', '1', '1', 'text', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_docs VARCHAR(255);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_docs', 'Zusätzliches Dokument (Server)', '1', '1', 'link', 'Docs');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_expose VARCHAR(255);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_expose', 'Exposé (URL)', '1', '1', 'url', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_extr_doc VARCHAR(255);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_extr_doc', 'Extra Dokument (URL)', '1', '1', 'url', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_factor VARCHAR(255);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_factor', 'Faktor', '1', '1', 'varchar', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_ob_ort VARCHAR(255);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_ob_ort', 'Ort', '1', '1', 'varchar', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_ort TEXT;/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_ort', 'Adresse', '1', '1', 'text', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_plz BIGINT;/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_plz', 'PLZ', '1', '1', 'int', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_price DECIMAL(18,2);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_price', 'Preis', '1', '1', 'currency', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_provision TEXT;/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_provision', 'Prov. Vereinbarung', '1', '1', 'text', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_rendit BOOLEAN NOT NULL DEFAULT 0;/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_rendit', 'Renditeobjekt', '1', '1', 'boolean', '');/*&*/ALTER TABLE x2_objekte ADD COLUMN c_rents DECIMAL(18,2);/*&*/INSERT INTO x2_fields (modelName, fieldName, attributeLabel, modified, custom, type, linkType) VALUES ('objekte', 'c_rents', 'Mieteinnahmen', '1', '1', 'currency', '');/*&*/INSERT INTO x2_form_layouts (model, version, scenario, layout, defaultView, defaultForm, createDate, lastUpdated) VALUES ('Objekte', 'Default', 'Default', '{"version":"1.2","sections":[{"collapsible":false,"title":"Objekt-Details","rows":[{"cols":[{"width":294.083333,"items":[{"name":"formItem_name","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"},{"name":"formItem_c_ort","labelType":"left","readOnly":"0","height":"22","width":"196","tabindex":"undefined"},{"name":"formItem_c_plz","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"},{"name":"formItem_c_ob_ort","labelType":"left","readOnly":"0","height":"22","width":"154","tabindex":"undefined"},{"name":"formItem_c_rendit","labelType":"left","readOnly":"0","height":"22","width":"17","tabindex":"undefined"},{"name":"formItem_c_price","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"},{"name":"formItem_c_rents","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"},{"name":"formItem_c_factor","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"}]},{"width":294,"items":[{"name":"formItem_c_descr","labelType":"left","readOnly":"0","height":"72","width":"156","tabindex":"undefined"},{"name":"formItem_c_provision","labelType":"left","readOnly":"0","height":"82","width":"151","tabindex":"undefined"},{"name":"formItem_id","labelType":"left","readOnly":"1","height":"22","width":"134","tabindex":"undefined"}]}]}]},{"collapsible":false,"title":"Exposé","rows":[{"cols":[{"width":589,"items":[{"name":"formItem_c_expose","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"},{"name":"formItem_c_docs","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"},{"name":"formItem_c_extr_doc","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"}]}]}]},{"collapsible":false,"title":"Anbieter-Details","rows":[{"cols":[{"width":589,"items":[{"name":"formItem_c_an_name","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"},{"name":"formItem_c_an_adress","labelType":"left","readOnly":"0","height":"82","width":"166","tabindex":"undefined"},{"name":"formItem_c_an_phone","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"},{"name":"formItem_c_an_mail","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"},{"name":"formItem_c_an_fax","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"},{"name":"formItem_c_an_mobile","labelType":"left","readOnly":"0","height":"22","width":"134","tabindex":"undefined"}]}]}]},{"collapsible":false,"title":"Intern","rows":[{"cols":[{"width":589,"items":[{"name":"formItem_assignedTo","labelType":"left","readOnly":"0","height":"19","width":"61","tabindex":"undefined"},{"name":"formItem_lastUpdated","labelType":"left","readOnly":"1","height":"22","width":"134","tabindex":"undefined"}]}]}]}]}', '1', '1', '1387649480', '1387649480');