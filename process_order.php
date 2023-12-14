<!--
    Name:           Dylan Holmwood
    Student Number: D21124331
    Date:           14, December 2023
    Module:         Open-source Software
    Module Code:    OSSW4604
    Lecturer:       Dr. Ali Malik
    Description:    This php page to for TUD Sandwich Shop where the order
                    details for the customer are taken from the form
                    submitted on index.html and used to display a message
                    to the user about their order and to insert their 
                    order details into the table orders in the mariadb
                    databased TUD_Sandwich_Shop.
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <div class="container">
        <h2>Order Confirmation</h2>

        <?php
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve form data
            $name = $_POST['name'];
            $bread = $_POST['bread'];
            $filling = $_POST['filling'];
            $topping = isset($_POST['topping']) ? $_POST['topping'] : ''; // Topping is optional
            $condiment = isset($_POST['condiment']) ? $_POST['condiment'] : ''; // Condiment is optional

            // Calculate the price 
            $price = calculatePrice($bread, $filling, $topping, $condiment);

            // Database connection parameters
            $servername = "127.0.0.1";
            $username = "dylan";
            $password = "123456";
            $dbname = "TUD_Sandwich_Shop"; // your database name

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL to insert order details into the orders table
            $sql = "INSERT INTO orders (name, bread, filling, topping, condiment, price) VALUES ('$name', '$bread', '$filling', '$topping', '$condiment', $price)";

            if ($conn->query($sql) === TRUE) {
                echo "<p>Thank you, $name! Your order has been placed successfully.</p>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            // Close the database connection
            $conn->close();

            // Display order details
            echo "<div class='order-details-box'>";
            echo "<p>Order Details:</p>";
            echo "<ul>";
            echo "<li>Bread: $bread</li>";
            echo "<li>Filling: $filling</li>";
            echo "<li>Topping: $topping</li>";
            echo "<li>Condiment: $condiment</li>";
            echo "</ul>";
            echo "<p>Total Price: €$price</p>";
            echo "</div>";
        } else {
            // If the form is not submitted, display a message
            echo "<p>Please submit the order form to place an order.</p>";
        }

        // Function to calculate the price
        function calculatePrice($bread, $filling, $topping, $condiment) {
            $basePrice = 5.00; // Base price
            $toppingPrice = 1.00; // Additional price for each topping
            $condimentPrice = 0.50; // Additional price for each condiment

            // Calculate total price
            $totalPrice = $basePrice + ($topping ? $toppingPrice : 0) + ($condiment ? $condimentPrice : 0);

            return number_format($totalPrice, 2); // Format price with two decimal places
        }
        ?>

    </div>

</body>

</html>

