<?php
require_once '../auth/authenticate.php';
require_once '../includes/db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Suppliers</title>
    <style>

        table {
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }
    </style>
</head>

<body>
    <h1>Suppliers List</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
        <?php
        $sql = "SELECT * FROM suppliers";
        $result = $conn->query($sql);
        if ($result->rowCount() > 0) {
            while ($row = $result->fetch()) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["name"] . "</td>
                        <td>" . $row["contact"] . "</td>
                        <td>" . $row["address"] . "</td>
                        <td>
                            <a href='edit.php?id=" . $row["id"] . "'>Edit</a> |
                            <a href='delete.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure?\");'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No suppliers.</td></tr>";
        }
        ?>
    </table>
    <a href="../index.php">Back</a>
</body>

</html>
<?php $conn = null; ?>