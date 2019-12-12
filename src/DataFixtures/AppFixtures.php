<?php

namespace App\DataFixtures;

use App\Entity\Contractor;
use App\Entity\ContractorSettings;
use App\Entity\CoverPhoto;
use App\Entity\ProfilePhoto;
use App\Entity\Reservation;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var array
     */
    private $workingHours = [
        '08:00 - 17:00',
        '11:00 - 16:00',
        '09:00 - 21:00',
        '12:00 - 12:00',
        '-1',
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

    /**
     * @var array
     */
    private $profilePhotos = [
        'twitter.jpg',
        'dark.png',
        'blue.jpg',
        'pink.png',
    ];

    /**
     * @var array
     */
    private $coverPhotos = [
        'cover1.jpg',
        'dark.jpg',
        'circles.png',
        'leafs.png',
    ];

    /**
     * @var array
     */
    private $firstnames = [
        'Jerry',
        'Jesse',
        'Daniel',
        'Shirley',
        'Helen',
        'James',
        'Ben',
        'Stewart',
        'Carol',
        'Wayne',
        'William',
        'Gerald',
        'Kai',
        'Albert',
        'Brian',
        'Benjamin',
        'Kevin'
    ];

    /**
     * @var array
     */
    private $lastnames = [
        'Evans',
        'Holmes',
        'Hopkins',
        'Fowler',
        'Murphy',
        'Ray',
        'Thompson',
        'Garcia',
        'Brooks',
        'Phillips',
        'Palmer',
        'Hawkins',
        'Wilson',
        'Castro',
        'Weber',
        'Little'
    ];

    /**
     * @var array
     */
    private $descriptions = [
        'Hey there! I\'m a professional that you certainly need. With experience of over 10 years in the industry' .
        ' I\'m proud to say that I am one of the best. Here\'s the best part - if you do not really like my ' . '
            services, You don\'t have to pay. Sign up now and let me prove you how good I am!',
        'I\'ve been working in the field since 2000 and since then I did not have a single disappointed customer.' .
        'What is more, if you ever have difficulties coming to my place, I can show up at yours and provide as ' .
        'good service as it can be. ',
        'Since you are here, let me introduce myself. I\'m a 35 year old male currently living in USA and this ' .
        'is what I am doing for living. With over 5000 customers satisfied, I\'m feeling confident in what I am' .
        'doing. Sign up now and let me show you what kind of quality you deserve. ',
        'I\'ve learned everything from my mentors in my young days and now I provide top quality services in this' .
        'town. Feel free to check it out!',
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param LoggerInterface $logger
     */
    public function __construct(UserPasswordEncoderInterface $encoder, LoggerInterface $logger)
    {
        $this->encoder = $encoder;
        $this->logger = $logger;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadContractors($manager, $this->encoder);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     */
    private function loadContractors(ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $services = ['Massages', 'Hairdressing', 'Driving services', 'Teaching'];

        for ($i = 1; $i < 51; $i++) {
            try {
                $firstname = $this->firstnames[random_int(0, 9)];
                $lastname = $this->lastnames[random_int(0, 9)];
                $serviceTitle = $services[random_int(0, 3)];
                $description = $this->descriptions[random_int(0, 3)];

                $contractor = new Contractor();
                $contractor->setPassword($encoder->encodePassword($contractor, 'fixture'));
                $contractor->setFirstname($firstname);
                $contractor->setLastName($lastname);
                $contractor->setUsername($firstname . $i);
                $contractor->setEmail($firstname . '@' . $lastname . '.com');
                $contractor->setPhoneNumber(random_int(860000000, 869999999));
                $contractor->setVerificationKey();
                $contractor->setAddress('Brastos g. 15, Kaunas');
                $contractor->setIsVerified(random_int(0, 1));
                $contractor->setTitle($serviceTitle);
                $contractor->setDescription($description);
                $this->addCoverPhoto($contractor, $manager);
                $this->addProfilePhoto($contractor, $manager);
                $this->loadSettings($contractor, $manager);
                $this->loadReservations($contractor, $manager);
                $this->loadReviews($contractor, $manager);

                $manager->persist($contractor);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }

    /**
     * @param Contractor $contractor
     * @param ObjectManager $manager
     */
    private function addCoverPhoto(Contractor $contractor, ObjectManager $manager)
    {
        $coverPhoto = new CoverPhoto();
        try {
            $coverPhoto->setContractor($contractor);
            $coverPhoto->setFilename($this->coverPhotos[random_int(0, 3)]);
            $manager->persist($coverPhoto);
            $contractor->setCoverPhoto($coverPhoto);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @param Contractor $contractor
     * @param ObjectManager $manager
     */
    private function addProfilePhoto(Contractor $contractor, ObjectManager $manager)
    {
        $profilePhoto = new ProfilePhoto();
        try {
            $profilePhoto->setContractor($contractor);
            $profilePhoto->setFilename($this->profilePhotos[random_int(0, 3)]);
            $manager->persist($profilePhoto);
            $contractor->setProfilePhoto($profilePhoto);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
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
            $settings->setMonday($this->workingHours[random_int(0, 4)]);
            $settings->setTuesday($this->workingHours[random_int(0, 4)]);
            $settings->setWednesday($this->workingHours[random_int(0, 4)]);
            $settings->setThursday($this->workingHours[random_int(0, 4)]);
            $settings->setFriday($this->workingHours[random_int(0, 4)]);
            $settings->setSaturday($this->workingHours[random_int(0, 4)]);
            $settings->setSunday($this->workingHours[random_int(0, 4)]);
            $settings->setVisitDuration($this->visitDurations[random_int(0, 3)]);
            $settings->setContractor($contractor);
            $manager->persist($settings);
            $contractor->setSettings($settings);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @param Contractor $contractor
     * @param ObjectManager $manager
     */
    private function loadReservations(Contractor $contractor, ObjectManager $manager)
    {
        for ($i = 1; $i < 20; $i++) {
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
                $this->logger->error($e->getMessage());
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
                $review->setDescription('The rating tells it all!');
                $review->setReservation($reservation);
                $review->setStars(random_int(1, 5));
                $manager->persist($review);
                $contractor->addReview($review);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['contractors'];
    }
}
