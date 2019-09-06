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
            self::column('id'),
            self::column('category_id'),
            self::column('published'),
            self::column('icon'),
            AdviceTranslationMapper::column('lang_id'),
            AdviceTranslationMapper::column('title'),
            AdviceTranslationMapper::column('content')
        );
    }

    /**
     * Returns shared select
     * 
     * @param boolean $published
     * @param boolean $rand Whether to select random record
     * @param int $categoryId Optional category ID constraint
     * @return \Krystal\Db\Sql\Db
     */
    private function createSelect($published, $rand = false, $categoryId = null)
    {
        // Columns to be selected
        $columns = array_merge(
            array(
                CategoryMapper::column('name') => 'category'
            ),
            $this->getColumns()
        );

        $db = $this->createEntitySelect($columns)
                   // Category relation
                   ->leftJoin(CategoryMapper::getTableName(), array(
                        CategoryMapper::column('id') => self::getRawColumn('category_id')
                   ))
                   ->whereEquals(AdviceTranslationMapper::column('lang_id'), $this->getLangId());

        if ($published === true) {
            $db->andWhereEquals(self::column('published'), '1');
        }

        // Apply category ID constraint if provided
        if ($categoryId !== null) {
            $db->andWhereEquals(self::column('category_id'), $categoryId);
        }

        if ($rand === true) {
            $db->orderBy()
               ->rand();
        } else {
            $db->orderBy(self::column('id'))
               ->desc();
        }

        return $db;
    }

    /**
     * Update settings
     * 
     * @param array $settings
     * @return boolean
     */
    public function updateSettings($settings)
    {
        return $this->updateColumns($settings, array('published'));
    }

    /**
     * Fetches a random published advice
     * 
     * @return array
     */
    public function fetchRandom()
    {
        return $this->createSelect(true, true)
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
        return $this->createSelect($published)
                    ->paginate($page, $itemsPerPage)
                    ->queryAll();
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
        return $this->createSelect($published, false, $categoryId)
                    ->queryAll();
    }

    /**
     * Fetches advice data by its associated id
     * 
     * @param string $id Advice id
     * @param boolean $withTranslations Whether to fetch translations or not
     * @return array
     */
    public function fetchById($id, $withTranslations)
    {
        return $this->findEntity($this->getColumns(), $id, $withTranslations);
    }
}
