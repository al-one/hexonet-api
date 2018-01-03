# Hexonet APIs For PHP

## Installing

```sh
$ composer require al-one/hexonet-api -vvv
```

or 

```sh
$ composer require al-one/hexonet-api:dev-master -vvv
```


## Usage

```php
<?php

$username = 'user';
$password = 'pass';

$auth = new \Alone\Hexonet\Auth($username,$password);
$api = new \Alone\Hexonet\Connection($auth);

$ret = $api->call('command',[
    'parameter1' => 'value1',
    'parameter2' => 'value2',
]);

$hash1 = $ret->getHash();
$hash2 = $ret->getHashLower();
```

## Documentation

- [Domain API](https://www.hexonet.net/sites/default/files/downloads/DOMAIN_API_Reference.pdf)