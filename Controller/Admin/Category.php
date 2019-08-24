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
use Krystal\Stdlib\VirtualEntity;

final class Category extends AbstractController
{
    /**
     * Creates form
     * 
     * @param \Krystal\Stdlib\VirtualEntity $category
     * @param string $title Page title
     * @return string
     */
    private function createForm(VirtualEntity $category, $title)
    {
        // Append a breadcrumb
        $this->view->getBreadcrumbBag()
                   ->addOne('Advices', 'Advice:Admin:Advice@gridAction')
                   ->addOne($title);

        return $this->view->render('category.form', array(
            'category' => $category
        ));
    }

    /**
     * Renders add form
     * 
     * @return string
     */
    public function addAction()
    {
        return $this->createForm(new VirtualEntity, 'Add a category');
    }

    /**
     * Renders edit form
     * 
     * @param int $id Category ID
     * @return boolean
     */
    public function editAction($id)
    {
        $category = $this->getModuleService('categoryService')->fetchById($id);

        if ($category !== false) {
            return $this->createForm($category, $this->translator->translate('Edit the category "%s"', $category->getName()));
        } else {
            return false;
        }
    }

    /**
     * Deletes a category by its ID
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteAction($id)
    {
        $this->getModuleService('categoryService')->deleteById($id);

        $this->flashBag->set('success', 'Selected element has been removed successfully');
        return 1;
    }

    /**
     * Saves a category
     * 
     * @return boolean
     */
    public function saveAction()
    {
        $input = $this->request->getPost('category');

        $categoryService = $this->getModuleService('categoryService');
        $categoryService->save($input);

        if ($input['id']) {
            $this->flashBag->set('success', 'The element has been updated successfully');
            return 1;
        } else {
            $this->flashBag->set('success', 'The element has been created successfully');
            return $categoryService->getLastId();
        }
    }
}
