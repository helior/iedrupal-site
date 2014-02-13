[![Stories in Ready](https://badge.waffle.io/helior/iedrupal-site.png?label=ready)](https://waffle.io/helior/iedrupal-site)
[![Build Status](https://travis-ci.org/helior/iedrupal-site.png?branch=master)](https://travis-ci.org/helior/iedrupal-site)
[![Built with Grunt](https://cdn.gruntjs.com/builtwith.png)](http://gruntjs.com/)
[![David Dependency Watcher](https://david-dm.org/helior/iedrupal-site.png)

Inland Empire Drupal site
=============
Website for the Inland Empire Drupal community.

## Installation
This project depends on Phrozn (via [Composer](https://getcomposer.org/)), Grunt (via [NPM](https://npmjs.org/)), and Compass (via [Bundler](http://bundler.io/))

#### Install dependencies
- Run `bundle install` to install Compass.
- Run `npm install` to install Grunt.
- Run `composer install` to install Phrozn.

#### Build project
To build the site files to the `www` directory, run `phr build src www`.
Then, run `grunt dev` to compile sass/coffee scripts to the `www` directory to complete the app.

#### TODO:
- Ensure paths to styles, scripts, and links work
