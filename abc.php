<?php

use App\Models\Social\MProduct;

$gtin = [];

if (($handle = fopen("f4.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle)) !== FALSE) {
        $gtin[] = $data[0];
    }
    fclose($handle);
}

echo count($gtin);

$mproducts = MProduct::select(['gtin_code', 'scan_count', 'view_count', 'like_count', 'vote_count', 'vote_average', 'comment_count'])->whereIn('gtin_code', $gtin)->get();

$fp = fopen('file4.csv', 'w');
fputcsv($fp, ['ID', 'GTIN', 'scan_count', 'view_count', 'like_count', 'vote_count', 'vote_average', 'comment_count']);

foreach ($mproducts as $mp) {
    fputcsv($fp, $mp->toArray());
    unset($gtin[array_search($mp->gtin_code, $gtin)]);
}
foreach ($gtin as $g) {
    fputcsv($fp, ['', $g, 0, 0, 0, 0, 0, 0]);
}

fclose($fp);

exit(0);
