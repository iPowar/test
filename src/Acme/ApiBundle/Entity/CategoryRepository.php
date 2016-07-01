<?php
namespace Acme\ApiBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Mikhail Kudryashov <mikhail.kudryashov@opensoftdev.ru>
 */
class CategoryRepository extends EntityRepository
{
    /**
     * @param $id
     */
    public function requestById($id)
    {
        $category = $this->find($id);
        
        if ($category === null) {
            throw new NotFoundHttpException();
        }
    }
}
