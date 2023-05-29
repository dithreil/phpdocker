<?php

namespace App\Controller\Api\Product\GetPaymentInfo;

use App\Validator\CouponCodeConstraint;
use App\Validator\TaxNumberConstraint;
use App\Controller\ApiController;
use App\Entity\Product;
use App\Model\Payment\PaymentInfoModel;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetPaymentInfo extends ApiController
{
    /**
     * @Route("/api/product/{id}/{taxNumber?null}/{couponCode?null}", methods={"GET"}, requirements={"id" = "\d+"})
     * @OA\Response(
     *     response=200,
     *     description="Возвращает информацию по оплате за продукт",
     *     @Model(type=PaymentInfoModel::class)
     * )
     * @OA\Response(
     *      response=404,
     *      description="Продукт не найден",
     *  )
     * @OA\Response(
     *      response=400,
     *      description="Ошибка в параметрах запроса",
     *  )
     * @OA\Tag(name="Product")
     */
    public function getInfo(
        Product $product,
        Handler $handler,
        ValidatorInterface $validator,
        ?string $taxNumber = null,
        ?string $couponCode = null,
    ): JsonResponse {
        $errorMessages = [];
        $taxNumberErrors = $validator->validate($taxNumber, new TaxNumberConstraint());
        if ($taxNumberErrors->count() > 0) {
            foreach ($taxNumberErrors as $error) {
                $errorMessages[] = $error->getMessage();
            }
        }
        $couponCodeErrors = $validator->validate($couponCode, new CouponCodeConstraint());
        if ($couponCodeErrors->count() > 0) {
            foreach ($couponCodeErrors as $error) {
                $errorMessages[] = $error->getMessage();
            }
        }
        if (count($errorMessages) > 0) {
            return $this->apiErrorResponse(Response::HTTP_BAD_REQUEST, $errorMessages);
        }

        return $this->json($handler->handle($product, $taxNumber, $couponCode));
    }
}
