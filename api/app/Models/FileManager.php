<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileManager extends Model
{
    protected $fillable = [
        'user_id',
        'uuid',
        'name',
        'file',
        'type',
        'size',
        'path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
