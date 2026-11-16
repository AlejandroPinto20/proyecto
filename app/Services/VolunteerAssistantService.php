<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class VolunteerAssistantService
{
    public function answer(string $question, User $user): string
    {
        $context = $this->applicationContext($user);

        try {
            $response = Http::timeout(config('ollama.timeout'))
                ->acceptJson()
                ->post(config('ollama.url').'/api/chat', [
                    'model' => config('ollama.model'),
                    'stream' => false,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "Eres el asistente virtual del Sistema de Voluntariado UNIFRANZ. Determina primero si la pregunta trata sobre esta aplicación. Preguntas sobre registro, inicio de sesión, perfil, actividades, participación, calendario, habilidades, rankings, certificados, donaciones, notificaciones o funciones administrativas SÍ están relacionadas y debes responderlas de forma breve y útil usando el contexto. Por ejemplo, si preguntan cómo ver actividades, indica que deben ingresar al módulo Actividades. Si la pregunta no se relaciona con la aplicación, responde exactamente: 'Solo puedo ayudar con el Sistema de Voluntariado UNIFRANZ.' No inventes datos, políticas, actividades ni resultados.",
                        ],
                        [
                            'role' => 'system',
                            'content' => $context,
                        ],
                        [
                            'role' => 'user',
                            'content' => $question,
                        ],
                    ],
                ]);
        } catch (\Throwable $exception) {
            throw new RuntimeException('No fue posible conectar con Ollama local. Verifica que Ollama esté iniciado.', previous: $exception);
        }

        if (! $response->successful()) {
            throw new RuntimeException('Ollama no pudo generar una respuesta. Verifica el modelo configurado.');
        }

        $answer = trim((string) data_get($response->json(), 'message.content'));

        if ($answer === '') {
            throw new RuntimeException('Ollama devolvió una respuesta vacía.');
        }

        return $answer;
    }

    private function applicationContext(User $user): string
    {
        $activities = Activity::query()
            ->whereIn('status', ['active', 'pending'])
            ->orderBy('start_date')
            ->limit(5)
            ->get(['title', 'category', 'start_date', 'location']);

        $activitySummary = $activities->isEmpty()
            ? 'No hay actividades activas o pendientes registradas.'
            : $activities->map(fn (Activity $activity) => sprintf(
                '- %s | categoría: %s | fecha: %s | lugar: %s',
                $activity->title,
                $activity->category ?? 'sin categoría',
                optional($activity->start_date)->format('d/m/Y H:i') ?? 'sin fecha',
                $activity->location ?? 'sin ubicación'
            ))->implode("\n");

        return "Usuario actual: {$user->name}. Rol: {$user->role}.\n\nFunciones disponibles:\n- Los estudiantes pueden consultar actividades, participar, administrar su perfil, registrar habilidades, consultar recomendaciones, rankings, certificados y donaciones.\n- Los administradores pueden gestionar usuarios, actividades, participaciones, certificados, donaciones, habilidades, reportes y registros de acceso.\n\nActividades actuales:\n{$activitySummary}";
    }
}
