<?php
    require 'util/connection.php';
    
    session_start();
    if(!isset($_SESSION['username'])) {
        echo "<script>alert('Please log in')</script>";
        echo "<script>location.href='index.php'</script>";
    }

    if(isset($_POST['add'])) {
        $name = $_POST['name'];
        $type = $_POST['type'];

        $query = mysqli_query($connect,"INSERT INTO category VALUES ('','$name','$type')");
        if($query) {
            echo "<script>location.href='category.php'</script>";
        } else {
            echo "<script>alert('Error')</script>";
        }
    }

    if(isset($_GET['delete'])) {
        $id = $_GET['delete'];

        $query = mysqli_query($connect,"DELETE FROM category WHERE id = '$id'");
        if($query) {
            echo "<script>location.href='category.php'</script>";
        }
    }

    if(isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $type = $_POST['type'];

        $query = mysqli_query($connect,"UPDATE category SET name='$name', type='$type' WHERE id = '$id'");
        if($query) {
            echo "<script>location.href='category.php'</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        <title>Category</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="dashboard.php">Buku Kas</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transaction.php">Transaction</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="">Category</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sign out</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <?php echo $_SESSION['username']?>
                </span>
            </div>
        </nav>
        <div class="container" style="max-width: 1440px">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Category</h4>
                    <br>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategory">Add Category</button>
                    <div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="addCategorylabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="categoryLabel">Add Category</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="POST">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name" class="col-form-label">Name:</label>
                                            <input type="text" class="form-control" id="name" name="name">
                                        </div>
                                        <div class="form-group">
                                            <label for="type" class="col-form-label">Type:</label>
                                            <select class="custom-select" id="type" name="type">
                                                <option value="income">Income</option>
                                                <option value="outcome">Outcome</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="add" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $number = 1;
                                $result = mysqli_query($connect, "SELECT * FROM category ORDER BY type");
                                while($data = mysqli_fetch_array($result)) {
                            ?>
                            <tr>
                                <td><?php echo $number++; ?></td>
                                <td><?php echo $data['name']; ?></td>
                                <td><?php echo ucwords($data['type']); ?></td>
                                <td>
                                    <a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#editCategory<?php echo $data['id']?>">Edit</a>
                                    <a class="btn btn-danger btn-xs" onclick="return confirm('Delete this item?')" href="category.php?delete=<?php echo $data['id']?>">Delete</a></td>
                                </td>
                            </tr>
                            <div class="modal fade" id="editCategory<?php echo $data['id']?>" tabindex="-1" aria-labelledby="editCategorylabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editCategoryLabel">Edit Category</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="" method="POST">
                                            <div class="modal-body">
                                                <?php 
                                                    $name = $data['name']; 
                                                    $query_edit = mysqli_query($connect, "SELECT * FROM category WHERE name='$name'");
                                                    while ($row = mysqli_fetch_array($query_edit)) {
                                                ?>
                                                <div class="form-group">
                                                    <label for="name" class="col-form-label">Name:</label>
                                                    <input type="hidden" class="form-control" id="name" name="id" value="<?php echo $row['id'] ?>">
                                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name'] ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="type" class="col-form-label">Type:</label>
                                                    <select class="custom-select" id="type" name="type">
                                                        <?php
                                                            if($row['type'] == "income") {
                                                                echo "<option value='income' selected>Income</option>
                                                                <option value='outcome'>Outcome</option>";
                                                            } else {
                                                                echo "<option value='income'>Income</option>
                                                                <option value='outcome' selected>Outcome</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" name="edit" class="btn btn-primary">Submit</button>
                                            </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</html>