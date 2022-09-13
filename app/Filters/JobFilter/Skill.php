<?php

namespace App\Filters\JobFilter;
use App\Filters\BaseFilter;

class Skill extends BaseFilter {
    protected function applyFilter($query)
    {
        return $query->whereHas('skills', function($query) {
            $query->where('name', request($this->filterName()));
        });
    }
}
