<!-- <!DOCTYPE html>
<html lang="en">

<head>



</head>

<body> -->

<div id="Header" class="Header">
    <div class="bar_butt">
        <label for="check" class="checkbars"><i class="fa fa-bars" aria-hidden="true"></i></label>
    </div>
    <input type="checkbox" onclick="mysidebar()" id="check">

    <div class="header_con">
        <nav>
            <a href="index.php"><img class="logo_img" src="Images/logo.png" alt=""></a>
        </nav>
    </div>
    <ul class="header-right" id="header-right">
        <li class="backbutton active"><a href="index.php" class="textd">Home</a></li>
        <li class="backbutton"><a href="about.php" class="textd">Contact</a></li>
        <li class="backbutton"><a href="about.php" class="textd">About</a></li>
        <!-- <li class="backbutton registera_button"><a href="about.php" class="textd">Register</a></li> -->
    </ul>
    <div class="left_items">
        <!-- <div class="hover_button">
            <button class="login_button Register_btn" onclick="location.href='new_vendor.php'"><i class="fa fa-briefcase" aria-hidden="true"></i></button>
            <div class="hide">Login as a Proffectional.</div>
        </div> -->
        <?php
        if (empty($customer_id)) { ?>
            <a class="login_button hidebutton" href="new_vendor.php">Join as Provider</a>
            <a class="login_button hidebutton" onclick="openSignUpModal()" href="javascript:void();">Join as Customer</a>
        <?php } ?>

        <?php echo $LOGINBTN; ?>
    </div>
</div>