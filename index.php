<html>
<form action="" method="post" >
    Insert the prodcut link:
    <input type="text" name="un"> 
    <input type="submit" value="Get Price" name="submit" />
</form>

<?php
// link of the amazon product
if (isset($_POST['un'])) {
    $link = $_POST["un"];
    
$page_content = file_get_contents($link);

$res=preg_match('/"priceblock_ourprice".*\₹(.*)</i',
$page_content, $matches);

$res2=preg_match('/"priceblock_dealprice".*\₹(.*)</i',
$page_content, $matches2);

$res3=preg_match('/"priceblock_saleprice".*\₹(.*)</i',
$page_content, $matches3);


if($res) {
    $price = trim($matches[1]);
}
elseif($res2){
    $price=trim($matches2[1]);
} 
elseif($res3)
{
    $price=trim($matches3[1]);
}
else {
    echo "Price not found.";
    $price = 0;
}

if($price)
{
    echo "The Price of given Product is ₹".$price;
}
}
?>

</html>