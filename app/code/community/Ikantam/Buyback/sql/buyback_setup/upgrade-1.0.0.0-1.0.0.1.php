<?php

$attributeCode = 'price';
$attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode(4, $attributeCode);
$applyToString = $attributeModel->getApplyTo();

$applyTo = explode(',', $applyToString);

if (!in_array('buyback', $applyTo)) {
    $applyTo[] = 'buyback';
}

$newApplyToString = implode(',',$applyTo);
$attributeModel->setApplyTo($newApplyToString)
    ->save();


