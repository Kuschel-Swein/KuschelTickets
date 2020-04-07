<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
/**
 * du kannst mehrere installationen in einer Datenbank haben, modifiziere hierzu einfach die folgende variable
 */
$KT_N = 1;
/**
 * ================================================================================
 */

define("KT_N", $KT_N);
define("VERSION", "v2.2");
if(file_exists("data/INSTALLED")) {
    header("Location: index.php");
}
session_name("KuschelTickets");
session_start();
if(!isset($_GET['step']) || empty($_GET['step']) || !is_numeric($_GET['step'])) {
    header("Location: install.php?step=1");
} else {
    define("STEP", $_GET['step']);
}
$permissions = [ 'general.tickets.quote', 'mod.view.tickets', 'general.login', 'general.view.tickets.self', 'general.tickets.add', 'general.view.ticket.own', 'mod.view.ticket.all', 'general.tickets.answer', 'general.tickets.deletemessage.own', 'general.tickets.deletemessage.other', 'mod.tickets.close', 'general.tickets.close.own', 'mod.tickets.done', 'general.tickets.done.own', 'mod.tickets.reopen', 'general.tickets.reopen.own', 'mod.tickets.delete', 'general.tickets.delete.own', 'mod.tickets.answers.delete', 'general.tickets.answers.delete.own', 'mod.view.tickets.list', 'general.view.dashboard', 'general.view.faq', 'general.account.manage', 'admin.acp.use', 'admin.acp.page.dashboard', 'admin.acp.page.faq', 'admin.acp.page.faqcategories', 'admin.acp.page.pages', 'admin.acp.page.settings', 'admin.acp.page.accounts', 'admin.bypass.bannable', 'admin.bypass.delete', 'admin.acp.page.groups', 'admin.acp.page.ticketcategories', 'admin.login.other', 'admin.bypass.login.other', 'general.notifications.view', 'general.notifications.settings', 'admin.acp.page.cleanup', 'admin.acp.page.errors', 'general.editor.templates']; 
$pdoerror = "";
if(STEP == 2 && isset($_POST['submit'])) {
    $_SESSION['database'] = $_POST['database'];
    $_SESSION['databasehost'] = $_POST['databasehost'];
    $_SESSION['databaseport'] = $_POST['databaseport'];
    $_SESSION['databaseuser'] = $_POST['databaseuser'];
    $_SESSION['databasepassword'] = $_POST['databasepassword'];
    try {
        $pdo = new PDO("mysql:host=".$_SESSION['databasehost'].":".$_SESSION['databaseport'].";dbname=".$_SESSION['database'], $_SESSION['databaseuser'], $_SESSION['databasepassword']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e){
        $pdoerror = $e->getMessage();
    }
    if($pdoerror == "") {

        // clean the database
        $pdo->query("DROP TABLE IF EXISTS kuscheltickets".KT_N."_accounts");
        $pdo->query("DROP TABLE IF EXISTS kuscheltickets".KT_N."_faq");
        $pdo->query("DROP TABLE IF EXISTS kuscheltickets".KT_N."_faq_categorys");
        $pdo->query("DROP TABLE IF EXISTS kuscheltickets".KT_N."_groups");
        $pdo->query("DROP TABLE IF EXISTS kuscheltickets".KT_N."_group_permissions");
        $pdo->query("DROP TABLE IF EXISTS kuscheltickets".KT_N."_pages");
        $pdo->query("DROP TABLE IF EXISTS kuscheltickets".KT_N."_tickets");
        $pdo->query("DROP TABLE IF EXISTS kuscheltickets".KT_N."_ticket_answers");
        $pdo->query("DROP TABLE IF EXISTS kuscheltickets".KT_N."_ticket_categorys");
        $pdo->query("DROP TABLE IF EXISTS kuscheltickets".KT_N."_notifications");
        $pdo->query("DROP TABLE IF EXISTS kuscheltickets".KT_N."_editortemplates");

        // create new tables
        $pdo->query("CREATE TABLE kuscheltickets".KT_N."_accounts (
            `userID` int(11) NOT NULL,
            `username` varchar(255) NOT NULL,
            `password` text NOT NULL,
            `email` varchar(255) NOT NULL,
            `token` text NOT NULL,
            `userGroup` int(11) NOT NULL,
            `notificationsettings` text NOT NULL,
            `banned` int(11) NOT NULL,
            `banreason` text,
            `password_reset` int(11) DEFAULT NULL,
            `email_change_email` text,
            `email_change_time` int(11) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $pdo->query("CREATE TABLE kuscheltickets".KT_N."_faq (
            `faqID` int(11) NOT NULL,
            `question` text NOT NULL,
            `answer` text NOT NULL,
            `category` int(11) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $pdo->query("CREATE TABLE kuscheltickets".KT_N."_faq_categorys (
            `categoryID` int(11) NOT NULL,
            `name` text NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $pdo->query("CREATE TABLE kuscheltickets".KT_N."_groups (
            `groupID` int(11) NOT NULL,
            `name` varchar(255) NOT NULL,
            `badge` text NOT NULL,
            `system` int(11) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $pdo->query("CREATE TABLE kuscheltickets".KT_N."_group_permissions (
            `permissionID` int(11) NOT NULL,
            `groupID` int(11) NOT NULL,
            `name` text NOT NULL,
            `value` int(11) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $pdo->query("CREATE TABLE kuscheltickets".KT_N."_pages (
            `pageID` int(11) NOT NULL,
            `identifier` varchar(255) NOT NULL,
            `url` varchar(255) NOT NULL,
            `title` text NOT NULL,
            `content` text NOT NULL,
            `system` int(11) DEFAULT NULL,
            `groups` text NOT NULL,
            `type` int(1) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $pdo->query("CREATE TABLE kuscheltickets".KT_N."_tickets (
            `ticketID` int(11) NOT NULL,
            `creator` int(11) NOT NULL,
            `title` text NOT NULL,
            `category` text NOT NULL,
            `content` text NOT NULL,
            `state` int(11) NOT NULL,
            `time` int(11) NOT NULL,
            `color` varchar(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $pdo->query("CREATE TABLE kuscheltickets".KT_N."_ticket_answers (
            `answerID` int(11) NOT NULL,
            `ticketID` int(11) NOT NULL,
            `creator` int(11) NOT NULL,
            `content` text NOT NULL,
            `time` int(11) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $pdo->query("CREATE TABLE kuscheltickets".KT_N."_ticket_categorys (
            `categoryID` int(11) NOT NULL,
            `categoryName` text NOT NULL,
            `color` varchar(535) NOT NULL,
            `inputs` text NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $pdo->query("CREATE TABLE kuscheltickets".KT_N."_notifications (
            `notificationID` int(11) NOT NULL,
            `linkIdentifier` text NOT NULL,
            `content` text NOT NULL,
            `userID` int(11) NOT NULL,
            `time` int(11) NOT NULL,
            `done` int(1) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // alter the tables with the (primary) keys
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_accounts ADD PRIMARY KEY (`userID`);");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_editortemplates ADD PRIMARY KEY (`templateID`), ADD KEY `userID` (`userID`);");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_faq ADD PRIMARY KEY (`faqID`), ADD KEY `category` (`category`);");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_faq_categorys ADD PRIMARY KEY (`categoryID`);");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_groups ADD PRIMARY KEY (`groupID`);");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_group_permissions ADD PRIMARY KEY (`permissionID`), ADD KEY `groupID` (`groupID`);");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_notifications ADD PRIMARY KEY (`notificationID`), ADD KEY `userID` (`userID`);");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_pages ADD PRIMARY KEY (`pageID`);");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_tickets ADD PRIMARY KEY (`ticketID`), ADD KEY `creator` (`creator`);");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_ticket_answers ADD PRIMARY KEY (`answerID`), ADD KEY `ticketID` (`ticketID`);");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_ticket_categorys ADD PRIMARY KEY (`categoryID`);");
        
        
        // alter the tables with auto increment
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_accounts MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_faq MODIFY `faqID` int(11) NOT NULL AUTO_INCREMENT;");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_faq_categorys MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT;");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_groups MODIFY `groupID` int(11) NOT NULL AUTO_INCREMENT;");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_group_permissions MODIFY `permissionID` int(11) NOT NULL AUTO_INCREMENT;");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_pages MODIFY `pageID` int(11) NOT NULL AUTO_INCREMENT;");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_tickets MODIFY `ticketID` int(11) NOT NULL AUTO_INCREMENT;");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_ticket_answers MODIFY `answerID` int(11) NOT NULL AUTO_INCREMENT;");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_ticket_categorys MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT;");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_notifications MODIFY `notificationID` int(11) NOT NULL AUTO_INCREMENT;");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_editortemplates MODIFY `templateID` int(11) NOT NULL AUTO_INCREMENT;");

        // foreign keys
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_notifications ADD CONSTRAINT kuscheltickets".KT_N."_notifications_ibfk_1 FOREIGN KEY (`userID`) REFERENCES kuscheltickets".KT_N."_accounts (`userID`) ON DELETE CASCADE;");
        $pdo->query("ALTER TABLE kuscheltickets".KT_N."_editortemplates ADD CONSTRAINT kuscheltickets".KT_N."_editortemplates_ibfk_1 FOREIGN KEY (`userID`) REFERENCES kuscheltickets".KT_N."_accounts (`userID`) ON DELETE CASCADE;");

        // insert the required data
        $pdo->query("INSERT INTO kuscheltickets".KT_N."_pages (`pageID`, `identifier`, `url`, `title`, `content`, `system`, `groups`, `type`) VALUES (1, 'legal-notice', 'legal-notice', 'Datenschutzerklärung', '<h1>Datenschutzerklärung</h1>\n<h2>1. Datenschutz auf einen Blick</h2>\n<h3>Allgemeine Hinweise</h3>\n<p>Die folgenden Hinweise geben einen einfachen Überblick darüber, was mit Ihren personenbezogenen Daten passiert, wenn Sie diese Website besuchen. Personenbezogene Daten sind alle Daten, mit denen Sie persönlich identifiziert werden können. Ausführliche Informationen zum Thema Datenschutz entnehmen Sie unserer unter diesem Text aufgeführten Datenschutzerklärung.</p>\n<h3>Datenerfassung auf dieser Website</h3>\n<p><strong>Wer ist verantwortlich für die Datenerfassung auf dieser Website?</strong></p>\n<p>Die Datenverarbeitung auf dieser Website erfolgt durch den Websitebetreiber. Dessen Kontaktdaten können Sie dem Impressum dieser Website entnehmen.</p>\n<p><strong>Wie erfassen wir Ihre Daten?</strong></p>\n<p>Ihre Daten werden zum einen dadurch erhoben, dass Sie uns diese mitteilen. Hierbei kann es sich z. B. um Daten handeln, die Sie in ein Kontaktformular eingeben.</p>\n<p>Andere Daten werden automatisch oder nach Ihrer Einwilligung beim Besuch der Website durch unsere IT-Systeme erfasst. Das sind vor allem technische Daten (z. B. Internetbrowser, Betriebssystem oder Uhrzeit des Seitenaufrufs). Die Erfassung dieser Daten erfolgt automatisch, sobald Sie diese Website betreten.</p>\n<p><strong>Wofür nutzen wir Ihre Daten?</strong></p>\n<p>Ein Teil der Daten wird erhoben, um eine fehlerfreie Bereitstellung der Website zu gewährleisten. Andere Daten können zur Analyse Ihres Nutzerverhaltens verwendet werden.</p>\n<p><strong>Welche Rechte haben Sie bezüglich Ihrer Daten?</strong></p>\n<p>Sie haben jederzeit das Recht, unentgeltlich Auskunft über Herkunft, Empfänger und Zweck Ihrer gespeicherten personenbezogenen Daten zu erhalten. Sie haben außerdem ein Recht, die Berichtigung oder Löschung dieser Daten zu verlangen. Wenn Sie eine Einwilligung zur Datenverarbeitung erteilt haben, können Sie diese Einwilligung jederzeit für die Zukunft widerrufen. Außerdem haben Sie das Recht, unter bestimmten Umständen die Einschränkung der Verarbeitung Ihrer personenbezogenen Daten zu verlangen. Des Weiteren steht Ihnen ein Beschwerderecht bei der zuständigen Aufsichtsbehörde zu.</p>\n<p>Hierzu sowie zu weiteren Fragen zum Thema Datenschutz können Sie sich jederzeit unter der im Impressum angegebenen Adresse an uns wenden.</p>\n<h2>2. Hosting und Content Delivery Networks (CDN)</h2>\n<h3>Externes Hosting</h3>\n<p>Diese Website wird bei einem externen Dienstleister gehostet (Hoster). Die personenbezogenen Daten, die auf dieser Website erfasst werden, werden auf den Servern des Hosters gespeichert. Hierbei kann es sich v. a. um IP-Adressen, Kontaktanfragen, Meta- und Kommunikationsdaten, Vertragsdaten, Kontaktdaten, Namen, Webseitenzugriffe und sonstige Daten, die über eine Website generiert werden, handeln.</p>\n<p>Der Einsatz des Hosters erfolgt zum Zwecke der Vertragserfüllung gegenüber unseren potenziellen und bestehenden Kunden (Art. 6 Abs. 1 lit. b DSGVO) und im Interesse einer sicheren, schnellen und effizienten Bereitstellung unseres Online-Angebots durch einen professionellen Anbieter (Art. 6 Abs. 1 lit. f DSGVO).</p>\n<p>Unser Hoster wird Ihre Daten nur insoweit verarbeiten, wie dies zur Erfüllung seiner Leistungspflichten erforderlich ist und unsere Weisungen in Bezug auf diese Daten befolgen.</p>\n<h2>3. Allgemeine Hinweise und Pflichtinformationen</h2>\n<h3>Datenschutz</h3>\n<p>Die Betreiber dieser Seiten nehmen den Schutz Ihrer persönlichen Daten sehr ernst. Wir behandeln Ihre personenbezogenen Daten vertraulich und entsprechend der gesetzlichen Datenschutzvorschriften sowie dieser Datenschutzerklärung.</p>\n<p>Wenn Sie diese Website benutzen, werden verschiedene personenbezogene Daten erhoben. Personenbezogene Daten sind Daten, mit denen Sie persönlich identifiziert werden können. Die vorliegende Datenschutzerklärung erläutert, welche Daten wir erheben und wofür wir sie nutzen. Sie erläutert auch, wie und zu welchem Zweck das geschieht.</p>\n<p>Wir weisen darauf hin, dass die Datenübertragung im Internet (z. B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich.</p>\n<h3>Hinweis zur verantwortlichen Stelle</h3>\n<p>Die verantwortliche Stelle für die Datenverarbeitung auf dieser Website ist:</p>\n<p>[Voller Namen bzw. die vollständige Unternehmensbezeichnung des Website-Betreibers sowie die vollständige Anschrift]</p>\n<p>Telefon: [Telefonnummer der verantwortlichen Stelle]<br />E-Mail: [E-Mail-Adresse der verantwortlichen Stelle]</p>\n<p>Verantwortliche Stelle ist die natürliche oder juristische Person, die allein oder gemeinsam mit anderen über die Zwecke und Mittel der Verarbeitung von personenbezogenen Daten (z. B. Namen, E-Mail-Adressen o. Ä.) entscheidet.</p>\n<h3>Widerruf Ihrer Einwilligung zur Datenverarbeitung</h3>\n<p>Viele Datenverarbeitungsvorgänge sind nur mit Ihrer ausdrücklichen Einwilligung möglich. Sie können eine bereits erteilte Einwilligung jederzeit widerrufen. Dazu reicht eine formlose Mitteilung per E-Mail an uns. Die Rechtmäßigkeit der bis zum Widerruf erfolgten Datenverarbeitung bleibt vom Widerruf unberührt.</p>\n<h3>Widerspruchsrecht gegen die Datenerhebung in besonderen Fällen sowie gegen Direktwerbung (Art. 21 DSGVO)</h3>\n<p>WENN DIE DATENVERARBEITUNG AUF GRUNDLAGE VON ART. 6 ABS. 1 LIT. E ODER F DSGVO ERFOLGT, HABEN SIE JEDERZEIT DAS RECHT, AUS GRÜNDEN, DIE SICH AUS IHRER BESONDEREN SITUATION ERGEBEN, GEGEN DIE VERARBEITUNG IHRER PERSONENBEZOGENEN DATEN WIDERSPRUCH EINZULEGEN; DIES GILT AUCH FÜR EIN AUF DIESE BESTIMMUNGEN GESTÜTZTES PROFILING. DIE JEWEILIGE RECHTSGRUNDLAGE, AUF DENEN EINE VERARBEITUNG BERUHT, ENTNEHMEN SIE DIESER DATENSCHUTZERKLÄRUNG. WENN SIE WIDERSPRUCH EINLEGEN, WERDEN WIR IHRE BETROFFENEN PERSONENBEZOGENEN DATEN NICHT MEHR VERARBEITEN, ES SEI DENN, WIR KÖNNEN ZWINGENDE SCHUTZWÜRDIGE GRÜNDE FÜR DIE VERARBEITUNG NACHWEISEN, DIE IHRE INTERESSEN, RECHTE UND FREIHEITEN ÜBERWIEGEN ODER DIE VERARBEITUNG DIENT DER GELTENDMACHUNG, AUSÜBUNG ODER VERTEIDIGUNG VON RECHTSANSPRÜCHEN (WIDERSPRUCH NACH ART. 21 ABS. 1 DSGVO).</p>\n<p>WERDEN IHRE PERSONENBEZOGENEN DATEN VERARBEITET, UM DIREKTWERBUNG ZU BETREIBEN, SO HABEN SIE DAS RECHT, JEDERZEIT WIDERSPRUCH GEGEN DIE VERARBEITUNG SIE BETREFFENDER PERSONENBEZOGENER DATEN ZUM ZWECKE DERARTIGER WERBUNG EINZULEGEN; DIES GILT AUCH FÜR DAS PROFILING, SOWEIT ES MIT SOLCHER DIREKTWERBUNG IN VERBINDUNG STEHT. WENN SIE WIDERSPRECHEN, WERDEN IHRE PERSONENBEZOGENEN DATEN ANSCHLIESSEND NICHT MEHR ZUM ZWECKE DER DIREKTWERBUNG VERWENDET (WIDERSPRUCH NACH ART. 21 ABS. 2 DSGVO).</p>\n<h3>Beschwerderecht bei der zuständigen Aufsichtsbehörde</h3>\n<p>Im Falle von Verstößen gegen die DSGVO steht den Betroffenen ein Beschwerderecht bei einer Aufsichtsbehörde, insbesondere in dem Mitgliedstaat ihres gewöhnlichen Aufenthalts, ihres Arbeitsplatzes oder des Orts des mutmaßlichen Verstoßes zu. Das Beschwerderecht besteht unbeschadet anderweitiger verwaltungsrechtlicher oder gerichtlicher Rechtsbehelfe.</p>\n<h3>Recht auf Datenübertragbarkeit</h3>\n<p>Sie haben das Recht, Daten, die wir auf Grundlage Ihrer Einwilligung oder in Erfüllung eines Vertrags automatisiert verarbeiten, an sich oder an einen Dritten in einem gängigen, maschinenlesbaren Format aushändigen zu lassen. Sofern Sie die direkte Übertragung der Daten an einen anderen Verantwortlichen verlangen, erfolgt dies nur, soweit es technisch machbar ist.</p>\n<h3>SSL- bzw. TLS-Verschlüsselung</h3>\n<p>Diese Seite nutzt aus Sicherheitsgründen und zum Schutz der Übertragung vertraulicher Inhalte, wie zum Beispiel Bestellungen oder Anfragen, die Sie an uns als Seitenbetreiber senden, eine SSL- bzw. TLS-Verschlüsselung. Eine verschlüsselte Verbindung erkennen Sie daran, dass die Adresszeile des Browsers von „http://“ auf „https://“ wechselt und an dem Schloss-Symbol in Ihrer Browserzeile.</p>\n<p>Wenn die SSL- bzw. TLS-Verschlüsselung aktiviert ist, können die Daten, die Sie an uns übermitteln, nicht von Dritten mitgelesen werden.</p>\n<h3>Auskunft, Löschung und Berichtigung</h3>\n<p>Sie haben im Rahmen der geltenden gesetzlichen Bestimmungen jederzeit das Recht auf unentgeltliche Auskunft über Ihre gespeicherten personenbezogenen Daten, deren Herkunft und Empfänger und den Zweck der Datenverarbeitung und ggf. ein Recht auf Berichtigung oder Löschung dieser Daten. Hierzu sowie zu weiteren Fragen zum Thema personenbezogene Daten können Sie sich jederzeit unter der im Impressum angegebenen Adresse an uns wenden.</p>\n<h3>Recht auf Einschränkung der Verarbeitung</h3>\n<p>Sie haben das Recht, die Einschränkung der Verarbeitung Ihrer personenbezogenen Daten zu verlangen. Hierzu können Sie sich jederzeit unter der im Impressum angegebenen Adresse an uns wenden. Das Recht auf Einschränkung der Verarbeitung besteht in folgenden Fällen:</p>\n<ul><li>Wenn Sie die Richtigkeit Ihrer bei uns gespeicherten personenbezogenen Daten bestreiten, benötigen wir in der Regel Zeit, um dies zu überprüfen. Für die Dauer der Prüfung haben Sie das Recht, die Einschränkung der Verarbeitung Ihrer personenbezogenen Daten zu verlangen.</li>\n<li>Wenn die Verarbeitung Ihrer personenbezogenen Daten unrechtmäßig geschah/geschieht, können Sie statt der Löschung die Einschränkung der Datenverarbeitung verlangen.</li>\n<li>Wenn wir Ihre personenbezogenen Daten nicht mehr benötigen, Sie sie jedoch zur Ausübung, Verteidigung oder Geltendmachung von Rechtsansprüchen benötigen, haben Sie das Recht, statt der Löschung die Einschränkung der Verarbeitung Ihrer personenbezogenen Daten zu verlangen.</li>\n<li>Wenn Sie einen Widerspruch nach Art. 21 Abs. 1 DSGVO eingelegt haben, muss eine Abwägung zwischen Ihren und unseren Interessen vorgenommen werden. Solange noch nicht feststeht, wessen Interessen überwiegen, haben Sie das Recht, die Einschränkung der Verarbeitung Ihrer personenbezogenen Daten zu verlangen.</li>\n</ul><p>Wenn Sie die Verarbeitung Ihrer personenbezogenen Daten eingeschränkt haben, dürfen diese Daten – von ihrer Speicherung abgesehen – nur mit Ihrer Einwilligung oder zur Geltendmachung, Ausübung oder Verteidigung von Rechtsansprüchen oder zum Schutz der Rechte einer anderen natürlichen oder juristischen Person oder aus Gründen eines wichtigen öffentlichen Interesses der Europäischen Union oder eines Mitgliedstaats verarbeitet werden.</p>\n<h2>4. Datenerfassung auf dieser Website</h2>\n<h3>Cookies</h3>\n<p>Unsere Internetseiten verwenden so genannte „Cookies“. Cookies sind kleine Textdateien und richten auf Ihrem Endgerät keinen Schaden an. Sie werden entweder vorübergehend für die Dauer einer Sitzung (Session-Cookies) oder dauerhaft (permanente Cookies) auf Ihrem Endgerät gespeichert. Session-Cookies werden nach Ende Ihres Besuchs automatisch gelöscht. Permanente Cookies bleiben auf Ihrem Endgerät gespeichert, bis Sie diese selbst löschen oder eine automatische Löschung durch Ihren Webbrowser erfolgt.</p>\n<p>Teilweise können auch Cookies von Drittunternehmen auf Ihrem Endgerät gespeichert werden, wenn Sie unsere Seite betreten (Third-Party-Cookies). Diese ermöglichen uns oder Ihnen die Nutzung bestimmter Dienstleistungen des Drittunternehmens (z.B. Cookies zur Abwicklung von Zahlungsdienstleistungen).</p>\n<p>Cookies haben verschiedene Funktionen. Zahlreiche Cookies sind technisch notwendig, da bestimmte Webseitenfunktionen ohne diese nicht funktionieren würden (z.B. die Warenkorbfunktion oder die Anzeige von Videos). Andere Cookies dienen dazu, das Nutzerverhalten auszuwerten oder Werbung anzuzeigen.</p>\n<p>Cookies, die zur Durchführung des elektronischen Kommunikationsvorgangs (notwendige Cookies) oder zur Bereitstellung bestimmter, von Ihnen erwünschter Funktionen (funktionale Cookies, z. B. für die Warenkorbfunktion) oder zur Optimierung der Webseite (z.B. Cookies zur Messung des Webpublikums) erforderlich sind, werden auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO gespeichert, sofern keine andere Rechtsgrundlage angegeben wird. Der Websitebetreiber hat ein berechtigtes Interesse an der Speicherung von Cookies zur technisch fehlerfreien und optimierten Bereitstellung seiner Dienste. Sofern eine Einwilligung zur Speicherung von Cookies abgefragt wurde, erfolgt die Speicherung der betreffenden Cookies ausschließlich auf Grundlage dieser Einwilligung (Art. 6 Abs. 1 lit. a DSGVO); die Einwilligung ist jederzeit widerrufbar.</p>\n<p>Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies informiert werden und Cookies nur im Einzelfall erlauben, die Annahme von Cookies für bestimmte Fälle oder generell ausschließen sowie das automatische Löschen der Cookies beim Schließen des Browsers aktivieren. Bei der Deaktivierung von Cookies kann die Funktionalität dieser Website eingeschränkt sein.</p>\n<p>Soweit Cookies von Drittunternehmen oder zu Analysezwecken eingesetzt werden, werden wir Sie hierüber im Rahmen dieser Datenschutzerklärung gesondert informieren und ggf. eine Einwilligung abfragen.</p>\n<h3>Server-Log-Dateien</h3>\n<p>Der Provider der Seiten erhebt und speichert automatisch Informationen in so genannten Server-Log-Dateien, die Ihr Browser automatisch an uns übermittelt. Dies sind:</p>\n<ul><li>Browsertyp und Browserversion</li>\n<li>verwendetes Betriebssystem</li>\n<li>Referrer URL</li>\n<li>Hostname des zugreifenden Rechners</li>\n<li>Uhrzeit der Serveranfrage</li>\n<li>IP-Adresse</li>\n</ul><p>Eine Zusammenführung dieser Daten mit anderen Datenquellen wird nicht vorgenommen.</p>\n<p>Die Erfassung dieser Daten erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Der Websitebetreiber hat ein berechtigtes Interesse an der technisch fehlerfreien Darstellung und der Optimierung seiner Website – hierzu müssen die Server-Log-Files erfasst werden.</p>\n<h3>Kommentarfunktion auf dieser Website</h3>\n<p>Für die Kommentarfunktion auf dieser Seite werden neben Ihrem Kommentar auch Angaben zum Zeitpunkt der Erstellung des Kommentars, Ihre E-Mail-Adresse und, wenn Sie nicht anonym posten, der von Ihnen gewählte Nutzername gespeichert.</p>\n<p><strong>Speicherung der IP-Adresse</strong></p>\n<p>Unsere Kommentarfunktion speichert die IP-Adressen der Nutzer, die Kommentare verfassen. Da wir Kommentare auf dieser Website nicht vor der Freischaltung prüfen, benötigen wir diese Daten, um im Falle von Rechtsverletzungen wie Beleidigungen oder Propaganda gegen den Verfasser vorgehen zu können.</p>\n<p><strong>Speicherdauer der Kommentare</strong></p>\n<p>Die Kommentare und die damit verbundenen Daten (z. B. IP-Adresse) werden gespeichert und verbleiben auf dieser Website, bis der kommentierte Inhalt vollständig gelöscht wurde oder die Kommentare aus rechtlichen Gründen gelöscht werden müssen (z. B. beleidigende Kommentare).</p>\n<p><strong>Rechtsgrundlage</strong></p>\n<p>Die Speicherung der Kommentare erfolgt auf Grundlage Ihrer Einwilligung (Art. 6 Abs. 1 lit. a DSGVO). Sie können eine von Ihnen erteilte Einwilligung jederzeit widerrufen. Dazu reicht eine formlose Mitteilung per E-Mail an uns. Die Rechtmäßigkeit der bereits erfolgten Datenverarbeitungsvorgänge bleibt vom Widerruf unberührt.</p>\n<h2>5. Plugins und Tools</h2>\n<h3>Google reCAPTCHA</h3>\n<p>Wir nutzen „Google reCAPTCHA“ (im Folgenden „reCAPTCHA“) auf dieser Website. Anbieter ist die Google Ireland Limited („Google“), Gordon House, Barrow Street, Dublin 4, Irland.</p>\n<p>Mit reCAPTCHA soll überprüft werden, ob die Dateneingabe auf dieser Website (z. B. in einem Kontaktformular) durch einen Menschen oder durch ein automatisiertes Programm erfolgt. Hierzu analysiert reCAPTCHA das Verhalten des Websitebesuchers anhand verschiedener Merkmale. Diese Analyse beginnt automatisch, sobald der Websitebesucher die Website betritt. Zur Analyse wertet reCAPTCHA verschiedene Informationen aus (z. B. IP-Adresse, Verweildauer des Websitebesuchers auf der Website oder vom Nutzer getätigte Mausbewegungen). Die bei der Analyse erfassten Daten werden an Google weitergeleitet.</p>\n<p>Die reCAPTCHA-Analysen laufen vollständig im Hintergrund. Websitebesucher werden nicht darauf hingewiesen, dass eine Analyse stattfindet.</p>\n<p>Die Speicherung und Analyse der Daten erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Der Websitebetreiber hat ein berechtigtes Interesse daran, seine Webangebote vor missbräuchlicher automatisierter Ausspähung und vor SPAM zu schützen. Sofern eine entsprechende Einwilligung abgefragt wurde (z. B. eine Einwilligung zur Speicherung von Cookies), erfolgt die Verarbeitung ausschließlich auf Grundlage von Art. 6 Abs. 1 lit. a DSGVO; die Einwilligung ist jederzeit widerrufbar.</p>\n<p>Weitere Informationen zu Google reCAPTCHA entnehmen Sie den Google-Datenschutzbestimmungen und den Google Nutzungsbedingungen unter folgenden Links: <a href=\"https://policies.google.com/privacy?hl=de\" target=\"_blank\" rel=\"noreferrer noopener\">https://policies.google.com/privacy?hl=de</a> und <a href=\"https://policies.google.com/terms?hl=de\" target=\"_blank\" rel=\"noreferrer noopener\">https://policies.google.com/terms?hl=de</a>.</p>\n<p>Quelle: <a href=\"https://www.e-recht24.de\">eRecht24</a></p>', 1, '[]', 0),");
        $pdo->query("INSERT INTO kuscheltickets".KT_N."_groups (`groupID`, `name`, `badge`, `system`) VALUES (3, 'nicht Aktiviert', '<div class=\"ui black label groupBadge\">%NAME%</div>', 1);");
        $pdo->query("INSERT INTO kuscheltickets".KT_N."_groups (`groupID`, `name`, `badge`, `system`) VALUES (1, 'Administrator', '<div class=\"ui red label groupBadge\">%NAME%</div>', 1);");
        $pdo->query("INSERT INTO kuscheltickets".KT_N."_groups (`groupID`, `name`, `badge`, `system`) VALUES (2, 'Benutzer', '<div class=\"ui blue label groupBadge\">%NAME%</div>', 1);");
        
        foreach($permissions as $permission) {
            $stmt = $pdo->prepare("INSERT INTO kuscheltickets".KT_N."_group_permissions(`groupID`, `name`, `value`) VALUES (3, ?, 0)");
            $stmt->execute([$permission]);
            $stmt = $pdo->prepare("INSERT INTO kuscheltickets".KT_N."_group_permissions(`groupID`, `name`, `value`) VALUES (2, ?, 0)");
            $stmt->execute([$permission]);
            $stmt = $pdo->prepare("INSERT INTO kuscheltickets".KT_N."_group_permissions(`groupID`, `name`, `value`) VALUES (1, ?, 1)");
            $stmt->execute([$permission]);
        }
        header("Location: install.php?step=3");
        }
}

$mailexception = "";
if(STEP == 3 && isset($_POST['submit'])) {
    require("lib/PHPMailer/Exception.php");
    require("lib/PHPMailer/PHPMailer.php");
    require("lib/PHPMailer/SMTP.php");
    $mail = new PHPMailer();
    $auth = (isset($_POST['smtpauth'])) ? true : false;
    try {                    
        $mail->isSMTP(); 
        $mail->CharSet = "UTF-8";                                          
        $mail->Host = $_POST['smtphost'];                    
        $mail->SMTPAuth = $auth;   
        if($auth) {                           
            $mail->Username = $_POST['smtpusername'];                    
            $mail->Password = $_POST['smtppassword'];   
        }                            
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
        $mail->Port = $_POST['smtpport'];                                 
        $mail->setFrom($_POST['smtpfrom'], "KuschelTickets - SMTP Test");
        $mail->addAddress($_POST['smtpfrom'], "KuschelTickets SMTP Server");  
        $mail->isHTML(true);                               
        $mail->Subject = "SMTP Server Test";
        $mail->Body = "<div style='font-family: Arial, sans-serif'>Dies ist nur der Test deines SMTP Servers, du kannst diese E-Mail ignorieren.</div>";
        $mail->send();
    } catch (Exception $e) {
        $mailexception = $e;
    }
    if($mailexception == "") {
        $file = fopen("config.php", "w");
        fwrite($file, '<?php' . PHP_EOL . '  /*' . PHP_EOL . '      Automatisch erstellte Config-Datei    ' . PHP_EOL . '      Erstellt am ' . date('d.m.Y  H:i:s') . '    ' . PHP_EOL . '  */'.PHP_EOL.
        '$database = array('.PHP_EOL.
        '    "database" => "'.$_SESSION['database'].'",'.PHP_EOL.
        '    "host" => "'.$_SESSION['databasehost'].'",'.PHP_EOL.
        '    "port" => '.$_SESSION['databaseport'].','.PHP_EOL.
        '    "user" => "'.$_SESSION['databaseuser'].'",'.PHP_EOL.
        '    "password" => "'.$_SESSION['databasepassword'].'"'.PHP_EOL.
        ');'.PHP_EOL.
        ''.PHP_EOL.
        '$config = array('.PHP_EOL.
        '    "version" => "'.VERSION.'",'.PHP_EOL.
        '    "pagetitle" => "KuschelTickets",'.PHP_EOL.
        '    "databaseaccess" => $database,'.PHP_EOL.
        '    "db" => new PDO("mysql:host=".$database["host"].":".$database["port"].";dbname=".$database["database"], $database["user"], $database["password"]),'.PHP_EOL.
        '    "debugging" => array('.PHP_EOL.
        '        "templates" => false,'.PHP_EOL.
        '        "php" => false, // stellst du dies auf true, kann es zu Anzeigefehlern kommen'.PHP_EOL.
        '        "database" => false'.PHP_EOL.
        '    ),'.PHP_EOL.
        '    "cookie" => "KuschelTickets",'.PHP_EOL.
        '    "cookienotice" => true,'.PHP_EOL.
        '    "seourls" => false,'.PHP_EOL.
        '    "faviconextension" => "png",'.PHP_EOL.
        '    "externalURLTitle" => true,'.PHP_EOL.
        '    "faviconmime" => "image/png",'.PHP_EOL.
        '    "proxyAllImages" => true,'.PHP_EOL.
        '    "externalURLFavicons" => true,'.PHP_EOL.
        '    "externalURLWarning" => true,'.PHP_EOL.
        '    "useDesktopNotification" => true,'.PHP_EOL.
        '    "emailnotifications" => true,'.PHP_EOL.
        '    "adminmail" => "admin@example.com",'.PHP_EOL.
        '    "mail" => array('.PHP_EOL.
        '        "host" => "'.$_POST['smtphost'].'",'.PHP_EOL.
        '        "auth" => '.$auth.','.PHP_EOL.
        '        "port" => '.$_POST['smtpport'].','.PHP_EOL.
        '        "username" => "'.$_POST['smtpusername'].'",'.PHP_EOL.
        '        "password" => "'.$_POST['smtppassword'].'",'.PHP_EOL.
        '        "from" => "'.$_POST['smtpfrom'].'"'.PHP_EOL.
        '    ),'.PHP_EOL.
        '    "state_colors" => array('.PHP_EOL.
        '        "closed" => "red",'.PHP_EOL.
        '        "open" => "blue",'.PHP_EOL.
        '        "done" => "green"'.PHP_EOL.
        '    ),'.PHP_EOL.
        '    "recaptcha" => array('.PHP_EOL.
        '        "public" => "",'.PHP_EOL.
        '        "secret" => "",'.PHP_EOL.
        '        "use" => false,'.PHP_EOL.
        '        "version" => 2,'.PHP_EOL.
        '        "cases" => []'.PHP_EOL.
        '    )'.PHP_EOL.
        ');'.PHP_EOL.
        ''.PHP_EOL.
        'define("KT_N", "'.KT_N.'");'.PHP_EOL.
        '');
        fclose($file);
        header("Location: install.php?step=4");
    }
}

$error4 = "";
if(STEP == 4 && isset($_POST['submit'])) {
    if($_POST['password'] == $_POST['password_confirm']) {
        $pdo = new PDO("mysql:host=".$_SESSION['databasehost'].":".$_SESSION['databaseport'].";dbname=".$_SESSION['database'], $_SESSION['databaseuser'], $_SESSION['databasepassword']);
        $stmt = $pdo->prepare("INSERT INTO kuscheltickets".KT_N."_accounts(`username`, `password`, `email`, `token`, `userGroup`, `banned`) VALUES (?, ?, ?, ?, 1, 0)");
        $username = strip_tags($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $email = strip_tags($_POST['email']);
        $characters = "0123456789";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < 60; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $token = $randomString;
        $stmt->execute([$username, $password, $email, $token]);
        header("Location: install.php?step=5");
    } else {
        $error4 = "<div class='ui error message display-block'>Deine beiden Passwörter stimmen nicht überein.</div>";
    }
}
if(STEP == 5) {
    $file = fopen("data/INSTALLED", "w");
    fclose($file);
    session_destroy();
    
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Schritt <?php echo STEP; ?> - Installation - KuschelTickets</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato&display=swap">
        <link rel="stylesheet" href="assets/semantic.min.css"> 
        <link rel="stylesheet" href="assets/master.css">    
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="assets/semantic.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="ui grid container">
            <div class="ui sixteen wide column segment">
                <div class="ui ordered steps" style="overflow-x: scroll">
                    <div class="<?php if(STEP == 1) { echo "active"; } else if(STEP > 1) { echo "completed"; } else { echo "disabled"; } ?> step">
                        <div class="content">
                        <div class="title">Lizensbestimmungen</div>
                        <div class="description">Akzeptiere die Lizenzbestimmungen</div>
                        </div>
                    </div>
                    <div class="<?php if(STEP == 2) { echo "active"; } else if(STEP > 2) { echo "completed"; } else { echo "disabled"; } ?> step">
                        <div class="content">
                        <div class="title">Datenbank</div>
                        <div class="description">Datenbank Einstellungen und Einrichtung</div>
                        </div>
                    </div>
                    <div class="<?php if(STEP == 3) { echo "active"; } else if(STEP > 3) { echo "completed"; } else { echo "disabled"; } ?> step">
                        <div class="content">
                        <div class="title">SMTP Server</div>
                        <div class="description">Zugangsdaten und Einstellungen</div>
                        </div>
                    </div>
                    <div class="<?php if(STEP == 4) { echo "active"; } else if(STEP > 4) { echo "completed"; } else { echo "disabled"; } ?> step">
                        <div class="content">
                        <div class="title">Administrator</div>
                        <div class="description">Benutzerkonto Erstellung</div>
                        </div>
                    </div>
                    <div class="<?php if(STEP == 5) { echo "active"; } else if(STEP > 5) { echo "completed"; } else { echo "disabled"; } ?> step">
                        <div class="content">
                        <div class="title">Abschließen</div>
                        <div class="description">Installation beenden</div>
                        </div>
                    </div>
                </div>
                <br>
        <?php
            switch(STEP) {
                case 5:
        ?>
        <p>Du hast die Installation von KuschelTickets erfolgreich beendet und dein Account wurde erstellt.</p>
        <p>Es wird empfohlen, dass du nun gleich in die Einstellungen im ACP von KuschelTickets gehst und wichtige Einstellungen triffst.</p>
        <p></p>
        <p>Ich empfehle dir dass du die Datenschutzerklärung anpasst.</p>
        <p>Ich wünsche dir viel Spaß und Erfolg mit KuschelTickets</p>
        <p></p>
        <a class="ui button blue" href="index.php?login">Zum Login</a>
        <?php
                break;
                case 4:
        ?>

        <form class="ui form" action="install.php?step=4" method="post">
            <div class="field required">
                <label>Benutzername</label>
                <div class="ui input">
                <input type="text" name="username">
                </div>
            </div>
            <div class="field required">
                <label>E-Mail Adresse</label>
                <div class="ui input">
                <input type="email" name="email">
                </div>
            </div>
            <div class="field required">
                <label>Passwort</label>
                <div class="ui input">
                <input type="password" name="password">
                </div>
            </div>
            <div class="field required">
                <label>Passwort bestätigen</label>
                <div class="ui input">
                <input type="password" name="password_confirm">
                </div>
            </div>
            <button type="submit" name="submit" class="ui blue submit button">Absenden</button>
            <div class="ui error message"></div>
            <?php echo $error4; ?>
            </form>
            <script>
                $('.ui.form').form({
                    fields: {
                        username: {
                            identifier: 'username',
                            rules: [
                                {
                                    type   : 'empty',
                                    prompt : 'Bitte gib einen Benutzernamen an.'
                                }
                            ]
                        },
                        email: {
                            identifier: 'email',
                            rules: [
                                {
                                    type   : 'empty',
                                    prompt : 'Bitte gib eine E-Mail Adresse an.'
                                },
                                {
                                    type   : 'email',
                                    prompt : 'Bitte gib eine valide E-Mail Adresse an.'
                                }
                            ]
                        },
                        password: {
                            identifier: 'password',
                            rules: [
                                {
                                    type   : 'empty',
                                    prompt : 'Bitte gib ein Passwort an.'
                                }
                            ]
                        },
                        passwordconfirm: {
                            identifier: 'password_confirm',
                            rules: [
                                {
                                    type   : 'empty',
                                    prompt : 'Bitte bestätige dein Passwort.'
                                }
                            ]
                        }
                    }
                });
            </script>               

        <?php
                break;
                case 3:
        ?>
        <p>Deine Datenbank wurde erfolgreich eingerichtet, als nächstest musst du den SMTP Server konfigurieren.</p>
        <?php if($mailexception !== "") { ?>
            <div class="ui error message">
                SMTP Test Fehlgeschlagen: <?php echo $mailexception; ?>
            </div>
        <?php } ?>
        <form action="install.php?step=3" class="ui form" method="post">
            <div class="field required">
                <label>SMTP Host</label>
                <div class="ui input">
                    <input type="text" name="smtphost">
                </div>
            </div>
            <div class="field required">
                <label>SMTP Port</label>
                <div class="ui input">
                    <input type="text" name="smtpport">
                </div>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="smtpauth" onchange="if(this.checked) { document.getElementById('smtpauthdata').style.display = 'block'; } else { document.getElementById('smtpauthdata').style.display = 'none'; }">
                    <label>SMTP Login</label>
                </div>
                <br>
                <small class="helper">Hat der SMTP Server einen Login?</small>
            </div>
            <div id="smtpauthdata" style="display: none">
                <div class="field">
                    <label>SMTP Benutzername</label>
                    <div class="ui input">
                        <input type="text" name="smtpusername">
                    </div>
                </div>
                <div class="field">
                    <label>SMTP Passwort</label>
                    <div class="ui input">
                        <input type="password" name="smtppassword">
                    </div>
                </div>
            </div>
        <br>
        <div class="field required">
            <label>SMTP Sender</label>
            <div class="ui input">
                <input type="email" name="smtpfrom">
            </div>
            <small class="helper">meistens ist dies gleich zu dem Benutzernamen</small>
        </div>
        <button type="submit" name="submit" class="ui blue button">Absenden</button>
        <div class="ui error message"></div>
    </div>
    </form>
    <script>
        $('.ui.form').form({
            fields: {
                host: {
                    identifier: 'smtphost',
                    rules: [
                        {
                            type   : 'empty',
                            prompt : 'Bitte gib einen SMTP Host an.'
                        }
                    ]
                },
                port: {
                    identifier: 'smtpport',
                    rules: [
                        {
                            type   : 'empty',
                            prompt : 'Bitte gib einen SMTP Port an.'
                        },
                        {
                            type   : 'integer',
                            prompt : 'Bitte gib einen validen SMTP Port an.'
                        }
                    ]
                },
                from: {
                    identifier: 'smtpfrom',
                    rules: [
                        {
                            type   : 'empty',
                            prompt : 'Bitte gib einen Absender an.'
                        },
                        {
                            type   : 'email',
                            prompt : 'Bitte gib einen validen Absender an.'
                        }
                    ]
                }
            }
        });
    </script>
        <?php
                break;
                case 2:
        ?>
        <p>Stelle hier nun deine Datenbank Verbindung ein. Nach dem Absenden dieses Formulares kann es etwas dauern bis die Seite fertig geladen hat.</p>
        <?php if($pdoerror !== "") { ?>
            <div class="ui error message">
                Datenbank Verbindung Fehlgeschlagen: <?php echo $pdoerror; ?>
            </div>
        <?php } ?>
        <form action="install.php?step=2" class="ui form" method="post">
            <div class="field required">
                <label>Datenbank</label>
                <div class="ui input">
                    <input type="text" name="database">
                </div>
            </div>
            <div class="field required">
                <label>Datenbank Host</label>
                <div class="ui input">
                    <input type="text" name="databasehost">
                </div>
            </div>
            <div class="field required">
                <label>Datenbank Port</label>
                <div class="ui input">
                    <input type="number" name="databaseport">
                </div>
            </div>
            <div class="field required">
                <label>Datenbank Benutzer</label>
                <div class="ui input">
                    <input type="text" name="databaseuser">
                </div>
            </div>
            <div class="field">
                <label>Datenbank Passwort</label>
                <div class="ui input">
                    <input type="password" name="databasepassword">
                </div>
            </div>
            <button type="submit" name="submit" class="ui blue button">Absenden</button>
        <div class="ui error message"></div>
        </form>
        <script>
            $('.ui.form').form({
                fields: {
                    database: {
                        identifier: 'database',
                        rules: [
                            {
                                type   : 'empty',
                                prompt : 'Bitte gib eine Datenbank an.'
                            }
                        ]
                    },
                    host: {
                        identifier: 'databasehost',
                        rules: [
                            {
                                type   : 'empty',
                                prompt : 'Bitte gib einen Datenbank Host an.'
                            }
                        ]
                    },
                    port: {
                        identifier: 'databaseport',
                        rules: [
                            {
                                type   : 'empty',
                                prompt : 'Bitte gib einen Datenbank Port an.'
                            },
                            {
                                type   : 'integer',
                                prompt : 'Bitte gib einen validen Datenbank Port an.'
                            }
                        ]
                    },
                    user: {
                        identifier: 'databaseuser',
                        rules: [
                            {
                                type   : 'empty',
                                prompt : 'Bitte gib einen Datenbank Benutzer an.'
                            }
                        ]
                    }
                }
            });
        </script>
        <?php
                break;
                default: 
        ?>
        <h1>KuschelTickets</h1>
        <p>Vielen Dank dass du dich für KsuchelTickets entschieden hast. Diese Installation wird dir helfen KuschelTickets auf deinem Webserver zu installieren. Bevor du starten kannst, musst du die Lizenzbestimmungen akzeptieren.</p>
        <p></p>
        <blockquote>
                BSD 3-Clause License<br>
                <br>
                Copyright (c) 2020, Kuschel-Swein<br>
                All rights reserved.<br>
                <br>
                Redistribution and use in source and binary forms, with or without<br>
                modification, are permitted provided that the following conditions are met:<br>
                <br>
                1. Redistributions of source code must retain the above copyright notice, this<br>
                list of conditions and the following disclaimer.<br>
                <br>
                2. Redistributions in binary form must reproduce the above copyright notice,<br>
                this list of conditions and the following disclaimer in the documentation<br>
                and/or other materials provided with the distribution.<br>
                <br>
                3. Neither the name of the copyright holder nor the names of its<br>
                contributors may be used to endorse or promote products derived from<br>
                this software without specific prior written permission.<br>
                <br>
                THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"<br>
                AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE<br>
                IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE<br>
                DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE<br>
                FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL<br>
                DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR<br>
                SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER<br>
                CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,<br>
                OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE<br>
                OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.<br>
        </blockquote>
        <p></p>
        <p>
            <a class="ui blue button" href="install.php?step=2">Ich akzeptiere diese Lizenzbestimmungen.</a> 
        </p>
        <?php
                break;
            }





        ?>
            </div>
        </div>
    </body>
</html>