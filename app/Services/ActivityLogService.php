<?php

namespace App\Services;

use App\Models\JournalActivite;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public static function log(string $action, object $model, ?array $before = null, ?array $after = null): void
    {
        $user = Auth::user();
        
        JournalActivite::create([
            'utilisateur_id' => $user?->id,
            'action' => $action,
            'objet_type' => get_class($model),
            'objet_id' => $model->id ?? null,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'donnees_avant' => $before,
            'donnees_apres' => $after,
        ]);
    }

    public static function created(object $model, ?array $data = null): void
    {
        self::log('create', $model, null, $data);
    }

    public static function updated(object $model, ?array $before = null, ?array $after = null): void
    {
        self::log('update', $model, $before, $after);
    }

    public static function deleted(object $model, ?array $data = null): void
    {
        self::log('delete', $model, $data, null);
    }
}