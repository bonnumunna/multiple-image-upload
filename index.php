<?php
    // Connection to database
    $conn = mysqli_connect('localhost', 'root', '', 'mylessons');
    // Red alert if connection didn't hold
    if(!$conn){
        echo "<h2 style='color:red;'>Not Connected</h2>";
    }
    // Variable to hold messages (error or success)
    $msg = "";
    // Check if url has msg parameter and get the value
    if(isset($_GET['msg'])){
        $msg = $_GET['msg'];
    }
    // form submission and processing
    if(isset($_POST['submit'])){
        // Count how many files were uploaded
        $count = count($_FILES['images']['name']);
        // Run loop to upload each file
        for($i=0; $i<$count; $i++){
            $imageName = $_FILES['images']['name'][$i];
            $imageTempName = $_FILES['images']['tmp_name'][$i];
            // Specify path to save uploaded file
            $filePath = "multiupload/".$imageName;
            // Move file from temporary cache folder to storage directory
            if(move_uploaded_file($imageTempName, $filePath)){
                $sql = "INSERT INTO multipleimages(image) VALUES('$imageName')";
                $query = mysqli_query($conn, $sql);
            }// close image upload if
        } // for close
        if($query){
            header("location:index.php?msg=successful upload");
        }
    }// if close
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Multi Image Upload</title>
</head>

<body>
    <header class="bg-success p-3 text-white text-center mb-5">
        <h1>Uploading Multiple Images Using PHP</h1>
        <h6>Bonn Umunna</h6>
    </header>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 border p-2">
                <?php
                    if(!empty($msg)){
                        echo "<h4 class='text-info'>".$msg."</h4>";
                    }
                ?>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                        <label for="" class="form-label">Select Images</label>
                        <input type="file" name="images[]" multiple class="form-control">
                        <br><br>
                        <input type="submit" name="submit" value="Upload" class="btn btn-success">
                    </form>
            </div>
        </div>
        <?php
            // Select all images from database
            $sql = "SELECT * FROM multipleimages ORDER BY id DESC LIMIT 4";
            $query = mysqli_query($conn, $sql);
        ?>
        <div class="row justify-content-center gap- my-5 p-3 border bg-success rounded">
            <?php if(mysqli_num_rows($query)> 0): ?>
                <!-- Display all images uploaded -->
                <?php while($pictures = mysqli_fetch_assoc($query)): ?>
                    <img src="<?php echo "multiupload/".$pictures['image']; ?>" width="250" height="200" alt="image" class="rounded col-3">
                <?php endwhile; ?>
            <?php endif;?>
        </div>
    </div>

</body>

</html>