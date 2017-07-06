<?php

class Spider
{
    private $mode = 'LIVE';
    private $pathTest = 'http://tirbox.local/ea/';
    private $host;
    private $url;
    private $skuSuffix = '';

    private $resolvedHosts = [
        'www.trutzi.ro' => 'TRT',
        'www.facsrl.com' => 'FAC'
    ];

    public function __construct()
    {
    }

    private function trutzi($url)
    {
        $error = '';
        $data = [
            'sku' => '',
            'name' => '',
            'descr' => '',
            'price' => '',
            'images' => []
        ];

        $html = new DOMDocument();
        if ($this->mode == 'TEST') {
            if (file_exists('file.html') && 1==1) {
                $content = file_get_contents('file.html');
            } else {
                $content = file_get_contents($url);
                $fp = fopen('file.html', 'w');
                fwrite($fp, $content);
                fclose($fp);
            }
            $page = $this->pathTest . 'file.html';
        } else {
            $page = $url;
        }
        @$html->loadHtmlFile($page);
        $xpath = new DOMXPath($html);

        // get name
        $nodelist = $xpath->query("//div[@class='pb-center-column col-xs-12 col-sm-4']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('h1') as $h1) {
                    if ($h1->getAttribute('itemprop') == 'name') {
                        $data['name'] = $h1->nodeValue;
                    }
                }
            }
        }
        if ($data['name'] == '') {
            $error = "name not found";
        }

        // get description
        $nodelist = $xpath->query("//section[@class='page-product-box']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('table') as $table) {
                    if ($table->getAttribute('class') == 'table-data-sheet') {
                        $data['descr'] = trim(str_replace("\n", "", $table->C14N()));
                    }
                    $data['name'] = $h1->nodeValue;
                }
            }
        }
        if ($data['descr'] == '') {
            $error = "descr not found";
        }

        // get price
        $nodelist = $xpath->query("//p[@class='our_price_display']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('span') as $span) {
                    if ($span->getAttribute('class') == 'price') {
                        $data['price'] = str_replace([',', ' '], ['.', ''], explode(' lei', $span->nodeValue)[0]);
                    }
                }
            }
        }
        if ($data['price'] == '') {
            $error = "price not found";
        }

        // get sku
        $nodelist = $xpath->query("//div[@class='pb-center-column col-xs-12 col-sm-4']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('p') as $p) {
                    if ($p->getAttribute('id') == 'product_reference') {
                        foreach ($p->getElementsByTagName('span') as $span) {
                            if ($span->getAttribute('itemprop') == 'sku') {
                                $data['sku'] = $span->nodeValue.$this->skuSuffix;
                                $data['name'] = str_replace(' '.$span->nodeValue, '', $data['name']);
                            }
                        }
                    }
                }
            }
        }
        if ($data['sku'] == '') {
            $error = "sku not found";
        }

        // get pictures
        $nodelist = $xpath->query("//div[@id='image-block']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('span') as $span) {
                    if ($span->getAttribute('id') == 'view_full_size') {
                        foreach ($span->getElementsByTagName('a') as $a) {
                            if ($a->getAttribute('class') == 'jqzoom') {
                                $data['images'][] = $a->getAttribute('href');
                            }
                        }
                    }
                }
            }
        }
        if (count($data['images']) == 0) {
            $error = "images not found";
        }



        $data['error'] = $error;

        return $data;
    }

    public function grabByHost($url)
    {
        $this->url = $url;
        $this->host = explode('/', str_replace(['http://', 'https://'], ['', ''], $url))[0];
        if (isset($this->resolvedHosts[$this->host])) {
            $this->skuSuffix = $this->resolvedHosts[$this->host];
        }

        switch ($this->host) {
            case "www.trutzi.ro":
                $data = $this->trutzi($this->url);
                break;
            default:
                $data = ['error' => 'no valid url found'];
                break;
        }

        $data['host'] = $this->host;
        $data['skuSuffix'] = $this->skuSuffix;

        return $data;
    }

    public function getResolvedHosts()
    {
        return $this->resolvedHosts;
    }


}

class Project
{
    private $attributesCustom = [
        "alimentare_motor" => "Alimentare motor - alimentare_motor",
        "brand" => "Brand",
        "diametru_mm" => "Diametru(mm) - diametru_mm",
        "dimensiune_diamentru" => "Diametru Ø(mm) - dimensiune_diamentru",
        "greutate_max_poarta" => "Greutate Max Poarta - greutate_max_poarta",
        "grosime_mm" => "Grosime(mm) - grosime_mm",
        "lungime_m" => "Lungime(m) - lungime_m",
        "latime_mm" => "Latime(mm) - latime_mm",
        "inaltime_mm" => "Inaltime(mm) - inaltime_mm",
        "lungime_mm" => "Lungime(mm) - lungime_mm",
        "lungime_poarta_m" => "Lungime Poarta(m) - lungime_poarta_m",
    ];
    private $attributesMagento = [
        'sku',
        'name',
        'description',
        'price',
        'categories',
        'image',
        'media_gallery',
        'qty',
        'type'
    ];

    private function fputcsv2 ($fh, array $fields, $delimiter = ',', $enclosure = '"', $mysql_null = false) {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');

        $output = array();
        foreach ($fields as $field) {
            if ($field === null && $mysql_null) {
                $output[] = 'NULL';
                continue;
            }

            $output[] = preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field) ? (
                $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure
            ) : $field;
        }

