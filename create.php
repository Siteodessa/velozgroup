<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$vendor = $category = $price = "";
$vendor_err = $category_err = $price_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate vendor
    $input_vendor = trim($_POST["vendor"]);
    if(empty($input_vendor)){
        $vendor_err = "Please enter a vendor.";
    } elseif(!filter_var(trim($_POST["vendor"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $vendor_err = 'Please enter a valid vendor.';
    } else{
        $vendor = $input_vendor;
    }

    // Validate category
    $input_category = trim($_POST["category"]);
    if(empty($input_category)){
        $category_err = 'Please enter an category.';
    } else{
        $category = $input_category;
    }

    // Validate price
    $input_price = trim($_POST["price"]);
    if(empty($input_price)){
        $price_err = "Please enter the price amount.";
    } elseif(!ctype_digit($input_price)){
        $price_err = 'Please enter a positive integer value.';
    } else{
        $price = $input_price;
    }

    // Check input errors before inserting in database
    if(empty($vendor_err) && empty($category_err) && empty($price_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO Invoice (vendor, category, price) VALUES (?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_vendor, $param_category, $param_price);

            // Set parameters
            $param_vendor = $vendor;
            $param_category = $category;
            $param_price = $price;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add Invoice record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($vendor_err)) ? 'has-error' : ''; ?>">
                            <label>vendor</label>
                            <input type="text" name="vendor" class="form-control" value="<?php echo $vendor; ?>">
                            <span class="help-block"><?php echo $vendor_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($category_err)) ? 'has-error' : ''; ?>">
                            <label>category</label>
                            <textarea name="category" class="form-control"><?php echo $category; ?></textarea>
                            <span class="help-block"><?php echo $category_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
                            <label>price</label>
                            <input type="text" name="price" class="form-control" value="<?php echo $price; ?>">
                            <span class="help-block"><?php echo $price_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
