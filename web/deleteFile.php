<?php $name = $_POST['filename'];
if(file_exists($name)){
unlink($name);
echo "ok";
}else{
echo "error";
}
?>