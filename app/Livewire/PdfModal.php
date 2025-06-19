<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Document;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Storage;

class PdfModal extends Component
{

    public $show = False; 
    public $url, $iaServer;

    public function render()
    {
        return view('livewire.pdf-modal');
    }

    public function mount() : void
    {
        $this->show = false; 
        $this->iaServer = config('services.ia_server');
    }

    #[On('show-pdf')]
    public function showPdf($file)
    {
        $document = Document::where('full_name', $file)->first();
        
        if (!$document) {
            $this->dispatch('pdf-error');
            return;
        }

        $extension = strtolower(pathinfo($document->name, PATHINFO_EXTENSION));
        $basename = pathinfo($document->name, PATHINFO_FILENAME);
        $pdfName = ($extension === 'doc' || $extension === 'docx') ? $basename . '.pdf' : $document->name . '.pdf';
        
        $storagePath = 'documents/' . $pdfName;
        if (!Storage::disk('public')->exists($storagePath)){
            $this->dispatch('pdf-error');
            return;
        }

        $this->url = Storage::url($storagePath);
        $this->show = true;
    }



    public function close(){
        $this->show = false;
    }
}
