<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function proxy(Request $request)
    {
        $file = $request->query('file');
        if (!$file) {
            abort(400, 'Falta el parámetro file');
        }

        $iaServer = config('services.ia_server');

        $response = Http::withOptions(['stream' => true])
            ->get("{$iaServer}/documents/file?file_name=" . urlencode($file));

        if (!$response->ok()) {
            abort(404, 'No se pudo obtener el archivo');
        }

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $mime = match($ext) {
            'pdf' => 'application/pdf',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            default => 'application/octet-stream',
        };

        return response($response->body())
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . basename($file) . '"');
    }
}
