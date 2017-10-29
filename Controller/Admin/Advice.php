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
        // Append a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('Advices');

        // Grab a service
        $adviceManager = $this->getModuleService('adviceManager');

        // Configure pagination
        $paginator = $adviceManager->getPaginator();
        $paginator->setUrl($this->createUrl('Advice:Admin:Advice@gridAction', array(), 1));

        return $this->view->render('browser', array(
            'advices' => $adviceManager->fetchAllByPage($page, $this->getSharedPerPageCount(), false),
            'paginator' => $paginator,
        ));
    }

    /**
     * Returns a form
     * 
     * @param \Krystal\Stdlib\VirtualEntity|array $advice
     * @param string $title
     * @return string
     */
    private function createForm($advice, $title)
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
        $advice = $this->getModuleService('adviceManager')->fetchById($id, true);

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
        $this->getModuleService('adviceManager')->updateSettings($this->request->getPost());

        $this->flashBag->set('success', 'Settings have been updated successfully');
        return '1';
    }

    /**
     * Removes selected advice by its id
     * 
     * @param string $id
     * @return string
     */
    public function deleteAction($id)
    {
        $service = $this->getModuleService('adviceManager');

        // Batch removal
        if ($this->request->hasPost('toDelete')) {
            $ids = array_keys($this->request->getPost('toDelete'));

            $service->deleteByIds($ids);
            $this->flashBag->set('success', 'Selected elements have been removed successfully');

        } else {
            $this->flashBag->set('warning', 'You should select at least one element to remove');
        }

        // Single removal
        if (!empty($id)) {
            $service->deleteById($id);
            $this->flashBag->set('success', 'Selected element has been removed successfully');
        }

        return '1';
    }

    /**
     * Persists an advice
     * 
     * @return string
     */
    public function saveAction()
    {
        $input = $this->request->getPost();

        $formValidator = $this->createValidator(array(
            'input' => array(
                'source' => $input,
                'definition' => array(
                    'title' => new Pattern\Title(),
                    'content' => new Pattern\Content()
                )
            )
        ));

        if (1) {
            $service = $this->getModuleService('adviceManager');

            if (!empty($input['advice']['id'])) {
                if ($service->update($input)) {
                    $this->flashBag->set('success', 'The element has been updated successfully');
                    return '1';
                }

            } else {
                if ($service->add($input)) {
                    $this->flashBag->set('success', 'The element has been created successfully');
                    return $service->getLastId();
                }
            }

        } else {
            return $formValidator->getErrors();
        }
    }
}
