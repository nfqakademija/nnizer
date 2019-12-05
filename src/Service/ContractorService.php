<?php


namespace App\Service;

use App\Entity\Contractor;
use Doctrine\Common\Collections\Collection;

class ContractorService
{
    /**
     * @var SerializerService
     */
    protected $json;

    /**
     * ContractorService constructor.
     * @param SerializerService $json
     */
    public function __construct(SerializerService $json)
    {
        $this->json = $json;
    }

    /**
     * @param Contractor $contractor
     * @return array|null
     */
    public function generateContractorCalenderResponse(Contractor $contractor): ?array
    {
        if ($contractor && $settings = $contractor->getSettings()) {
            $reservations = $contractor->getReservations();
            $settings = $this->json->getResponse($settings);
            $days = ['days' => $this->restructuredDaysInfo(array_splice($settings, 0, 7))];
            $workingDays = ['workingDays' => $this->getWorkingDaysArray($days['days'])];
            $takenTimes = ['takenDates' => $this->toDatesArray($reservations)];
            $result = $workingDays + $days + $settings + $takenTimes;
            return $result;
        } else {
            return null;
        }
    }
    /**
     * @param array $days
     * @return array
     */
    private function getWorkingDaysArray(array $days): array
    {
        $workingDays = [];
        $index = 0;
        foreach ($days as $day) {
            if ($day['isWorkday']) {
                $workingDays[] = $index;
            }
            $index++;
        }
        return $workingDays;
    }

    /**
     * @param array $settings
     * @return array
     */
    private function restructuredDaysInfo(array $settings): array
    {
        $refactured = array();
        foreach ($settings as $workingDay) {
            if (strpos($workingDay, '-1') === false) {
                $times = explode(" - ", $workingDay);
                $refactured[] = [
                    'startTime' => $times[0],
                    'endTime' => $times[1],
                    'isWorkday' => $times[0] !== $times[1],
                ];
            } else {
                $refactured[] = [
                    'startTime' => null,
                    'endTime' => null,
                    'isWorkday' => false,
                ];
            }
        }
        return $refactured;
    }

    /**
     * @param Collection $reservations
     * @return array
     */
    private function toDatesArray(Collection $reservations): array
    {
        $dates = [];
        foreach ($reservations as $reservation) {
            $dates[] = $reservation->getVisitDate()->format('Y-m-d H:i:s');
        }
        return $dates;
    }
}
