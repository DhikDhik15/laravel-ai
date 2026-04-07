<?php

namespace AiWorkspace\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class WorkspaceModelResolver
{
    public function chatClass(): string
    {
        return $this->resolveConfiguredClass('models.chat');
    }

    public function messageClass(): string
    {
        return $this->resolveConfiguredClass('models.message');
    }

    public function newChatQuery(): Builder
    {
        return $this->newModelInstance($this->chatClass())->newQuery();
    }

    public function newMessageQuery(): Builder
    {
        return $this->newModelInstance($this->messageClass())->newQuery();
    }

    public function newChatInstance(array $attributes = []): Model
    {
        return $this->newModelInstance($this->chatClass(), $attributes);
    }

    public function newMessageInstance(array $attributes = []): Model
    {
        return $this->newModelInstance($this->messageClass(), $attributes);
    }

    protected function resolveConfiguredClass(string $key): string
    {
        $class = config('ai-workspace.' . $key);

        if (! is_string($class) || ! class_exists($class)) {
            throw new InvalidArgumentException("Configured class for [ai-workspace.$key] is invalid.");
        }

        if (! is_subclass_of($class, Model::class)) {
            throw new InvalidArgumentException("Configured class [$class] must extend Illuminate\\Database\\Eloquent\\Model.");
        }

        return $class;
    }

    protected function newModelInstance(string $class, array $attributes = []): Model
    {
        return new $class($attributes);
    }
}
