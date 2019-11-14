<?php


namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;

class JsonService
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
     * @param $object
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
}