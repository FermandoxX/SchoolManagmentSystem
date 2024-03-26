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
        $adminNumber = count($this->userModel->getData(['role_name'=>'admin']));
        $teacherNumber = count($this->userModel->getData(['role_name'=>'teacher']));
        $studentNumber = count($this->userModel->getData(['role_name'=>'student']));

        return view('main',['adminNumber'=>$adminNumber,'teacherNumber'=>$teacherNumber,'studentNumber'=>$studentNumber]);
    }
}