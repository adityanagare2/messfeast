<?php
session_start();
include("dbconnect.php");
include("header.php");

$result = mysqli_query($conn, "SELECT * FROM menu WHERE day_of_week=DAYNAME(CURDATE())");
?>

<div class="container mt-5">
    <h3 class="mb-4">Today's Mess Menu (<?php echo date('l'); ?>)</h3>
    <div class="row">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['item_name']; ?></h5>
                    <p class="card-text"><?php echo $row['description']; ?></p>
                    <p class="fw-bold text-primary">₹<?php echo $row['price']; ?></p>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php include("footer.php"); ?>
