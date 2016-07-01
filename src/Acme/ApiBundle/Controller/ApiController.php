<?php
namespace Acme\ApiBundle\Controller;

use Acme\ApiBundle\Entity\Product;
use Acme\ApiBundle\Entity\ProductRepository;
use Acme\ApiBundle\Exception\ApiException;
use Acme\ApiBundle\Handler\ProductHandler;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Mikhail Kudryashov <mikhail.kudryashov@opensoftdev.ru>
 */
class ApiController extends FOSRestController
{
    /**
     * @return Response
     */
    public function getProductsAction()
    {
        $products = $this->getProductRepository()->findAll();
        $view = $this->view($products);
        
        return $this->handleView($view);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function getProductAction($id)
    {
        $product = $this->getProductRepository()->find($id);
        $view = $this->view($product);

        return $this->handleView($view);
    }

    /**
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="name", nullable=false, strict=true, description="Name.")
     * @RequestParam(name="category", nullable=false, strict=true, description="Category.")
     *
     * @return Response
     */
    public function postProductAction(ParamFetcher $paramFetcher)
    {
        try {
            $product = $this->getProductHandler()->createProduct($paramFetcher->get('name'), $paramFetcher->get('category'));
            $view = $this->view($product);
        } catch (\Exception $e) {
            $view = $this->view($e->getMessage(), $e->getCode());
        }

        return $this->handleView($view);
    }

    /**
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="product", nullable=false, strict=true, description="Product.")
     * @RequestParam(name="name", nullable=false, strict=true, description="Name.")
     * @RequestParam(name="category", nullable=false, strict=true, description="Category.")
     *
     * @return Response
     */
    public function pathProductAction(ParamFetcher $paramFetcher)
    {
        try {
            $product = $this->getProductHandler()->updateProduct(
                $paramFetcher->get('product'),
                $paramFetcher->get('name'),
                $paramFetcher->get('category')
            );
            $view = $this->view($product);
        } catch (\Exception $e) {
            $view = $this->view($e->getMessage(), $e->getCode());
        }

        return $this->handleView($view);
    }

    /**
     * @param int $id
     * @return Response
     * @throws ApiException
     */
    public function deleteProductAction($id)
    {
        try {
            $this->getProductHandler()->deleteProduct($id);

            $view = View::create();
            $view->setData("product deleted")->setStatusCode(204);
        } catch (\Exception $e) {
            $view = $this->view($e->getMessage(), $e->getCode());
        }

        return $view;
    }

    /**
     * @return ProductRepository
     */
    private function getProductRepository()
    {
        return $this->getEntityManager()->getRepository(Product::class);
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return ProductHandler
     */
    private function getProductHandler()
    {
        return $this->get('product_handler');
    }
}
