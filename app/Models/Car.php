<?php

require_once 'BaseModel.php';

class Car extends BaseModel {
    // Create a new car
    public function create($title, $description, $price, $imagePath, $userId) {
        $stmt = $this->pdo->prepare("INSERT INTO cars (title, description, price, image_path, created_by) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $description, $price, $imagePath, $userId]);
    }

    // Get all cars
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM cars");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single car by ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a car's details
    public function update($id, $title, $description, $price, $imagePath) {
        $stmt = $this->pdo->prepare("UPDATE cars SET title = ?, description = ?, price = ?, image_path = ? WHERE id = ?");
        return $stmt->execute([$title, $description, $price, $imagePath, $id]);
    }

    // Delete a car by ID
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM cars WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
