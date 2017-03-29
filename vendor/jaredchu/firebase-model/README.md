<p align="center"><img src="http://i.imgur.com/CTP9Dmu.jpg"></p>
<h1 align="center">PHP model mapping with Firebase</h1>

[![Packagist](https://img.shields.io/packagist/v/jaredchu/firebase-model.svg)](https://packagist.org/packages/jaredchu/firebase-model)
[![Travis](https://img.shields.io/travis/jaredchu/FirebaseModel.svg)](https://travis-ci.org/jaredchu/FirebaseModel)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/jaredchu/FirebaseModel.svg)](https://scrutinizer-ci.com/g/jaredchu/FirebaseModel/)
[![Scrutinizer branch](https://img.shields.io/scrutinizer/coverage/g/jaredchu/FirebaseModel/master.svg)](https://scrutinizer-ci.com/g/jaredchu/FirebaseModel/)
[![Packagist](https://img.shields.io/packagist/l/jaredchu/firebase-model.svg)](https://packagist.org/packages/jaredchu/firebase-model)

## About
FirebaseModel provide an easier way to connect to Firebase noSQL (without any line of CURL code).

## Requirements
The following versions of PHP are supported by this version.
- PHP 5.6
- PHP 7.0
- PHP 7.1
- HHVM

## Installation
Via Composer

`$ composer require jaredchu/firebase-model`

## Usage
Create [service account](https://cloud.google.com/iam/docs/service-accounts) to get `json key file`.

#### Create Firebase connector
```php
use JCFirebase\JCFirebase;
$firebase = new JCFirebase::fromKeyFile( $firebaseURI, $jsonKeyFile );
```
#### Extend your Model with FirebaseModel
```php
class Log extends FirebaseModel {
	/**
	 * @var integer
	 */
	public $code;
	/**
	 * @var string
	 */
	public $message;
}
```
#### Get record
```php
$log = Log::findByKey( $key, $firebase );
echo $log->key;
echo $log->code;
echo $log->message;

$logs = Log::findAll( $firebase );
foreach ($logs as $log){
    echo $log->key;
    echo $log->code;
    echo $log->message;
}
```

#### Create record
```php
$log          = new Log( $firebase );
$log->code    = 200;
$log->message = 'Success';
$log->save();
```

#### Update record
```php
$log = Log::findByKey( $key, $firebase );
$log->code    = 400;
$log->message = 'Bad Request';
$log->save();
```
#### Delete record
```php
$log = Log::findByKey( $key, $firebase );
$log->delete();
```

## Contributing
1. Fork it!
2. Create your feature branch: `$ git checkout -b feature/your-new-feature`
3. Commit your changes: `$ git commit -am 'Add some feature'`
4. Push to the branch: `$ git push origin feature/your-new-feature`
5. Submit a pull request.

## License
[MIT License](https://github.com/jaredchu/JC-Firebase-PHP/blob/master/README.md)

