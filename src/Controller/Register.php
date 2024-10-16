<?php 

namespace Mostafa\Kitchen\Controller;

use Mostafa\Kitchen\Model\User;
use Mostafa\Kitchen\public\Request;
use Mostafa\Kitchen\public\Session;
use Mostafa\Kitchen\View;

class Register{

    private $userRepository;
    private $session;
    public function __construct()
    {
        $this->userRepository = new User;
        $this->session = new Session();
    }

    public function showRegister(){
        View::Render('register.php');
    }
    public function register()
    {
        $_POST = filter_input_array(INPUT_POST); 
        if ($this->userRepository->findByUsername($_POST['username'])) {
            throw new \Exception('Username already exists.');
        }
        $passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $user = [
            'firstname' => trim($_POST['firstname']),
            'middlename' => trim($_POST['middlename']),
            'password' => $passwordHash,
            'lastname' => trim($_POST['lastname']),
            'username' => trim($_POST['username']),
            'avatar' => null,
            'type' => 0
        ];
        
        $this->session->flashSuccess('register_success', 'you are registerd you can login now');
        $this->userRepository->register($user);
        Request::redirect('login/loginPage');
    }
}
