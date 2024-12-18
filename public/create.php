<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once '../app/Controllers/CarController.php';

$error = null; // Initialize the error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ensure the fields are not empty
        if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['price'])) {
            throw new Exception("Please fill in all the fields.");
        }

        // Instantiate the CarController
        $carController = new CarController();
        
        // Call the create method
        $carController->create($_POST, $_FILES['image']);
        
        // Redirect after successful creation
        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!-- Display form and error if necessary -->
<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Car Title:</label>
    <input type="text" name="title" required><br>

    <label for="description">Description:</label>
    <textarea name="description" required></textarea><br>

    <label for="price">Price:</label>
    <input type="text" name="price" required><br>

    <label for="image">Car Image (JPG/PNG):</label>
    <input type="file" name="image" accept="image/jpeg, image/png"><br>

    <button type="submit">Add Car</button>
</form>
