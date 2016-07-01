<?php
namespace Acme\ApiBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * @author Mikhail Kudryashov <mikhail.kudryashov@opensoftdev.ru>
 */
class ProductRepository extends EntityRepository
{
    public function createProduct()
    {
        $product = new Product();
        
        
        return $product;
    }
}