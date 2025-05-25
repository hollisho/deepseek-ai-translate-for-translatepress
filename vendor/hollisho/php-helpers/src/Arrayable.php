<?php

namespace hollisho\helpers;

interface Arrayable
{
    public function toArray(array $fields = [], array $expand = [], $recursive = true);
}