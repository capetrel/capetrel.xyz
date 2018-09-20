<?php
namespace App\Framework\Twig;

use App\Framework\Middleware\CsrfMiddleware;

class CsrfExtension extends \Twig_Extension
{

    /**
     * @var CsrfMiddleware
     */
    private $csrfMiddleware;

    public function __construct(CsrfMiddleware $csrfMiddleware)
    {

        $this->csrfMiddleware = $csrfMiddleware;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('csrf_input', [$this, 'csrfInput'], ['is_safe' => ['html']])
        ];
    }

    public function csrfInput(): ?string
    {
        return '<input type="hidden" name="' . $this->csrfMiddleware->getFormKey() .
            '" value="'. $this->csrfMiddleware->generateToken() .'"/>';
    }
}
