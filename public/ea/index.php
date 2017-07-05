<?php

class spider
{
    private $host;
    private $url;
    private $skuSuffix = '';

    private $resolvedHosts = [
        'www.trutzi.ro',
        'www.facsrl.com'
    ];
    public function __construct($url)
    {
        $this->url = $url;
        $this->host = explode('/', str_replace(['http://', 'https://'], ['', ''], $url))[0];
    }

    private function trutzi($url)
    {
        $this->skuSuffix = 'TRT';
        $error = '';
        $data = [
            'sku' => '',
            'name' => '',
            'descr' => '',
            'price' => '',
            'images' => []
        ];

        if (file_exists('file.html') && 1==1) {
            $content = file_get_contents('file.html');
        } else {
            $content = file_get_contents($url);
            $fp = fopen('file.html', 'w');
            fwrite($fp, $content);
            fclose($fp);
        }

        $html = new DOMDocument();
        if ($this->mode == 'TEST') {
            $page = $this->pathTest . 'file.html';
        } else {
            $page = $this->pathLive . 'file.html';
        }
        $html->loadHtmlFile($page);
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

    public function grabByHost()
    {
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

}

class Project
{
    private $mode = 'TEST';
    private $pathTest = 'http://tirbox.local/ea/';

    private $attributesMagento = [
        'sku',
        'name',
        'description',
        'price',
        'categories',
        'image',
        'media_gallery',
        'qty',
        'type',
        'lungime_m',
        'greutate_kg'
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


    public function index()
    {
        $mode = 'init';
        require_once("index.phtml");
    }

    public function processUrl($url)
    {
        $mode = 'process';

        $spider = new Spider($url);
        $data = $spider->grabByHost();
        if ($data['error'] != '') {
            $error = $data['error'];
        }

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

            if (!file_exists($file)) {
                // add to csv
                $fp = fopen($file, 'a');
                $this->fputcsv2($fp, $this->attributesMagento);
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
                $csvRow = array_flip($this->attributesMagento);
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
}

$project = new Project();

switch ($_GET['p']) {
    case "process":
        $url = $_POST['url'];
        $project->processUrl($url);
        break;
    case "saveprod":
        $project->saveProduct($_POST);
        break;
    default:
        $project->index();
        break;
}