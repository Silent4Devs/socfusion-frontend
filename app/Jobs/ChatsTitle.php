<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Chat;
use Illuminate\Support\Facades\Http;


class ChatsTitle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chatId, $message;
    /**
     * Create a new job instance.
     */
    public function __construct(int $chatId, string $message)
    {
        $this->chatId = $chatId;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $chat = Chat::find($this->chatId);

        if (!$chat) {
            logger()->warning("Chat no encontrado con ID {$this->chatId}");
            return;
        }

        $prompt = "Dame un titulo que resuma el tema principal dado el siguiente mensaje: \n Mensaje: \n {$this->message} \n\n Da tu respuesta sin formato y en menos de 35 caracteres";
        $url = config('services.ia_server') . '/model/prompt';

        $response = Http::post($url, [
            'prompt' => $prompt,
            'model'  => 'cogito:14b',
        ]);

        if ($response->successful()) {
            $title = $response->json('response');
            $chat->title = $title;
            $chat->save();

            logger()->info("Título generado para chat {$this->chatId}: {$title}");
        } else {
            logger()->error('Error al generar título', [
                'chat_id' => $this->chatId,
                'status'  => $response->status(),
                'body'    => $response->body()
            ]);
        }
    }
}
