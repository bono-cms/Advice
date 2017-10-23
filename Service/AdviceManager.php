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

final class AdviceManager extends AbstractManager implements AdviceManagerInterface
{
    /**
     * Any compliant advice mapper
     * 
     * @var \Advice\Storage\AdviceMapperInterface
     */
    private $adviceMapper;

    /**
     * History manager to keep track of latest actions
     * 
     * @var \Cms\Storage\HistoryManagerInterface
     */
    private $historyManager;

    /**
     * State initialization
     * 
     * @param \Advice\Storage\AdviceMapperInterface $adviceMapper
     * @param \Cms\Service\HistoryManagerInterface $historyManager
     * @return void
     */
    public function __construct(AdviceMapperInterface $adviceMapper, HistoryManagerInterface $historyManager)
    {
        $this->adviceMapper = $adviceMapper;
        $this->historyManager = $historyManager;
    }

    /**
     * Tracks activity
     * 
     * @param string $message
     * @param string $placeholder
     * @return boolean
     */
    private function track($message, $placeholder)
    {
        return $this->historyManager->write('Advice', $message, $placeholder);
    }

    /**
     * Update published state by their associated ids
     * 
     * @param array $pair
     * @return boolean
     */
    public function updatePublished(array $pair)
    {
        foreach ($pair as $id => $published) {
            if (!$this->adviceMapper->updatePublishedById($id, $published)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function toEntity(array $advice)
    {
        $entity = new VirtualEntity();
        $entity->setId($advice['id'], VirtualEntity::FILTER_INT)
                ->setLangId($advice['lang_id'], VirtualEntity::FILTER_INT)
                ->setTitle($advice['title'], VirtualEntity::FILTER_HTML)
                ->setIcon($advice['icon'], VirtualEntity::FILTER_HTML)
                ->setContent($advice['content'], VirtualEntity::FILTER_SAFE_TAGS)
                ->setPublished($advice['published'], VirtualEntity::FILTER_BOOL);

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
     * Fetches all advice entities
     * 
     * @param boolean $published Whether to filter by published attribute
     * @return array
     */
    public function fetchAll($published)
    {
        return $this->prepareResults($this->adviceMapper->fetchAll($published));
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
    public function add(array $input)
    {
        // $this->track('Advice "%s" has been added', $input['title']);
        return $this->adviceMapper->saveEntity($input['advice'], $input['translation']);
    }

    /**
     * Updates an advice
     * 
     * @param array $input Raw input data
     * @return boolean
     */
    public function update(array $input)
    {
        // $this->track('Advice "%s" has been updated', $input['title']);
        return $this->adviceMapper->saveEntity($input['advice'], $input['translation']);
    }

    /**
     * Deletes an advice by its associated id
     * 
     * @param string $id Advice id
     * @return boolean
     */
    public function deleteById($id)
    {
        // Grab advice's title before we remove id
        //$title = Filter::escape($this->adviceMapper->fetchTitleById($id));

        if ($this->adviceMapper->deleteEntity($id)) {
            //$this->track('Advice "%s" has been removed', $title);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete advices by their associated ids
     * 
     * @param array $ids
     * @return boolean
     */
    public function deleteByIds(array $ids)
    {
        $this->track('Batch removal of %s advices', count($ids));
        $this->adviceMapper->deleteEntity($ids);

        return true;
    }
}
