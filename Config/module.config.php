<?php

/**
 * Module configuration container
 */

return array(
    'caption' => 'Advice',
    'description' => 'Advice module allows you to manage advices on your site',
    'menu' => array(
        'name' => 'Advice',
        'icon' => 'fa fa-hashtag fa-5x',
        'items' => array(
            array(
                'route' => 'Advice:Admin:Advice@gridAction',
                'name' => 'View all advices'
            ),
            array(
                'route' => 'Advice:Admin:Advice@addAction',
                'name' => 'Add new advice'
            )
        )
    )
);