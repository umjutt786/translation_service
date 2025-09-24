<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class TranslationExportTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_exports_translations_under_500ms()
    {
        $start = microtime(true);

        $this->actingAs(User::factory()->create(), 'sanctum')
            ->getJson('/api/translations/export/en')
            ->assertStatus(200);

        $this->assertTrue((microtime(true) - $start) < 0.5, 'Export took too long');
    }
}
