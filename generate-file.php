<?php
require('barcode.php');

$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);

$json = file_get_contents("doc/prepare.json");
$array = json_decode($json, true);
$phase = $queries['phase'];

for ($arr = 0; $arr < count($array); $arr++) {
    $slim = ($array[$arr]['price']) ? false : true;

    $barcode = new Barcode(
        $array[$arr]['ean'],
        4,
        $slim
    );
    ob_start();
    $barcode->display();
    $barcodeImg = ob_get_clean();
    $fileTitle =
        $array[$arr]['description'] . '_' . $array[$arr]['style'] . '_' . $array[$arr]['colour'] . '_' . $array[$arr]['size'] . '_' . $array[$arr]['ean'];
    $replacedTitle = str_replace(' ', '_', $fileTitle);
    ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title><?php echo $replacedTitle; ?></title>
    <style>
    @media print {
        body {
            border: 1px solid lightgrey;
            width: 21cm;
            height: 100%;
            margin: 0;
            /* change the margins as you want them to be. */
        }
    }

    body {
        font-family: "Arial Narrow", Arial, sans-serif;
        font-size: 12px;
        line-height: 1.2;
        width: 21cm;
        height: 100%;
        margin: 0;
    }

    .a4 {
        border: 1px solid grey;
        font-size: 10px;
        line-height: 1.2;
        width: 21cm;
        height: 29.7cm;
    }

    .rectangle {
        border: 1px solid black;
        margin-right: 8px;
        width: 200px;
        /* ini ukuran yg fix kondisi 1*/
        /* width: 225px; */
        /* ini ukuran nama kepanjangan */
    }

    /* .rectangle:not(:last-child) {
        margin-right: 8px;
    } */

    .barcode-wrapper-no-price {
        position: relative;
        top: -50px;
        /* ini ukuran yg fix kondisi 1*/
        /* top: -56px; */
        /* ini ukuran nama kepanjangan */
        z-index: -1;
        margin-bottom: -50px;
        /* ini ukuran yg fix kondisi 1*/
        /* margin-bottom: -56px; */
        /* ini ukuran nama kepanjangan */
        margin-left: -5px;
    }

    .barcode {
        width: 110px;
    }

    .barcode-no-price {
        width: 185px;
    }

    .price {
        font-size: 18px;
    }

    .article {
        font-size: 14px;
    }
    </style>
</head>

<body class="p-1">
    <div class="">
        <div class="mb-2">
            <?php echo $replacedTitle; ?>
        </div>
        <div class="d-flex flex-wrap justify-content-center">
            <?php
                for ($i = 0; $i < $queries['count']; $i++) {
                    if (!$array[$arr]['price']) {
                ?>
            <div class="rectangle p-2 mb-2">
                <div class="d-flex flex-column">
                    <div class="head d-flex flex-column text-center pb-1">
                        <div class="title">
                            <?php echo $array[$arr]['description']; ?>
                        </div>
                        <div class="vendor">
                            <?php echo $array[$arr]['style']; ?>
                        </div>
                        <div class="colour">
                            <?php echo $array[$arr]['colour']; ?>
                        </div>
                        <div class="size">
                            <?php echo $array[$arr]['size']; ?>
                        </div>
                    </div>
                    <div class="barcode-wrapper-no-price d-flex justify-content-center">
                        <img class="barcode-no-price"
                            src="<?php echo 'data:image/png;base64,' . base64_encode($barcodeImg); ?>" />
                    </div>
                </div>
            </div>
            <?php
                    } else {
                    ?>
            <div class="rectangle p-2 pb-3 mb-2">
                <div class="d-flex flex-column">
                    <div class="head d-flex flex-column text-center pb-1">
                        <div class="title">
                            <?php echo $array[$arr]['description'] . ', ' . $array[$arr]['colour'] . ', ' . $array[$arr]['size']; ?>
                        </div>
                        <div class="style">
                            <?php echo $array[$arr]['style']; ?>
                        </div>
                    </div>
                    <div class="barcode-wrapper d-flex justify-content-start">
                        <img class="barcode"
                            src="<?php echo 'data:image/png;base64,' . base64_encode($barcodeImg); ?>" />
                        <div
                            class="description text-left d-flex flex-column justify-content-center align-items-start w-80 ml-2">
                            <div class="price font-weight-bold">$
                                <?php echo $array[$arr]['price']; ?>
                            </div>
                            <div class="article">
                                <?php echo $array[$arr]['article']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    }
                }
                ?>
        </div>
    </div>
</body>

</html>
<?php
    file_put_contents($fileTitle . '.html', ob_get_contents());
}
?>