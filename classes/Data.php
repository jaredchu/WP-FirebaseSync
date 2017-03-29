<?php

/**
 * Created by PhpStorm.
 * User: jaredchu
 * Date: 3/29/17
 * Time: 11:49 PM
 */

require __DIR__ . '../vendor/autoload.php';

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class Data {

	/**
	 * @var Key
	 */
	private $encryptKey;

	/**
	 * Data constructor.
	 *
	 * @param Key $encryptKey
	 */
	public function __construct( Key $encryptKey ) {
		$this->encryptKey = $encryptKey;
	}

	public static function fromKey( Key $key ) {
		return new Data( $key );
	}

	public function encrypt( $plainText ) {
		return Crypto::encrypt( $plainText, $this->encryptKey );
	}

	public function decrypt( $plainText ) {
		return Crypto::decrypt( $plainText, $this->encryptKey );
	}
}