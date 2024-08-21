<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_Attachments extends Model
{
    use HasFactory;

    protected $table = "invoice_attachments";
    protected $fillable = [
        'file_name',
        'invoice_number',
        'Created_by',
        'invoice_id',
    ];
}
