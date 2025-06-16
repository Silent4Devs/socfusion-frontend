<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Document;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class NightlyDocsExtraction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $serverUrl = config('services.ia_server'); 
        $allFilesUrl = "{$serverUrl}/documents/all-files";

        $response = Http::get($allFilesUrl);

        if (!$response->ok()) {
            \Log::error("Error al obtener lista de archivos");
            return;
        }

        $files = $response->json(); 
        foreach ($files as $file) {
            if (!isset($file['fullpath'])) {
                \Log::warning("Archivo sin 'fullpath', se ignora");
                continue;
            }

            $fileName = $file['fullpath'];
            if (Document::where('full_name', $fileName)->exists()) {
                continue;
            }
            
            \Log::info("Procesando archivo: {$fileName}");

            $fileResponse = Http::get("{$serverUrl}/documents/file?file_name=" . urlencode($fileName));
            if (!$fileResponse->ok()) {
                \Log::warning("No se pudo obtener archivo: {$fileName}");
                continue;
            }

            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $shortName = pathinfo($fileName, PATHINFO_FILENAME);

            if ($extension === 'pdf') {
                $path = "documents/{$shortName}";
                Storage::disk('public')->put($path, $fileResponse->body());
                
                Document::create([
                    'name' => $shortName,
                    'full_name' => $fileName,
                    'file_type' => 'pdf',
                ]);
            } elseif ($extension === 'docx') {

                try {
                    $convertResponse = Http::attach(
                        'files',
                        $fileResponse->body(),
                        $fileName
                    )->post(config('services.gotenberg') . '/forms/libreoffice/convert');

                    if ($convertResponse->ok()) {
                        $pdfName = $shortName . '.pdf';
                        $path = "documents/{$pdfName}";
                        Storage::disk('public')->put($path, $fileResponse->body());

                        Document::create([
                            'name' => $shortName,
                            'full_name' => $fileName,
                            'file_type' => 'docx',
                        ]);
                    } else {
                        \Log::error("Error al convertir DOCX: {$fileName}. Estado: " . $convertResponse->status());
                    }

                } catch (\Exception $e) {
                    \Log::error("Excepción al convertir DOCX {$fileName}: " . $e->getMessage());
                }
            }
        }
    }
}
