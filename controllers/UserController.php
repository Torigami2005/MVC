<?php

class UserController {
    private $userModel;


    public function __construct($userModel){
    $this->userModel = $userModel;
    }
    
    public function getALLUsers(){
        return $this->userModel->getAllUsers();
    }

    public function getUserById($id){
        return $this->userModel->getUserById($id);
    }

    public function updateUser($id, $name, $description, $status){
        return $this->userModel->updateUser($id, $name, $description, $status);
    }

    public function createUser($name, $description, $status){
        return $this->userModel->createUser($name, $description, $status);
    }

    public function deleteUser($id){
        return $this->userModel->deleteUser($id);
    }
} 