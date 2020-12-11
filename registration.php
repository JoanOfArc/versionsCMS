<?php  include "includes/db.php"; ?>
<?php  include "includes/header.php"; ?>
<?php include_once "admin/functions.php"; ?> 

<?php

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $error = [

            'username' => '',
            'email' => '',
            'password' => ''

        ];

        if (strlen($username) < 4) {

            $error['username'] = 'Your Username must be longer.';

        }

        if ($username == '') {
            $error['username'] = 'Username cannot be empty';
        }

        if (username_exists($username)) {
            $error['username'] = 'Username already exists. Please choose another one';
        }

        if ($email == '') {
            $error['user_email'] = 'Email field cannot be empty';
        }


        if (email_exists($email)) {

            $error['user_email'] = 'You have already registered using this email address. <a href=" index.php">Log in?</a> ';

        }

        if ($password == '') {

            $error['password'] = 'Password cannot be empty';
        }

        foreach ($error as $key => $value) { // goes through each error

            if(empty($value)) {  // if the errors are empty then unset the key

                unset($error[$key]); 

                login_user($username, $password);
                
            }
        } // foreach

        if (empty($error)) {

            register_user($username, $email, $password);

            login_user($username, $password);

        }

        
        
    }



?>



    <!-- Navigation -->
    
    <?php  include "includes/navigation.php"; ?>
    
 
    <!-- Page Content -->
    <div class="container">
    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                <h1 style="font-weight: bold; text-align: center; ">Register</h1><br>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                        <div class="form-group">
                            <label for="username" class="sr-only">username</label>
                            <input type="text" 
                            name="username" 
                            id="username" 
                            class="form-control" 
                            placeholder="Enter Desired Username"
                            autocomplete = "on"
                            value = "<?php echo isset($username) ? $username : '' ?>"
                                                       
                            >

                            <p><?php echo isset($error ['username']) ? $error ['username'] : '' ?></p>
                        </div>
                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" 
                            name="email" id="email" 
                            class="form-control" 
                            placeholder="somebody@example.com"
                            autocomplete = "on"
                            value = "<?php echo isset($email) ? $email : '' ?>"
                            >

                            <p><?php echo isset($error ['user_email']) ? $error ['user_email'] : '' ?></p>
                        </div>
                         <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="Password">
                            <p><?php echo isset($error ['password']) ? $error ['password'] : '' ?></p>
                        </div>

                        
                
                        <input type="submit" name="register" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Create versions account">
                    </form>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>




<?php include "includes/footer.php";?>
