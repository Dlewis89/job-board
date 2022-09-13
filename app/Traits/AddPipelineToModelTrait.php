<?php

namespace App\Traits;
use Illuminate\Pipeline\Pipeline;

trait AddPipelineToModelTrait {
    public function scopeFilterWithPipeline($query, Array $pipes) {
        return app(Pipeline::class)->send($query)->through($pipes)->thenReturn();
    }
}
