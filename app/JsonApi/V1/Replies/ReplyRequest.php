<?php

namespace App\JsonApi\V1\Replies;

use App\Models\Thread;
use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class ReplyRequest extends ResourceRequest
{

    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'content' => 'required|string|max:5000',
            'thread' => [
                JsonApiRule::toOne(),
                function (string $attribute, mixed $value, \Closure $fail) {
                    $thread = Thread::findOrFail($value['id']);

                    if (! auth()->user()->isAdmin() && $thread->locked) {
                        $fail('Thread is locked.');
                    }
                },
            ]
        ];
    }


}
