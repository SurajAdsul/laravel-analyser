<?php

namespace App\Checkers;

use App\Analyser\Crawler;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ResponseInterface;

class HasLoginForm extends AbstractChecker
{
    const MAX_INPUT_COUNT = 12;
    const PASSWORD_INPUT_COUNT = 1;
    const FORM_KEYWORDS = ['log in', 'login', 'sign in'];

    /**
     * @param Crawler $crawler
     * @param ResponseInterface $response
     * @param UriInterface $uri
     * @return bool
     */
    public function check(Crawler $crawler, ResponseInterface $response, UriInterface $uri): bool
    {
        $formFields = $crawler->filter('form input');

        $inputCount = $formFields->count();
        $passwordInputCount = $formFields->filter('input[type=password]')->count();

        $buttonLabels = $crawler->filter('input[type=submit],button[type=submit],input[type=button]')->each(function (Crawler $node, $i) {
            return strtolower($node->text());
        });

        $hasValidLoginButton = array_intersect($buttonLabels, self::FORM_KEYWORDS);

        $pageHasLoginForm = $inputCount < self::MAX_INPUT_COUNT && $passwordInputCount == self::PASSWORD_INPUT_COUNT && ! empty($hasValidLoginButton);

        return $pageHasLoginForm;
    }

    /**
     * @return string
     */
    public function successMessage(): string
    {
        return 'This page contains a login form';
    }

    /**
     * @return string
     */
    public function failedMessage(): string
    {
        return 'This page does not contain any login form';
    }
}
