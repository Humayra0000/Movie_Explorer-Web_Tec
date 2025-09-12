<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Editor Dashboard</title>
<link rel="stylesheet" href="css/fetch_movies.css">
<style>
header{background:#333;color:#fff;padding:10px 0;position:sticky;top:0;width:100%;z-index:1000;}
nav{display:flex;justify-content:space-around;align-items:center;}
nav a{color:#fff;text-decoration:none;font-weight:bold;padding:8px 12px;transition:0.3s;cursor:pointer;}
nav a:hover{background:#555;border-radius:4px;}
.section{display:none;margin-top:1rem;}
.msg{color:green;font-weight:bold;margin:10px 0;}
.error{color:red;font-weight:bold;margin:10px 0;}
table{border-collapse:collapse;width:100%;margin-top:1rem;}
th,td{border:1px solid #ccc;padding:.6rem;text-align:left;}
th{background:#eee;}
</style>
</head>
<body>



<h2>Editor Dashboard</h2>

<?php include 'fetch_movies.php'; ?>
<?php include 'add_pending_movies.php'; ?>

<div id="reviews-ratings-section" class="section">
    <?php include 'reviews_ratings.php'; ?>
</div>





<?php include 'fetch_notifications.php'; ?>

<script>
const navLinks = document.querySelectorAll('nav a[data-target]');
const sections = document.querySelectorAll('.section');

navLinks.forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();
        const targetId = link.getAttribute('data-target');
        sections.forEach(sec => {
            sec.style.display = (sec.id === targetId) ? 'block' : 'none';
        });
    });
});

// Optionally, show first section by default
sections.forEach((sec, i) => sec.style.display = i === 0 ? 'block' : 'none');



document.getElementById('movieSearch').addEventListener('keyup',function(){
 const term=this.value.toLowerCase();
 const rows=document.querySelectorAll('#moviesTable tr:not(:first-child)');
 rows.forEach(row=>{
   const title=row.cells[1].textContent.toLowerCase();
   const genre=row.cells[2].textContent.toLowerCase();
   row.style.display=(title.includes(term)||genre.includes(term))?'':'none';
 });
});
</script>

</body>
</html>
