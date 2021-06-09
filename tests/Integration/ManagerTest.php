<?php

namespace Tests\Integration;

use Tests\TestCase;
use InvalidArgumentException;
use SoapBox\Settings\Manager;
use SoapBox\Settings\Setting;
use Illuminate\Support\Collection;
use SoapBox\Settings\Models\SettingValue;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Utilities\SettingFactory;
use SoapBox\Settings\Exceptions\InvalidKeyException;
use SoapBox\Settings\Exceptions\InvalidGroupException;

class ManagerTest extends TestCase
{
    /**
     * @test
     */
    public function itCanGetAllSettingsWithOverridesForASingleIdentifier()
    {
        $definition = SettingDefinition::factory()->create([
            'key' => 'setting1',
            'value' => 'default',
        ]);
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'override',
        ]);

        SettingDefinition::factory()->create([
            'key' => 'setting2',
            'value' => 'default',
        ]);

        $settings = app(Manager::class);
        $result = $settings->get('settings', '1');

        $this->assertCount(2, $result);
        $this->assertSame('override', $result->get('setting1')->getValue());
        $this->assertSame('default', $result->get('setting2')->getValue());
    }

    /**
     * @test
     */
    public function itCanGetAllSettingsWithOverridesForAManyIdentifier()
    {
        $definition = SettingDefinition::factory()->create([
            'key' => 'setting1',
            'value' => 'default',
        ]);
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'override1',
        ]);
        $definition = SettingDefinition::factory()->create([
            'group' => 'settings',
            'key' => 'setting2',
            'value' => 'default',
        ]);
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '2',
            'value' => 'override2',
        ]);

        $settings = app(Manager::class);
        $result = $settings->getMultiple('settings', new Collection(['1', '2', '3']));

        $this->assertCount(3, $result);
        $this->assertSame('override1', $result->get('1')->get('setting1')->getValue());
        $this->assertSame('default', $result->get('1')->get('setting2')->getValue());
        $this->assertSame('default', $result->get('2')->get('setting1')->getValue());
        $this->assertSame('override2', $result->get('2')->get('setting2')->getValue());
        $this->assertSame('default', $result->get('3')->get('setting1')->getValue());
        $this->assertSame('default', $result->get('3')->get('setting2')->getValue());
    }

    /**
     * @test
     */
    public function callingLoadWarmsUpTheCache()
    {
        $definition = SettingDefinition::factory()->create([
            'key' => 'setting1',
            'value' => 'default',
        ]);
        $settings = app(Manager::class);
        $settings->load('settings', '1');

        $definition->value = 'new_value';
        $definition->save();

        $result = $settings->get('settings', '1');

        $this->assertSame('default', $result->get('setting1')->getValue());
    }

    /**
     * @test
     */
    public function callingLoadMultipleWarmsUpTheCache()
    {
        $definition = SettingDefinition::factory()->create([
            'key' => 'setting1',
            'value' => 'default',
        ]);
        $settings = app(Manager::class);
        $settings->loadMultiple('settings', new Collection('1'));

        $definition->value = 'new_value';
        $definition->save();

        $result = $settings->get('settings', '1');

        $this->assertSame('default', $result->get('setting1')->getValue());
    }

    /**
     * @test
     */
    public function callingStoreSavesTheSetting()
    {
        $definition = SettingDefinition::factory()->create();

        $setting = SettingFactory::make('1', $definition);
        $setting->setValue('override');

        $settings = app(Manager::class);
        $result = $settings->store($setting);

        $this->assertDatabaseHas('setting_values', ['identifier' => '1', 'value' => 'override']);
        $this->assertSame('settings', $result->getGroup());
        $this->assertSame('key', $result->getKey());
        $this->assertSame('1', $result->getIdentifier());
        $this->assertSame('override', $result->getValue());
    }

    /**
     * @test
     */
    public function callingStoreUpdatesTheSetting()
    {
        $definition = SettingDefinition::factory()->create();
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
        ]);

        $setting = SettingFactory::make('1', $definition);
        $setting->setValue('new_override');

        $settings = app(Manager::class);
        $result = $settings->store($setting);

        $this->assertDatabaseHas('setting_values', ['identifier' => '1', 'value' => 'new_override']);
        $this->assertSame('settings', $result->getGroup());
        $this->assertSame('key', $result->getKey());
        $this->assertSame('1', $result->getIdentifier());
        $this->assertSame('new_override', $result->getValue());
    }

    /**
     * @test
     */
    public function callingStoreThrowsAnInvalidArgumentExceptionWhenTheGroupIsInvalid()
    {
        $setting = new Setting('invalid.group', 'key', 'identifier', 'value');

        $settings = app(Manager::class);
        $this->expectException(InvalidArgumentException::class);
        $settings->store($setting);
    }

    /**
     * @test
     */
    public function callingStoreThrowsAnInvalidArgumentExceptionWhenTheKeyIsInvalid()
    {
        $setting = new Setting('group', 'invalid.key', 'identifier', 'value');

        $settings = app(Manager::class);
        $this->expectException(InvalidArgumentException::class);
        $settings->store($setting);
    }

    /**
     * @test
     */
    public function callingStoreThrowsAnInvalidArgumentExceptionWhenTheIdentifierIsInvalid()
    {
        $setting = new Setting('group', 'key', 'invalid.identifier', 'value');

        $settings = app(Manager::class);
        $this->expectException(InvalidArgumentException::class);
        $settings->store($setting);
    }

    /**
     * @test
     */
    public function loadThrowsAnInvalidArgumentExceptionWhenTheGroupContainsADot()
    {
        $this->expectException(InvalidArgumentException::class);
        $settings = app(Manager::class);
        $settings->load('invalid.group', 'key');
    }

    /**
     * @test
     */
    public function loadThrowsAnInvalidArgumentExceptionWhenTheIdentifierContainsADot()
    {
        $this->expectException(InvalidArgumentException::class);
        $settings = app(Manager::class);
        $settings->load('group', 'invalid.key');
    }

    /**
     * @test
     */
    public function loadMultipleThrowsAnInvalidArgumentExceptionWhenTheGroupContainsADot()
    {
        $this->expectException(InvalidArgumentException::class);
        $settings = app(Manager::class);
        $settings->loadMultiple('invalid.group', new Collection('key'));
    }

    /**
     * @test
     */
    public function loadMultipleThrowsAnInvalidArgumentExceptionWhenAIdentifierContainsADot()
    {
        $this->expectException(InvalidArgumentException::class);
        $settings = app(Manager::class);
        $settings->loadMultiple('group', new Collection('invalid.key'));
    }

    /**
     * @test
     */
    public function getThrowsAnInvalidArgumentExceptionWhenTheGroupContainsADot()
    {
        $this->expectException(InvalidArgumentException::class);
        $settings = app(Manager::class);
        $settings->get('invalid.group', 'key');
    }

    /**
     * @test
     */
    public function getThrowsAnInvalidArgumentExceptionWhenTheIdentifierContainsADot()
    {
        $this->expectException(InvalidArgumentException::class);
        $settings = app(Manager::class);
        $settings->get('group', 'invalid.key');
    }

    /**
     * @test
     */
    public function getMultipleThrowsAnInvalidArgumentExceptionWhenTheGroupContainsADot()
    {
        $this->expectException(InvalidArgumentException::class);
        $settings = app(Manager::class);
        $settings->getMultiple('invalid.group', new Collection('key'));
    }

    /**
     * @test
     */
    public function getMultipleThrowsAnInvalidArgumentExceptionWhenTheIdentifierContainsADot()
    {
        $this->expectException(InvalidArgumentException::class);
        $settings = app(Manager::class);
        $settings->getMultiple('group', new Collection('invalid.key'));
    }

    /**
     * @test
     */
    public function storeThrowsInvalidGroupExceptionWhenItCannotFindTheSettingDefinitionForTheGroup()
    {
        $this->expectException(InvalidGroupException::class);
        $setting = new Setting('invalid_group', 'key', '1', 'test');
        app(Manager::class)->store($setting);
    }

    /**
     * @test
     */
    public function storeThrowsInvalidKeyExceptionWhenItCannotFindTheSettingDefinitionForTheKey()
    {
        SettingDefinition::factory()->create();
        $this->expectException(InvalidKeyException::class);
        $setting = new Setting('settings', 'invalid_key', '1', 'test');
        app(Manager::class)->store($setting);
    }
}
