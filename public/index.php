<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once '../app/Controllers/CarController.php';

// Initialize CarController
$carController = new CarController();

// Handle delete request
if (isset($_GET['delete'])) {
    try {
        // Call delete function from CarController
        $carController->delete($_GET['delete']);
        header('Location: index.php'); // Redirect after deletion
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

require_once '../app/Models/Car.php';
$carModel = new Car();
$cars = $carModel->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></h1>
    <a href="logout.php">Logout</a>

    <h2>Car List</h2>
    <a href="create.php">Add New Car</a>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <?php if ($_SESSION['user']['role'] !== 'Viewer') : ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars as $car): ?>
            <tr>
                <td><?php echo htmlspecialchars($car['title']); ?></td>
                <td><?php echo htmlspecialchars($car['description']); ?></td>
                <td><?php echo number_format($car['price'], 2); ?></td>
                <td>
                    <?php if ($car['image_path']): ?>
                        <img src="../uploads/<?php echo htmlspecialchars(basename($car['image_path'])); ?>" alt="Car Image" width="100">
                    <?php endif; ?>
                </td>
                <?php if ($_SESSION['user']['role'] !== 'Viewer') : ?>
                <td>
                    <a href="edit.php?id=<?php echo $car['id']; ?>">Edit</a> |
                    <a href="?delete=<?php echo $car['id']; ?>" onclick="return confirm('Are you sure you want to delete this car?');">Delete</a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
