<?php

namespace hollisho\translatepress\translate\deepseek\inc\Base;

class Deactivate
{
    public static function handler()
    {
        flush_rewrite_rules();
    }
}