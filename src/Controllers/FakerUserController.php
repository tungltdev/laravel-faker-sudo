<?php

namespace Tungltdev\fakerUserSudoSu\Controllers;

use Illuminate\Http\Request;
use Tungltdev\fakerUserSudoSu\FakerUserSudoSu;
use Illuminate\Routing\Controller;

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

        return redirect()->back();
    }

    public function returnUser()
    {
        $this->sudoSu->returnUser();

        return redirect()->back();
    }
}
