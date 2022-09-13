<?php

namespace App\Filters\JobFilter;
use App\Filters\BaseFilter;

class Title extends BaseFilter {
    protected function applyFilter($query)
    {
        return $query->where($this->filterName(), request($this->filterName()));
    }
}
