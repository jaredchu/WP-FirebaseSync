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
require __DIR__ . '/classes/JC_Post.php';

use JCFirebase\JCFirebase;

/**
 * Register menu
 */

add_action('admin_menu', 'wfs_add_admin_menu');
add_action('admin_init', 'wfs_settings_init');


function wfs_add_admin_menu()
{

    add_options_page('WP Firebase Sync', 'WP Firebase Sync', 'manage_options', 'wp_firebase_sync', 'wfs_options_page');

}


function wfs_settings_init()
{

    register_setting('pluginPage', 'wfs_settings');

    add_settings_section(
        'wfs_pluginPage_section',
        __('Firebase config', 'wp-firebase-sync'),
        'wfs_settings_section_callback',
        'pluginPage'
    );

    add_settings_field(
        'wfs_firebase_uri',
        __('Firebase URI:', 'wp-firebase-sync'),
        'wfs_text_field_0_render',
        'pluginPage',
        'wfs_pluginPage_section'
    );

    add_settings_field(
        'wfs_firebase_email',
        __('Firebase account email:', 'wp-firebase-sync'),
        'wfs_text_field_1_render',
        'pluginPage',
        'wfs_pluginPage_section'
    );

    add_settings_field(
        'wfs_firebase_private_key',
        __('Firebase account private key:', 'wp-firebase-sync'),
        'wfs_text_field_2_render',
        'pluginPage',
        'wfs_pluginPage_section'
    );
}


function wfs_text_field_0_render()
{

    $options = get_option('wfs_settings');
    ?>
    <input type='text' name='wfs_settings[wfs_firebase_uri]' value='<?php echo $options['wfs_firebase_uri']; ?>'>
    <?php

}


function wfs_text_field_1_render()
{

    $options = get_option('wfs_settings');
    ?>
    <input type='text' name='wfs_settings[wfs_firebase_email]'
           value='<?php echo $options['wfs_firebase_email']; ?>'>
    <?php

}

function wfs_text_field_2_render()
{

    $options = get_option('wfs_settings');
    ?>
    <textarea cols='40' rows='5'
              name='wfs_settings[wfs_firebase_private_key]'><?php echo $options['wfs_firebase_private_key']; ?></textarea>
    <?php

}


function wfs_settings_section_callback()
{

    echo __('Enter your firebase URI & service account information', 'wp-firebase-sync');

}


function wfs_options_page()
{

    ?>
    <form action='options.php' method='post'>

        <h2>WP Firebase Sync</h2>

        <?php
        settings_fields('pluginPage');
        do_settings_sections('pluginPage');
        submit_button();
        ?>

    </form>
    <?php

}

/**
 * Config firebase
 */

$firebaseURI = '';
$jsonString = '';
$firebaseDefaultPath = '/wordpress';

global $firebase;
if (is_null($firebase)) {
//    $firebase = JCFirebase::fromJson($firebaseURI, $jsonString, $firebaseDefaultPath);
}

function save_post_to_firebase($post_id)
{
    global $firebase;

    // Cancel if this is just a revision or firebase is not set
    if (is_null($firebase) || wp_is_post_revision($post_id)) {
        return;
    }

    $post = WP_Post::get_instance($post_id);

    if ($post->ID) {
        $mapper = new JsonMapper();
        $fbPost = $mapper->map($post, new JC_Post($firebase, $post->ID));

        $fbPost->save();
    }
}

add_action('save_post', 'save_post_to_firebase');