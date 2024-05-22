<?php

namespace Tests\Integration\Repositories;

use Tests\TestCase;
use SoapBox\Settings\Setting;
use Illuminate\Support\Collection;
use SoapBox\Settings\Models\SettingValue;
use Illuminate\Validation\ValidationException;
use SoapBox\Settings\Models\SettingDefinition;
use SoapBox\Settings\Utilities\SettingFactory;
use SoapBox\Settings\Models\TextSettingDefinition;
use SoapBox\Settings\Repositories\DatabaseSettings;
use SoapBox\Settings\Exceptions\InvalidKeyException;
use SoapBox\Settings\Exceptions\InvalidGroupException;
use SoapBox\Settings\Models\SingleSelectSettingDefinition;

class DatabaseSettingsTest extends TestCase
{
    /**
     * @test
     */
    public function itFetchesDefinitionsFromTheDatabaseAndAppliesTheirOverride()
    {
        $definition = SettingDefinition::factory()->create([
            'key' => 'setting1',
        ]);
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'override',
        ]);
        SettingDefinition::factory()->create([
            'key' => 'setting2',
        ]);

        $repository = new DatabaseSettings();
        $settings = $repository->get('settings', '1');

        $this->assertCount(2, $settings);
        $this->assertSame('override', $settings->get('setting1')->getValue());
        $this->assertSame('default', $settings->get('setting2')->getValue());
    }

    /**
     * @test
     */
    public function itFetchesDefinitionsFromTheDatabaseAndAppliesTheirOverrideForMultipleIdentifiers()
    {
        $definition = SettingDefinition::factory()->create([
            'key' => 'setting1',
        ]);
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'override1',
        ]);
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '2',
            'value' => 'override2',
        ]);

        $repository = new DatabaseSettings();
        $result = $repository->getMultiple('settings', new Collection(['1', '2']));

        $this->assertCount(2, $result);

        $settings = $result->get('1');
        $this->assertSame('override1', $settings->get('setting1')->getValue());

        $settings = $result->get('2');
        $this->assertSame('override2', $settings->get('setting1')->getValue());
    }

    /**
     * @test
     */
    public function storeThrowsInvalidGroupExceptionIfTheGroupDoesntExist()
    {
        $this->expectException(InvalidGroupException::class);

        $setting = new Setting('invalid_group', 'key', 'identifier', 'value');

        $repository = new DatabaseSettings();
        $setting = $repository->store($setting);
    }

    /**
     * @test
     */
    public function storeThrowsInvalidKeyExceptionIfTheGroupDoesntExist()
    {
        $this->expectException(InvalidKeyException::class);

        $definition = factory(TextSettingDefinition::class)->create();
        $setting = new Setting($definition->group, 'invalid_key', 'identifier', 'value');

        $repository = new DatabaseSettings();
        $setting = $repository->store($setting);
    }

    /**
     * @test
     */
    public function itStoresASettingValue()
    {
        $definition = TextSettingDefinition::factory()->create();
        $setting = SettingFactory::make('1', $definition);
        $setting->setValue('override');

        $repository = new DatabaseSettings();
        $setting = $repository->store($setting);

        $this->assertDatabaseHas('setting_values', ['identifier' => '1', 'value' => 'override']);
        $this->assertSame('settings', $setting->getGroup());
        $this->assertSame('key', $setting->getKey());
        $this->assertSame('1', $setting->getIdentifier());
        $this->assertSame('override', $setting->getValue());
    }

    /**
     * @test
     */
    public function itUpdatesAnExistingSettings()
    {
        $definition = TextSettingDefinition::factory()->create();
        SettingValue::factory()->create([
            'setting_definition_id' => $definition->id,
            'identifier' => '1',
            'value' => 'override',
        ]);
        $setting = SettingFactory::make('1', $definition);
        $setting->setValue('new_override');

        $repository = new DatabaseSettings();
        $setting = $repository->store($setting);

        $this->assertDatabaseHas('setting_values', ['identifier' => '1', 'value' => 'new_override']);
        $this->assertSame('settings', $setting->getGroup());
        $this->assertSame('key', $setting->getKey());
        $this->assertSame('1', $setting->getIdentifier());
        $this->assertSame('new_override', $setting->getValue());
    }

    /**
     * @test
     */
    public function itFailsToSaveASettingTheFailsValidation()
    {
        $definition = SingleSelectSettingDefinition::factory()->create();
        $setting = SettingFactory::make('1', $definition);
        $setting->setValue('invalid_option');

        $repository = new DatabaseSettings();
        try {
            $repository->store($setting);
        } catch (ValidationException $exception) {
            $this->assertDatabaseMissing('setting_values', ['identifier' => '1', 'value' => 'invalid_option']);
            return;
        }

        $this->fail();
    }
}
