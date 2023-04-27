<?php

namespace App\JsonApi\V1;

use App\Models\Thread;
use Illuminate\Support\Str;
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
            $thread->slug = str($thread->title)->slug() . '-' . Str::lower(Str::random(8));
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
