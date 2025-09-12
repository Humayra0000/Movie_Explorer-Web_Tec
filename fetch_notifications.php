<?php
require 'config.php';
$editor = $_SESSION['user'];
$notifications = mysqli_query($conn, "SELECT * FROM notification WHERE editor='$editor' ORDER BY created_at DESC");
?>
<div id="notifications-section" class="section">
<h3>Notifications</h3>
<?php if($notifications && mysqli_num_rows($notifications) > 0): ?>
<ul>
<?php while($n = mysqli_fetch_assoc($notifications)): ?>
<li>
<?php echo htmlspecialchars($n['message']); ?> 
<small style="color:gray;">(<?php echo $n['created_at']; ?>)</small>
</li>
<?php endwhile; ?>
</ul>
<?php else: ?>
<p>No notifications yet.</p>
<?php endif; ?>
</div>
