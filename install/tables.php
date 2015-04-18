<?php
/**
 * Created by PhpStorm.
 * User: dan
 * Date: 2/16/15
 * Time: 9:30 PM
 */

$SQL_TABLES = array(
    'posts' => array(
        'id'    => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
        'date'  => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'title' => 'CHAR(255) NOT NULL',
        'text'  => 'VARCHAR(40960) NOT NULL',
        'media' => 'CHAR(50) DEFAULT NULL'
    ),
    'tags' => array(
        'tag'                   => 'CHAR(255)',
        'post_id'               => 'INT(6) UNSIGNED',
        'PRIMARY KEY'           => '(tag, post_id)',
        'FOREIGN KEY (post_id)' => 'REFERENCES posts(id) ON DELETE CASCADE'
    )
);
