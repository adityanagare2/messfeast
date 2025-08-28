<?php
// This file is for testing purposes only
// Add some sample data to test the system

include("php/dbconnect.php");

echo "<h2>Adding Sample Data...</h2>";

// Add sample students if they don't exist
$students = [
    ['name' => 'John Doe', 'email' => 'john@test.com', 'phone' => '1234567890', 'password' => md5('password123')],
    ['name' => 'Jane Smith', 'email' => 'jane@test.com', 'phone' => '0987654321', 'password' => md5('password123')],
    ['name' => 'Mike Johnson', 'email' => 'mike@test.com', 'phone' => '5555555555', 'password' => md5('password123')]
];

foreach ($students as $student) {
    $check = mysqli_query($conn, "SELECT id FROM students WHERE email = '{$student['email']}'");
    if (mysqli_num_rows($check) == 0) {
        $insert = mysqli_query($conn, "INSERT INTO students (name, email, phone, password) VALUES ('{$student['name']}', '{$student['email']}', '{$student['phone']}', '{$student['password']}')");
        if ($insert) {
            echo "Added student: {$student['name']}<br>";
        }
    } else {
        echo "Student already exists: {$student['name']}<br>";
    }
}

// Add sample orders
$student_ids = [];
$result = mysqli_query($conn, "SELECT id FROM students LIMIT 3");
while ($row = mysqli_fetch_assoc($result)) {
    $student_ids[] = $row['id'];
}

if (!empty($student_ids)) {
    // Add some sample orders
    $orders = [
        ['student_id' => $student_ids[0], 'total_amount' => 45.00, 'status' => 'Completed'],
        ['student_id' => $student_ids[1], 'total_amount' => 70.00, 'status' => 'Pending'],
        ['student_id' => $student_ids[2], 'total_amount' => 35.00, 'status' => 'Completed'],
        ['student_id' => $student_ids[0], 'total_amount' => 50.00, 'status' => 'Pending'],
        ['student_id' => $student_ids[1], 'total_amount' => 80.00, 'status' => 'Completed']
    ];
    
    foreach ($orders as $order) {
        $insert = mysqli_query($conn, "INSERT INTO orders (student_id, total_amount, status) VALUES ({$order['student_id']}, {$order['total_amount']}, '{$order['status']}')");
        if ($insert) {
            $order_id = mysqli_insert_id($conn);
            echo "Added order #$order_id for student {$order['student_id']}<br>";
            
            // Add order items
            $menu_items = mysqli_query($conn, "SELECT id FROM menu LIMIT 2");
            while ($item = mysqli_fetch_assoc($menu_items)) {
                $quantity = rand(1, 3);
                mysqli_query($conn, "INSERT INTO order_items (order_id, menu_item_id, quantity) VALUES ($order_id, {$item['id']}, $quantity)");
            }
        }
    }
}

echo "<h3>Sample data added successfully!</h3>";
echo "<p><a href='php/login.php'>Go to Login</a></p>";
echo "<p><strong>Test Credentials:</strong></p>";
echo "<p>Admin: admin / admin123</p>";
echo "<p>Student: john@test.com / password123</p>";
echo "<p>Student: jane@test.com / password123</p>";
echo "<p>Student: mike@test.com / password123</p>";
?> 