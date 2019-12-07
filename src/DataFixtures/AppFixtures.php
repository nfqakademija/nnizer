<?php

namespace App\DataFixtures;

use App\Entity\Contractor;
use App\Entity\ContractorSettings;
use App\Entity\Reservation;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use http\Exception;

class AppFixtures extends Fixture
{
    /**
     * @var array
     */
    private $workingHours = [
        '08:00 - 17:00',
        '11:00 - 16:00',
        '09:00 - 21:00',
        '12:00 - 12:00',
    ];

    /**
     * @var array
     */
    private $visitDurations = [
        '15',
        '20',
        '60',
        '30'
    ];


    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadContractors($manager);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadUsers(ObjectManager $manager)
    {
        $names = ['nfq', 'dominykas', 'kornelijus', 'migle', 'tadas'];
        foreach ($names as $name) {
            $user = new User();
            $user->setEmail($name . '@' . $name . '.com');
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($name);
            $user->setName($name);
            $manager->persist($user);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadContractors(ObjectManager $manager)
    {
        $services = ['Massages', 'Hairdressing', 'Driving', 'Teaching'];
        for ($i = 1; $i < 51; $i++) {
            try {
                $contractor = new Contractor();
                $contractor->setPassword("fixture");
                $contractor->setFirstname('Contractor');
                $contractor->setLastName('Fixture ' . $i);
                $contractor->setUsername('fixture' . $i);
                $contractor->setEmail('contractor' . $i . '@' . $i . '.com');
                $contractor->setPhoneNumber(random_int(860000000, 869999999));
                $contractor->setVerificationKey();
                $contractor->setIsVerified(true);
                $contractor->setTitle($services[random_int(0, 3)]);
                $contractor->setDescription('There\'s not much to tell. Try it out and tell me what you think');
                $this->loadSettings($contractor, $manager);
                $this->loadReservations($contractor, $manager);
                $this->loadReviews($contractor, $manager);

                $manager->persist($contractor);
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * @param Contractor $contractor
     * @param ObjectManager $manager
     */
    private function loadSettings(Contractor $contractor, ObjectManager $manager)
    {
        $settings = new ContractorSettings();
        try {
            $settings->setMonday($this->workingHours[random_int(0, 3)]);
            $settings->setTuesday($this->workingHours[random_int(0, 3)]);
            $settings->setWednesday($this->workingHours[random_int(0, 3)]);
            $settings->setThursday($this->workingHours[random_int(0, 3)]);
            $settings->setFriday($this->workingHours[random_int(0, 3)]);
            $settings->setSaturday('-1');
            $settings->setSunday('-1');
            $settings->setVisitDuration($this->visitDurations[random_int(0, 3)]);
            $settings->setContractor($contractor);
            $manager->persist($settings);
            $contractor->setSettings($settings);
        } catch (\Exception $e) {
        }
    }

    /**
     * @param Contractor $contractor
     * @param ObjectManager $manager
     */
    private function loadReservations(Contractor $contractor, ObjectManager $manager)
    {
        for ($i = 1; $i < 10; $i++) {
            $reservation = new Reservation();
            try {
                $reservation->setFirstname($contractor->getUsername() . 'client' . $i);
                $reservation->setLastname('fixture');
                $reservation->setEmail($reservation->getFirstname() . '@' . $i . '.com');
                $reservation->setIsVerified(random_int(0, 1));
                $reservation->setVerificationKey($reservation->generateActivationKey());
                $reservation->setContractor($contractor);
                $reservation->setVisitDate(
                    (new \DateTime('now'))
                        ->modify(
                            '+'. $contractor->getSettings()->getVisitDuration() * $i . ' minutes'
                        )
                );
                $reservation->setIsCompleted(true);
                $manager->persist($reservation);
                $contractor->addReservation($reservation);
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * @param Contractor $contractor
     * @param ObjectManager $manager
     */
    private function loadReviews(Contractor $contractor, ObjectManager $manager)
    {
        $reservations = $contractor->getReservations();
        foreach ($reservations as $reservation) {
            $review = new Review();
            try {
                $review->setContractor($contractor);
                $review->setDescription('Don\'t take it serious, i\'m a fixture!');
                $review->setReservation($reservation);
                $review->setStars(random_int(0, 5));
                $manager->persist($review);
                $contractor->addReview($review);
            } catch (\Exception $e) {
            }
        }
    }
}
