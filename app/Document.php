<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use DB;

class Document extends Authenticatable
{
    public $timestamps = false;

    protected $fillable = [
        'doc_path', 'doc_name', 'doc_meta', 'doc_project_id', 'doc_status', 'doc_meta'
    ];


    // public function __construct() {           
    //     return $this;
    // }
}
