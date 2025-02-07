<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class ModelUpdated implements ShouldBroadcastNow
{
    use Dispatchable;

    public $model;
    public $modelType;
    public $action; // 'created', 'updated', 'deleted'

    public function __construct($model, string $modelType, string $action)
    {
        $this->model = $model;
        $this->modelType = $modelType;
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return new Channel('global-updates');
    }

    public function broadcastAs()
    {
        return 'model.updated';
    }
}