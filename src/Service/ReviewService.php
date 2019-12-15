<?php

namespace App\Service;

class ReviewService
{

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
