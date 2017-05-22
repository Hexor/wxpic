<?php

namespace Hexor\WXPic;

use App\Http\Controllers\Controller;
use Hexor\WXPic\Facades\WXPic;
use Illuminate\Http\Request;

class WXPicController extends Controller
{
    public function index()
    {
        return WXPic::donow();

        return "hello world";
    }
}
