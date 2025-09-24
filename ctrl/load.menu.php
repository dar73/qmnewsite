<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div class="">
            <img src="assets/images/logo-icon.png" class="logo-icon-2" alt="" />
        </div>
        <div>
            
        </div>
        <a href="javascript:;" class="toggle-btn ml-auto"> <i class="bx bx-menu"></i>
        </a>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
      <?php
     
                 //active class = mm-active;
     			foreach($MENU_ARR as $mKEY=>$mVALUE)
     			{
                    
     				echo '<li class="menu-label">'.$mVALUE['TEXT'].'</li>';
     				if($mVALUE['IS_SUB']=='Y' && !empty($mVALUE['SUB_MENU']) && count($mVALUE['SUB_MENU']))
     				{
     					foreach($mVALUE['SUB_MENU'] as $sKEY=>$sVALUE)
     					{
     						$drop = ($sVALUE['IS_SUB']=='Y' && !empty($sVALUE['MENU']) && count($sVALUE['MENU']))?'<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>':'';

     						$active = GetActiveLink(basename($_SERVER['SCRIPT_FILENAME']),$sVALUE);
     						//$active = (basename($_SERVER['SCRIPT_FILENAME'])==$sVALUE['HREF'])?' class="mm-active"':'';
     						$sVALUE['HREF'] = (!empty($sVALUE['HREF']))?$sVALUE['HREF']:'';

     						if(empty($sVALUE['HREF']))
     						{
     							if($sVALUE['IS_SUB']=='Y') $sVALUE['HREF'] = 'javascript:;';
     							else $sVALUE['HREF'] = 'underconstruction.php';
     						}

     						echo '<li'.$active.'>';
     						echo '<a href="'.$sVALUE['HREF'].'"> <div class="parent-icon"><i class="'.$sVALUE['ICON'].'"></i>
                                    </div><div class="menu-title"> '.$sVALUE['TEXT'].' '.$drop.'</div></a>';
                // echo '<a href="'.$sVALUE['HREF'].'" class="has-arrow">
                //
                //     <div class="menu-title">'.$sVALUE['TEXT'].'</div>
                // </a>';

     						if($sVALUE['IS_SUB']=='Y' && !empty($sVALUE['MENU']) && count($sVALUE['MENU']))
     						{
     							echo '<ul>';
     							foreach($sVALUE['MENU'] as $sKEY2=>$sVALUE2)
     							{
     								$active2 = GetActiveLink(basename($_SERVER['SCRIPT_FILENAME']),$sVALUE2);
     								//$active2 = (basename($_SERVER['SCRIPT_FILENAME'])==$sVALUE2['HREF'])?' class="mm-active"':'';

     								echo '<li> <a href="'.$sVALUE2['HREF'].'"'.$active2.'> <i class="'.$sVALUE2['ICON'].'"> </i> '.$sVALUE2['TEXT'].'</a> </li>';
     							}
     							echo '</ul>';
     						}

     						echo '</li>';
     					}
     				}
     			}
                 ?>

    </ul>
    <!--end navigation-->
</div>
                    <!-- <li>
                            <a href="javascript:;" class="has-arrow">
                                <div class="parent-icon"><i class="bx bx-home-alt"></i>
                                </div>
                                <div class="menu-title">Dashboard</div>
                            </a>
                            <ul>
                                <li> <a href="index.html"><i class="bx bx-right-arrow-alt"></i>Analytics</a>
                                </li>
                                <li> <a href="index2.html"><i class="bx bx-right-arrow-alt"></i>Sales</a>
                                </li>
                            </ul>
                </li> -->