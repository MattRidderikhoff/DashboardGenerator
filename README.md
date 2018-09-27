# DashboardGenerator

#First time installation
##Setting up PHP and xdebug 
1. Run the following on the command line (you need homebrew installed): <code>brew install php</code>
2. Run the following on the command line: <code>pecl install xdebug</code>
3. Run the following on the coomand line: <code>php -i</code> and copy all of the output
4. Paste the output from (3) into the textbox here: https://xdebug.org/wizard.php
5. Follow the instructions the xdebug wizard gives you
6. Edit php.ini again, using <code>sudo nanon php.ini</code> in /etc, and add/replace this line: <code>xdebug.remote_port="9000"</code>
##Setting up PHPSTORM to debug
1. Install the "xdebug" browser extension
2. In the prefernces of the extension, set the IDE key to "PHPSTORM" and hit save
3. In PHPSTORM, go to Languages & Frameworks -> PHP. Click the 3 dots beside "CLI Interpreter". Click the "+" icon and select "usr/bin/php"
4. Start your php server (instructions below under "Recurring Tasks")
5. Go to "localhost:8000", and the click the xdebug bug icon in your address bar, and set it to "Debug". Refresh the page
6. Set a breakpoint anywhere in the codebase
7. In PHPStorm you will see a dialog around "Incoming Connection". Press ok
8. Now you should see that the php script has paused on your breakpoint! You're done!

#Recurring Tasks
###To spin up the site:
1. Go to the directory for this project
2. Enter the following on the command line:
    <code>php -S localhost:8000</code> 
3. In any browser go to "localhost:8000"
 
