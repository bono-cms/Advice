<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Controller\Admin;

use Cms\Controller\Admin\AbstractController;
use Krystal\Validate\Pattern;
use Krystal\Stdlib\VirtualEntity;

final class Advice extends AbstractController
{
    /**
     * Shows a grid
     * 
     * @param integer $page Current page
     * @return string
     */
    public function gridAction($page = 1)
    {
        // Load view plugins
        $this->view->getPluginBag()
                   ->appendScript('@Advice/admin/browser.js');

        // Append a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('Advices');

        // Grab a service
        $adviceManager = $this->getModuleService('adviceManager');

        // Configure pagination
        $paginator = $adviceManager->getPaginator();
        $paginator->setUrl('/admin/module/advice/page/%s');

        return $this->view->render('browser', array(
            'advices' => $adviceManager->fetchAllByPage($page, $this->getSharedPerPageCount()),
            'paginator' => $paginator,
        ));
    }

    /**
     * Returns a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $advice
     * @param string $title
     * @return string
     */
    private function createForm(VirtualEntity $advice, $title)
    {
        // Load view plugins
        $this->view->getPluginBag()
                   ->load($this->getWysiwygPluginName());

        // Append breadcrumbs
        $this->view->getBreadcrumbBag()->addOne('Advice', 'Advice:Admin:Advice@gridAction')
                                       ->addOne($title);

        return $this->view->render('advice.form', array(
            'advice' => $advice
        ));
    }

    /**
     * Renders empty form
     * 
     * @return string
     */
    public function addAction()
    {
        $advice = new VirtualEntity();
        $advice->setPublished(true);

        return $this->createForm($advice, 'Add an advice');
    }

    /**
     * Renders edit form
     * 
     * @param string $id
     * @return string
     */
    public function editAction($id)
    {
        $advice = $this->getModuleService('adviceManager')->fetchById($id);

        if ($advice !== false) {
            return $this->createForm($advice, 'Edit the advice');
        } else {
            return false;
        }
    }

    /**
     * Save changes
     * 
     * @return string
     */
    public function tweakAction()
    {
        if ($this->request->hasPost('published')) {
            $published = $this->request->getPost('published');

            $this->getModuleService('adviceManager')->updatePublished($published);
            $this->flashBag->set('success', 'Settings have been updated successfully');
            return '1';
        }
    }

    /**
     * Removes selected advice by its id
     * 
     * @return string
     */
    public function deleteAction()
    {
        return $this->invokeRemoval('adviceManager');
    }

    /**
     * Persists an advice
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost('advice');

        return $this->invokeSave('adviceManager', $input['id'], $input, array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'title' => new Pattern\Title(),
                    'content' => new Pattern\Content()
                )
            )
        ));
    }
}
