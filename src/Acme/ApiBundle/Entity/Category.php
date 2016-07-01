<?php
namespace Acme\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Acme\ApiBundle\Entity\CategoryRepository")
 * 
 * @author Mikhail Kudryashov <mikhail.kudryashov@opensoftdev.ru>
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     * 
     * @var Product[]|ArrayCollection
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }
}
