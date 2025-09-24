<?php
$EMAIL_LEFT = $EMAIL_CLASS = 'text-warning';
$EMAIL_COUNT = GET_EMAILCOUNT();
if (!empty($EMAIL_COUNT)) {
	$EMAIL_COUNT2 = json_decode($EMAIL_COUNT);
	if (isset($EMAIL_COUNT2->plan)) {
		foreach ($EMAIL_COUNT2->plan as $KEY => $VALUE) {
			if ($VALUE->type == 'subscription') {
				$EMAIL_LEFT = $VALUE->credits;
				$EMAIL_LEFT .= ' | ' . FormatDate($VALUE->endDate, 'B');

				$dateDiff = DateDiff(TODAY, $VALUE->endDate);
				//echo '=>'.$dateDiff.'<=';

				if ($VALUE->credits <= '1000')
					$EMAIL_CLASS = 'text-danger icon-anim-pulse';
				if ($dateDiff <= '10')
					$EMAIL_CLASS = 'text-danger icon-anim-pulse';
			}
		}
	}
}
?>
<style>
		.blinking-text {
			
			/* Adjust the color */
			font-size: 24px;
			/* Adjust the size */
			animation: blinker 1s linear infinite;
		}

		@keyframes blinker {
			50% {
				opacity: 0;
			}
		}
	</style>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-gray">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../index.php" class="nav-link">Home</a>
        </li>
        
        <!-- <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li> -->
    </ul>

    <!-- SEARCH FORM -->
    <!-- <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form> -->

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <?php if($sess_user_level=='0' || $sess_user_level=='1'){ ?>
    <li>
        <a class="nav-link mr-2" data-toggle="dropdown" aria-expanded="false">EMAIL CREDITS:&nbsp;<span class="<?php echo $EMAIL_CLASS; ?> blinking-text"><?php echo $EMAIL_LEFT; ?></span></a>
        </li>
        <?php } ?>
        <!-- Messages Dropdown Menu -->
        <!-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item"> -->
        <!-- Message Start -->
        <!-- <div class="media">
                        <img src="../dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Brad Diesel
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div> -->
        <!-- Message End -->
        <!-- </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item"> -->
        <!-- Message Start -->
        <!-- <div class="media">
                        <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                John Pierce
                                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">I got your message bro</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div> -->
        <!-- Message End -->
        <!-- </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item"> -->
        <!-- Message Start -->
        <!-- <div class="media">
                        <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Nora Silvester
                                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">The subject goes here</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div> -->
        <!-- Message End -->
        <!-- </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li> -->
        <!-- Notifications Dropdown Menu -->
        <!-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>-->

        <?php if ($sess_user_level == 2) {
            $q = "select * from notifications where cStatus='A' ";
            $r = sql_query($q);?>
            <li class="nav-item dropdown">
                <a class="nav-link blink_me" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-danger navbar-badge"><?php echo sql_num_rows($r); ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <?php
                   
                    if (sql_num_rows($r)) {
                        while ($a = sql_fetch_assoc($r)) {

                    ?>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item" style="white-space: wrap !important;">
                                <strong><?php echo $a['vTitle']; ?></strong><br />
                                <small><em><?php echo db_output2($a['vMessage']); ?></em></small>
                            </a>

                    <?php  }
                    }
                    ?>
                </div>
            </li>
        <?php } ?>

    </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../index.php" class="brand-link">
        <img src="../Images/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">

            <?php
            if ($sess_user_level == 1 || $sess_user_level == 0) {
                echo 'Admin';
            } elseif ($sess_user_level == 2) {
                echo 'SP';
            } elseif ($sess_user_level == 3) {
                echo 'Customer';
            } elseif ($sess_user_level == 4) {
                echo 'Manager';
            } elseif ($sess_user_level == 5) {
                echo 'Accounts';
            } elseif ($sess_user_level == 6) {
                echo 'CE';
            }elseif ($sess_user_level == 7) {
                echo 'PU';
            }elseif ($sess_user_level == 8) {
                echo 'LU';
            }else{
                echo 'NA';
            }

            ?>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->

        <div class="user-panel mt-3 pb-3 mb-3 flex-column">
            <div class="image">
                <!-- <img src="../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
                <?php if ($sess_user_level == 2) {
                    $file_name = GetXFromYID('select vLicence_file from service_providers where id=' . $sess_user_id);
                    $SRC = '../dist/img/user2-160x160.jpg';
                    if (IsExistFile($file_name, LICENCE_UPLOAD)) {
                        $SRC = LICENCE_PATH . $file_name;
                    } ?>
                    <img src="<?php echo $SRC; ?>" class="img-circle elevation-2" alt="User Image">
                <?php } else { ?>
                    <img src="../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                <?php } ?>
            </div>
            <div class="info">
                <a href="#" class="d-block">


                    <?php
                    echo $sess_user_name;
                    ?></a>
            </div>
            <?php
            if ($sess_user_level == 2) { ?>
                <div class="progress m-3" style="height: 2px;">
                    <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

            <?php    } ?>
        </div>




        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <?php
                //DFA($MENU_ARR);
                //active class = mm-active;
                foreach ($MENU_ARR as $mKEY => $mVALUE) {
                    //<li class="nav-header">SERVICE PROVIDERS</li>
                    $active = GetActiveLink(basename($_SERVER['SCRIPT_FILENAME']), $mVALUE);
                    $HREF = (!empty($mVALUE['HREF']) && $mVALUE['HREF'] != '#') ? $mVALUE['HREF'] : 'javascript:;';
                    // echo '<li class="nav-header" style="font-weight: bold;color: blue;">' . $mVALUE['TEXT'] . '</li>';

                    if ($mVALUE['IS_SUB'] == 'Y' && !empty($mVALUE['SUB_MENU']) && count($mVALUE['SUB_MENU'])) {
                        echo '<li class="nav-item has-treeview menu-open">
                                    <a href="#" class="nav-link ' . $active . '">
                                    <i class="'.$mVALUE['ICON'].'"></i>
                                    <p>
                                        ' . $mVALUE['TEXT'] . '
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                    </a>
                                    <ul class="nav nav-treeview">';

                        foreach ($mVALUE['SUB_MENU'] as $sKEY => $sVALUE) {
                            $drop = ($sVALUE['IS_SUB'] == 'Y' && !empty($sVALUE['MENU']) && count($sVALUE['MENU'])) ? '<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>' : '';

                            $active = GetActiveLink(basename($_SERVER['SCRIPT_FILENAME']), $sVALUE);
                            //$active = (basename($_SERVER['SCRIPT_FILENAME'])==$sVALUE['HREF'])?' class="mm-active"':'';
                            $sVALUE['HREF'] = (!empty($sVALUE['HREF'])) ? $sVALUE['HREF'] : '';

                            if (empty($sVALUE['HREF'])) {
                                if ($sVALUE['IS_SUB'] == 'Y') $sVALUE['HREF'] = 'javascript:;';
                                else $sVALUE['HREF'] = 'underconstruction.php';
                            }



                            echo '<li class="nav-item">';
                            echo '<a href="' . $sVALUE['HREF'] . '" class="nav-link ' . $active . '"> <i class="' . $sVALUE['ICON'] . '"></i>
                                    <p> ' . $sVALUE['TEXT'] . ' ' . $drop . '</p></a></li>';

                            if ($sVALUE['IS_SUB'] == 'Y' && !empty($sVALUE['MENU']) && count($sVALUE['MENU'])) {
                                echo '<ul>';
                                foreach ($sVALUE['MENU'] as $sKEY2 => $sVALUE2) {
                                    $active2 = GetActiveLink(basename($_SERVER['SCRIPT_FILENAME']), $sVALUE2);
                                    //$active2 = (basename($_SERVER['SCRIPT_FILENAME'])==$sVALUE2['HREF'])?' class="mm-active"':'';

                                    echo '<li> <a href="' . $sVALUE2['HREF'] . '"' . $active2 . '> <i class="' . $sVALUE2['ICON'] . '"> </i> ' . $sVALUE2['TEXT'] . '</a> </li>';
                                }
                                echo '</ul>';
                            }
                        }
                        echo '</ul></li>';
                    } else {
                        echo '<li class="nav-item"><a href="' . $HREF . '" class="nav-link ' . $active . '"><i class="' . $mVALUE['ICON'] . '"></i><p> ' . $mVALUE['TEXT'] . '</p></a></li>';
                    }
                }

                ?>



            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>