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
use Cms\Service\HistoryManagerInterface;
use Advice\Storage\AdviceMapperInterface;
use Krystal\Stdlib\VirtualEntity;
use Krystal\Security\Filter;

final class AdviceManager extends AbstractManager
{
    /**
     * Any compliant advice mapper
     * 
     * @var \Advice\Storage\AdviceMapperInterface
     */
    private $adviceMapper;

    /**
     * State initialization
     * 
     * @param \Advice\Storage\AdviceMapperInterface $adviceMapper
     * @return void
     */
    public function __construct(AdviceMapperInterface $adviceMapper)
    {
        $this->adviceMapper = $adviceMapper;
    }

    /**
     * Update settings
     * 
     * @param array $settings
     * @return boolean
     */
    public function updateSettings(array $settings)
    {
        return $this->adviceMapper->updateSettings($settings);
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $advice)
    {
        $entity = new VirtualEntity();
        $entity->setId($advice['id'], VirtualEntity::FILTER_INT)
                ->setLangId($advice['lang_id'], VirtualEntity::FILTER_INT)
                ->setCategoryId($advice['category_id'], VirtualEntity::FILTER_INT)
                ->setTitle($advice['title'], VirtualEntity::FILTER_HTML)
                ->setIcon($advice['icon'], VirtualEntity::FILTER_HTML)
                ->setContent($advice['content'], VirtualEntity::FILTER_SAFE_TAGS)
                ->setPublished($advice['published'], VirtualEntity::FILTER_BOOL);

        if (isset($advice['category'])) {
            $entity->setCategory($advice['category'], VirtualEntity::FILTER_SAFE_TAGS);
        }

        return $entity;
    }

    /**
     * Fetches random advice entity
     * 
     * @return boolean|\Krystal\Stdlib\VirtualEntity
     */
    public function fetchRandom()
    {
        return $this->prepareResult($this->adviceMapper->fetchRandom());
    }

    /**
     * Fetches advices's entity by its associated id
     * 
     * @param string $id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return \Krystal\Stdlib\VirtualEntity|boolean
     */
    public function fetchById($id, $withTranslations)
    {
        if ($withTranslations == true) {
            return $this->prepareResults($this->adviceMapper->fetchById($id, true));
        } else {
            return $this->prepareResult($this->adviceMapper->fetchById($id, false));
        }
    }

    /**
     * Fetches all advices
     * 
     * @param boolean $published Whether to filter by published attribute
     * @param int $categoryId Optional category ID constraint
     * @return array
     */
    public function fetchAll($published, $categoryId = null)
    {
        return $this->prepareResults($this->adviceMapper->fetchAll($published, $categoryId));
    }

    /**
     * Fetches all advice entities filtered by pagination
     * 
     * @param integer $page Current page
     * @param integer $itemsPerPage Items per page count
     * @param boolean $published Whether to filter by published attribute
     * @return array
     */
    public function fetchAllByPage($page, $itemsPerPage, $published)
    {
        return $this->prepareResults($this->adviceMapper->fetchAllByPage($page, $itemsPerPage, $published));
    }

    /**
     * Returns prepared paginator's instance
     * 
     * @return \Krystal\Paginate\Paginator
     */
    public function getPaginator()
    {
        return $this->adviceMapper->getPaginator();
    }

    /**
     * Returns last advice id
     * 
     * @return integer
     */
    public function getLastId()
    {
        return $this->adviceMapper->getLastId();
    }

    /**
     * Adds an advice
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function save(array $input)
    {
        return $this->adviceMapper->saveEntity($input['advice'], $input['translation']);
    }

    /**
     * Deletes an advice by its associated id
     * 
     * @param string|array $id Advice id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->adviceMapper->deleteEntity($id);
    }
}
