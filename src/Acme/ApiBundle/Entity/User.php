<?php
namespace Acme\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table("users")
 * @ORM\Entity
 *
 * @author Mikhail Kudryashov <mikhail.kudryashov@opensoftdev.ru>
 */
class User extends BaseUser
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @var integer
     */
    protected $id;
    
    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct() {
        parent::__construct();

        $this->products = new ArrayCollection();
    }
}
