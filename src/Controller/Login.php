<?php

namespace Mostafa\Kitchen\Controller;

use Mostafa\Kitchen\Model\User;
use Mostafa\Kitchen\View;
use Mostafa\Kitchen\public\Session;
use Mostafa\Kitchen\public\Request;

class Login
{
    private $userRepository;
    private $session;

    public function __construct()
    {
        $this->userRepository = new User;
        $this->session = new Session();
    }

    public function login()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); 
        $data = [
            'username' => trim($_POST['username']),
            'password' => trim($_POST['password']),
        ];
        $loginUser= $this->userRepository->login($data['username'], $data['password']);
        if ($loginUser) {
            $this->createUserSession($loginUser);
            // Redirect to the index page
            Request::redirect('Home/index');
        } else {
            Request::redirect('login/loginPage');
            echo 'Login failed! Invalid username or password.';
        }
    }

    public function loginPage(){
        View::Render('login.php');
    }

    public function createUserSession($user){
        $this->session->setSession('user_id',$user->id);
        $this->session->setSession('username',$user->username);
        // redirect('posts/index');
    }

    //logout and destroy user session
    public function logout(){
        $this->session->removeSession('user_id');
        $this->session->removeSession('username');
        $this->session->sessionDestroy();
        Request::redirect('login/loginPage');
    }
}
