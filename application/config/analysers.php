<?php

$analysers = [
    \App\Checkers\ExtractHtmlVersion::class,
    \App\Checkers\ExtractTitle::class,
    \App\Checkers\ExtractHeadings::class,
    \App\Checkers\HasLoginForm::class,
    \App\Checkers\ExtractLinks::class,
    \App\Checkers\HasLangAttribute::class,
];

return $analysers;
