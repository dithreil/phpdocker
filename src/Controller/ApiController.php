<?php

namespace App\Controller;

use App\Exception\ApiException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiController extends AbstractController
{
    protected function errorResponse(Throwable $exception, int $status, array $headers = []): JsonResponse
    {
        if ($exception instanceof ApiException) {
            return $this->json($exception->toArray(), $status, $headers);
        }

        $data = [
            'message' => $exception->getMessage(),
        ];

        return $this->json($data, $status, $headers);
    }

    protected function gatherFormErrors(FormInterface $form): array
    {
        if (!$form->isSubmitted()) {
            $errors['message'] = 'Empty input';

            return $errors;
        }

        $errors = [];
        foreach ($form->getErrors(true) as $formError) {
            $path = $formError->getOrigin()->getName();
            $form = $formError->getOrigin();
            if (!$path && $form->isRoot()) {
                $errors[] = [
                    'propertyName' => null,
                    'message' => $formError->getMessage(),
                ];
            } else {
                while ($form->getParent() && !$form->getParent()->isRoot()) {
                    $form = $form->getParent();
                    $path = sprintf('%s.%s', $form->getName(), $path);
                }
                $errors[] = [
                    'propertyName' => $path,
                    'message' => $formError->getMessage(),
                ];
            }
        }

        return [
            'message' => 'Error validation request',
            'errors' => $errors,
        ];
    }

    /**
     * @param string[] $headers
     */
    protected function emptyResponse(int $status = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        return new JsonResponse(null, $status, $headers);
    }

    protected function apiErrorResponse(int $status, $errors = []): JsonResponse
    {
        return $this->json($errors, $status);
    }
}
