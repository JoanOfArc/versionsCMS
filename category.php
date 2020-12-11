<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php include "admin/functions.php"; ?>


    <!-- Navigation -->
<?php 
    include "includes/navigation.php";
?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

            <h1 class="page-header">
                versions.
                <small>Your words, your voice</small>
            </h1>
                <?php

                    if (isset($_GET['category'])) {
                        $post_category_id = $_GET['category'];
                    

                        if(is_admin($_SESSION['username'])) { // $_SESSION['user_role']) && $_SESSION['user_role'] == 'admin' checks active user role session is an admin

                            $stmt1 = mysqli_prepare($connection, "SELECT post_id, post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_category_id = ?"); // show all posts for admin
    
                        } else {
    
                            $stmt2 = mysqli_prepare($connection, "SELECT post_id, post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_category_id = ? AND post_status = ? "); // show only published posts for non-admin

                            $published = 'published';
                        }

                        if (isset($stmt1)) {

                            mysqli_stmt_bind_param($stmt1, "i", $post_category_id);

                            mysqli_stmt_execute($stmt1);

                            mysqli_stmt_bind_result($stmt1, $post_id, $post_title, $post_author, $post_date, $post_image, $post_content);

                            $stmt = $stmt1;
                        
                        } else {

                            
                            mysqli_stmt_bind_param($stmt2, "is", $post_category_id, $published);

                            mysqli_stmt_execute($stmt2);

                            mysqli_stmt_bind_result($stmt2, $post_id, $post_title, $post_author, $post_date, $post_image, $post_content);

                            $stmt = $stmt2;

                        }



                    // if (mysqli_stmt_num_rows($stmt) === 0) {
                    //     echo "<h1 class='text-center'>No posts available</h1>";
                    // }

                    while (mysqli_stmt_fetch($stmt)):

                        ?>


                        <!-- First Blog Post -->
                        <h2>
                            <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
                        </h2>
                        <p class="lead">
                            by <a href="index.php"><?php echo $post_author ?></a>
                        </p>
                        <p><?php echo $post_date ?></p>
                        <hr>
                        <a href="post.php?p_id=<?php echo $post_id; ?>"><img class="img-responsive" src="img/<?php echo $post_image; ?>" alt=""></a>
                        <hr>
                        <p><?php echo $post_content ?></p>
                        <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id; ?>">Read More <i class="fa fa-chevron-right" aria-hidden="true"></i></a>

                        <hr>

                    <?php endwhile; mysqli_stmt_close($stmt); } else {

                        header("Location: index.php");

                   }
                   
                   
                   
                   
                   
                   
                   ?>

                

               


            </div>

            
            <?php
                include "includes/sidebar.php";
            ?>

        </div>
        <!-- /.row -->
<?php

    include "includes/footer.php";

?>