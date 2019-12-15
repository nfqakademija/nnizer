<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Entity\LostPassword;
use App\Repository\ContractorRepository;
use App\Repository\LostPasswordRepository;
use App\Service\LostPasswordFactory;
use App\Service\MailerService;
use App\Validator\LostPasswordValidator;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LostPasswordController extends AbstractController
{
    /**
     * @Route("/lost-password", name="app_lost-password-page")
     * @param Request $request
     * @return Response
     */
    public function lostPasswordPage(Request $request): Response
    {
        $errors = $request->getSession()->get('errors');
        $success = $request->getSession()->get('success');
        $request->getSession()->remove('errors');
        $request->getSession()->remove('success');

        return $this->render('contractor/lostpassword.html.twig', ['errors' => $errors, 'success' => $success]);
    }

    /**
     * @Route("/lost-password/submit", name="app_lost-password-submit", methods="POST")
     * @param Request $request
     * @param ContractorRepository $contractorRepository
     * @param LostPasswordRepository $lostPasswordRepository
     * @param LostPasswordValidator $validator
     * @param LostPasswordFactory $lostPasswordFactory
     * @param MailerService $mailer
     * @return Response
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function lostPasswordSubmit(
        Request $request,
        ContractorRepository $contractorRepository,
        LostPasswordRepository $lostPasswordRepository,
        LostPasswordValidator $validator,
        LostPasswordFactory $lostPasswordFactory,
        MailerService $mailer
    ): Response {
        $email = $request->get('email');
        $errors = $validator->validateEmail($email);

        if (count($errors) != 0) {
            $request->getSession()->set('errors', $errors);

            return $this->redirectToRoute('app_lost-password-page');
        }

        $contractor = $contractorRepository->findOneBy(['email' => $email]);

        if ($contractor) {
            $lostPassword = $lostPasswordRepository->findActiveEntry($contractor);

            $now = new \DateTime('now');
            if ($lostPassword && $lostPassword->getExpiresAt() > $now) {
                $errors = [
                    'lost_password.existing'
                ];
                $request->getSession()->set('errors', $errors);
            } else {
                $this->deleteIfExpired($lostPassword, $lostPasswordRepository);
                $lostPassword = $lostPasswordFactory->createLostPassword($contractor);
                $contractor->setLostPassword($lostPassword);
                $contractorRepository->save($contractor);

                $mailer->sendLostPasswordEmail($contractor);
                $success = [
                    'lost_password.sent'
                ];
                $request->getSession()->set('success', $success);
            }
        } else {
            $errors = [
                'lost_password.nonexisting'
            ];
            $request->getSession()->set('errors', $errors);
        }

        return $this->redirectToRoute('app_lost-password-page');
    }

    /**
     * @Route("/password-reset/{key}", name="app_password-reset")
     * @param Request $request
     * @param LostPasswordRepository $lostPasswordRepository
     * @param string $key
     * @return Response
     * @throws Exception
     */
    public function resetPasswordPage(
        Request $request,
        LostPasswordRepository $lostPasswordRepository,
        String $key
    ): Response {
        $lostPassword = $lostPasswordRepository->findActiveEntryByKey($key);
        if ($lostPassword === null) {
            return $this->redirectToRoute('home');
        }

        $this->deleteIfExpired($lostPassword, $lostPasswordRepository);

        $errors = $request->getSession()->get('errors');
        $request->getSession()->remove('errors');

        return $this->render('contractor/resetpassword.html.twig', ['errors' => $errors, 'key'=> $key]);
    }

    /**
     * @Route("/password-reset/{key}/submit", name="app_password-reset-submit", methods="POST")
     * @param Request $request
     * @param ContractorRepository $contractorRepository
     * @param LostPasswordRepository $lostPasswordRepository
     * @param String $key
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     * @throws Exception
     */
    public function resetPasswordSubmit(
        Request $request,
        ContractorRepository $contractorRepository,
        LostPasswordRepository $lostPasswordRepository,
        String $key,
        UserPasswordEncoderInterface $encoder
    ): Response {
        $pass1 = $request->get('password1');
        $pass2 = $request->get('password2');

        $lostPassword = $lostPasswordRepository->findActiveEntryByKey($key);

        if ($pass1 != $pass2 || strlen($pass1) === 0 || strlen($pass2) === 0) {
            $errors = ['reset_password.mismatch'];
            $request->getSession()->set('errors', $errors);

            return $this->redirectToRoute('app_password-reset', ['key' => $key]);
        } else {
            $contractor = $lostPassword->getContractor();
            $contractor->setPassword($encoder->encodePassword($contractor, $pass1));
            $contractorRepository->save($contractor);
            $lostPasswordRepository->remove($lostPassword);

            return $this->redirectToRoute('home');
        }
    }

    /**
     * @param LostPassword|null $lostPassword
     * @param LostPasswordRepository $lostPasswordRepository
     * @return RedirectResponse|null
     * @throws Exception
     */
    private function deleteIfExpired(
        ?LostPassword $lostPassword,
        LostPasswordRepository $lostPasswordRepository
    ): ?RedirectResponse {
        $now = new \DateTime('now');
        if ($lostPassword != null && $lostPassword->getExpiresAt() < $now) {
            $lostPasswordRepository->remove($lostPassword);

            return $this->redirectToRoute('home');
        }
        return null;
    }
}
