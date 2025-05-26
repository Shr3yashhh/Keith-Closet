<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// class NightlyReportMail extends Mailable
// {
//     use Queueable, SerializesModels;

//     public $pdfPath;

//     public function __construct($pdfPath)
//     {
//         $this->pdfPath = $pdfPath;
//     }

//     public function build()
//     {
//         return $this->subject('Nightly Inventory Report')
//             ->with([
//                 "date" => now()->format('Y-m-d'),
//             ])
//             ->view('admin.pages.report.nightly_report_mail') // create a simple blank view or change to use markdown
//             ->attach($this->pdfPath, [
//                 'mime' => 'application/pdf',
//             ]);
//     }
// }

class NightlyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $path;
    public string $reportType;
    public string $reportFrom;
    public string $reportTo;

    public function __construct(string $path, string $reportType, $reportFrom, $reportTo)
    {
        $this->path = $path;
        $this->reportType = $reportType;
        $this->reportFrom = $reportFrom;
        $this->reportTo = $reportTo;
    }

    public function build()
    {
        return $this->subject("{$this->reportType} Inventory Report ({$this->reportFrom} to {$this->reportTo})")
            ->view('admin.pages.report.nightly_report_mail')
            ->with([
                "date" => now()->format('Y-m-d'),
                "reportType" => $this->reportType,
            ])
            ->attach($this->path, [
                'as' => basename($this->path),
                'mime' => 'application/pdf',
            ]);
    }
}


