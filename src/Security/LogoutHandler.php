<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutHandler implements LogoutSuccessHandlerInterface
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * LogoutHandler constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    public function onLogoutSuccess(Request $request)
    {
        $lang = $request->getSession()->get('_locale');
        $request->getSession()->invalidate();
        $request->getSession()->set('_locale', $lang);
        return new RedirectResponse($this->router->generate('home'));
    }
}
