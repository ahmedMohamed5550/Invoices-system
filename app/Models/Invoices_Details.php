<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices_Details extends Model
{
    use HasFactory;
    protected $table = "invoices_details";
    protected $fillable = [
        'invoice_id',
        'invoice_number',
        'product',
        'Section', // Assuming this is the foreign key for the section relationship
        'Status',
        'Value_Status',
        'Payment_Date',
        'note',
        'user',
    ];

}
