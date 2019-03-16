<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Service;

use Cms\Service\AbstractManager;
use Advice\Storage\CategoryMapperInterface;
use Krystal\Stdlib\VirtualEntity;

final class CategoryService extends AbstractManager
{
    /**
     * Any compliant category mapper
     * 
     * @var \Advice\Storage\CategoryMapperInterface
     */
    private $categoryMapper;

    /**
     * State initialization
     * 
     * @param \Advice\Storage\CategoryMapperInterface $categoryMapper
     * @return void
     */
    public function __construct(CategoryMapperInterface $categoryMapper)
    {
        $this->categoryMapper = $categoryMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $row)
    {
        $entity = new VirtualEntity();
        $entity->setId($row['id'])
               ->setName($row['name']);

        return $entity;
    }

    /**
     * Returns last category id
     * 
     * @return int
     */
    public function getLastId()
    {
        return $this->categoryMapper->getMaxId();
    }

    /**
     * Fetch all categories
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->prepareResults($this->categoryMapper->fetchAll());
    }

    /**
     * Fetches a category by its ID
     * 
     * @param int $id Category ID
     * @return mixed
     */
    public function fetchById($id)
    {
        return $this->prepareResult($this->categoryMapper->findByPk($id));
    }

    /**
     * Deletes a category by its ID
     * 
     * @param int $id Category ID
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->categoryMapper->deleteByPk($id);
    }

    /**
     * Saves a category
     * 
     * @param array $input
     * @return boolean
     */
    public function save(array $input)
    {
        return $this->categoryMapper->persist($input);
    }
}
