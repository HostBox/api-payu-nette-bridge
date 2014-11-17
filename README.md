Nette bridge for PayU Api  [![Build Status](https://api.travis-ci.org/HostBox/api-payu-nette-bridge.png)](https://travis-ci.org/HostBox/api-payu-nette-bridge)
===================

Package Installation
-------------------

The best way to install Social Plugins is using [Composer](http://getcomposer.org/):

```sh
$ composer require hostbox/api-payu @dev
$ composer require hostbox/api-payu-nette-bridge
```

[Packagist - Versions](https://packagist.org/packages/hostbox/api-payu-nette-bridge)

[Nette Addons](http://addons.nette.org/hostbox/api-payu-nette-bridge)

or manual edit composer.json in your project

```json
"require": {
    "hostbox/api-payu": "@dev",
    "hostbox/api-payu-nette-bridge": "~1.0.0"
}
```

Bridge Installation
-------------------

**Registration**

```
extensions:
	payu: HostBox\Bridge\PayU\Extension
```

**Configuration: Single POS**

Autowire and inject is ON

```
payu:
	posId: '123456789'
	posAuthKey: 'qwertyuiop'
	key1: 'asdfghjkl'
	key2: 'zxcvbnm'

services:
    - MyFirstService
    - MySecondService(@payu.default) #manual inject
```

**Configuration: Multi POS**

Autowire and inject is OFF

```
payu:
	firstPOS:
		posId: '123456789'
		posAuthKey: 'qwertyuiop'
		key1: 'asdfghjkl'
		key2: 'zxcvbnm'
	secondPOS:
		posId: '987654321'
		posAuthKey: 'poiuytrewq'
		key1: 'lkjhgfdsa'
		key2: 'mnbvcxz'
		encoding: 'ISO'
		format: 'txt'

services:
    - MyFirstService(@payu.firstPOS)
    - MySecondService(@payu.secondPOS)
```
