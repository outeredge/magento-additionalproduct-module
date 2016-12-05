<?php

namespace OuterEdge\AdditionalProduct\Plugin;

class AddPlugin {

    public function aroundAddProductsByIds(\Magento\Checkout\Model\Cart $subject, callable $proceed, $productIds)
    {
        $allAdded = true;
        if (!empty($productIds)) {
            foreach ($productIds as $key => $productId) {

                $productId = (int)$productId;
                if (!$productId) {
                    continue;
                }
                $product = $this->getLoadProduct($productId);

                if ($product->getTypeId() === 'bundle') {

                    $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $options = $_objectManager->get('Magento\Bundle\Model\Option')
                        ->getResourceCollection()
                        ->setProductIdFilter($product->getId())
                        ->setPositionOrder();
                    $options->joinValues($product->getStore()->getWebsiteId());

                    $typeInstance = $_objectManager->get('Magento\Bundle\Model\Product\Type');
                    $selections = $typeInstance->getSelectionsCollection($typeInstance->getOptionsIds($product), $product);

                    $params = [];
                    foreach ($selections as $sel) {
                        $params['bundle_option'][$sel->getOptionId()] = [$sel->getId() => $sel->getSelectionId()];
                    }

                    if ($params) {
                        try {
                            $subject->addProduct($product, $params);
                        } catch (\Exception $e) {
                            $allAdded = false;
                        }
                    }
                }
            }
            unset($productIds[$key]);
        }

        return $proceed($productIds);
    }

    protected function getLoadProduct($productInfo)
    {
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        return $_objectManager->get('Magento\Catalog\Model\Product')->load($productInfo);
    }
}