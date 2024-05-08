<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Repository\ProductRepository;
use App\Service\Serializer\DTOSerializer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductsController extends AbstractController
{
    public function __construct(private ProductRepository $repository, private EntityManagerInterface $entityManager)
    {

    }

    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(Request $request, DTOSerializer $serializer, int $id): Response
    {
        /** @var LowestPriceEnquiry $lowestPriceEnquiry */
        $lowestPriceEnquiry = $serializer->deserialize($request->getContent(), LowestPriceEnquiry::class, 'json');

        $product = $this->repository->find($id);
        $lowestPriceEnquiry->setProduct($product);

        $promotions = $this->entityManager->getRepository(Promotion::class)->findValidForProduct(
            $product,
            date_create_immutable($lowestPriceEnquiry->getRequestDate())
        );

        dd($promotions);
//        $lowestPriceEnquiry->setDiscountedPrice(50);
//        $lowestPriceEnquiry->setPrice(100);
//        $lowestPriceEnquiry->setPromotionId(3);
//        $lowestPriceEnquiry->setPromotionName('The Best!');

        $responseContent = $serializer->serialize($lowestPriceEnquiry, 'json');

        return new Response($responseContent, 200);
    }
}