<?php

namespace OuterEdge\AdditionalProduct\Model;

class Product extends \Magento\Catalog\Model\Product {

    /**
     * Retrieve array of additional products
     *
     * @return array
     */
    public function getAdditionalProducts()
    {
        if (!$this->hasAdditionalProducts()) {
            $products = [];
            foreach ($this->getAdditionalProductCollection() as $product) {
                $products[] = $product;
            }
            $this->setAdditionalProducts($products);
        }
        return $this->getData('additional_products');
    }

    /**
     * Retrieve additional products identifiers
     *
     * @return array
     */
    public function getAdditionalProductIds()
    {
        if (!$this->hasAdditionalProducts()) {
            $ids = [];
            foreach ($this->getAdditionalProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setAdditionalProductIds($ids);
        }
        return $this->getData('additional_product_ids');
    }

    /**
     * Retrieve collection additional product
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getAdditionalProductCollection()
    {
        $collection = $this->getLinkInstance()->useAdditionalLinks()->getProductCollection()->setIsStrongMode();
        $collection->setProduct($this);
        return $collection;
    }

    /**
     * Retrieve collection additional
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Collection
     */
    public function getAdditionallLinkCollection()
    {
        $collection = $this->getLinkInstance()->useAdditionalLinks()->getLinkCollection();
        $collection->setProduct($this);
        $collection->addLinkTypeIdFilter();
        $collection->addProductIdFilter();
        $collection->joinAttributes();
        return $collection;
    }

}