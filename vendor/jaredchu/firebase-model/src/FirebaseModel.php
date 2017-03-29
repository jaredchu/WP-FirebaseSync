<?php
/**
 * Created by PhpStorm.
 * User: jaredchu
 * Date: 1/14/17
 * Time: 2:25 PM
 */

namespace JCFirebase;

use JsonMapper;

class FirebaseModel {

	/**
	 * @var string
	 */
	public $key;

	/**
	 * @var string
	 */
	public $created;

	/**
	 * @var string
	 */
	public $updated;

	/**
	 * @var JCFirebase
	 */
	protected $firebase;

	/**
	 * FirebaseModel constructor.
	 *
	 * @param \JCFirebase\JCFirebase $firebase
	 */
	public function __construct( \JCFirebase\JCFirebase $firebase = null ) {
		$this->firebase = $firebase;
	}

	public function getData() {
		$object = clone $this;
		unset( $object->firebase );
		unset( $object->key );

		return get_object_vars( $object );
	}

	public static function getPath() {
		$reflection = ( new \ReflectionClass( get_called_class() ) );

		return strtolower( $reflection->getShortName() );
	}

	/**
	 * @return bool
	 */
	public function create() {
		$this->created = ( new \DateTime() )->format( @"Y-m-d H:i:s" );

		$response = $this->firebase->post( $this->getPath(), array(
			'data' => $this->getData()
		) );

		$this->key = json_decode( $response->body )->name;

		return $response->success;
	}


	/**
	 * @return bool
	 */
	public function save() {
		if ( ! empty( $this->key ) ) {
			$this->updated = ( new \DateTime() )->format( @"Y-m-d H:i:s" );

			$response = $this->firebase->put( $this->getPath() . '/' . $this->key, array(
				'data' => $this->getData()
			) );

			$success = $response->success;
		} else {
			$success = $this->create();
		}

		return $success;
	}

	/**
	 * @return bool
	 */
	public function delete() {
		$success = false;
		if ( ! empty( $this->key ) ) {
			$response = $this->firebase->delete( $this->getPath() . '/' . $this->key );

			$success = $response->success;
		}

		return $success;
	}

	/**
	 * @param $key
	 * @param JCFirebase $firebase
	 *
	 * @return object
	 */
	public static function findByKey( $key, JCFirebase $firebase ) {
		$response = $firebase->get( self::getPath() . '/' . $key );
		$object   = null;
		if ( $response->success && $response->body != 'null' ) {
			$mapper      = new JsonMapper();
			$object      = $mapper->map( json_decode( $response->body ), new static() );
			$object->key = $key;
			$object->firebase = $firebase;
		}

		return $object;
	}

	/**
	 * @param JCFirebase $firebase
	 *
	 * @return array(FirebaseModel)
	 */
	public static function findAll( JCFirebase $firebase ) {
		$response = $firebase->get( self::getPath() );
		$objects  = array();

		$jsonObject = json_decode( $response->body, true );
		if ( $response->success && count( $jsonObject ) ) {
			do {
				$mapper           = new JsonMapper();
				$object           = $mapper->map( (object) current( $jsonObject ), new static() );
				$object->key      = key( $jsonObject );
				$object->firebase = $firebase;
				$objects[]        = $object;
			} while ( next( $jsonObject ) );
		}

		return $objects;
	}
}