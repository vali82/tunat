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
        'www.facsrl.com' => 'FAC',
        'www.ultramaster.ro' => 'ULM',
        'www.vonmag.ro' => 'VMG'
    ];

    public function __construct()
    {
    }

    private function normalizeSKU($sku)
    {
        $result = preg_replace("/[^a-zA-Z0-9]+/", "-", $sku);
        return $result;
    }

    private function trutzi($page)
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
//                    $data['name'] = $h1->nodeValue;
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
                                $data['sku'] = $this->normalizeSKU($span->nodeValue).$this->skuSuffix;
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

    private function ultramaster($page)
    {
        $error = '';
        $data = [
            'sku' => '',
            'name' => '',
            'descr' => '',
            'price' => '',
            'images' => [],
            'docs' => [],
            'brand' => ''
        ];
        $imageContent = null;

        $html = new DOMDocument();
        @$html->loadHtmlFile($page);
        $xpath = new DOMXPath($html);

        // get name
        $nodelist = $xpath->query("//h1[@class='ty-product-block-title']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                $data['name'] = $n->nodeValue;
            }
        }
        if ($data['name'] == '') {
            $error = "name not found";
        }

        // get description
        $nodelist = $xpath->query("//div[@id='tabs_content']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('div') as $div) {
                    if ($div->getAttribute('id') == 'content_description') {
                        $descr = str_replace(
                            [
                                'ty-wysiwyg-content content-description',
                                ' id="content_description"'
                            ],
                            ['description-ulm', ''],
                            trim(str_replace("\n", "", $div->C14N()))
                        );
                        $images = strip_tags($descr, '<img>');
                        if (strpos($images, '<img') !== false) {
                            $imageContent = explode('"', explode('src="', $images, 2)[1])[0];
                        }
                        $descr = strip_tags($descr, '<div><p><ul><li><h1><h2><h3><h4><h5><h6><span><strong><i><em><table><tr><td>');

                        $data['descr'] = $descr;
                    } elseif ($div->getAttribute('id') == 'content_attachments' && $div->getAttribute('class') == 'attachments') {
                        foreach ($div->getElementsByTagName('p') as $p) {
                            if ($p->getAttribute('class') == 'attachment__item') {
                                $fileName = explode(',', explode(' (', $p->nodeValue, 2)[1], 2)[0];
                                $dlink = $p->getElementsByTagName('a');
                                if ($dlink->length > 0) {
                                    foreach ($dlink as $elem) {
                                        $data['docs'][] = [
                                            'name' => $fileName,
                                            'url' => $elem->getAttribute('href')
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($data['descr'] == '') {
            $error = "descr not found";
        }

        // get price
        $nodelist = $xpath->query("//span[@class='ty-price']");
        if ($nodelist->length > 0) {
            $prc = null;
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('span') as $span) {
                    if ($span->getAttribute('class') == 'ty-price-num' && $prc == null) {
                        $prc = $span->nodeValue;
                        $data['price'] = str_replace([',', ' '], ['', ''], explode(' lei', $prc)[0]);
                    }
                }
            }
        }
        if ($data['price'] == '') {
            $error = "price not found";
        }

        // get sku
        $nodelist = $xpath->query("//div[@class='ty-control-group ty-sku-item cm-hidden-wrapper']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('span') as $span) {
                    $data['sku'] = $this->normalizeSKU($span->nodeValue);
                }
            }
        }
        if ($data['sku'] == '') {
            $error = "sku not found";
        }

        // get pictures
        $nodelist = $xpath->query("//div[@class='ty-product-img cm-preview-wrapper']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('a') as $a) {
                    if ($a->getAttribute('class') == 'jqzoom' || 1==1) {
                        $data['images'][] = $a->getAttribute('href');
                    }
                }
            }
        }
        if ($imageContent !== null) {
            $data['images'][] = $imageContent;
        }
        if (count($data['images']) == 0) {
            $error = "images not found";
        }

        // brand
        $nodelist = $xpath->query("//div[@class='brand']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                $data['brand'] = trim($n->nodeValue);
            }
        }
        /*if ($data['brand'] == '') {
            $error = "brand not found";
        }*/

        $data['error'] = $error;

        return $data;
    }

    private function vonmag($page)
    {
        $error = '';
        $data = [
            'sku' => '',
            'name' => '',
            'descr' => '',
            'price' => '',
            'images' => [],
            'docs' => [],
            'brand' => ''
        ];
        $imageContent = null;

        $html = new DOMDocument();
        @$html->loadHtmlFile($page);
        $xpath = new DOMXPath($html);

        // get name
        $nodelist = $xpath->query("//h1[@class='pagetitle']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                $data['name'] = $n->nodeValue;
            }
        }
        if ($data['name'] == '') {
            $error = "name not found";
        }

        // get description
        $nodelist = $xpath->query("//div[@id='descriere']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('div') as $div) {
                    if ($div->getAttribute('class') == 'product_info_h') {
                        $descr = str_replace(
                            ['product_info_h'],
                            ['description-vmg'],
                            trim(str_replace("\n", "", $div->C14N()))
                        );
//                        $descr = trim(str_replace("\n", "", $div->C14N()));
                        $descr = strip_tags($descr, '<div><p><ul><li><h1><h2><h3><h4><h5><h6><span><strong><b><i><em><table><tr><td><br>');
                        $data['descr'] = $descr;
                    }
                }
            }
        }
        if ($data['descr'] == '') {
            $error = "descr not found";
        }

        // get price
        $nodelist = $xpath->query("//div[@class='pricerow']");
        if ($nodelist->length > 0) {
            $prc = null;
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('span') as $span) {
                    if ($prc == null) {
                        $prc = $span->nodeValue;
                        $data['price'] = (float)str_replace(['.', ' '], ['', ''], explode(' lei', $prc)[0]);
                    }
                }
            }
        }
        if ($data['price'] == '') {
            $error = "price not found";
        }

        // get sku
        $nodelist = $xpath->query("//div[@class='p-model']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('span') as $span) {
                    $data['sku'] = $this->skuSuffix . '-' .$this->normalizeSKU($span->nodeValue);
                }
            }
        }
        if ($data['sku'] == '') {
            $error = "sku not found";
        }

        // get pictures
        $nodelist = $xpath->query("//div[@class='imgdetails']");
        if ($nodelist->length > 0) {
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('a') as $a) {
                    if ($a->getAttribute('class') == 'jqzoom' || 1==1) {
                        $data['images'][] = 'https://www.vonmag.ro'.$a->getAttribute('href');
                    }
                }
            }
        }
        if ($imageContent !== null) {
            $data['images'][] = $imageContent;
        }
        if (count($data['images']) == 0) {
            $error = "images not found";
        }

        // brand
        $nodelist = $xpath->query("//div[@class='p-related']");
        if ($nodelist->length > 0) {
            $brand = null;
            foreach ($nodelist as $n) {
                foreach ($n->getElementsByTagName('img') as $img) {
                    if ($brand == null) {
                        $brand = $img->getAttribute('alt');
                        $data['brand'] = $brand;
                    }
                }
            }
        }
        /*if ($data['brand'] == '') {
            $error = "brand not found";
        }*/

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

        switch ($this->host) {
            case "www.trutzi.ro":
                $data = $this->trutzi($page);
                break;
            case "www.ultramaster.ro":
                $data = $this->ultramaster($page);
                break;
            case "www.vonmag.ro":
                $data = $this->vonmag($page);
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
        "dimensiune_placa" => "Dimensiune Placa(mm) - dimensiune_placa",
        "greutate_max_poarta" => "Greutate Max Poarta - greutate_max_poarta",
        "grosime_mm" => "Grosime(mm) - grosime_mm",
        "lungime_m" => "Lungime(m) - lungime_m",
        "latime_mm" => "Latime(mm) - latime_mm",
        "inaltime_mm" => "Inaltime(mm) - inaltime_mm",
        "lungime_mm" => "Lungime(mm) - lungime_mm",
        "lungime_poarta_m" => "Lungime Poarta(m) - lungime_poarta_m",
        "deschidere_max_poarta_m" => "Deschidere Max Poarta(m) - deschidere_max_poarta_m",
        "kit_automatizare" => "Kit Automatizare",
        "lungime_bariera_m" => "Lungime Bariera(m) - lungime_bariera_m",
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

        $structure = __DIR__ . '/import/' . $data['skuSuffix'];
        if (!is_dir($structure)) {
            mkdir($structure, 0777, true);
            chmod($structure, 0777);
        }
        if (!is_dir($structure.'/images')) {
            mkdir($structure.'/images', 0777, true);
            chmod($structure.'/images', 0777);
        }
        if (!is_dir($structure.'/docs')) {
            mkdir($structure.'/docs', 0777, true);
            chmod($structure.'/docs', 0777);
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
                $imgCnt = 0;
                foreach ($data['images'] as $image) {
                    if ($image != '') {
                        $content = file_get_contents($image);
                        $imageFileName = $data['sku'] . ($imgCnt > 0 ? '_' . $imgCnt : '') . '.jpg';
                        file_put_contents($structure . '/images/' . $imageFileName, $content);
                        if ($imgCnt > 0) {
                            $imageGallery[] = $imageFileName;
                        } else {
                            $firstImage = $data['sku'] . '.jpg';
                        }
                        $imgCnt++;
                    }
                }

                if (count($data['docs']) > 0) {
                    $docsHtml = [];
                    foreach ($data['docs']['name'] as $k => $fileName) {
                        if ($fileName != '') {
                            $content = file_get_contents($data['docs']['url'][$k]);
                            $imageFileName = $fileName;
                            file_put_contents($structure . '/docs/' . $imageFileName, $content);
                            $docsHtml[] = '' .
                                '<p>Descarca manual instructiuni: ' .
                                '<a class="product-brosure-download" target="_blank" href="/media/wysiwyg/' . $fileName . '">' .
                                $fileName .
                                '</a></p>';
                        }
                    }
                    if (count($docsHtml)) {
                        $data['descr'] = '<div class="brosure-container">'.implode('', $docsHtml).'</div>' .
                            $data['descr'];
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
                $csvRow['categories'] = 'Categorii/'.$data['category'];
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
            header('Content-Disposition: attachment; filename="products_'.$furnizor.'_'.date('Y-m-d_H-i').'.csv"');
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