        fwrite($fh, join($delimiter, $output) . "\n");
    }

    private function recursiveDelFolder($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir."/".$object))
                        $this->recursiveDelFolder($dir."/".$object);
                    else
                        unlink($dir."/".$object);
                }
            }
            rmdir($dir);
        }
    }

    public function index()
    {
        $mode = 'init';
        require_once("index.phtml");
    }

    public function processUrl($url)
    {
        $mode = 'process';

        $spider = new Spider();
        $data = $spider->grabByHost($url);
        if ($data['error'] != '') {
            $error = $data['error'];
        }

        $attributes = $this->attributesCustom;
        require_once("index.phtml");
    }

    public function saveProduct($data)
    {
        $mode = 'init';

        $now = new \DateTime();

        $structure = __DIR__ . '/import/' . $data['skuSuffix'];
        if (!is_dir($structure)) {
            mkdir($structure, 0777, true);
            chmod($structure, 0777);
        }
        if (!is_dir($structure.'/images')) {
            mkdir($structure.'/images', 0777, true);
            chmod($structure.'/images', 0777);
        }

        $file = $structure . '/new_products.csv';

        try {
            $message = 'Product successfully saved!';

            $allAttributes = $this->attributesMagento;
            foreach ($this->attributesCustom as $attr => $value) {
                $allAttributes[] = $attr;
            }
            if (!file_exists($file)) {
                // add to csv
                $fp = fopen($file, 'a');
                $this->fputcsv2($fp, $allAttributes);
                ///
            }

            if (count($data)) {
                $imageGallery = [];
                $firstImage = '';
                foreach ($data['images'] as $k=>$image) {
                    $content = file_get_contents($image);
                    $imageFileName = $data['sku'].($k > 0 ? '_'.$k : '').'.jpg';
                    file_put_contents($structure.'/images/'.$imageFileName, $content);
                    if ($k > 0) {
                        $imageGallery[] = $imageFileName;
                    } else {
                        $firstImage = $data['sku'].'.jpg';
                    }
                }

                // add to csv
                $csvRow = array_flip($allAttributes);
                foreach($csvRow as $k=>$v) {
                    $csvRow[$k] = '';
                }
                $csvRow['type'] = 'simple';
                $csvRow['sku'] = $data['sku'];
                $csvRow['name'] = $data['name'];
                $csvRow['price'] = $data['price'];
                $csvRow['description'] = $data['descr'];
                $csvRow['image'] = $firstImage;
                $csvRow['price'] = $data['price'];
                $csvRow['categories'] = $data['category'];
                $csvRow['qty'] = $data['qty'];
                if (count($imageGallery)) {
                    $csvRow['media_gallery'] = implode(',', $imageGallery);
                }

                foreach ($data['attr_k'] as $k => $attribute) {
                    if ($attribute != '' && $data['attr_v'][$k] != '') {
                        $csvRow[$attribute] = $data['attr_v'][$k];
                    }
                }
                $csvRow = array_values($csvRow);

                $fp = fopen($file, 'a');
                $this->fputcsv2($fp, $csvRow);
                ///

                setcookie('lastcat', $data['category'], time() + 7*24*3600, '/');
            }

        } catch (PDOException  $e) {
            $error = $e->getMessage();
        }

        require_once("index.phtml");
    }

    public function login()
    {
        if (isset($_POST['pass'])) {
            if ($_POST['pass'] == 'vali11spider') {
                setcookie('passpider', 'uyfsb98342h839thfoibq9r4r3ht34t89hfn89wf', time() + (1 * 24 * 3600), '/');
            }
            header('Location: index.php');
        } else {
            require_once 'login.phtml';
        }
    }


    public function resetFurnizor($furnizor)
    {
        $spider = new Spider();
        if (in_array($furnizor, $spider->getResolvedHosts())) {
            $this->recursiveDelFolder(__DIR__ . '/import/' . $furnizor);
            $message = 'CSV generated for: '.$furnizor . ', has been deleted!';
        } else {
            $error = "Furnizor not found";
        }
        require_once 'index.phtml';
    }

    public function download()
    {
        $furnizor = isset($_GET['f']) ? $_GET['f'] : '';
        $spider = new Spider();
        if (in_array($furnizor, $spider->getResolvedHosts())) {
            $file = __DIR__ . '/import/' . $furnizor . '/new_products.csv';
            header('Content-Description: File Transfer');
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="products_'.$furnizor.'.csv"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            die();
        } else {
            $error = "Furnizor not found";
            require_once 'index.phtml';
        }
    }
}

$project = new Project();

if (isset($_COOKIE['passpider']) && $_COOKIE['passpider'] = 'uyfsb98342h839thfoibq9r4r3ht34t89hfn89wf') {
    switch ($_GET['p']) {
        case "process":
            $url = $_POST['url'];
            $project->processUrl($url);
            break;
        case "saveprod":
            $project->saveProduct($_POST);
            break;
        case "reset":
            $project->resetFurnizor($_GET['furnizor']);
            break;
        case "login":
            $project->login();
            break;
        case "download":
            $project->download();
            break;
        default:
            $project->index();
            break;
    }
} else {
    $project->login();
}