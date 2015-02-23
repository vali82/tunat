<?php
header('Content-Type: text/html; charset=utf-8');
$html = new DOMDocument();
$page = 'http://en.bildelsbasen.se/?link=search&searchmode=1&vc1=168';
echo $page.'<hr>';
@$html->loadHtmlFile($page);
$xpath = new DOMXPath( $html );
$nodelist = $xpath->query( "//div[@class='box']/table/tr" );
$cnt = 0;
$carMake = 'Mercedes';
foreach ($nodelist as $n) {

    $categoryName = $n->childNodes->item(0)->nodeValue;

    if (
        strpos($categoryName, 'Övrigt') === false &&
        strpos($categoryName, 'Lastbilar') === false &&
        strpos($categoryName, 'Övriga') === false
    ) {

        echo "Model Category: ".$categoryName.'<br />';
        for ($i = 0; $i<$n->childNodes->item(1)->childNodes->item(0)->childNodes->length; $i++) {
            $cnt++;
            $ex = explode("(", $n->childNodes->item(1)->childNodes->item(0)->childNodes->item($i)->nodeValue);
            if (count($ex)>2) {
                $name = $ex[0].'('.$ex[1];
                $years = $ex[2];
            } else {
                $name = $ex[0];
                $years = $ex[1];
            }

            $modelCar = trim(str_replace(strtoupper($carMake), "", $name));

            $ex2 = explode("-", $years);
            if (count($ex2) > 1) {
                $yearStart = trim($ex2[0]);
                $yearEnd = trim(str_replace(')', '', $ex2[1]));

            } else {
                $yearStart = 0;
                $yearEnd = 0;
            }
            echo $modelCar . ', an: '.$yearStart.' - '.$yearEnd . "<br />";

            /*$statement = $dbh->prepare(
                "INSERT INTO cars_model_init(car_id, model_categ, model, year_start, year_end, popularity)".
                " VALUES({$carID}, '{$categoryName}', '{$modelCar}', {$yearStart}, {$yearEnd}, {$cnt})");
            $results = $statement->execute();*/

        }
        echo '<hr>';
    }
}