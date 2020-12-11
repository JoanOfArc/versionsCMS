<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>


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

                    if (isset($_GET['p_id'])) {

                        $the_post_id = $_GET['p_id'];
                        $the_post_author = $_GET['author'];

                    }

                    $query = "SELECT * FROM posts WHERE post_user = '{$the_post_author}' ";

                    $select_all_posts_query = mysqli_query($connection, $query);

                    while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                        $post_title = $row['post_title'];
                        $post_author = $row['post_user'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];

                        ?>


                        <!-- First Blog Post -->
                        <h2>
                            <a href="#"><?php echo $post_title ?></a>
                        </h2>
                        <p class="lead">
                            All posts by <?php echo $post_author ?>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
                        <hr>
                        <img class="img-responsive" src="img/<?php echo $post_image; ?>" alt="">
                        <hr>
                        <!-- <p><?php echo $post_content ?></p> -->
                        

                        <hr>

                   <?php } ?>

                    <!-- Blog Comments -->

                <?php

                if (isset($_POST['create_comment'])) { // collects data from the form input 'submit' button


                    $the_post_id = $_GET['p_id'];

                    $comment_author = $_POST['comment_author'];
                    $comment_email = $_POST['comment_email'];
                    $comment_content = $_POST['comment_content'];


                    if (!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {  // IF Statement to check to see if comment field has content in it.

                        $query = "INSERT INTO comments (comment_post_id, comment_date, comment_author, comment_email, comment_content, comment_status) ";
                        $query .= "VALUES ($the_post_id, now(), '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unapproved')";
    
                        $create_comment_query = mysqli_query($connection, $query); // connects to the Mysql versions db
    
                        if (!$create_comment_query) {
                            die('Query failed' . mysqli_error($connection));
                        }
    
                        $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
                        $query .= "WHERE post_id = $the_post_id ";
    
                        $update_comment_count = mysqli_query($connection, $query);

                    } else {

                        echo "<script>alert('Fields cannot be empty!');</script>";

                    }


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