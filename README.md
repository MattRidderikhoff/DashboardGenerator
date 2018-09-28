# DashboardGenerator

### First time installation
##PHP and debugging
1. Run the following on the command line (you need homebrew installed): <code>brew install php</code>
2. Run the following on the command line: <code>pecl install xdebug</code>
3. Run the following on the command line: <code>php -i</code> and copy all of the output
4. Paste the output from (3) into the textbox here: https://xdebug.org/wizard.php
- Warning, if you are on Mac, run xcode-select --install, otherwise phpize will give you error for missing some files.
5. Follow the instructions the xdebug wizard gives you
6. Edit your php.ini by typing <code>cd /etc</code> in your root folder, and edit it using <code>sudo nano php.ini</code>. Then add/replace this line: <code>xdebug.remote_port="9000"</code>
7. Install the "xdebug" browser extension
8. In the preferences of the extension, set the IDE key to "PHPSTORM" and hit save
9. In PHPSTORM, go to Languages  Frameworks -> PHP. Click the 3 dots beside "CLI Interpreter". Click the "+" icon and select "usr/bin/php"
10. Start your php server (instructions below under "Spin up the site")
11. Go to "localhost:8000", and the click the xdebug bug icon in your address bar, and set it to "Debug". Refresh the page
12. Set a breakpoint anywhere in the codebase
13. In PHPStorm you will see a dialog around "Incoming Connection". Press ok
14. Now you should see that the php script has paused on your breakpoint! You're done!
##Composer And Twig
1. In Terminal, cd into DashboardCreator/src
2. Run the following commands to install composer: <br />
<code>php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"</code><br />
<code>php -r "if (hash_file('SHA384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"</code><br />
<code>php composer-setup.php</code><br />
<code>php -r "unlink('composer-setup.php');"</code><br />
<code>mv composer.phar /usr/local/bin/composer</code><br />
3. Run the following command to install twig:
<code>composer install</code><br />
4. Run the following to install chartjs
<code>brew install node</code><br />
<code>npm install --prefix ./vendor</code><br />


### Spin up the site:
1. Go to the directory for this project
2. Enter the following on the command line:
    <code>php -S localhost:8000</code> 
3. In any browser go to "localhost:8000"
 
