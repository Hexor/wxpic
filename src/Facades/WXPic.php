<?php
namespace Hexor\WXPic\Facades;

use Illuminate\Support\Facades\Facade;

class WXPic extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'wxpic';
    }
}
