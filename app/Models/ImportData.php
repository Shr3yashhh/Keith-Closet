<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportData extends Model
{
    protected $fillable = [
        "import_id",
        "data",
        "status",
        "description",
    ];

    public static function searchable(): array
    {
        return [
            "import_id",
            "status",
        ];
    }

    protected $casts = [
        "data" => "array",
    ];

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }
}
