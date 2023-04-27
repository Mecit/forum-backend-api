<?php

beforeEach(function () {
   $this->thread = \App\Models\Thread::factory()->make();

   $this->data = [
       'type' => 'threads',
       'attributes' => [
           'title' => $this->thread->title,
           'content' => $this->thread->content,
           'slug' => $this->thread->slug,
           'locked' => false,
       ],
   ];
});

test('guests cannot create threads', function () {
    $response = $this
        ->jsonApi()
        ->expects('threads')
        ->withData($this->data)
        ->post('api/v1/threads');

    $response->assertErrorStatus([
        'status' => '401',
        'detail' => 'Unauthenticated.',
        'title' => 'Unauthorized',
    ]);
});

test('authenticated users can create threads', function () {
    $response = $this
        ->actingAs(\App\Models\User::inRandomOrder()->first())
        ->jsonApi()
        ->expects('threads')
        ->withData($this->data)
        ->post('api/v1/threads');

    $response->assertCreatedWithServerId(
        'http://forum-api.test/api/v1/threads',
        $this->data
    );
})->skip();

