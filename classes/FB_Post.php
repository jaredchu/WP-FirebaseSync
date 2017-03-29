<?php

/**
 * Created by PhpStorm.
 * User: jaredchu
 * Date: 29/03/2017
 * Time: 19:35
 */

require __DIR__ . '../vendor/autoload.php';

use JCFirebase\FirebaseModel;
use JCFirebase\JCFirebase;

class FB_Post extends FirebaseModel
{
    public $title;
    public $content;

    public function __construct(JCFirebase $firebase, $id)
    {
        parent::__construct($firebase);
        $this->key = $id;
    }
}