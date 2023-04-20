<?php

use App\Models\Thread;

it('fetches threads resource collection', function () {
    $threads = Thread::factory(5)->create();

    $response = $this
        ->jsonApi()
        ->expects('threads')
        ->get('api/v1/threads');

    $response->assertFetchedMany($threads);
});

it('fetches a single thread resource', function () {
    $thread = Thread::factory()->create();
    $self = 'http://forum-api.test/api/v1/threads/' .$thread->getRouteKey();

    $expected = [
        'type' => 'threads',
        'id' => (string) $thread->getRouteKey(),
        'attributes' => [
            'title' => $thread->title,
            'content' => $thread->content,
            'slug' => $thread->slug,
            'locked' => $thread->locked,
            'createdAt' => $thread->created_at,
            'updatedAt' => $thread->updated_at,
        ],
        'relationships' => [
            'user' => [
                'links' => [
                    'self' => "{$self}/relationships/user",
                    'related' => "{$self}/user",
                ],
            ],
        ],
        'links' => [
            'self' => $self,
        ]
    ];

    $response = $this
        ->jsonApi()
        ->expects('threads')
        ->get($self);

    $response->assertFetchedOneExact($expected);
});
