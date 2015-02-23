<?php
namespace Application\Models;

interface MMDataMapperInterface
{
    public function createRow($element);
    public function updateRow($element);
    public function deleteOne($element);
    public function deleteRows($where);

    public function fetchOne($selectKey);
    public function fetchAllDefault($selectKey, $orderBy);
    public function countResults($selectKey);
}
