<?php


namespace App\Service;

use App\Entity\Contractor;
use App\Entity\ContractorSettings;
use App\Entity\Reservation;
use App\Entity\Review;
use App\Entity\ServiceType;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

class SerializerService
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * JsonService constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Contractor|Collection<Review|Contractor>|ContractorSettings|Review[]|ServiceType[]|Reservation[] $object
     * @param array $groups
     * @return array
     * @throws ExceptionInterface
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
}
