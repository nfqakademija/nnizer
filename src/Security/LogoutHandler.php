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
     * @var string
     */
    private $defaultLocale;

    /**
     * LogoutHandler constructor.
     * @param RouterInterface $router
     * @param string $defaultLocale
     */
    public function __construct(RouterInterface $router, string $defaultLocale)
    {
        $this->router = $router;
        $this->defaultLocale = $defaultLocale;
    }
    public function onLogoutSuccess(Request $request)
    {
        $lang = $request->getSession()->get('_locale', $this->defaultLocale);
        $request->getSession()->invalidate();
        $request->getSession()->set('_locale', $lang);

        return new RedirectResponse($this->router->generate('home'));
    }
}
