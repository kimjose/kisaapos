<?php

namespace Infinitops\Inventory\Controllers;

use Infinitops\Inventory\Models\User;

class UsersController extends BaseController
{

    public function createUser($data)
    {
        // ["user_name", "first_name", "last_name", "email", "phone_number"]
        try {
            $attributes = ["user_name", "first_name", "last_name", "email", 'gender', "phone_number"];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed"));
            User::create([
                "user_name" => $data['user_name'], "first_name" => $data['first_name'], "last_name" => $data['last_name'],
                "email" => $data['email'], "phone_number" => $data['phone_number'], 'gender' => $data['gender'], 'password' => password_hash($data['password'], PASSWORD_DEFAULT)
            ]);
            $this->getUsers();
        } catch (\Throwable $th) {
            $this->logError(412, $th->getMessage());
            $this->response(412, "Unable to save user.");
        }
    }

    public function updateUser($data, $id)
    {
        try {
            $attributes = ["user_name", "first_name", "last_name", "email", "phone_number", 'gender'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed"));
            $user = User::findOrFail($id);
            $user->user_name = trim($data['user_name']);
            $user->first_name = trim($data['first_name']);
            $user->last_name = trim($data['last_name']);
            $user->email = trim($data['email']);
            $user->gender = $data['gender'];
            $user->phone_number = trim($data['phone_number']);
            if(isset($data['password']) && trim($data['password']) != '') $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $user->save();
            $this->getUsers();
        } catch (\Throwable $th) {
            $this->logError(412, $th->getMessage());
            $this->response(412, "Unable to update user");
        }
    }

    public function getUser($id)
    {
        $this->response(200, "User", User::find($id));
    }

    public function getUsers()
    {
        $users = User::all();
        $this->response(200, "Users ", $users);
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
        } catch (\Throwable $th) {
            $this->logError(412, $th->getMessage());
            $this->response(412, "Unable to delete user.");
        }
    }

    public function login()
    {
    }
}
