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
    
    '/admin/module/advice' => array(
        'controller' => 'Admin:Advice@gridAction'
    ),
    
    '/admin/module/advice/page/(:var)' => array(
        'controller' => 'Admin:Advice@gridAction'
    ),
    
    '/admin/module/advice/delete' => array(
        'controller' => 'Admin:Advice@deleteAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/advice/tweak' => array(
        'controller' => 'Admin:Advice@tweakAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/advice/add' => array(
        'controller' => 'Admin:Advice@addAction'
    ),
    
    '/admin/module/advice/edit/(:var)' => array(
        'controller' => 'Admin:Advice@editAction'
    ),
    
    '/admin/module/advice/save' => array(
        'controller' => 'Admin:Advice@saveAction',
        'disallow' => array('guest')
    )
);
