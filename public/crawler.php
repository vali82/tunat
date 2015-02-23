<?php
header('Content-Type: text/html; charset=utf-8');
$html = new DOMDocument();

if (!isset($_GET['page'])) {
    //@$html->loadHtmlFile('http://www.bildelsbasen.se/?link=search&searchmode=1');
    @$html->loadHtmlFile('http://tunat.local/allcars.html');
    $xpath = new DOMXPath( $html );
    $nodelist = $xpath->query( "//div[@class='box']/table/tr/td[@class='bdrSldBtm']/a[@class='invert']" );
    foreach ($nodelist as $n){
        $carMake = $n->nodeValue;
        $url = 'http://www.bildelsbasen.se'.$n->getAttribute('href');
//        $url = 'http://tunat.local/audi.html';
        echo
            '<a href="/crawler.php?page='.base64_encode($url).'&make='.base64_encode($carMake).'" target="_blank">'.$carMake.'</a>, ';
    }
// PENTRU o MAsisa in parte
} else {

//    die('verifica sa fie ok baza de date...');

    $dsn = 'mysql:dbname=tunat;host=localhost;charset=utf8';
    $user = 'tunat';
    $password = 'tunat!@#$';

    try {
        $dbh = new PDO($dsn, $user, $password);

        $carMake = base64_decode($_GET['make']);
        $statement = $dbh->prepare("INSERT INTO cars_make_init(make) VALUES('".$carMake."')");
        $results = $statement->execute();
        $carID = $dbh->lastInsertId();


        $cnt = 0;
        //@$html->loadHtmlFile('http://www.bildelsbasen.se/se-sv/Opel/');
        $page = base64_decode($_GET['page']);
        echo $page.'<hr>';
        @$html->loadHtmlFile($page);
        $xpath = new DOMXPath( $html );
        $nodelist = $xpath->query( "//div[@class='box']/table/tr" );

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

                    $statement = $dbh->prepare(
                        "INSERT INTO cars_model_init(car_id, model_categ, model, year_start, year_end, popularity)".
                        " VALUES({$carID}, '{$categoryName}', '{$modelCar}', {$yearStart}, {$yearEnd}, {$cnt})");
                    $results = $statement->execute();

                }
                echo '<hr>';
            }
        }

    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }


}
