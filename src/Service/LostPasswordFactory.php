<?php

namespace App\Service;

use App\Entity\Contractor;
use App\Entity\LostPassword;
use Exception;

class LostPasswordFactory
{

    /**
     * @param Contractor $contractor
     * @return LostPassword
     * @throws Exception
     */
    public function createLostPassword(Contractor $contractor): LostPassword
    {
        $lostPassword = new LostPassword();
        $lostPassword->setContractor($contractor);
        $lostPassword->setExpiresAt((new \DateTime('now'))->modify('+60 minutes'));
        $lostPassword->setResetKey(sha1(random_bytes(6)));

        return $lostPassword;
    }
}
