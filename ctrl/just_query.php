<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('memory_limit', 1);
$NO_PRELOAD = $NO_REDIRECT = 1;
require_once("../includes/common.php");

$PAGE_TITLE = 'QuotemasterMyAdmin';

$action_arr = array('select', 'insert', 'update', 'delete', 'truncate');
$table_arr = array();

if (isset($_POST["mode"])) $mode = $_POST["mode"];
else $mode = "A";

$txtq = false;

if ($mode == 'submit') {
    $txtq = str_replace("\'", "'", $_POST['txtq']);
}
?>
<form name="frm" id="frm" method="post" action="just_query.php">
    <input type="hidden" name="mode" value="submit" />
    <table align="center" border="1" width="80%">
        <tr>
            <td align="center"><textarea name="txtq" id="txtq" style="width:90%;"><?php echo $txtq; ?></textarea><br />

                <input type="submit" name="btn_submit" value="go" />
            </td>
        </tr>
    </table>
</form>
<br />
<?php
if ($txtq) {
    $invalid_keywords = array('drop', 'truncate', 'empty');  // 'delete', 'update', 'insert'
    // $txtq = strtolower($txtq);

    $q_flag = 'Y';
    foreach ($invalid_keywords as $ik)
        if (strpos($txtq, $ik) !== false) {
            $q_flag = 'N';
            break;
        }

    // LogQuery($txtq, $q_flag);

    if ($q_flag == 'N') {
        echo 'Invalid Query Parameter';
        exit;
    }

    $r = sql_query($txtq, '58');
    //echo $r;

    if (sql_num_rows($r)) {
?>
        <table align="center" border="1" width="100%">
            <?php
            for ($i = 1; $a = sql_fetch_assoc($r); $i++) {
                if ($i == 1) {
            ?>
                    <tr>
                        <?php
                        echo '<th>#</th>';
                        foreach ($a as $h => $a1) {
                            echo '<th>' . $h . '</th>';
                        }
                        ?>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <?php
                    echo '<td align="center">' . $i . '</td>';
                    foreach ($a as $a1) {
                        echo '<td>' . $a1 . '</td>';
                    }
                    ?>
                </tr>
        <?php
            }
        }
        ?>
        </table>
    <?php
}
sql_close();
    ?>



    <!-- SHOW CREATE TABLE service_providers; -->