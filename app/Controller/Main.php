<?php
namespace App\Controller;

use App\Model\UserModel;

class Main {

    public UserModel $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function index(){
        
        return view('main');
    }
}