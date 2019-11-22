<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Entity\Reservation;
use App\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    /**
     * @Route("/api/reservation/{id}/review/star/{starCount}", name="review_star", methods="GET")
     * @param Request $request
     * @param int $id
     * @param int $starCount
     * @return JsonResponse
     */
    public function setStars(Request $request, int $id, int $starCount): JsonResponse
    {
        if (!preg_match('/^[0-5]$/', $starCount)) {
            return new JsonResponse(null, Response::HTTP_NOT_ACCEPTABLE);
        }

        $reservation = $this->getReservationObject($id);
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
     * @Route("/api/reservation/{id}/review", name="review_description", methods="GET")
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function addDescription(Request $request, int $id): JsonResponse
    {
        $description = $request->get('description');
        if ($description === null) {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        $reservation = $this->getReservationObject($id);
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
        }

        return new JsonResponse();
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
     * @param int $id
     * @return Reservation|null
     */
    private function getReservationObject(int $id): ?Reservation
    {
        return $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->find($id);
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
