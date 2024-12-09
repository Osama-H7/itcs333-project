<?php
session_start();
require 'database.php';

$sql = "SELECT id, name FROM rooms";
$stmt = $pdo->query($sql);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Schedule Management</title>
    <link rel="stylesheet" href="last.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f5; 
            margin: 0;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #d3d3d3; 
            padding: 10px 20px;
            border-radius: 8px;
        }

        h1 {
            margin: 0;
            text-align: center;
            color: #333;
            flex-grow: 1; 
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white; 
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #0056b3; 
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1; 
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #28a745; 
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #218838; 
        }

        .no-rooms {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Room Schedule Management</h1>
        <a class="button" href="dashboard.php">Go to Dashboard</a>
    </div>
    
    <?php if (empty($rooms)): ?>
        <div class="no-rooms">
            <p>No rooms available.</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Schedule</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                    <?php
                    // Fetch reservation status for the room
                    $statusSql = "SELECT COUNT(*) as reserved_count FROM schedules WHERE room_id = :room_id AND status = 'reserved'";
                    $statusStmt = $pdo->prepare($statusSql);
                    $statusStmt->execute(['room_id' => $room['id']]);
                    $status = $statusStmt->fetch();

                    $isReserved = $status['reserved_count'] > 0;
                    ?>
                    <tr class="<?php echo $isReserved ? 'reserved' : ''; ?>">
                        <td><?php echo htmlspecialchars($room['name']); ?></td>
                        <td>
                            <a href="view_schedule.php?room_id=<?php echo $room['id']; ?>">View Schedule</a>
                        </td>
                        <td><?php echo $isReserved ? 'Reserved' : 'Available'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
