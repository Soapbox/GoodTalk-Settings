<?php

namespace Tests\Integration;

use Tests\TestCase;
use SoapBox\Settings\Setting;
use Illuminate\Validation\ValidationException;

class SettingTest extends TestCase
{
    /**
     * @test
     */
    public function itThrowsAValidationExceptionWhenTheGroupContainsADot()
    {
        $this->expectException(ValidationException::class);
        new Setting('invalid.group', 'key', 'identifier', 'value');
    }

    /**
     * @test
     */
    public function itThrowsAValidationExceptionWhenTheKeyContainsADot()
    {
        $this->expectException(ValidationException::class);
        new Setting('group', 'invalid.key', 'identifier', 'value');
    }

    /**
     * @test
     */
    public function itThrowsAValidationExceptionWhenTheIdentifierContainsADot()
    {
        $this->expectException(ValidationException::class);
        new Setting('group', 'key', 'invalid.identifier', 'value');
    }
}
