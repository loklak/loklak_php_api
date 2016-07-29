# Loklak PHP

[![Coverage Status](https://coveralls.io/repos/github/loklak/loklak_php_api/badge.svg?branch=master)](https://coveralls.io/github/loklak/loklak_php_api?branch=master)

This is the PHP Client and PHP API for accessing the Loklak API
It contains a series of calls that could be directly used for the requested JSON responses

This library is bundled with Requests library in PHP keeping it standalone and works by just plugging it into the `lib/` folder.

This is intended to be the generic PHP request library to loklak for integration into PHP websites and CMS frameworks like wordpress, drupal, joomla etc..,

## How to run PHPUnit tests?

### *AMP Solution Stack or Command Line Users

Install `PHPUnit` using `phar` or `composer`. 
Refer to [this](https://phpunit.de/manual/current/en/installation.html) for installation details. 

Once PHPUnit is installed, open XAMPP shell(for Windows users) / Terminal (for Ubuntu/Mac Users). 
Go to project root and type the following command:
`phpunit Tests`
This will execute all tests in `Tests` directory. 

##Wordpress plugin developers

To include loklak API support to your plugin. Follow the undermentioned steps:

1. Add `loklak_php_api` submodule to your plugin directory  
    `git submodule add https://github.com/loklak/loklak_php_api.git`

2. Include loklak settings in your plugin settings option_page
    ```
    <?php 
        settings_fields( 'loklak-settings' );
        do_settings_sections( 'loklak-settings' );
    ?>
    ```

3. Include `Lib/loklak-api-admin.php` and `loklak.php` in your plugin logic files as need be. 

4. Loklak settings are stored as an array (`loklak-settings`) in your wordpress database. 

##Plugins with Loklak Integration

The complete list of Wordpress plugins with Loklak Integration is present [here](https://github.com/loklak/loklak_wordpress_plugins/blob/master/Plugin-Readme.md).

To test the plugins, refer to [this](https://loklak-wordpress.herokuapp.com) sample wordpress installation.	