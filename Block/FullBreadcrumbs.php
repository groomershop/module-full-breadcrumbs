<?php
/**
 * Copyright © EAdesign by Eco Active S.R.L.,All rights reserved.
 * See LICENSE for license details.
 */
namespace Groomershop\FullBreadcrumbs\Block;

use Magento\Catalog\Helper\Data;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Registry;
use Magento\Framework\Api\AttributeValue;
use Groomershop\FullBreadcrumbs\Helper\Data as BreadcrumbsData;

class FullBreadcrumbs extends \Magento\Framework\View\Element\Template
{
    /**
     * Catalog data
     *
     * @var Data
     */
    private $catalogData = null;
    private $registry;
    private $categoryCollection;
    private $breadcrumbsData;
    public $excluded_categories_ids;
    public $enabled;

    /**
     * @param Context $context
     * @param Data $catalogData
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $catalogData,
        Registry $registry,
        BreadcrumbsData $breadcrumbsData,
        CollectionFactory $categoryCollection,
        array $data = []
    ) {
        $this->catalogData = $catalogData;
        $this->registry = $registry;
        $this->breadcrumbsData = $breadcrumbsData;
        $this->categoryCollection = $categoryCollection;
        parent::__construct($context, $data);
    }

    private function getExcludedCategoriesIds()
    {
        $excluded_categories_ids = $this->breadcrumbsData->hasConfig('full_breadcrumbs/general/excluded_categories_ids');
        return explode(',', str_replace(' ', '', $excluded_categories_ids));
    }

    private function isEnabled()
    {
        return $this->breadcrumbsData->hasConfig('full_breadcrumbs/general/enabled');
    }

    private function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    private function getProductCategories()
    {
        $productCategories = $this->getCurrentProduct()->getCategoryCollection();
        if (count($productCategories) === 0) {
            return [];
        }

        $productCategory = current(iterator_to_array($productCategories));
        $categories = $productCategory->getParentCategories();
        $excludedCategoriesIds = $this->getExcludedCategoriesIds();
        $filteredCategories = array_filter(
            $categories,
            function ($category) use($excludedCategoriesIds) {
                return !in_array($category->getId(), $excludedCategoriesIds);
            }
        );
        return $filteredCategories;
    }

    public function getProductBreadcrumbs()
    {
        if (!$this->isEnabled()) {
            return [];
        }

        $breadcrumbs = [];
        $categories = $this->getProductCategories();

        $breadcrumbs['home'] = [
            'link' => $this->_storeManager->getStore()->getBaseUrl(),
            'label' => __('Home'),
            'title' => null,
            'last' => false
        ];
        foreach ($categories as $category) {
            $breadcrumbs[ 'category' . $category->getId() ] = [
                'link' => $category->getUrl(),
                'label' => $category->getData('name'),
                'title' => null,
                'last' => false
            ];
        }

        $breadcrumbs['product'] = [
            'link' => null,
            'label' => $this->getCurrentProduct()->getName(),
            'title' => null,
            'last' => true
        ];

        return $breadcrumbs;
    }
}
