<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'id_matkul';

    public $incrementing = false;         // penting!
    protected $keyType = 'string';        // penting!

    protected $fillable = [
        'id_matkul', 'nama_matkul', 'sks', 'semester'
    ];
}
