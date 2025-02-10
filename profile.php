<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

if (!isset($_SESSION['damsid']) || empty($_SESSION['damsid'])) {
    header('Location: logout.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $did = $_SESSION['damsid'];
    $name = trim($_POST['fname']);
    $mobno = trim($_POST['mobilenumber']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $sid = intval($_POST['specializationid']);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email format.")</script>';
    } elseif (!preg_match('/^[0-9]{10}$/', $mobno)) {
        echo '<script>alert("Invalid mobile number.")</script>';
    } else {
        try {
            $sql = "UPDATE tbldoctor SET FullName = :name, MobileNumber = :mobilenumber, Email = :email, Specialization = :sid WHERE ID = :did";
            $query = $dbh->prepare($sql);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->bindParam(':mobilenumber', $mobno, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':sid', $sid, PDO::PARAM_INT);
            $query->bindParam(':did', $did, PDO::PARAM_INT);
            $query->execute();
            
            echo '<script>alert("Profile has been updated successfully.");</script>';
        } catch (PDOException $e) {
            echo '<script>alert("Error updating profile: ' . $e->getMessage() . '");</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAMS - Doctor Profile</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/core.css">
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/sidebar.php'); ?>
<main id="app-main" class="app-main">
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget">
                        <header class="widget-header">
                            <h3 class="widget-title">Doctor Profile</h3>
                        </header>
                        <hr class="widget-separator">
                        <div class="widget-body">
                            <?php
                            $did = $_SESSION['damsid'];
                            $sql = "SELECT d.*, s.ID as sid, s.Specialization as spec FROM tbldoctor d JOIN tblspecialization s ON s.ID = d.Specialization WHERE d.ID = :did";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':did', $did, PDO::PARAM_INT);
                            $query->execute();
                            $result = $query->fetch(PDO::FETCH_OBJ);
                            if ($result) {
                            ?>
                            <form method="post">
                                <div class="form-group">
                                    <label>Full Name:</label>
                                    <input type="text" class="form-control" name="fname" required value="<?php echo htmlspecialchars($result->FullName); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input type="email" class="form-control" name="email" required value="<?php echo htmlspecialchars($result->Email); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Contact Number:</label>
                                    <input type="text" class="form-control" name="mobilenumber" required maxlength="10" pattern="\d{10}" value="<?php echo htmlspecialchars($result->MobileNumber); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Specialization:</label>
                                    <select class="form-control" name="specializationid">
                                        <option value="<?php echo htmlspecialchars($result->sid); ?>"><?php echo htmlspecialchars($result->spec); ?></option>
                                        <?php
                                        $sql1 = "SELECT * FROM tblspecialization";
                                        $query1 = $dbh->prepare($sql1);
                                        $query1->execute();
                                        while ($row1 = $query1->fetch(PDO::FETCH_OBJ)) {
                                            echo '<option value="' . htmlspecialchars($row1->ID) . '">' . htmlspecialchars($row1->Specialization) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Registration Date:</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($result->CreationDate); ?>" readonly>
                                </div>
                                <button type="submit" class="btn btn-success">Update</button>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
<?php include_once('includes/footer.php'); ?>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>