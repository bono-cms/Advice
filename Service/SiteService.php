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

final class SiteService
{
    /**
     * Advice manager service
     * 
     * @var \Advice\Service\AdviceManager
     */
    private $adviceManager;

    /**
     * State initialization
     * 
     * @param \Advice\Service\AdviceManager $adviceManager
     * @return void
     */
    public function __construct(AdviceManager $adviceManager)
    {
        $this->adviceManager = $adviceManager;
    }

    /**
     * Returns all advice entities
     * 
     * @param int $categoryId Optional category ID constraint
     * @return array
     */
    public function getAll($categoryId = null)
    {
        return $this->adviceManager->fetchAll(true, $categoryId);
    }

    /**
     * Returns random advice entity
     * 
     * @return \Krystal\Stdlib\VirtualEntity
     */
    public function getRandom()
    {
        return $this->adviceManager->fetchRandom();
    }

    /**
     * Finds an advice by its associated id and returns its entity
     * 
     * @param string $id
     * @return \Krystal\Stdlib\VirtualEntity|boolean
     */
    public function getById($id)
    {
        return $this->adviceManager->fetchById($id, false);
    }
}
