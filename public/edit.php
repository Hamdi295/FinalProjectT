<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once '../app/Controllers/CarController.php';

// Initialize CarController
$carController = new CarController();

// Check if 'id' parameter is passed
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$carId = $_GET['id'];

$carModel = new Car();
$car = $carModel->getById($carId);

// Redirect if car not found
if (!$car) {
    header('Location: index.php');
    exit();
}

// Handle form submission for updating the car
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Call edit function from CarController
        $carController->edit($carId, $_POST, $_FILES['image']);
        header('Location: index.php'); // Redirect after editing
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Edit Car</h1>
    <a href="index.php">Back to Car List</a>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="title">Car Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($car['title']); ?>" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($car['description']); ?></textarea><br>

        <label for="price">Price:</label>
        <input type="text" name="price" value="<?php echo htmlspecialchars($car['price']); ?>" required><br>

        <label for="image">Car Image (JPG/PNG):</label>
        <input type="file" name="image" accept="image/jpeg, image/png"><br>

        <button type="submit">Update Car</button>
    </form>

    <?php if ($car['image_path']): ?>
        <h3>Current Image:</h3>
        <img src="../uploads/<?php echo htmlspecialchars(basename($car['image_path'])); ?>" alt="Car Image" width="100">
    <?php endif; ?>
</body>
</html>
