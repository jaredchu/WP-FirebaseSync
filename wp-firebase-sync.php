<?php

/*
Plugin Name: WP Firebase Sync
Plugin URI: https://github.com/jaredchu/WP-FirebaseSync
Description: Plugin that allow WordPress to sync Post data with Firebase in real time.
Version: 1.0
Author: Jared Chu
Author URI: http://jaredchu.com/
License: MIT
*/

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/classes/FB_Post.php';

use JCFirebase\JCFirebase;

$firebaseURI = '';
$jsonString = '';
$firebaseDefaultPath = '/wordpress';

global $firebase;
if (is_null($firebase)) {
    $firebase = JCFirebase::fromJson($firebaseURI, $jsonString, $firebaseDefaultPath);
}

function save_post_to_firebase($post_id)
{
    global $firebase;

    // If this is just a revision, cancel
    if (wp_is_post_revision($post_id))
        return;

    $post = WP_Post::get_instance($post_id);

    if ($post->ID) {
        $fbPost = new FB_Post($firebase, $post->ID);
        $fbPost->title = $post->post_title;
        $fbPost->content = $post->post_content;

        $fbPost->save();
    }
}

add_action('save_post', 'save_post_to_firebase');