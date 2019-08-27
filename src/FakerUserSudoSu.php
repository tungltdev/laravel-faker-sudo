<?php

namespace Tungltdev\fakerUserSudoSu;

use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Application;
use Illuminate\Session\SessionManager;
use Symfony\Component\HttpFoundation\Response;

class FakerUserSudoSu
{
    protected $app;
    protected $auth;
    protected $session;
    protected $sessionKey = 'faker_user.original_id';
    protected $usersCached = null;

    public function __construct(Application $app, AuthManager $auth, SessionManager $session)
    {
        $this->app = $app;
        $this->auth = $auth;
        $this->session = $session;
    }

    public function loginAsUser($userId, $currentUserId)
    {
        $this->session->put('faker_user.has_sudoed', true);
        $this->session->put($this->sessionKey, $currentUserId);
        $guard = config('faker_user.guard','web');
        $this->auth->guard($guard)->loginUsingId($userId);
    }

    public function returnUser()
    {
        if (!$this->hasSudoed()) {
            return false;
        }

        $this->auth->logout();

        $originalUserId = $this->session->get($this->sessionKey);

        if ($originalUserId) {
            $this->auth->loginUsingId($originalUserId);
        }

        $this->session->forget($this->sessionKey);
        $this->session->forget('faker_user.has_sudoed');

        return true;
    }

    public function injectToView(Response $response)
    {
        $packageContent = view('faker_user::user-selector', [
            'usersFakers' => $this->getUsers(),
            'hasSudoed' => $this->hasSudoed(),
            'originalUser' => $this->getOriginalUser(),
            'currentUser' => $this->auth->user()
        ])->render();

        $responseContent = $response->getContent();

        $response->setContent($responseContent . $packageContent);
    }

    public function getOriginalUser()
    {
        if (!$this->hasSudoed()) {
            return $this->auth->user();
        }

        $userId = $this->session->get($this->sessionKey);

        return $this->getUsers()->where('id', $userId)->first();
    }

    public function hasSudoed()
    {
        return $this->session->has('faker_user.has_sudoed');
    }

    public function getUsers()
    {
        if ($this->usersCached) {
            return $this->usersCached;
        }

        $user = $this->getUserModel();

        $order = config('faker_user.orderBy')?:config('faker_user.fields');
        return $this->usersCached = $user->orderBy($order)->get();
    }

    protected function getUserModel()
    {
        $userModel = config('faker_user.user_model');
        return $this->app->make($userModel);
    }
}
