<?php

namespace App\Controller;

use App\Entity\Contractor;
use App\Entity\LostPassword;
use App\Repository\ContractorRepository;
use App\Repository\LostPasswordRepository;
use App\Service\MailerService;
use App\Validator\LostPasswordValidation;
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
        $request->getSession()->remove('errors');
        return $this->render('contractor/lostpassword.html.twig', ['errors' => $errors]);
    }

    /**
     * @Route("/lost-password/submit", name="app_lost-password-submit", methods="POST")
     * @param Request $request
     * @param ContractorRepository $contractorRepository
     * @param LostPasswordValidation $validator
     * @param MailerService $mailer
     * @return Response
     * @throws Exception
     */
    public function lostPasswordSubmit(
        Request $request,
        ContractorRepository $contractorRepository,
        LostPasswordValidation $validator,
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
            $em = $this->getDoctrine()->getManager();
            $lostPassword = $em->getRepository(LostPassword::class)
                ->findActiveEntry($contractor);

            $now = new \DateTime('now');
            if ($lostPassword && $lostPassword->getExpiresAt() > $now) {
                $errors = [
                    'lost_password.existing'
                ];
                $request->getSession()->set('errors', $errors);
            } else {
                $this->deleteIfExpired($lostPassword);
                $lostPassword = $this->createLostPassword($contractor);
                $contractor->setLostPassword($lostPassword);
                $em->persist($contractor);
                $em->flush();

                $mailer->sendLostPasswordEmail($contractor);
                $errors = [
                    'lost_password.sent'
                ];
                $request->getSession()->set('errors', $errors);
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
     * @param Contractor $contractor
     * @return LostPassword
     * @throws Exception
     */
    private function createLostPassword(Contractor $contractor): LostPassword
    {
        $lostPassword = new LostPassword();
        $lostPassword->setContractor($contractor);
        $lostPassword->setExpiresAt((new \DateTime('now'))->modify('+60 minutes'));
        $lostPassword->setResetKey(sha1(random_bytes(6)));

        return $lostPassword;
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

        $this->deleteIfExpired($lostPassword);

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
            $pass1 = $encoder->encodePassword($contractor, $pass1);
            $contractor->setPassword($pass1);
            $contractorRepository->save($contractor);
            $em = $this->getDoctrine()->getManager();
            $em->remove($lostPassword);
            $em->flush();

            return $this->redirectToRoute('home');
        }
    }

    /**
     * @param LostPassword|null $lostPassword
     * @return RedirectResponse|null
     * @throws Exception
     */
    private function deleteIfExpired(?LostPassword $lostPassword): ?RedirectResponse
    {
        $now = new \DateTime('now');
        if ($lostPassword != null && $lostPassword->getExpiresAt() < $now) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($lostPassword);
            $em->flush();

            return $this->redirectToRoute('home');
        }
        return null;
    }
}
