# DashboardGenerator

## First time installation
1. Run the following on the command line (you need homebrew installed): <code>brew install php</code>
2. Run the following on the command line: <code>pecl install xdebug</code>
3. Run the following on the command line: <code>php -i</code> and copy all of the output
4. Paste the output from (3) into the textbox here: https://xdebug.org/wizard.php
-- Warning, if you are on Mac, run xcode-select --install, otherwise phpize will give you error for missing some files.
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
1. In Terminal, cd into DashboardCreator
2. Run the following commands to install composer: <br />
<code>php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"</code><br />
<code>php -r "if (hash_file('SHA384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"</code><br />
<code>php composer-setup.php</code><br />
<code>php -r "unlink('composer-setup.php');"</code><br />
<code>mv composer.phar /usr/local/bin/composer</code><br />
3. From within the DashboardGenerator directory, run the following command to install twig:
<code>composer install</code><br />
4. From within the DashboardGenerator directory, run the following to install chartjs:
<code>npm install --prefix ./vendor</code><br />


### Spin up the site:
1. cd into DashboardCreator
2. Enter the following on the command line:
    <code>php bin/console server:run</code> 
3. In any browser go to "localhost:8000"
 

# DSL For Users
## DSL Example

Create Bar
Title is “My Bar Chart”
X is “Name” as “AliasX”
Y is “Total” as “AliasY”
Order (X||Y) (ascending||descending)
End

Create Pie
Title is “My Pie Chart”
Category is “MovieTitle”
Value is “Gross Domestic”
End

Create Line
Title is “My Line Chart”
X is “Age”
Y is SUM “Points” by “Age” as “Points”
Lines are “Team”
End

Create Group
Orient horizontal
Title “My Report Chart”
Add “My Bar Chart”
Add “My Pie Chart”
End

## DSL EBNF Grammar

### Program Grammar

Program ::=
	"Create"

BarProgram ::=
	"Bar" (Title BarStm | BarStm Title)

GroupProgram ::=
	"Group" (Title GroupStm | GroupStm Title)

LineProgram ::=
	"Line" (Title LineStm | LineStm Title)

PieProgram ::=
	"Pie" (Title PieStm | PieStm Title)

### Abstract Grammar

BarStm ::=
	DefineXYStm Order 
	| Order DefineXYStm

GroupStm ::=
	Orient AddGraph
	| AddGraph Orient

LineStm ::=
	DefineXYStm Lines
	| Lines DefineXYStm

PieStm ::=
	DefineCVStm

### High Level Grammar

AddGraph ::=
	"Add" Identifier AddGraph*

DefineCVStm ::=
	Category Value 
	| Value Category

DefineXYStm ::= 
	X Y 
	| Y X

Lines ::=
	"Lines" "are" IDENTIFIER

Order ::=
	"Order" ("X"|"Y") ("ascending" | "descending")

Orient ::=
	"Orient" ("horizontal" | "vertical")

Title ::= 
	"Title" Define

### Intermediate Grammar

Category ::=
	"Category" Define
	
Value ::=
	"Value" Define

X ::= 
	"X" Define Nickname?

Y ::= 
	"Y" Define Nickname?

### Identifier Grammar

Define ::= "is" Identifier

Nickname ::= "as" Identifier

### Basic Grammar

Identifier ::= 
	IDENTIFIER+

IDENTIFIER ::= 
	(STRING|NUM)+

STRING ::=
	"a"|"b"|"c"|"d"|"e"|"f"|"g"|"h"|"i"|"j"|"k"|"l"|"m"|"n"|"o"|"p"|"q"|"r"|"s"|"t"|"u"|"v"|"w"|"x"|"y"|"z"

NUM ::=
	0|1|2|3|4|5|6|7|8|9

