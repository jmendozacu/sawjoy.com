<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Advanced Sphinx Search Pro
 * @version   2.3.25
 * @build     990
 * @copyright Copyright (C) 2017 Mirasvit (http://mirasvit.com/)
 */



class Mirasvit_SearchIndex_Model_Index_Tm_Knowledgebase_Faq_Indexer extends Mirasvit_SearchIndex_Model_Indexer_Abstract
{
    protected function _getSearchableEntities($storeId, $entityIds, $lastEntityId, $limit = 100)
    {
        $collection = Mage::getModel('knowledgebase/faq')->getCollection();
        $collection->addFieldToFilter('status', 1);

        if ($entityIds) {
            $collection->addFieldToFilter('id', array('in' => $entityIds));
        }

        $collection->getSelect()->where('main_table.id > ?', $lastEntityId)
            ->limit($limit)
            ->order('main_table.id');

        return $collection;
    }
}
