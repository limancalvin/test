<?php
    require 'util/connection.php';
    
    session_start();
    if(!isset($_SESSION['username'])) {
        echo "<script>alert('Please log in')</script>";
        echo "<script>location.href='index.php'</script>";
    } 
    
    $username = $_SESSION['username'];

    if(isset($_POST['add'])) {
        $transaction_type = $_POST['transaction'];
        $value = $_POST['value'];
        $category = $_POST['category'];
        $note = $_POST['note'];
        $date = $_POST['date'];

        $query = mysqli_query($connect,"INSERT INTO log VALUES ('','$value', '$date', '$note', '$transaction_type', '$category', '$username')");
        if($query) {
            echo "<script>location.href='transaction.php'</script>";
        } else {
            echo "<script>alert('Error')</script>";
        }
    }

    if(isset($_GET['delete'])) {
        $id = $_GET['delete'];

        $query = mysqli_query($connect,"DELETE FROM log WHERE id = '$id'");
        if($query) {
            echo "<script>location.href='transaction.php'</script>";
        }
    }

    if(isset($_POST['edit'])) {
        $id = $_POST['id'];
        $transaction_type = $_POST['transaction'];
        $value = $_POST['value'];
        $category = $_POST['category'];
        $note = $_POST['note'];
        $date = $_POST['date'];
        
        $query = mysqli_query($connect,"UPDATE log SET value='$value', date='$date', note='$note', type='$transaction_type', category='$category', user='$username' WHERE id = '$id'");
        if($query) {
            echo "<script>location.href='transaction.php'</script>";
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
        <title>Transaction</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <a class="navbar-brand" href="dashboard.php">Buku Kas</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsText" aria-controls="navbarsText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsText">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="">Transaction</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="category.php">Category</a>
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
                    <h4>Transaction</h4>
                    <br>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTransaction">Add Transaction</button>
                    <div class="modal fade" id="addTransaction" tabindex="-1" aria-labelledby="addTransactionlabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="transactionlLabel">Add Transaction</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="POST">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="type" class="col-form-label">Transaction:</label><br>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="transaction" id="inlineRadio1" value="income" onclick="categoryHandler()" checked>
                                                <label class="form-check-label" for="inlineRadio1">Income</label>
                                            </div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="transaction" id="inlineRadio2" value="outcome" onclick="categoryHandler()">
                                                <label class="form-check-label" for="inlineRadio2">Outcome</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="value" class="col-form-label">Value:</label>
                                            <input type="number" class="form-control" id="value" name="value">
                                        </div>
                                        <div class="form-group">
                                            <label for="category" class="col-form-label">Category:</label>
                                            <select class="custom-select" id="categoryIncome" name="category">
                                                <?php 
                                                    $category = mysqli_query($connect, "SELECT * FROM category WHERE type='income'");
                                                    while($cat_data = mysqli_fetch_array($category)) {
                                                ?>
                                                <option value="<?php echo $cat_data['name']?>"><?php echo $cat_data['name']?></option>
                                                <?php } ?>
                                            </select>
                                            <select class="custom-select" id="categoryOutcome" name="category" style="display: none;" disabled>
                                                <?php 
                                                    $category = mysqli_query($connect, "SELECT * FROM category WHERE type='outcome'");
                                                    while($cat_data = mysqli_fetch_array($category)) {
                                                ?>
                                                <option value="<?php echo $cat_data['name']?>"><?php echo $cat_data['name']?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="note" class="col-form-label">Note:</label>
                                            <textarea class="form-control" id="note" name="note"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="date" class="col-form-label">Date:</label>
                                            <input type="date" class="form-control" id="date" name="date">
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
                                <th>Value</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $number = 1;
                                $result = mysqli_query($connect, "SELECT * FROM log WHERE user='$username' ORDER BY date");
                                while($data = mysqli_fetch_array($result)) {
                            ?>
                            <tr>
                                <td><?php echo $number++; ?></td>
                                <td><?php echo number_format($data['value'], 2, ".", ","); ?></td>
                                <td><?php echo $data['date']; ?></td>
                                <td><?php echo ucwords($data['type']); ?></td>
                                <td><?php echo $data['category']; ?></td>
                                <td>
                                    <a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#editTransaction<?php echo $data['id']; ?>">Edit</a>
                                    <a class="btn btn-danger btn-xs" onClick="return confirm('Delete this item?')" href="transaction.php?delete=<?php echo $data['id'] ?>">Delete</a></td>
                                </td>
                            </tr>
                            <div class="modal fade" id="editTransaction<?php echo $data['id']; ?>" tabindex="-1" aria-labelledby="editTransactionlabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editTransactionLabel">Edit Transaction</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="" method="POST">
                                            <div class="modal-body">
                                                <?php 
                                                    $id = $data['id']; 
                                                    $query_edit = mysqli_query($connect, "SELECT * FROM log WHERE id='$id'");
                                                    while ($row = mysqli_fetch_array($query_edit)) {
                                                ?>
                                                <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                                <div class="form-group">
                                                    <label for="type" class="col-form-label">Transaction:</label><br>
                                                    <div class="form-check-inline">
                                                        <?php
                                                            if($row['type'] == "income") {
                                                                echo "<input class='form-check-input' type='radio' name='transaction' id='inlineRadio1' value='income' checked>";
                                                            } else {
                                                                echo "<input class='form-check-input' type='radio' name='transaction' id='inlineRadio1' value='income'>";
                                                            }
                                                        ?>
                                                        <label class="form-check-label" for="inlineRadio1">Income</label>
                                                    </div>
                                                    <div class="form-check-inline">
                                                        <?php 
                                                            if($row['type'] == "outcome") {
                                                                echo "<input class='form-check-input' type='radio' name='transaction' id='inlineRadio2' value='outcome' checked>";
                                                            } else {
                                                                echo "<input class='form-check-input' type='radio' name='transaction' id='inlineRadio2' value='outcome'>";
                                                            }
                                                        ?>
                                                        <label class="form-check-label" for="inlineRadio2">Outcome</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="value" class="col-form-label">Value:</label>
                                                    <input type="number" class="form-control" id="value" name="value" value="<?php echo $row['value'] ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="category" class="col-form-label">Category:</label>
                                                    <select class="custom-select" id="category" name="category">
                                                        <?php 
                                                            $category = mysqli_query($connect, "SELECT * FROM category ORDER BY type");
                                                            while($cat_data = mysqli_fetch_array($category)) {
                                                                if($cat_data['name'] == $row['category']) {
                                                                    echo "<option value=".$cat_data['name']." selected>".$cat_data['name']."</option>";
                                                                } else {
                                                                    echo "<option value=".$cat_data['name'].">".$cat_data['name']."</option>";
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="note" class="col-form-label">Note:</label>
                                                    <textarea class="form-control" id="note" name="note"><?php echo $row['note'] ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="date" class="col-form-label">Date:</label>
                                                    <input type="date" class="form-control" id="date" name="date" value="<?php echo $row['date'] ?>">
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
    <script>
        function categoryHandler() {
            var income = document.getElementById('categoryIncome');
            var outcome = document.getElementById('categoryOutcome');
            if(document.getElementById('inlineRadio1').checked) {
                income.style.display = 'inline';
                outcome.style.display = 'none';
                income.disabled = false;
                outcome.disabled = true;
            } else if(document.getElementById('inlineRadio2').checked) {
                outcome.style.display = 'inline';
                income.style.display = 'none';
                outcome.disabled = false;
                income.disabled = true;
            }
        }
    </script>
</html>