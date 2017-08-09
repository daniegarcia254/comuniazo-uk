# Comunio UK Points

## Description

Webapp that shows information about a Comunio's player lineup.

[Comunio](http://www.comunio.co.uk) is a football manager where you can manage your Premier League team and create a virtual league with your friends. You will be in charge of the lineup, tactics and transfers. Financial and strategic skills based on reality Premier League are decisive for winning the competition.

Player ratings for a matchday are get from the [WhoScored](http://www.whoscored.com) webpage and transformed to a personalized Comunio ratings. So, it takes to much time to calculate your lineup rating manually during a matchday.

With this webapp, you'll have this automatized!


## Run the project
- **Dependencies needed:** _PHP_
- **Download _Composer_**

  Go to the _api/_ folder and run this in your terminal to get the latest Composer version:
  
  ```curl -sS https://getcomposer.org/installer | php```
  
  Or if you don't have curl:
  
  ```php -r "readfile('https://getcomposer.org/installer');" | php```
  
  This installer script will simply check some php.ini settings, warn you if they are set incorrectly, and then download the latest composer.phar in the current directory
  
- **Installing dependencies with _Composer_**
  
  Run the next command to install the dependencies specified in the _api/composer.json_ file:
  
   ```php composer.phar install ```

  Two dependencies will be installed:
  - [**_Goutte_**](https://github.com/FriendsOfPHP/Goutte): a screen scraping and web crawling library for PHP
  - [**_Slim_**](http://www.slimframework.com/): PHP micro framework that helps you quickly write a simple API.

  
