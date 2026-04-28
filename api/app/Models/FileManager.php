<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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
        'fileable_id',
        'fileable_type',
    ];

    protected $hidden = [
        'path',
        'file',
        'fileable_id',
        'fileable_type',
    ];

    protected $appends = [
        'token',
        'access_url',
        'download_url',
        'extension',
        'base64_data',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fileable()
    {
        return $this->morphTo();
    }

    public function getTokenAttribute(): string
    {
        return rtrim(strtr(base64_encode($this->uuid), '+/', '-_'), '=');
    }

    public function getAccessUrlAttribute(): string
    {
        return $this->getDataUri();
    }

    public function getDownloadUrlAttribute(): string
    {
        return Route::has('files.download')
            ? route('files.download', ['token' => $this->token])
            : url('/api/v1/files/' . $this->token . '/download');
    }

    public function getExtensionAttribute(): ?string
    {
        return $this->file ? Str::of($this->file)->afterLast('.')->lower()->toString() : null;
    }

    public function getBase64DataAttribute(): ?string
    {
        $path = public_path($this->getRawOriginal('path'));

        if (!File::exists($path)) {
            return null;
        }

        return base64_encode((string) File::get($path));
    }

    protected function getDataUri(): string
    {
        $path = public_path($this->getRawOriginal('path'));

        if (!File::exists($path)) {
            return Route::has('files.show')
                ? route('files.show', ['token' => $this->token])
                : url('/api/v1/files/' . $this->token);
        }

        $mimeType = File::mimeType($path) ?: 'application/octet-stream';

        return 'data:' . $mimeType . ';base64,' . base64_encode((string) File::get($path));
    }
}
