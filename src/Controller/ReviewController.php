<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Entity\Reservation;
use App\Entity\Review;
use App\Repository\ContractorRepository;
use App\Repository\ReviewRepository;
use App\Service\SerializerService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ReviewController extends AbstractController
{
    /**
     * @Route("/api/contractor/{contractorKey}/get-reviews/", name="GET")
     * @param string $contractorKey
     * @param SerializerService $json
     * @param ContractorRepository $contractorRepository
     * @return JsonResponse
     * @throws NonUniqueResultException
     * @throws ExceptionInterface
     */
    public function getReviews(
        string $contractorKey,
        SerializerService $json,
        ContractorRepository $contractorRepository
    ): JsonResponse {
        $contractor = $contractorRepository->findOneByKey($contractorKey);
        if ($contractor === null || $contractor->getReviews()->isEmpty()) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        return new Jsonresponse($json->getResponse($contractor->getReviews()));
    }

    /**
     * @Route("/api/reservation/{key}/review/star/{starCount}",
     *     name="review_star", methods="GET", requirements={"starCount"="[1-5]"})
     * @param string $key
     * @param int $starCount
     * @param ReviewRepository $reviewRepository
     * @return Response
     * @throws ORMException
     */
    public function setStars(string $key, int $starCount, ReviewRepository $reviewRepository): Response
    {
        $reservation = $this->getReservationByKey($key);
        if ($reservation === null) {
            return $this->redirectToRoute('home');
        }

        if (!$this->reviewAlreadyExists($reservation)) {
            $review = new Review();
            $review->setReservation($reservation);
            $contractor = $reservation->getContractor();
            $contractor->addReview($review);
        } else {
            $review = $reviewRepository->findOneBy(['reservation' => $reservation]);
        }
        $review->setStars($starCount);
        $reviewRepository->save($review);

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/api/reservation/{key}/review", name="review_description", methods="GET")
     * @param Request $request
     * @param string $key
     * @param ReviewRepository $reviewRepository
     * @return Response
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    public function addDescription(Request $request, string $key, ReviewRepository $reviewRepository): Response
    {
        $description = $request->get('description');
        $reservation = $this->getReservationByKey($key);
        if ($description === null || $reservation === null) {
            return $this->redirectToRoute('home');
        }

        $review = $reviewRepository->findOneByReservation($reservation);
        if ($review !== null) {
            $review->setDescription($description);
            $reviewRepository->save($review);
            return new JsonResponse();
        }

        return $this->redirectToRoute('home');
    }


    /**
     * @param Reservation $reservation
     * @return bool
     */
    private function reviewAlreadyExists(Reservation $reservation): bool
    {
        $review = $this->getDoctrine()
            ->getRepository(Review::class)
            ->findOneBy(['reservation' => $reservation]);

        return $review !== null;
    }

    /**
     * @param string $key
     * @return Reservation|null
     */
    private function getReservationByKey(string $key): ?Reservation
    {
        return $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findOneBy(['verificationKey' => $key]);
    }
}
