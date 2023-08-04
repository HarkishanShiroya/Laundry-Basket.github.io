<?php
session_start();
$state = "Login";
if (isset($_SESSION["user"])) {
    $state = "Logout";
 }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Style Link -->
    <link rel="stylesheet" href="./css/style.css">

    <!-- Unicons -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />

    <!-- Boxicons CSS -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" />

    <!--Fontawsome CDN Link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
        integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scrollbar Settings -->
    <style>
    html::-webkit-scrollbar {
        width: 1vw;
    }

    html::-webkit-scrollbar-thumb {
        background: rgb(37, 37, 37);
        border-radius: 10px;
    }
    </style>

</head>

<body>

    <!-- Backend code for login and signup start-->

    <?php
        if (isset($_POST["submit"])) {
           $email = $_POST["email"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];
           
        //    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
           if (empty($email) OR empty($password) OR empty($passwordRepeat)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 charactes long");
           }
           if ($password!==$passwordRepeat) {
            array_push($errors,"Password does not match please make it same");
           }
           require_once "database.php";
           $sql = "SELECT * FROM users WHERE email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           }else{
            
            $sql = "INSERT INTO users (email, password) VALUES (?, ? )";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"ss", $email,$_POST["password"]);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
                header("Location: index.php");
                $state = "Log Out";
            }else{
                die("Something went wrong");
            }
           }
          

        }
