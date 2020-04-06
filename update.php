permission general.tickets.quote
permission admin.login.other
permission admin.bypass.login.other
permission general.notifications.view
permission general.notifications.settings
permission admin.acp.page.cleanup
permission admin.acp.page.errors
permission general.editor.templates

permission entfernt: general.view.ticket.all ==[UMBENANNT ZU]==> mod.view.ticket.all
permission entfernt: general.view.pages
====>[DELETE FROM `kuscheltickets1_group_permissions` WHERE name="general.view.pages"]

config option "seourls"
config option "emailnotifications"
config option "externalURLFavicons"
config option "useDesktopNotification"
config option "faviconextension"
config option "faviconmime"
config option "externalURLTitle"
config option "proxyAllImages"
config option "externalURLWarning"

Ticketcategorys: ALTER TABLE `kuscheltickets1_ticket_categorys` ADD `color` VARCHAR(535) NOT NULL AFTER `categoryName`;
-> UPDATE kuscheltickets1_ticket_categorys SET `color`='blue' WHERE 1;

-> Tabelle
CREATE TABLE kuscheltickets1_notifications (
    notificationID int NOT NULL AUTO_INCREMENT,
    linkIdentifier TEXT NOT NULL,
    content TEXT NOT NULL,
    userID int NOT NULL,
    PRIMARY KEY (notificationID),
    FOREIGN KEY (userID) REFERENCES kuscheltickets1_accounts(userID)
);

CREATE TABLE kuscheltickets1_editortemplates (
    templateID int NOT NULL AUTO_INCREMENT,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    userID int,
    PRIMARY KEY (templateID),
    FOREIGN KEY (userID) REFERENCES kuscheltickets1_accounts(userID) ON DELETE CASCADE
);
ALTER TABLE `kuscheltickets1_editortemplates` ADD `description` TEXT NOT NULL AFTER `content`;

ALTER TABLE `kuscheltickets1_notifications` ADD `time` INT NOT NULL AFTER `userID`;
ALTER TABLE `kuscheltickets1_notifications` ADD `done` INT(1) NOT NULL AFTER `time`;

ALTER TABLE `kuscheltickets1_tickets` ADD `color` VARCHAR(255) NOT NULL AFTER `time`;
UPDATE `kuscheltickets1_tickets` SET `color`='blue' WHERE 1

ALTER TABLE `kuscheltickets1_accounts` ADD `notificationsettings` TEXT NOT NULL AFTER `userGroup`;

ALTER TABLE `kuscheltickets1_pages` ADD `type` INT(1) NOT NULL AFTER `login`;

ALTER TABLE `kuscheltickets1_pages` CHANGE `login` `groups` TEXT NOT NULL;

UPDATE `kuscheltickets1_pages` SET `groups`="[]" WHERE 1