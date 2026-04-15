<?php

$storage_1 = ['Bow','Axe','Sword','Potion','Potion','Beef'];
$storage_2 = ['Knife','Arrow','Rope'];
// $storage_1 = array_merge($storage_1,$storage_2);
array_push($storage_2, 'Bandage','Arrow','Arrow');
// $storage_2 = array_unique($storage_2);
array_unshift($storage_1,'Lighter');
array_pop($storage_1);
array_shift($storage_2);
shuffle($storage_1);

// sort($storage_1);
// rsort($storage_2);

echo "===== Storage 1 =====\n";
print_r($storage_1);
echo "===== Storage 2 =====\n";
print_r($storage_2);

$total_str1 = count($storage_1);
$total_str2 = count($storage_2);
$total_str = $total_str1 + $total_str2;
// echo "Total Item In Storage 1 = " . $total_str1 . "\n";
// echo "Total Item In Storage 2 = " . $total_str2 . "\n";
echo "Total Item In Storage = " . $total_str . "\n";

// echo $storage_1[0]."\n";
// echo $storage_2[2]."\n";

// $location = array_search('Sword',$storage_1);
// echo $location;

// $loot = array_slice($storage_1,0,2);
// print_r($loot);

if (is_array($storage_1)) {
    echo "Array\n";
} else {
    echo "Bukan Array\n";
}

// shuffle($storage_1);
// shuffle($storage_2);

// $min = min(count($storage_1), count($storage_2));

// for ($i = 0; $i < $min; $i++) {
//     if (rand(0, 1)) {
//         $temp = $storage_1[$i];
//         $storage_1[$i] = $storage_2[$i];
//         $storage_2[$i] = $temp;
//     }
// }
?>  