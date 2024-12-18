<?php

require_once '../app/Models/Car.php';
require_once '../app/Models/AuditLog.php';  // Include the AuditLog class
require_once 'BaseController.php';

class CarController extends BaseController {
    private $carModel;
    private $auditLog;

    public function __construct() {
        $this->carModel = new Car();
        $this->auditLog = new AuditLog();  // Initialize the AuditLog model
    }

    // Create a new car
    public function create($data, $file) {
        try {
            $title = htmlspecialchars($data['title']);
            $description = htmlspecialchars($data['description']);
            $price = filter_var($data['price'], FILTER_VALIDATE_FLOAT);

            $uploadDir = '../uploads/';
            $imagePath = null;

            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $validTypes = ['image/jpeg', 'image/png'];
                if (!in_array($file['type'], $validTypes)) {
                    throw new Exception("Invalid file type.");
                }

                $imagePath = $uploadDir . uniqid() . '_' . basename($file['name']);
                move_uploaded_file($file['tmp_name'], $imagePath);
            }

            $this->carModel->create($title, $description, $price, $imagePath, $_SESSION['user']['id']);
            
            // Log the action
            $this->auditLog->record($_SESSION['user']['id'], 'Create Car', 'User created a new car titled: ' . $title);

        } catch (Exception $e) {
            error_log($e->getMessage(), 3, '../logs/error.log');
            throw $e;
        }
    }

    // Edit a car's details
    public function edit($id, $data, $file) {
        try {
            $car = $this->carModel->getById($id);
            if (!$car) {
                throw new Exception("Car not found.");
            }

            $title = htmlspecialchars($data['title']);
            $description = htmlspecialchars($data['description']);
            $price = filter_var($data['price'], FILTER_VALIDATE_FLOAT);

            $uploadDir = '../uploads/';
            $imagePath = $car['image_path']; // Default to the current image

            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $validTypes = ['image/jpeg', 'image/png'];
                if (!in_array($file['type'], $validTypes)) {
                    throw new Exception("Invalid file type.");
                }

                $imagePath = $uploadDir . uniqid() . '_' . basename($file['name']);
                move_uploaded_file($file['tmp_name'], $imagePath);
            }

            $this->carModel->update($id, $title, $description, $price, $imagePath);
            
            // Log the action
            $this->auditLog->record($_SESSION['user']['id'], 'Edit Car', 'User edited the car titled: ' . $title);

        } catch (Exception $e) {
            error_log($e->getMessage(), 3, '../logs/error.log');
            throw $e;
        }
    }

    // Delete a car
    public function delete($id) {
        try {
            $car = $this->carModel->getById($id);
            if ($car) {
                $this->carModel->delete($id);
                
                // Log the action
                $this->auditLog->record($_SESSION['user']['id'], 'Delete Car', 'User deleted the car with ID: ' . $id);
            } else {
                throw new Exception("Car not found.");
            }
        } catch (Exception $e) {
            error_log($e->getMessage(), 3, '../logs/error.log');
            throw $e;
        }
    }
}
