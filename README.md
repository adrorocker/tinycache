[Tiny Cache](https://github.com/adrorocker/tinycache)
===================================

A small library to cache assets and html pages.

[![Build status][Master image]][Master]
[![Coverage Status][Master covarage image]][Master covarage]
[![Latest Stable Version][Stable version image]][Stable version]
[![License][License image]][License]

-----------------------------------

## Installation

```
composer require adrorocker/tinycache
```

## Usage

```php
require '../vendor/autoload.php';

use TinyCache\Cache;
use TinyCache\Item;
use TinyCache\Adapter\FilesystemAdapter;

$cache = new Cache(new FilesystemAdapter);

$item1 = new Item('key1','Hola uno');
$item2 = new Item('key2','Hola dos');

$cache->save($item1)

// You can 'add' an Item to the cache collection before it is actually saved on the pool
$cache->saveDeferred($item2);

// Then commit to save the items on the pool
$cache->commit();

// Get an Item from the pool
$item = $cache->getItem('key1');

// Get several Items from the pool
$items = $cache->getItems(['key1','key2']);

// Delete on Item from the pool
$cache->deleteItem('key1');

// Delete several Items from the pool
$cache->deleteItems(['key1','key2']);

// Delete all Items from the pool
$cache->clear();

```

## Authors:

[Alejandro Morelos](https://github.com/adrorocker). 

  [Master]: https://travis-ci.org/adrorocker/tinycache/
  [Master image]: https://travis-ci.org/adrorocker/tinycache.svg?branch=master
  [Master covarage]: https://coveralls.io/github/adrorocker/tinycache
  [Master covarage image]: https://coveralls.io/repos/github/adrorocker/tinycache/badge.svg?branch=master
  [Stable version]: https://packagist.org/packages/adrorocker/tinycache
  [Stable version image]: https://poser.pugx.org/adrorocker/tinycache/v/stable
  [License]: https://packagist.org/packages/adrorocker/tinycache
  [License image]: https://poser.pugx.org/adrorocker/tinycache/license

