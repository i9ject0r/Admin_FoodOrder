<?php
include("./db_conn.php");
error_reporting(E_ALL); // Enable error reporting for debugging
ini_set('display_errors', 1);
session_start();

if (isset($_POST['submit'])) {
    // Check if required fields are empty
    if (empty($_POST['d_name']) || empty($_POST['about']) || empty($_POST['price'])) {
        $error = '<div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>All fields must be filled!</strong>
                  </div>';
    } else {
        // File upload logic
        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] !== '') {
            $fname = $_FILES['file']['name'];
            $temp = $_FILES['file']['tmp_name'];
            $fsize = $_FILES['file']['size'];

            // Extract file extension
            $extension = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
            $fnew = uniqid() . '.' . $extension;
            $store = "assets/img/dishes/" . $fnew;

            // Validate file type and size
            if (in_array($extension, ['jpg', 'png', 'gif'])) {
                if ($fsize <= 1024 * 1024) { // Max size: 1MB
                    // Insert data into the database
                    $sql = "INSERT INTO dishes (title, slogan, price, img) VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $sql);
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "ssis", $_POST['d_name'], $_POST['about'], $_POST['price'], $fnew);

                        if (mysqli_stmt_execute($stmt)) {
                            // Move uploaded file to the specified folder
                            if (move_uploaded_file($temp, $store)) {
                                // Redirect to add_menu.php with success message
                                header("Location: add_menu.php?success=New Dish Added Successfully");
                                exit();
                            } else {
                                $error = '<div class="alert alert-danger alert-dismissible fade show">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong>Failed to upload the image!</strong>
                                          </div>';
                            }
                        } else {
                            $error = '<div class="alert alert-danger alert-dismissible fade show">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Error adding dish to the database!</strong>
                                      </div>';
                        }
                    } else {
                        $error = '<div class="alert alert-danger alert-dismissible fade show">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Failed to prepare the SQL statement!</strong>
                                  </div>';
                    }
                } else {
                    $error = '<div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Max image size is 1MB! Try a smaller file.</strong>
                              </div>';
                }
            } else {
                $error = '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong>Invalid file extension! Only JPG, PNG, and GIF are allowed.</strong>
                          </div>';
            }
        } else {
            $error = '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Please select an image!</strong>
                      </div>';
        }
    }
}

// Display error if set
if (isset($error)) {
    echo $error;
}
?>
