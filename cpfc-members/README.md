Crystal Palace Members' Site
=================

Taken from our Default Wordpress repo as a starting point

Notes
---------------
* Integrated Templates can be found in /wp-content/themes/main_bs3/members-module/email_templates

Install
---------------

To install, run through the following steps:

* Clone the repo to your local machine
* Copy wp-config-live.php to wp-config-local.php
* Edit wp-config-local.php to use your database settings. Create a new database locally to use for development purposes
* Configure your local server with a new virtualhost for the site - we recommend http://cpfc-members.local/
* Run the Wordpress installer in your browser
* In Terminal, navigate to the theme directory (wp-content/themes/main_bs3)
* Run 'bower install' - this will look at the bower.json file and install all packages listed to /bower-components/
* Run 'npm install' - this will look at the package.json file and install all packages listed to /node_modules/
* Run 'grunt setup' - this will run the clean/shell commands to run pre- and post-commit git hook files