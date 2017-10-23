<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Storage;

interface AdviceMapperInterface
{
    /**
     * Updates published state by associated id
     * 
     * @param string $id
     * @param string $published
     * @return boolean
     */
    public function updatePublishedById($id, $published);

    /**
     * Fetches a random advice
     * 
     * @return array
     */
    public function fetchRandom();

    /**
     * Fetches all advices filtered by pagination
     * 
     * @param integer $page Current page
     * @param integer $itemsPerPage Items per page count
     * @param boolean $published Whether to filter by published attribute
     * @return array
     */
    public function fetchAllByPage($page, $itemsPerPage, $published);

    /**
     * Fetches all advices
     * 
     * @param boolean $published Whether to filter by published attribute
     * @return array
     */
    public function fetchAll($published);

    /**
     * Fetches block data by its associated id
     * 
     * @param string $id Block id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations);
}
