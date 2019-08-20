<?php

namespace Tungltdev\fakerUserSudoSu\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tungltdev\fakerUserSudoSu\FakerUserSudoSu;

class FakerUserController extends Controller
{
    protected $sudoSu;

    public function __construct(FakerUserSudoSu $sudoSu)
    {
        $this->sudoSu = $sudoSu;
    }

    public function loginAsUser(Request $request)
    {
        $this->sudoSu->loginAsUser($request->userId, $request->originalUserId);

        $link_comeback = config('faker_user.url_call_back');
        if ($link_comeback) {
            return redirect($link_comeback);
        } else {
            return redirect()->back();
        }
    }

    public function returnUser()
    {
        $this->sudoSu->returnUser();

        $link_comeback = config('faker_user.url_call_back');
        if ($link_comeback) {
            return redirect($link_comeback);
        } else {
            return redirect()->back();
        }
    }
}
