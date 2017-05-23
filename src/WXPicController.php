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

    public function toLocal(Request $request)
    {
        $remote = $request->input(['remote']);
        return WXPic::wx2Local($remote);

        return $remote;
    }

    public function toRemote(Request $request)
    {
        $local = $request->input(['local']);
        return WXPic::local2WX($local);

        return $local;
    }
}
