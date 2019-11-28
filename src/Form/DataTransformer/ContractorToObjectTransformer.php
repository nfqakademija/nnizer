<?php

namespace App\Form\DataTransformer;

use App\Entity\Contractor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ContractorToObjectTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ContractorToObjectTransformer constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (Contractor) to a string (username).
     * @param  Contractor|null $contractor
     * @return string
     */
    public function transform($contractor)
    {
        if (null === $contractor) {
            return '';
        }

        return $contractor->getUsername();
    }

    /**
     * Transforms a string (username) to an object (Contractor).
     *
     * @param  string $username
     * @return Contractor|null
     * @throws TransformationFailedException if object (Contractor) is not found.
     */
    public function reverseTransform($username)
    {
        $contractor = $this->entityManager
                            ->getRepository(Contractor::class)
                            ->findOneBy(['username' => $username]);

        return $contractor;
    }
}
