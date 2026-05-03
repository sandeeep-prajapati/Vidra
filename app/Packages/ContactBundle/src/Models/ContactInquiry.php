<?php

namespace App\Packages\ContactBundle\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    protected $fillable = ['name', 'email', 'subject', 'message'];
}
