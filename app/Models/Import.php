<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Import extends Model
{
    protected $table = "import";
    protected $fillable = [
        "created_by",
        "type",
        "batch_id",
        "total_rows",
        "updated_rows",
        "data",
        "status",
    ];

    public static function searchable(): array
    {
        return [
            "created_by",
            "type",
            "status",
            "created_at",
        ];
    }

    protected $casts = [
        "data" => "array",
    ];

    public function importData(): HasMany
    {
        return $this->hasMany(ImportData::class);
    }
}
