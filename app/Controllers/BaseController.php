<?php

class BaseController {
    // A method to handle common logic across controllers
    public function renderView($view, $data = []) {
        // Extract the data array to variables
        extract($data);

        // Include the header (optional)
        require_once '../app/Views/layout.php';

        // Include the specific view
        require_once '../app/Views/' . $view . '.php';

        // Optionally, you can include the footer here
    }
}
