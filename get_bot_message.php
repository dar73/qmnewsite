<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$txt=db_output2($_POST['txt']);
$sql="select reply from chatbot_hints where question like '%$txt%' and cStatus='A' ";
//ALTER TABLE chatbot_hints ADD FULLTEXT INDEX idx_question (question);

// $sql="SELECT reply FROM chatbot_hints WHERE MATCH(question) AGAINST('$txt' WITH QUERY EXPANSION) AND cStatus='A' ";
$res=sql_query($sql,"ERR1.chatbot");
if(sql_num_rows($res)>0){
	$row=sql_fetch_assoc($res);
	$html=$row['reply'];
}else{
	$html="Sorry not be able to understand you";
}
$added_on=date('Y-m-d h:i:s');
sql_query("insert into message(message,added_on,type) values('$txt','$added_on','user')","ERR2.chatbot");
$added_on=date('Y-m-d h:i:s');
sql_query("insert into message(message,added_on,type) values('$html','$added_on','bot')","ERR3.chatbot");
echo $html;
?>