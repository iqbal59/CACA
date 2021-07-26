INSERT INTO `email_template_fields` (`id`, `template_id`, `field_name`, `field_value`, `template_name`) VALUES (NULL, '6', 'login_email', 'login_email', 'new_contact_added');
INSERT INTO `email_template_fields` (`id`, `template_id`, `field_name`, `field_value`, `template_name`) VALUES (NULL, '6', 'login_password', 'login_password', 'new_contact_added');
INSERT INTO `email_templates` (`id`, `relation`, `name`, `subject`, `message`, `from_name`, `status`, `display`, `attachment`) VALUES (NULL, 'appointment', 'new_appointment', 'New Appointment', '<p>Hello {staff_name},<br></p><div>{contact_name} has requested for appointment.<br></div><div><br></div><div>Appointment Date: <strong>{appointment_date}</strong><br></div><div>Appointment Time: <strong>{appointment_time}</strong><strong><br data-mce-bogus="1"></strong></div><div><strong><br data-mce-bogus="1"></strong></div><div>Please contact us for more information.<br></div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{company_name},</strong></div><div>{company_email}</div>', 'Ciuis CRM', '1', '1', '1');
INSERT INTO `email_template_fields` (`id`, `template_id`, `field_name`, `field_value`, `template_name`) VALUES (NULL, '63', 'staff_name', 'staff_name', 'new_appointment'), (NULL, '63', 'customer_name', 'customer_name', 'new_appointment'), (NULL, '63', 'contact_name', 'contact_name', 'new_appointment'), (NULL, '63', 'appointment_date', 'appointment_date', 'new_appointment'), (NULL, '63', 'appointment_time', 'appointment_time', 'new_appointment'), (NULL, '63', 'company_name', 'company_name', 'new_appointment'), (NULL, '63', 'company_email', 'company_email', 'new_appointment');

ALTER TABLE `settings` CHANGE `unitseparator` `thousand_separator` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'auto', CHANGE `currencyposition` `currency_position` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'auto';
ALTER TABLE `settings` ADD `decimal_separator` VARCHAR(50) NULL DEFAULT 'auto' AFTER `currency_position`, ADD `currency_display` VARCHAR(50) NULL DEFAULT 'code' AFTER `decimal_separator`;

ALTER TABLE `settings` ADD `email_type` TINYINT(1) NULL DEFAULT '1' AFTER `languageid`;

UPDATE `email_templates` SET `relation` = 'staff' WHERE `email_templates`.`name` = 'event_reminder';
UPDATE `email_templates` SET `relation` = 'staff' WHERE `email_templates`.`name` = 'new_appointment';

INSERT INTO `languages` (`id`, `langcode`, `name`, `foldername`) VALUES (NULL, 'bg_BG', 'bulgarian', 'bulgarian');