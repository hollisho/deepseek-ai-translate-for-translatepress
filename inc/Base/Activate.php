<?php

namespace hollisho\translatepress\translate\deepseek\inc\Base;

class Activate
{
    public static function handler()
    {
        flush_rewrite_rules();
    }
}