<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReportEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $emailSubject;
    public array $alarm;
    public array $csvData;
    public array $ips;
    public array $urls;
    public array $report;
    public array $details;

    public function __construct(string $emailSubject, array $alarm, array $csvData, array $ips, array $urls, array $report, array $details)
    {
        $this->emailSubject = $emailSubject;
        $this->alarm = $alarm;
        $this->csvData = $csvData;
        $this->ips = $ips;
        $this->urls = $urls;
        $this->report = $report;
        $this->details = $details;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.report',
            with: [
                'alarm' => $this->alarm,
                'csvData' => $this->csvData,
                'ips' => $this->ips,
                'urls' => $this->urls,
                'details' => $this->details,
            ]
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath(Storage::path($this->report['filepath']))
                ->as('reporte.pdf')
                ->withMime('application/pdf'),
        ];
    }

    public function build()
    {
        return $this;
    }
}
