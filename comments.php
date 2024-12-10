<?php
require 'database.php'; // Ensure this connects to your database

$roomId = 1; // Example room ID to fetch comments for
$sql = "SELECT c.*, u.name 
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.room_id = :room_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['room_id' => $roomId]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Display the comments
foreach ($comments as $comment) {
    echo "<p><strong>{$comment['name']}:</strong> {$comment['comment']} (Posted on: {$comment['created_at']})</p>";
}
?>
?>
<form action="submit_comment.php" method="POST">
    <input type="hidden" name="room_id" value="1"> <!-- Set this dynamically based on the room -->
    <textarea name="comment" required placeholder="Leave your comment here..."></textarea>
    <button type="submit">Submit Comment</button>
</form>