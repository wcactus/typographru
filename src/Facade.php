<?php

namespace Wcactus\TypographRu;

class Facade extends \Illuminate\Support\Facades\Facade
{
	const LANGUAGE_RU = \Wcactus\TypographRu\TypographRu::LANGUAGE_RU;
	const LANGUAGE_EN = \Wcactus\TypographRu\TypographRu::LANGUAGE_EN;

    public static function getFacadeAccessor()
    {
        return 'Wcactus\TypographRu\TypographRu';
    }
}