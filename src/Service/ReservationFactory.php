<?php


namespace App\Service;

use App\Entity\Reservation;
use DateTime;

class ReservationFactory
{
    /**
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @param DateTime $visitDate
     * @param string $phoneNumber
     * @return Reservation
     * @throws \Exception
     */
    public function createReservation(
        string $email,
        string $firstname,
        string $lastname,
        DateTime $visitDate,
        string $phoneNumber = null
    ): Reservation {
        $reservation = new Reservation();
        $reservation->setEmail($email);
        $reservation->setFirstname($firstname);
        $reservation->setLastname($lastname);
        $reservation->setVisitDate($visitDate);
        $reservation->setPhoneNumber($phoneNumber);
        $reservation->setVerificationKey($reservation->generateActivationKey());
        $reservation->setVerificationKeyExpirationDate((new \DateTime('now'))->modify('+15 minutes'));
        return $reservation;
    }
}
