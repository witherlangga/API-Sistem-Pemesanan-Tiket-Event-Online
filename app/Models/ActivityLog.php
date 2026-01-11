<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    // Persist activity logs into the `log` database table (as requested)
    protected $table = 'log';

    protected $fillable = [
        'user_id', 'action', 'method', 'url', 'subject_type', 'subject_id', 'meta', 'ip_address', 'user_agent', 'status_code'
    ];

    protected $casts = [
        'meta' => 'array',
        'status_code' => 'integer',
    ];

    public static function record($userId, $action, $subject = null, $meta = [], $request = null, $status = null)
    {
        $subjectType = null;
        $subjectId = null;
        if ($subject) {
            if (is_object($subject)) {
                $subjectType = get_class($subject);
                $subjectId = $subject->id ?? null;
            } elseif (is_array($subject)) {
                $meta = array_merge($meta, $subject);
            }
        }

        $ip = null;
        $ua = null;
        $method = null;
        $url = null;
        if ($request) {
            $ip = $request->ip();
            $ua = $request->userAgent();
            $method = $request->method();
            $url = $request->fullUrl();
        }

        $record = static::create([
            'user_id' => $userId,
            'action' => $action,
            'method' => $method,
            'url' => $url,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'meta' => $meta ?: null,
            'ip_address' => $ip,
            'user_agent' => $ua,
            'status_code' => $status,
        ]);

        // NOTE: File-based activity logging removed per request; logs are stored in DB table `log` only.

        return $record;
    }
}
