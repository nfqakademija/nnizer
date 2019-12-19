<?php

namespace App\DataFixtures;

use App\Entity\Contractor;
use App\Entity\ContractorSettings;
use App\Entity\CoverPhoto;
use App\Entity\ProfilePhoto;
use App\Entity\Reservation;
use App\Entity\Review;
use App\Entity\ServiceType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
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
        '10:00 - 12:00',
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
        'pink.png',
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
    private $services = [
        'Massages',
        'Hairdressing',
        'Driver',
        'Fitness',
        'Teaching'
    ];

    /**
     * @var array
     */
    private $titles = [
        'Top quality',
        'Best in town',
        'Top notch',
        'Excellent',
        'First-class',
        'Highest quality',
        'Superior'
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
     * @var array
     */
    private $reviewMessages = [
        'There\'s not much to tell, I think my rating tells it all',
        'Thanks for your time',
        'Thanks for your services',
        'Didn\'t meet my expectations, but it was fine',
        'I really liked you as a person',
        'What I really liked is the location - got to the place in 5 minutes',
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->loadContractors($manager, $this->encoder);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @throws Exception
     */
    private function loadContractors(ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        for ($i = 1; $i < 51; $i++) {
            $firstname = $this->firstnames[random_int(0, 9)];
            $lastname = $this->lastnames[random_int(0, 9)];
            $description = $this->descriptions[random_int(0, 3)];
            $contractor = new Contractor();
            $contractor->setPassword($encoder->encodePassword($contractor, 'fixture'));
            $contractor->setFirstname($firstname);
            $contractor->setLastName($lastname);
            $contractor->setUsername($firstname . $i);
            $contractor->setEmail($firstname . '@' . $lastname . '.com');
            $contractor->setPhoneNumber((string)random_int(860000000, 869999999));
            $contractor->setVerificationKey();
            $contractor->setAddress('Brastos g. 15, Kaunas');
            $contractor->setIsVerified(true);
            $contractor->setDescription($description);
            $this->loadService($contractor, $manager);
            $service = $contractor->getServices()->getName();
            $contractor->setTitle($this->titles[random_int(0, 6)]  . ' ' . strtolower($service));
            $id = random_int(1, 10);
            $this->addCoverPhoto($contractor, $manager, strtolower($service) . '-' . $id . '.jpg');
            $this->addProfilePhoto($contractor, $manager);
            $this->loadSettings($contractor, $manager);
            $this->loadReservations($contractor, $manager);
            $this->loadReviews($contractor, $manager);

            $manager->persist($contractor);
        }
    }

    /**
     * @param Contractor $contractor
     * @param ObjectManager $manager
     * @param string $filename
     */
    private function addCoverPhoto(Contractor $contractor, ObjectManager $manager, string $filename)
    {
        $coverPhoto = new CoverPhoto();
        $coverPhoto->setContractor($contractor);
        $coverPhoto->setFilename($filename);
        $manager->persist($coverPhoto);
        $contractor->setCoverPhoto($coverPhoto);
    }

    /**
     * @param Contractor $contractor
     * @param ObjectManager $manager
     * @throws Exception
     */
    private function addProfilePhoto(Contractor $contractor, ObjectManager $manager)
    {
        $profilePhoto = new ProfilePhoto();
        $profilePhoto->setContractor($contractor);
        $profilePhoto->setFilename($this->profilePhotos[random_int(0, 2)]);
        $manager->persist($profilePhoto);
        $contractor->setProfilePhoto($profilePhoto);
    }


    /**
     * @param Contractor $contractor
     * @param ObjectManager $manager
     * @throws Exception
     */
    private function loadSettings(Contractor $contractor, ObjectManager $manager)
    {
        $settings = new ContractorSettings();
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
    }

    /**
     * @param Contractor $contractor
     * @param ObjectManager $manager
     * @throws Exception
     */
    private function loadReservations(Contractor $contractor, ObjectManager $manager)
    {
        for ($i = 1; $i < 20; $i++) {
            $reservation = new Reservation();
            $username = $this->firstnames[random_int(0, 9)];
            $lastname = $this->lastnames[random_int(0, 9)];
            $reservation->setFirstname($username);
            $reservation->setLastname($lastname);
            $reservation->setEmail($reservation->getFirstname() . '@' . $i . '.com');
            $reservation->setIsVerified((boolean)random_int(0, 1));
            $reservation->setPhoneNumber((string)random_int(860000000, 869999999));
            $reservation->setVerificationKey($reservation->generateActivationKey());
            $reservation->setContractor($contractor);
            $reservation->setVisitDate(
                (new \DateTime('now'))
                    ->setTime(11, 00)
                    ->modify(
                        '+' . $i . ' days'
                    )
            );
            $reservation->setIsCompleted(true);
            $manager->persist($reservation);
            $contractor->addReservation($reservation);
        }
    }

    /**
     * @param Contractor $contractor
     * @param ObjectManager $manager
     * @throws Exception
     */
    private function loadReviews(Contractor $contractor, ObjectManager $manager)
    {
        $reservations = $contractor->getReservations();
        foreach ($reservations as $reservation) {
            $review = new Review();
            $review->setContractor($contractor);
            $review->setDescription($this->reviewMessages[random_int(0, 5)]);
            $review->setReservation($reservation);
            $review->setStars(random_int(1, 5));
            $manager->persist($review);
            $contractor->addReview($review);
        }
    }

    /**
     * @param Contractor $contractor
     * @param ObjectManager $manager
     * @throws \Exception
     */
    private function loadService(Contractor $contractor, ObjectManager $manager)
    {
        $serviceName = $this->services[random_int(0, 4)];
        $service = $manager->getRepository(ServiceType::class)->findOneBy(['name' => $serviceName]);
        if ($service) {
            $service->addContractor($contractor);
            $manager->persist($service);
            $contractor->setServices($service);
        } else {
            $service = new ServiceType();
            $service->setName($serviceName);
            $service->addContractor($contractor);
            $manager->persist($service);
            $contractor->setServices($service);
        }
        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['contractors'];
    }
}
