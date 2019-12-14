<?php


namespace App\Service;

use App\Entity\Contractor;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerService
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * JsonService constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param object $object
     * @param array $groups
     * @return array
     */
    public function getResponse($object, array $groups = ['Default']): array
    {
        $data = $this->serializer->normalize(
            $object,
            'json',
            [
                'groups' => $groups
            ]
        );

        return $data;
    }

    /**
     * @param array $contractors
     * @return array
     */
    public function reformatReviews(array $contractors): array
    {
        $newContractors = [];
        foreach ($contractors as $contractor) {
            $reviewsInTotal = 0;
            $reviewsSum = 0;
            foreach ($contractor['reviews'] as $review) {
                $reviewsInTotal++;
                $reviewsSum += $review['stars'];
            }
            $contractor['reviews'] = null;
            $contractor['reviews']['totalReviews'] = $reviewsInTotal;
            if ($reviewsInTotal != 0) {
                $contractor['reviews']['average'] = round($reviewsSum / $reviewsInTotal, 1);
            } else {
                $contractor['reviews']['average'] = 0;
            }
            $newContractors[] = $contractor;
        }

        return $newContractors;
    }
}
