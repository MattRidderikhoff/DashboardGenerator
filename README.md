# DashboardGenerator

### First time installation
1. Run the following on the command line (you need homebrew installed): <code>brew install php</code>
2. Run the following on the command line: <code>pecl install xdebug</code>
3. Run the following on the coomand line: <code>php -i</code> and copy all of the output
4. Paste the output from (3) into the textbox here: https://xdebug.org/wizard.php
5. Follow the instructions the xdebug wizard gives you
6. Edit your php.ini by typing <code>cd /etc</code> in your root folder, and edit it using <code>sudo nano php.ini</code>. Then add/replace this line: <code>xdebug.remote_port="9000"</code>
7. Install the "xdebug" browser extension
8. In the prefernces of the extension, set the IDE key to "PHPSTORM" and hit save
9. In PHPSTORM, go to Languages & Frameworks -> PHP. Click the 3 dots beside "CLI Interpreter". Click the "+" icon and select "usr/bin/php"
10. Start your php server (instructions below under "Spin up the site")
11. Go to "localhost:8000", and the click the xdebug bug icon in your address bar, and set it to "Debug". Refresh the page
12. Set a breakpoint anywhere in the codebase
13. In PHPStorm you will see a dialog around "Incoming Connection". Press ok
14. Now you should see that the php script has paused on your breakpoint! You're done!

### Spin up the site:
1. Go to the directory for this project
2. Enter the following on the command line:
    <code>php -S localhost:8000</code> 
3. In any browser go to "localhost:8000"
 
