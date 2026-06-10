<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class ActivityLogger
{
    public static function record(?User $user, string $type, string $title, ?string $detail = null, ?string $link = null): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        ActivityLog::create([
            'user_id' => $user?->id,
            'activity_type' => $type,
            'title' => $title,
            'detail' => $detail,
            'link' => $link,
        ]);
    }
}
