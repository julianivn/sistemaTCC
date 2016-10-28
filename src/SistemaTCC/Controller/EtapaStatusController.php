<?php

namespace SistemaTCC\Controller;

use DateTime;
use Exception;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use SistemaTCC\Model\EtapaStatus;

class EtapaStatusController {

    public function add(Application $app, Request $request) {

		$etapaStatus = new EtapaStatus();
		$etapaStatus->setNome($request->get('nome'));

        try {
            $app['orm']->persist($etapaStatus);
            $app['orm']->flush();
        }
        catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('Status cadastrado com sucesso.', JsonResponse::HTTP_CREATED);
    }

    public function edit(Application $app, Request $request, $id) {

        if (null === $etapaStatus = $app['orm']->find('SistemaTCC\Model\EtapaStatus', (int) $id)) {
        	return new JsonResponse('O status não foi encontrado.', JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $app['orm']->flush();
        }
        catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('Status editado com sucesso.', JsonResponse::HTTP_OK);
    }

    public function del(Application $app, Request $request, $id) {

        if (null === $etapaStatus = $app['orm']->find('SistemaTCC\Model\EtapaStatus', (int) $id)) {
        	return new JsonResponse('O status não foi encontrado.', JsonResponse::HTTP_NOT_FOUND);
        }

        try {
			$app['orm']->remove($semestre);
            $app['orm']->flush();
        }
        catch (Exception $e) {
            return new JsonResponse($e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

		return new JsonResponse('Status excluído com sucesso.', JsonResponse::HTTP_OK);
    }

    public function find(Application $app, Request $request, $id) {

        if (null === $etapaStatus = $app['orm']->find('SistemaTCC\Model\EtapaStatus', (int) $id)) {
			return new JsonResponse('O status não foi encontrado.', JsonResponse::HTTP_NOT_FOUND);
		}

        return new JsonResponse($etapaStatus->toArray());
    }

}
