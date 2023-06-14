<?php

namespace App\Controller\Api\Purchase\PurchaseAction;

use App\Controller\Api\Purchase\DTO\PurchaseProductDto;
use App\Controller\ApiController;
use App\Form\Product\PurchaseForm;
use App\Model\Payment\PaymentInfoModel;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class PostPurchase extends ApiController
{
    /**
     * @Route("/api/product/purchase", methods={"POST"})
     * @OA\Parameter(
     *     name="json",
     *     in="query",
     *     @Model(type=PurchaseForm::class)
     * )
     * @OA\Response(
     *     response=200,
     *     description="Покупка продукта прошла успешно",
     *     @Model(type=PaymentInfoModel::class)
     * )
     * @OA\Response(
     *      response=400,
     *      description="Указаны неверные параметры для покупки",
     *  )
     * @OA\Tag(name="Product")
     */
    public function purchase(Request $request, Handler $handler): JsonResponse
    {
        $dto = new PurchaseProductDto();
        $form = $this->createForm(PurchaseForm::class, $dto);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            try {
                return $this->json($handler->handle($data));
            } catch (Throwable $exception) {
                return $this->errorResponse(
                    new RuntimeException($exception->getMessage(), $exception->getCode(), $exception),
                    Response::HTTP_BAD_REQUEST,
                );
            }
        }

        return $this->json($this->gatherFormErrors($form), Response::HTTP_BAD_REQUEST);
    }
}
