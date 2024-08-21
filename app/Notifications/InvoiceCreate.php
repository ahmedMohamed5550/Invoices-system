<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceCreate extends Notification
{
    use Queueable;

    public $invoice_id;
    protected $invoice_number;
    protected $invoice_Date;
    protected $section_name;
    protected $create_by;

    public function __construct($invoice_id,$invoice_number, $invoice_Date, $section_name, $create_by)
    {
        $this->invoice_id = $invoice_id;
        $this->invoice_number = $invoice_number;
        $this->invoice_Date = $invoice_Date;
        $this->section_name = $section_name;
        $this->create_by = $create_by;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id' => $this->invoice_id,
            'invoice_number' => $this->invoice_number,
            'invoice_Date'   => $this->invoice_Date,
            'section_name'   => $this->section_name,
            'create_by'           => $this->create_by,
        ];
    }
}
