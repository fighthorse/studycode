Installation
Installation is possible using Composer.

If you don't already use Composer, you can download the composer.phar binary:

curl -sS https://getcomposer.org/installer | php
Then install the library:

php composer.phar require facebook/webdriver
Getting started
Start Server
The required server is the selenium-server-standalone-#.jar file provided here: http://selenium-release.storage.googleapis.com/index.html

Download and run the server by replacing # with the current server version. Keep in mind you must have Java 8+ installed to run this command.

java -jar selenium-server-standalone-#.jar
NOTE: If using Firefox, see alternate command below.