?>

    <?php
        if (isset($_POST["login"])) {
           $email = $_POST["email"];
           $password = (string)$_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo "result found";
            }
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            echo strlen($user["password"]);
            $fatchedHash =(string)$user["password"];
            if ($user) {
                if ($_POST["password"] == $user["password"]) {
                    session_start();
                    $_SESSION["user"] = "yes";
                    header("Location: index.php");
                    $state = "Log Out";
                    die();
                }else{
                    echo "<div class='alert alert-danger'>Password does not match</div>";
                }
            }else{
                echo "<div class='alert alert-danger'>Email does not match</div>";
            }
        }
        ?>

    <!-- Backend code for login and signup end-->


    <!-- Navigation bar start -->

    <header>
        <a href="#home" class="logo" data-aos="fade-down">Laundry</a>
        <div class="menuToggle" onclick="toggleMenu()"></div>
        <ul class="nav">
            <li data-aos="fade-down" data-aos-delay="50"><a href="#home" onclick="toggleMenu()">Home</a></li>
            <li data-aos="fade-down" data-aos-delay="100"><a href="#about" onclick="toggleMenu()">About</a></li>
            <li data-aos="fade-down" data-aos-delay="150"><a href="#system" onclick="toggleMenu()">System</a></li>
            <li data-aos="fade-down" data-aos-delay="200"><a href="#service" onclick="toggleMenu()">Service</a></li>
            <li data-aos="fade-down" data-aos-delay="250"><a href="#testimonials"
                    onclick="toggleMenu()">Testimonials</a></li>
            <li data-aos="fade-down" data-aos-delay="300"><a href="#contact" onclick="toggleMenu()">Contact</a></li>
            <li data-aos="fade-down" data-aos-delay="350"><button class="button" id="form-open"
                    onclick="toggleMenu()"><a href="#home"><?php echo $state ?></a></button></li>
        </ul>

    </header>

    <!-- Navigation bar end -->

    <!-- Login page start -->

    <section class="house">
        <div class="form_container">
            <i class="uil uil-times form_close"></i>

            <!-- Login From -->

            <div class="form login_form">
                <form action="index.php" method="post">
                    <h2>Login</h2>
                    <div class="input_box">
                        <input type="email" name="email" placeholder="Enter your email" required />
                        <i class="uil uil-envelope-alt email"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" name="password" placeholder="Enter your password" required />
                        <i class="uil uil-lock password"></i>
                        <i class="uil uil-eye-slash pw_hide"></i>
                    </div>
                    <div class="option_field">
                        <span class="checkbox">
                            <input type="checkbox" id="check" />
                            <label for="check">Remember me</label>
                        </span>
                        <a href="#" class="forgot_pw">Forgot password?</a>
                    </div>
                    <!-- <input type="submit" value="Login" name="login" class="btn btn-primary"> -->
                    <button class="button" type="submit" name="login">Login</button>
                    <div class="login_signup">Don't have an account? <a href="#" id="signup">Signup</a></div>
                </form>
            </div>

            <!-- Signup From -->

            <div class="form signup_form">
                <form action="index.php" method="post">
                    <h2>Signup</h2>
                    <div class="input_box">
                        <input type="email" placeholder="Enter your email" name="email" required />
                        <i class="uil uil-envelope-alt email"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" placeholder="Create password" name="password" required />
                        <i class="uil uil-lock password"></i>
                        <i class="uil uil-eye-slash pw_hide"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" placeholder="Confirm password" name="repeat_password" required />
                        <i class="uil uil-lock password"></i>
                        <i class="uil uil-eye-slash pw_hide"></i>
                    </div>
                    <!-- <button class="button" type="submit" name="submit">Signup Now</button> -->
                    <input type="submit" class="btn btn-primary button" value="Register" name="submit">
                    <div class="login_signup">Already have an account? <a href="#" id="login">Login</a></div>
                </form>
            </div>
        </div>
    </section>

    <!-- Login page end -->

    <!-- Landing Page start -->

    <section class="home" id="home">
        <div class="content">
            <h2 data-aos="fade-up">Laundry Basket</h2>
            <a href="#service" class="btn-1" data-aos="fade-up" data-aos-delay="200">Menu</a>
        </div>
    </section>

    <!-- Landing Page start -->

    <!-- About section start -->

    <section class="about" id="about">
        <div class="container">

            <!-- About item start -->

            <div class="row">
                <div class="col50" data-aos="fade-right">
                    <div class="titleText">
                        <span>A</span>bout Us
                    </div>
                    <p>Laundry Basket is an on-demand laundry and service that makes doing your laundry easier than ever
                        before. With our online platform, you can now get your clothes washed without leaving the
                        comfort of
                        your home no more lugging heavy loads of dirty clothes. We offer affordable and convenient
                        services and
                        guarantee fresh, clean clothes delivered right to your doorstep.Let us help make doing laundry
                        simpler for you today!</p>
                </div>
                <div class="col50">
                    <div class="imgBx" data-aos="fade-left">
                        <img src="./image/about.jpg" alt="">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col50" data-aos="fade-left">
                    <p>We started Laundry Basket with a simple goal to make laundry a hassle-free experience for busy
                        students. Laundry day is a necessity for everyone, but it can be such a huge time-sink.
                        Let us help you redeem some of those priceless hours.We want to grow and serve thousands of
                        customers, and we're committed to providing the best possible laundry service.</p>
                </div>
                <div class="col50" data-aos="fade-right">
                    <div class="imgBx">
                        <img src="./image/about2.jpg" alt="" style="transform: scaleY(1.2);">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col50" data-aos="fade-right">
                    <p> Right now We are group of only 3 person. With Harkishan leading our way with Pavan in designing,
                        and taking care of every visual aspect of our website, while all 3 of them working on to provide
                        our coustomers the services that have been promised.We strive to be transparent in delivering
                        our promises. We continue to develop and improvise to provide coustomer services.</p>
                </div>
                <div class="col50">
                    <div class="imgBx" data-aos="fade-left">
                        <img src="./image/about3.jpg" alt="">
                    </div>
                </div>
            </div>

            <!-- About item end -->

        </div>
    </section>

    <!-- About section end -->

    <!--System Section Start-->

    <section class="system" id="system">

        <div class="container">

            <div class="title" data-aos="fade-up">
                <h2 class="titleText">Our<span>S</span>ystem</h2>
                <p>HOW IT WORKS IN 5 EASY STEPS</p>
            </div>

            <div class="row">
                <div class="timeline">
                    <div class="row">

                        <!--Timeline item satrt-->

                        <div class="timeline-item">
                            <div class="timeline-item-inner" data-aos="fade-right">
                                <i class="icon"></i>
                                <span>Administration</span>
                                <h4>Pick-up</h4>
                                <p>This is the stage from where this cleaning journey start by picking-up your CLOTHES.
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-item-inner" data-aos="fade-left">
                                <i class="icon"></i>
                                <span>Washing Stage</span>
                                <h4>Laundry Attendent</h4>
                                <p>Your clothes are cleaned.</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-item-inner" data-aos="fade-right">
                                <i class="icon"></i>
                                <span>Drying stage</span>
                                <h4>Drying Attendent</h4>
                                <p>It takes few time but it will be done.</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-item-inner" data-aos="fade-left">
                                <i class="icon"></i>
                                <span>Ironing Stage</span>
                                <h4>Ironing Attendent</h4>
                                <p>Your clothes also need some warmness.</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-item-inner" data-aos="fade-right">
                                <i class="icon"></i>
                                <span>Delivering Stage</span>
                                <h4>Delivery Attendent</h4>
                                <p>Your clothes in your hand washed, ironed, folded as you wanted.</p>
                            </div>
                        </div>

                        <!--TIMELINE ITEM END-->

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--System section end-->

    <!--Service Station Start-->

    <section class="service" id="service">

        <div class="container">

            <div class="title" data-aos="fade-up">
                <h2 class="titleText">Our <span>S</span>ervice</h2>
                <p>THE SERVICES WE PROVIDE</p>
            </div>

            <div class="menu">

                <!--Service menu start-->

                <!--Service menu item start-->

                <div class="row" data-aos="fade-up" data-aos-delay="100" style="margin-top: 6rem;">
                    <div class="card">
                        <div class="boxcard">
                            <div class="imBx">
                                <img src="./image/service1.jpg" alt="">
                            </div>
                            <div class="text">
                                <h3>Clothes</h3>
                                <label for="check1" class="next">
                                    <a id="order">Order <i class="fa-solid fa-arrow-right"></i></i></a>
                                </label>
                            </div>
                            <input type="checkbox" id="check1">

                            <!--Form Service start-->

                            <div class="contentBx">
                                <div class="content">
                                    <h2>Details</h2>
                                    <div class="col10">
                                        <h1>Customer:</h1><input type="text" placeholder="Name">
                                        <h1>No. Of Clothes :</h1><input type="text" placeholder="Number">
                                    </div>

                                    <div class="col50">
                                        <div class="select">
                                        <select name="clothes" id="clothes">
                                                <option selected disabled>Type</option>
                                                <option value="">T-Shirt</option>
                                                <option value="">Shirt</option>
                                                <option value="">Kurta</option>
                                            </select>

                                            <select name="hostel" id="hostel">
                                                <option selected disabled>Hostel Name</option>
                                                <option value="">Shastri Bhavn - A</option>
                                                <option value="">Shastri Bhavn - B</option>
                                                <option value="">Tagor Bhavan - A</option>
                                                <option value="">Tagor Bhavan - B</option>
                                                <option value="">Kalam Bhavan - A</option>
                                                <option value="">Kalam Bhavan - B</option>
                                            </select>

                                            <select name="skin_diseases" id="skin_diseases">
                                                <option selected disabled>Skin Diseases</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col100">
                                        <div class="Select">
                                            <select name="service" id="service-item">
                                                <option selected disabled>Service</option>
                                                <option value="laundry">Laundry</option>
                                                <option value="ironing">Ironing</option>
                                                <option value="doubleService">Laundry & ironing</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="end">
                                        <label for="check1">
                                            <a class="back" id="back"><i class="fa-solid fa-arrow-left"></i>Back</a>
                                        </label>
                                        <label for="confirm1">
                                            <button class="confirm" id="confirm">Confirm<i
                                                    class="fa-solid fa-arrow-right"></i></button>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!--Form Service end-->

                        </div>
                    </div>
                </div>

                <!--Service menu item end-->

                <!--Service 2 menu item start-->

                <div class="row" data-aos="fade-up" data-aos-delay="200" style="margin-top: 6rem;">
                    <div class="card">
                        <div class="boxcard">
                            <div class="imBx">
                                <img src="./image/service2.jpg" alt="" style="height: 310px;">
                            </div>
                            <div class="text">
                                <h3>Blanket</h3>
                                <label for="check2" class="next">
                                    <a id="order">Order <i class="fa-solid fa-arrow-right"></i></i></a>
                                </label>
                            </div>
                            <input type="checkbox" id="check2">

                            <!--Form Service start-->

                            <div class="contentBx">
                                <div class="content">
                                    <h2>Details</h2>
                                    <div class="col10">
                                        <h1>Customer:</h1><input type="text" placeholder="Name">
                                        <h1>Pcs :</h1><input type="text" placeholder="Pcs">
                                    </div>

                                    <div class="col50">
                                        <div class="select">
                                            <select name="hostel" id="hostel">
                                                <option selected disabled>Hostel Name</option>
                                                <option value="">Shastri Bhavn - A</option>
                                                <option value="">Shastri Bhavn - B</option>
                                                <option value="">Tagor Bhavan - A</option>
                                                <option value="">Tagor Bhavan - B</option>
                                                <option value="">Kalam Bhavan - A</option>
                                                <option value="">Kalam Bhavan - B</option>
                                            </select>

                                            <select name="skin_diseases" id="skin_diseases">
                                                <option selected disabled>Skin Diseases</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col100">
                                        <div class="Select">
                                            <select name="service" id="service-item">
                                                <option selected disabled>Service</option>
                                                <option value="laundry">Laundry</option>
                                                <option value="ironing">Ironing</option>
                                                <option value="doubleService">Laundry & ironing</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="end">
                                        <label for="check2">
                                            <a class="back" id="back"><i class="fa-solid fa-arrow-left"></i>Back</a>
                                        </label>
                                        <label for="confirm2">
                                            <button class="confirm" id="confirm">Confirm<i
                                                    class="fa-solid fa-arrow-right"></i></button>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!--Form Service end-->

                        </div>
                    </div>
                </div>

                <!--Service 2 menu item end-->

                <!--Service 3 menu item start-->

                <div class="row" data-aos="fade-up" data-aos-delay="300" style="margin-top: 6rem;">
                    <div class="card">
                        <div class="boxcard">
                            <div class="imBx">
                                <img src="./image/service3.jpg" style="height: 310px;" alt="">

                            </div>
                            <div class="text">
                                <h3>Pants</h3>
                                <label for="check3" class="next">
                                    <a id="order">Order <i class="fa-solid fa-arrow-right"></i></i></a>
                                </label>
                            </div>
                            <input type="checkbox" id="check3">

                            <!--Form Service start-->

                            <div class="contentBx">
                                <div class="content">
                                    <h2> Details</h2>
                                    <div class="col10">
                                        <h1>Customer:</h1><input type="text" placeholder="Name">
                                        <h1> Pcs :</h1><input type="text" placeholder="Pcs">
                                    </div>

                                    <div class="col50">
                                        <div class="select">
                                        <select name="pants" id="pants">
                                                <option selected disabled>Type</option>
                                                <option value="">Jeans</option>
                                                <option value="">Trousers</option>
                                                <option value="">Shots</option>
                                            </select>

                                            <select name="hostel" id="hostel">
                                                <option selected disabled>Hostel Name</option>
                                                <option value="">Shastri Bhavn - A</option>
                                                <option value="">Shastri Bhavn - B</option>
                                                <option value="">Tagor Bhavan - A</option>
                                                <option value="">Tagor Bhavan - B</option>
                                                <option value="">Kalam Bhavan - A</option>
                                                <option value="">Kalam Bhavan - B</option>
                                            </select>

                                            <select name="skin_diseases" id="skin_diseases">
                                                <option selected disabled>Skin Diseases</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col100">
                                        <div class="Select">
                                            <select name="service" id="service-item">
                                                <option selected disabled> Service</option>
                                                <option value="laundry">Laundry</option>
                                                <option value="ironing">Ironing</option>
                                                <option value="doubleService">Laundry & ironing</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="end">
                                        <label for="check3">
                                            <a class="back" id="back"><i class="fa-solid fa-arrow-left"></i>Back</a>
                                        </label>
                                        <label for="confirm3">
                                            <button class="confirm" id="confirm">Confirm<i
                                                    class="fa-solid fa-arrow-right"></i></button>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!--Service 3 menu item end-->

                            <!--Service menu end-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Service Station End-->

    <!-- Testimonials Section start -->

    <section class="testimonials" id="testimonials">

        <div class="title" data-aos="fade-up">
            <h2 class="titleText">Their <span>T</span>estimonials</h2>
        </div>

        <div class="content">

            <!-- Testi item start -->

            <div class="box" data-aos="fade-up" data-aos-delay="100">
                <div class="imgBx">
                    <img src="./image/testi1.jpg" alt="">
                </div>
                <div class="text">
                    <p>Excellent laundry and one of the best laundry i have ever seen. <br> I regularly give my clothes
                        over there.</p>
                    <h3>Shweta</h3>
                </div>
            </div>

            <div class="box" data-aos="fade-up" data-aos-delay="200">
                <div class="imgBx">
                    <img src="./image/testi2.jpg" alt="">
                </div>
                <div class="text">
                    <p>Exceptional services, highly professional staff and prompt response, overall excellent rating.
                    </p>
                    <h3>Vaibhav</h3>
                </div>
            </div>

            <div class="box" data-aos="fade-up" data-aos-delay="300">
                <div class="imgBx">
                    <img src="./image/testi3.jpg" alt="">
                </div>
                <div class="text">
                    <p>Bright and clean, well upkept.. great for when i have to clean bulky items.</p>
                    <h3>Diya</h3>
                </div>
            </div>

            <!-- Testi item end -->

        </div>

    </section>

    <!-- Testimonials Section end -->

    <!-- contact section start -->

    <section class="contact" id="contact">

        <div class="container">

            <div class="title" data-aos="fade-up">
                <h2 class="titleText">contact <span>U</span>s</h2>
            </div>

            <div class="box">

                <div class="contactForm" data-aos="fade-right">

                    <h3>Send Message</h3>
                    <div class="inputBox" data-aos="fade-right" data-aos-delay="20">
                        <input type="text" placeholder="Name">
                    </div>
                    <div class="inputBox" data-aos="fade-right" data-aos-delay="40">
                        <input type="text" placeholder="Email">
                    </div>
                    <div class="inputBox" data-aos="fade-right" data-aos-delay="60">
                        <textarea placeholder="Message"></textarea>
                    </div>
                    <div class="inputBox" data-aos="fade-right" data-aos-delay="80">
                        <input type="submit" to="shiroyaharry52@gmail.com" class="btn" value="Send">
                    </div>

                </div>

                <div class="map" data-aos="fade-left">

                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3691.7173104615913!2d73.3634204!3d22.2886958!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395fda2400192473%3A0xc319c9237f2928e8!2sParul%20University!5e0!3m2!1sen!2sin!4v1690348629861!5m2!1sen!2sin"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                </div>

                <div class="icon-container" data-aos="fade-up">

                    <div class="icons" data-aos="fade-up" data-aos-delay="100">
                        <span>address :</span>
                        <p>Vadodara, India</p>
                    </div>
                    <div class="icons" data-aos="fade-up" data-aos-delay="200">
                        <span>email :</span>
                        <p>laundrybasket@gmail.com</p>
                    </div>
                    <div class="icons" data-aos="fade-up" data-aos-delay="300">
                        <span>phone :</span>
                        <p>+91 98765 43210</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- contact section end -->

    <!-- Copyright -->

    <div class="cp">
        <p>&copy; 2023 <a href="#">Laundry Basket</a>. All Right Reserved</p>
    </div>

    <!-- JS Link -->
    <script src="./js/script.js"></script>

    <!-- AOS JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"
        integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
    AOS.init({
        duration: 400,
    })
    </script>

</body>

</html>