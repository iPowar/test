<?php
namespace Acme\ApiBundle\Handler;

use Acme\ApiBundle\Entity\Category;
use Acme\ApiBundle\Entity\CategoryRepository;
use Acme\ApiBundle\Entity\Product;
use Acme\ApiBundle\Entity\ProductRepository;
use Acme\ApiBundle\Entity\User;
use Acme\ApiBundle\Exception\ApiException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @author Mikhail Kudryashov <mikhail.kudryashov@opensoftdev.ru>
 */
class ProductHandler
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param TokenStorage $tokenStorage
     * @param ManagerRegistry $doctrine
     */
    public function __construct(TokenStorage $tokenStorage, ManagerRegistry $doctrine)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $doctrine->getManager();
        $this->categoryRepository = $doctrine->getRepository(Category::class);
        $this->productRepository = $doctrine->getRepository(Product::class);
    }

    /**
     * @param string $name
     * @param int $categoryId
     * @return Product
     * @throws ApiException
     */
    public function createProduct($name, $categoryId)
    {
        $product = new Product();
        $product->setName($name);
        $product->setOwner($this->getCurrentUser());
        $category = $this->getCategoryById($categoryId);
        if ($category !== null) {
            $product->setCategory($category);
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush($product);
        
        return $product;
    }

    /**
     * @param int $productId
     * @param string|null $name
     * @param int|null $categoryId
     * @return Product
     * @throws ApiException
     */
    public function updateProduct($productId, $name = null, $categoryId = null)
    {
        $product = $this->productRepository->find($productId);

        if ($productId === null) {
            throw ApiException::NotFoundException();
        }

        if ($product->getOwner() !== $this->getCurrentUser()) {
            throw ApiException::AccessDeniedException();
        }

        if ($name !== null) {
            $product->setName($name);
        }

        if ($categoryId) {
            $product->setCategory($this->getCategoryById($categoryId));
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush($product);

        return $product;
    }

    /**
     * @param int $id
     * @throws ApiException
     */
    public function deleteProduct($id)
    {
        $product = $this->productRepository->find($id);

        if ($id === null) {
            throw ApiException::NotFoundException();
        }

        if ($product->getOwner() !== $this->getCurrentUser()) {
            throw ApiException::AccessDeniedException();
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    /**
     * @return User
     * @throws ApiException
     */
    private function getCurrentUser()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        
        if (!$user) {
            throw ApiException::notAuthorizedException();
        }
        
        return $user;
    }

    /**
     * @param int $categoryId
     * @return null|Category
     */
    private function getCategoryById($categoryId)
    {
        $category = $this->categoryRepository->find($categoryId);

        return $category;
    }
}
