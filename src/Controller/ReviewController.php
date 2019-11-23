<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Entity\Reservation;
use App\Entity\Review;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    /**
     * @Route("/api/contractor/{contractorKey}/get-reviews/", name="GET")
     * @param string $contractorKey
     * @param SerializerService $json
     * @return JsonResponse
     */
    public function getReviews(string $contractorKey, SerializerService $json): JsonResponse
    {
        $contractor = $this->getDoctrine()->getRepository(Contractor::class)
                        ->findOneBy(
                            [
                                'verificationKey' => $contractorKey
                            ]
                        );
        if ($contractor === null) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $reviews = $contractor->getReviews();

        if ($reviews !== null) {
            return new Jsonresponse($json->getResponse($reviews));
        } else {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
    }
    /**
     * @Route("/api/reservation/{key}/review/star/{starCount}",
     *     name="review_star", methods="GET", requirements={"starCount"="[1-5]"})
     * @param string $key
     * @param int $starCount
     * @return JsonResponse
     */
    public function setStars(string $key, int $starCount): JsonResponse
    {
        $reservation = $this->getReservationObject($key);
        if ($reservation === null) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        if (!$this->reviewAlreadyExists($reservation)) {
            $review = new Review();
            $review->setReservation($reservation);
            $contractor = $this->getContractorObject($reservation->getContractor());
            $contractor->addReview($review);
        } else {
            $review = $this->getDoctrine()
                ->getRepository(Review::class)
                ->findOneBy(['reservation' => $reservation]);
        }
        $review->setStars($starCount);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($review);
        $entityManager->flush();

        return new JsonResponse();
    }

    /**
     * @Route("/api/reservation/{key}/review", name="review_description", methods="GET")
     * @param Request $request
     * @param string $key
     * @return JsonResponse
     */
    public function addDescription(Request $request, string $key): JsonResponse
    {
        $description = $request->get('description');
        if ($description === null) {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        $reservation = $this->getReservationObject($key);
        if ($reservation === null) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        if ($this->reviewAlreadyExists($reservation)) {
            $review = $this->getDoctrine()
                ->getRepository(Review::class)
                ->findOneBy(['reservation' => $reservation]);
            $review->setDescription($description);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();
            return new JsonResponse();
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
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
    private function getReservationObject(string $key): ?Reservation
    {
        return $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findOneBy(['verificationKey' => $key]);
    }

    /**
     * @param string $username
     * @return Contractor
     */
    private function getContractorObject(string $username): Contractor
    {
        return $this->getDoctrine()
            ->getRepository(Contractor::class)
            ->findOneBy(['username' => $username]);
    }
}
