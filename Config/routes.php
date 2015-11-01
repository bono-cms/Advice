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
        'controller' => 'Admin:Browser@indexAction'
    ),
    
    '/admin/module/advice/page/(:var)' => array(
        'controller' => 'Admin:Browser@indexAction'
    ),
    
    '/admin/module/advice/delete.ajax' => array(
        'controller' => 'Admin:Browser@deleteAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/advice/save.ajax' => array(
        'controller' => 'Admin:Browser@saveAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/advice/delete-selected.ajax' => array(
        'controller' => 'Admin:Browser@deleteSelectedAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/advice/add' => array(
        'controller' => 'Admin:Add@indexAction'
    ),
    
    '/admin/module/advice/add.ajax' => array(
        'controller' => 'Admin:Add@addAction',
        'disallow' => array('guest')
    ),
    
    '/admin/module/advice/edit/(:var)' => array(
        'controller' => 'Admin:Edit@indexAction'
    ),
    
    '/admin/module/advice/edit.ajax' => array(
        'controller' => 'Admin:Edit@updateAction',
        'disallow' => array('guest')
    )
);
