<?php

require_once '../app/Models/User.php';
require_once 'BaseController.php';

class AuthController extends BaseController {

    // Login method
    public function login($username, $password) {
        try {
            $userModel = new User();
            $user = $userModel->login($username, $password);

            if ($user) {
                session_start();
                $_SESSION['user'] = $user;
                session_regenerate_id(true);
                header('Location: ../public/index.php');
                exit();
            } else {
                return "Invalid username or password.";
            }
        } catch (Exception $e) {
            error_log($e->getMessage(), 3, '../logs/error.log');
            return "An error occurred. Please try again.";
        }
    }

    // Logout method
    public function logout() {
        session_start();
        session_destroy();
        header('Location: ../public/login.php');
    }

    public function register($username, $password, $role) {
        try {
            $userModel = new User();
            $userModel->createUser($username, $password, $role);
            return "Registration successful.";
        } catch (Exception $e) {
            return $e->getMessage(); // Return the error message
        }
    }
    // Render login page
    public function renderLoginPage() {
        $this->renderView('login');
    }
}
