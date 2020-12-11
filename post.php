<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>


    <!-- Navigation -->
<?php 
    include "includes/navigation.php";
?>

<?php

    if(isset($_POST['liked'])) {

        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];

        // 1. FETCHING THE RIGHT POST 

        $query = "SELECT * FROM posts WHERE post_id=$post_id"; 
        $postResult = mysqli_query($connection, $query);
        $post = mysqli_fetch_array($postResult);
        $likes = $post['likes'];

        // 2. UPDATE POST WITH LIKES

        mysqli_query($connection, "UPDATE posts SET likes=$likes+1 WHERE post_id=$post_id");

        // 3. CREATE LIKES FOR POST

        mysqli_query($connection, "INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)");
        exit();

    }

    if(isset($_POST['unliked'])) {

        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];

        // // 1. FETCHING THE RIGHT POST 

        $query = "SELECT * FROM posts WHERE post_id=$post_id"; 
        $postResult = mysqli_query($connection, $query);
        $post = mysqli_fetch_array($postResult);
        $likes = $post['likes'];

        // 2. DELETE LIKES

        mysqli_query($connection, "DELETE FROM likes WHERE post_id=$post_id AND user_id=$user_id ");

        // 3. UPDATE DECREMENT LIKES

        mysqli_query($connection, "UPDATE posts SET likes=$likes-1 WHERE post_id=$post_id");
        exit();

    }


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

                    $view_query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = $the_post_id ";
                    $send_query = mysqli_query($connection, $view_query);

                    if (!$send_query) {
                        die("query failed");
                    }

                    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin' ) { // checks active user role session is an admin

                        $query = "SELECT * FROM posts WHERE post_id = $the_post_id "; // show all posts for admin

                    } else {

                        $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status = 'published' "; // show only published posts for non-admin
                    }

                    $select_all_posts_query = mysqli_query($connection, $query);

                    if (mysqli_num_rows($select_all_posts_query) < 1) {
                        echo "<h1 class='text-center'>No posts available</h1>";
                    } else { 

                    while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
                        $post_title = $row['post_title'];
                        $post_user = $row['post_user'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];

                        ?>


                        <!-- First Blog Post -->
                        <h2>
                            <a href="#"><?php echo $post_title ?></a>
                        </h2>
                        <p class="lead">
                             by <a href="index.php"><?php echo $post_user ?></a>
                        </p>
                        <p><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $post_date ?></p>
                        <hr>
                        <img class="img-responsive" src="img/<?php echo $post_image; ?>" alt="">
                        <hr>
                        <p><?php echo $post_content ?></p>
                        
                        <hr>

                        <div class="row">
                            <p class="pull-right"><a href="#" class="like"> <i class="fa fa-thumbs-up" aria-hidden="true"></i> Like</a></p>
                        </div>

                        <div class="row">
                            <p class="pull-right"><a href="#" class="unlike"> <i class="fa fa-thumbs-down" aria-hidden="true"></i> Unlike</a></p>
                        </div>

                        <div class="row">
                            <p class="pull-right">Likes: 10</p>
                        </div>

                   <?php } 
                
                

                    
                    
                    
                    ?>

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
    
                        // $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
                        // $query .= "WHERE post_id = $the_post_id ";
    
                        // $update_comment_count = mysqli_query($connection, $query);

                    } else {

                        echo "<script>alert('Fields cannot be empty!');</script>";

                    }


                }

                ?>
                <!-- Comments Form -->
                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form action="" method="post" role="form">

                        <div class="form-group">
                        <label for="Author">Author</label>
                            <input type="text" class="form-control" name="comment_author">
                        </div>

                        <div class="form-group">
                        <label for="Email">Email</label>
                            <input type="email" class="form-control" name="comment_email">
                        </div>

                        <div class="form-group">
                        <label for="Comment">Your Comment</label>
                            <textarea name="comment_content" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->
                <?php

                    $query =  "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
                    $query .= "AND comment_status = 'approve' ";
                    $query .= "ORDER BY comment_id DESC ";

                    $select_comment_query = mysqli_query($connection, $query);
                    if (!$select_comment_query) {
                        die("Query failed" . mysqli_error($connection));
                    }

                    while ($row = mysqli_fetch_array($select_comment_query)) { // loops through the comment information
                        $comment_date = $row['comment_date'];
                        $comment_content = $row['comment_content'];
                        $comment_author = $row['comment_author'];
                   
                    ?>
                    <!-- Comment -->
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" src="http://placehold.it/64x64" alt="">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading"><?php echo $comment_author; ?>
                                <small><?php echo $comment_date; ?></small>
                            </h4>
                            <?php echo $comment_content; ?>
                        </div>
                    </div>
                

            <?php } } } else {

                    header("Location: index.php");

                } ?>

            </div>

            
            <?php
                include "includes/sidebar.php";
            ?>

        </div>
        <!-- /.row -->
<?php

    include "includes/footer.php";

?>

<script>

                $(document).ready(function() { //click event

                    var post_id = <?php echo $the_post_id; ?> 

                    var user_id = 26;

                    // LIKING

                    $('.like').click(function () {
                        
                        $.ajax({
                            url: "/versionscms/post.php?p_id=<?php echo $the_post_id; ?>",
                            type: 'post',
                            data: {
                                'liked': 1,
                                'post_id': post_id,
                                'user_id': user_id
                            }
                        });
                    
                    });

                    // UNLIKING

                    $('.unlike').click(function () {
                        
                        $.ajax({
                            url: "/versionscms/post.php?p_id=<?php echo $the_post_id; ?>",
                            type: 'post',
                            data: {
                                'unliked': 1,
                                'post_id': post_id,
                                'user_id': user_id
                            }
                        });
                    
                    });
                
                });

</script>