<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

return array(
    '/%s/module/advice' => array(
        'controller' => 'Admin:Advice@gridAction'
    ),
    
    '/%s/module/advice/page/(:var)' => array(
        'controller' => 'Admin:Advice@gridAction'
    ),
    
    '/%s/module/advice/delete/(:var)' => array(
        'controller' => 'Admin:Advice@deleteAction',
        'disallow' => array('guest')
    ),
    
    '/%s/module/advice/tweak' => array(
        'controller' => 'Admin:Advice@tweakAction',
        'disallow' => array('guest')
    ),
    
    '/%s/module/advice/add' => array(
        'controller' => 'Admin:Advice@addAction'
    ),
    
    '/%s/module/advice/edit/(:var)' => array(
        'controller' => 'Admin:Advice@editAction'
    ),
    
    '/%s/module/advice/save' => array(
        'controller' => 'Admin:Advice@saveAction',
        'disallow' => array('guest')
    )
);
