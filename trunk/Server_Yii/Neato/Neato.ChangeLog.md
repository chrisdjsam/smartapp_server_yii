Neato Change Log
=========
___________________________________________________________________________________
Raja Software 

Last Updated: 08/14/2014

Latest RSL SVN Version: 562
___________________________________________________________________________________

Changes deployed on NeatoStaging
------------------------------------------------------
RSL SVN Version: 562

- Added "{acce ss, max_user_offline_messages, [{5000, admin}, {1, all}]}." in the ejabberd.cfg file to improves the touch point between Apache and XMPP/Ejabberd.
- Checked in nodejs test scripts with README file.
- Added 3 levels of logverbosity: 0 - none, 1 - low, 2 - high at API level.
- Modified the UI of app/log page for better view and to improved readability.
- Updated /etc/sysctl.conf configuration file for improving the connection speed by disabling parameter net.ipv4.tcp_timestamps at the  TCP packet level.
- Checked in instance's sysctl.conf configuration file in order to improve the scalability.
- Implemented cron job to logs off ALL inactive robots.
- Updated SmartApps.Server.Setup.docx document + Network topology diagrams. 
- Checked in AMQP producers and consumers that are used to handle async processing of XMPP, SMTP and Push notification sending.
- Checked in Tsung scripts and performance numbers with README file.
- Checked in WordPress integration doc.
- Removed all deprecated APIs.
- Added 'Diagnostics' module in Admin web console.
__________________________________________________________________________________

Changes deployed on NeatoDev
------------------------------------------------------
RSL SVN Version: 463
- Change log file update
___________________________________________________________________________________

Changes deployed on NeatoStaging
------------------------------------------------------
RSL SVN Version: 460
- Email sending is now handled by it's own consumer
- There are 3 consumers for sending XMPP Notification, iOS/Android Push Notifications and Emails
- Bug fixes 
    - 391 (Vorwerk Server Portal: Provide subject lines of notification emails)
    - 397 (Vorwerk Server Portal: Size/Appearance of search button)
    - 398 (Vorwerk Server Portal: Action "Delete robot" should send a notification email)
    - 399 (Vorwerk Server Portal: Action "Add/Modify" alternate email address should send email notification)
    - 400 (Vorwerk Server Portal: Action "Delete alternate email address" should send email notification)
- Enabled support for sending email in the multiple languages
- Currently only Germal and English templates are available
- Default language is English so if the template is not found for a specific language, the email goes out in English.

___________________________________________________________________________________

Changes deployed on VorwerkBeta
------------------------------------------------------
RSL SVN Version: 460
- Email sending is now handled by it's own consumer
- There are 3 consumers for sending XMPP Notification, iOS/Android Push Notifications and Emails
- Bug fixes 
    - 391 (Vorwerk Server Portal: Provide subject lines of notification emails)
    - 397 (Vorwerk Server Portal: Size/Appearance of search button)
    - 398 (Vorwerk Server Portal: Action "Delete robot" should send a notification email)
    - 399 (Vorwerk Server Portal: Action "Add/Modify" alternate email address should send email notification)
    - 400 (Vorwerk Server Portal: Action "Delete alternate email address" should send email notification)
- Enabled support for sending email in the multiple languages
- Currently only Germal and English templates are available
- Default language is English so if the template is not found for a specific language, the email goes out in English.

___________________________________________________________________________________

End.