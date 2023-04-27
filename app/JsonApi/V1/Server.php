<?php

namespace App\JsonApi\V1;

use App\Models\Thread;
use LaravelJsonApi\Core\Server\Server as BaseServer;

class Server extends BaseServer
{

    /**
     * The base URI namespace for this server.
     *
     * @var string
     */
    protected string $baseUri = '/api/v1';

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
        auth()->shouldUse('sanctum');

        Thread::creating(function (Thread $thread) {
            $thread->user()->associate(auth()->user());
        });

        Thread::saving(function (Thread $thread) {
            $slug = str($thread->title)->slug();

            $count = $thread->whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            $thread->slug = $count > 0 ? "{$slug}-{$count}" : $slug;
        });
    }

    /**
     * Get the server's list of schemas.
     *
     * @return array
     */
    protected function allSchemas(): array
    {
        return [
            Threads\ThreadSchema::class,
            Users\UserSchema::class,
        ];
    }
}
