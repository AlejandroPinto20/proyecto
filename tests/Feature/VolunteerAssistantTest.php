<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VolunteerAssistantTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_ask_the_local_assistant(): void
    {
        Http::fake([
            'http://127.0.0.1:11434/api/chat' => Http::response([
                'message' => ['content' => 'Puedes inscribirte desde el detalle de una actividad.'],
            ]),
        ]);

        $response = $this->actingAs(User::factory()->create())->postJson('/api/assistant/ask', [
            'message' => '¿Cómo me inscribo en una actividad?',
        ]);

        $response->assertOk()->assertJson([
            'answer' => 'Puedes inscribirte desde el detalle de una actividad.',
        ]);

        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'http://127.0.0.1:11434/api/chat'
                && $request['model'] === 'gemma3:1b'
                && $request['stream'] === false;
        });
    }

    public function test_assistant_requires_authentication(): void
    {
        $this->postJson('/api/assistant/ask', [
            'message' => '¿Cómo me inscribo?',
        ])->assertUnauthorized();
    }
}
