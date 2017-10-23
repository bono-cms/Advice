<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Advice\Storage\MySQL;

use Cms\Storage\MySQL\AbstractMapper;
use Advice\Storage\AdviceMapperInterface;

final class AdviceMapper extends AbstractMapper implements AdviceMapperInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return self::getWithPrefix('bono_module_advice');
    }

    /**
     * {@inheritDoc}
     */
    public static function getTranslationTable()
    {
        return AdviceTranslationMapper::getTableName();
    }

    /**
     * Returns shared columns
     * 
     * @return array
     */
    private function getColumns()
    {
        return array(
            self::getFullColumnName('id'),
            self::getFullColumnName('published'),
            self::getFullColumnName('icon'),
            AdviceTranslationMapper::getFullColumnName('lang_id'),
            AdviceTranslationMapper::getFullColumnName('title'),
            AdviceTranslationMapper::getFullColumnName('content')
        );
    }

    /**
     * Returns shared select
     * 
     * @param boolean $published
     * @param boolean $rand Whether to select random record
     * @return \Krystal\Db\Sql\Db
     */
    private function getSelectQuery($published, $rand = false)
    {
        $db = $this->createEntitySelect($this->getColumns())
                   ->whereEquals(AdviceTranslationMapper::getFullColumnName('lang_id'), $this->getLangId());

        if ($published === true) {
            $db->andWhereEquals(self::getFullColumnName('published'), '1');
        }

        if ($rand === true) {
            $db->orderBy()
               ->rand();
        } else {
            $db->orderBy(self::getFullColumnName('id'))
               ->desc();
        }

        return $db;
    }

    /**
     * Updates published state by advice's associated id
     * 
     * @param string $id Advice id
     * @param string $published Either 0 or 1
     * @return boolean
     */
    public function updatePublishedById($id, $published)
    {
        return $this->updateColumnByPk($id, 'published', $published);
    }

    /**
     * Fetches a random published advice
     * 
     * @return array
     */
    public function fetchRandom()
    {
        return $this->getSelectQuery(true, true)
                    ->limit(1)
                    ->query();
    }

    /**
     * Fetches all advices filtered by pagination
     * 
     * @param integer $page Current page
     * @param integer $itemsPerPage Per page count
     * @param boolean $published Whether to filter by published attribute
     * @return array
     */
    public function fetchAllByPage($page, $itemsPerPage, $published)
    {
        return $this->getSelectQuery($published)
                    ->paginate($page, $itemsPerPage)
                    ->queryAll();
    }

    /**
     * Fetches all advices
     * 
     * @param boolean $published Whether to filter by published attribute
     * @return array
     */
    public function fetchAll($published)
    {
        return $this->getSelectQuery($published)
                    ->queryAll();
    }

    /**
     * Fetches block data by its associated id
     * 
     * @param string $id Block id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findEntity($this->getColumns(), $id, $withTranslations);
    }

    /**
     * Fetches advice's title by its associated id
     * 
     * @param string $id Advice id
     * @return string
     */
    public function fetchTitleById($id)
    {
        return $this->findColumnByPk($id, 'title');
    }
}
