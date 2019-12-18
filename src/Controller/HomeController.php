<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Repository\ContractorRepository;
use App\Repository\ServiceTypeRepository;
use App\Service\ReviewService;
use App\Service\SerializerService;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/services", name="services")
     * @return Response
     */
    public function services()
    {
        return $this->render('services/index.html.twig');
    }

    /**
     * @Route("/lang/{lang}", name="language")
     * @param Request $request
     * @param string $lang
     * @return Response
     */
    public function setLanguage(Request $request, string $lang): Response
    {
        if ($request->headers->get('referer') && $lang !== null) {
            $request->getSession()->set('_locale', $lang);

            return $this->redirect($request->headers->get('referer'));
        } else {
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/public-api/services")
     * @param ServiceTypeRepository $serviceTypeRepository
     * @param SerializerService $serializer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function getServiceTypes(
        ServiceTypeRepository $serviceTypeRepository,
        SerializerService $serializer
    ): JsonResponse {
        $services = $serviceTypeRepository->findAll();
        $json = $serializer->getResponse($services, ['nameOnly']);

        return new JsonResponse($json);
    }

    /**
     * @Route("/public-api/services/{service}")
     * @param string $service
     * @param ServiceTypeRepository $serviceTypeRepository
     * @param SerializerService $serializer
     * @param ReviewService $reviewsService
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function getContractorsByServiceType(
        string $service,
        ServiceTypeRepository $serviceTypeRepository,
        SerializerService $serializer,
        ReviewService $reviewsService
    ): JsonResponse {
        $contractors = $serviceTypeRepository->findOneBy(['name' => $service])->getContractors();
        $contractors = $this->filterInactiveContractors($contractors);
        $json = $serializer->getResponse($contractors, ['filtered']);
        $json = $reviewsService->reformatReviews($json);

        return new JsonResponse($json);
    }

    /**
     * @param Collection $contractors
     * @return Contractor[]
     */
    private function filterInactiveContractors(Collection $contractors): array
    {
        $filteredData = [];
        foreach ($contractors as $contractor) {
            if ($contractor->getSettings() !== null) {
                $filteredData[] = $contractor;
            }
        }
        return $filteredData;
    }
}
