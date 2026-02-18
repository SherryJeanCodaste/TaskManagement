<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $notifications = [];
        
        if ($request->user()) {
            $user = $request->user();
            
            if ($user->role === 'customer') {
                // Customers see:
                // 1. Unread comments from developers on their tasks
                $comments = \App\Models\TaskComment::whereHas('task', function($query) use ($user) {
                    $query->where('customer_id', $user->id);
                })
                    ->where('user_id', '!=', $user->id)
                    ->whereNull('read_at')
                    ->with(['user', 'task'])
                    ->latest()
                    ->get()
                    ->map(function($comment) {
                        return [
                            'id' => $comment->id,
                            'type' => 'comment',
                            'user' => $comment->user,
                            'task_id' => $comment->task_id,
                            'task' => $comment->task,
                            'comment' => $comment->comment,
                            'created_at' => $comment->created_at,
                        ];
                    });
                
                // 2. Unread work submissions (attachments) from developers
                $attachments = \App\Models\TaskAttachment::whereHas('task', function($query) use ($user) {
                    $query->where('customer_id', $user->id);
                })
                    ->where('user_id', '!=', $user->id)
                    ->whereNull('read_at')
                    ->with(['user', 'task'])
                    ->latest()
                    ->get()
                    ->map(function($attachment) {
                        return [
                            'id' => $attachment->id,
                            'type' => 'attachment',
                            'user' => $attachment->user,
                            'task_id' => $attachment->task_id,
                            'task' => $attachment->task,
                            'attachment_type' => $attachment->type,
                            'attachment_name' => $attachment->name,
                            'created_at' => $attachment->created_at,
                        ];
                    });
                
                $notifications = $comments->concat($attachments)->sortByDesc('created_at')->take(10)->values();
            } else {
                // Developers see unread comments from customers on their assigned tasks
                $notifications = \App\Models\TaskComment::whereHas('task', function($query) use ($user) {
                    $query->where('assigned_to', $user->id);
                })
                    ->where('user_id', '!=', $user->id)
                    ->whereNull('read_at')
                    ->with(['user', 'task'])
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(function($comment) {
                        return [
                            'id' => $comment->id,
                            'type' => 'comment',
                            'user' => $comment->user,
                            'task_id' => $comment->task_id,
                            'task' => $comment->task,
                            'comment' => $comment->comment,
                            'created_at' => $comment->created_at,
                        ];
                    });
            }
        }
        
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'notifications' => $notifications,
        ];
    }
}
