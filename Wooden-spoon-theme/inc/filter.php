<?php

require_once "../../../../wp-load.php";

// Create connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);

// Check connection
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

$sql = "
  SELECT
    p.post_title  AS title,
    p.post_name   AS name,
    m1.meta_value AS region,
    m2.meta_value AS postcode
  FROM
    " . DB_NAME . ".wp_posts p
    INNER JOIN
      " . DB_NAME . ".wp_postmeta m1
        ON
          p.id = m1.post_id
      AND
          m1.meta_key = \"map_region\"
    INNER JOIN
      " . DB_NAME . ".wp_postmeta m2
        ON
          p.id = m2.post_id
        AND
          m2.meta_key = \"postcode\"
  WHERE
    p.post_type = \"region\"";

$region_array_db = array();

if ($result = $mysqli->query($sql)) {
  while ($row = $result->fetch_assoc()) {

    if ($_GET["filter"] == "") {
      $status = "filterin";
    } elseif ($_GET["filter"] != "" && preg_match("/\b" . $_GET["filter"] . "/i", $row["postcode"])) {
      $status = "filterin";
    } else {
      $status = "filterout";
    }

    $region_array_db[$row["region"]] = array(
      "status" => $status,
      "label" => $row["title"],
      "url" => "../region/" . $row["name"],
    );
  }
  $result->close();
}

$region_array_base = array(
  "reg01" => array("status" => "inactive", "label" => false, "url" => false),
  "reg02" => array("status" => "inactive", "label" => false, "url" => false),
  "reg03" => array("status" => "inactive", "label" => false, "url" => false),
  "reg04" => array("status" => "inactive", "label" => false, "url" => false),
  "reg05" => array("status" => "inactive", "label" => false, "url" => false),
  "reg06" => array("status" => "inactive", "label" => false, "url" => false),
  "reg07" => array("status" => "inactive", "label" => false, "url" => false),
  "reg08" => array("status" => "inactive", "label" => false, "url" => false),
  "reg09" => array("status" => "inactive", "label" => false, "url" => false),
  "reg10" => array("status" => "inactive", "label" => false, "url" => false),
  "reg11" => array("status" => "inactive", "label" => false, "url" => false),
  "reg12" => array("status" => "inactive", "label" => false, "url" => false),
  "reg13" => array("status" => "inactive", "label" => false, "url" => false),
  "reg14" => array("status" => "inactive", "label" => false, "url" => false),
  "reg15" => array("status" => "inactive", "label" => false, "url" => false),
  "reg16" => array("status" => "inactive", "label" => false, "url" => false),
  "reg17" => array("status" => "inactive", "label" => false, "url" => false),
  "reg18" => array("status" => "inactive", "label" => false, "url" => false),
  "reg19" => array("status" => "inactive", "label" => false, "url" => false),
  "reg20" => array("status" => "inactive", "label" => false, "url" => false),
  "reg21" => array("status" => "inactive", "label" => false, "url" => false),
  "reg22" => array("status" => "inactive", "label" => false, "url" => false),
  "reg23" => array("status" => "inactive", "label" => false, "url" => false),
  "reg24" => array("status" => "inactive", "label" => false, "url" => false),
  "reg25" => array("status" => "inactive", "label" => false, "url" => false),
  "reg26" => array("status" => "inactive", "label" => false, "url" => false),
  "reg27" => array("status" => "inactive", "label" => false, "url" => false),
  "reg28" => array("status" => "inactive", "label" => false, "url" => false),
  "reg29" => array("status" => "inactive", "label" => false, "url" => false),
  "reg30" => array("status" => "inactive", "label" => false, "url" => false),
  "reg31" => array("status" => "inactive", "label" => false, "url" => false),
  "reg32" => array("status" => "inactive", "label" => false, "url" => false),
  "reg33" => array("status" => "inactive", "label" => false, "url" => false),
  "reg34" => array("status" => "inactive", "label" => false, "url" => false),
  "reg35" => array("status" => "inactive", "label" => false, "url" => false),
  "reg36" => array("status" => "inactive", "label" => false, "url" => false),
  "reg37" => array("status" => "inactive", "label" => false, "url" => false),
  "reg38" => array("status" => "inactive", "label" => false, "url" => false),
  "reg39" => array("status" => "inactive", "label" => false, "url" => false),
  "reg40" => array("status" => "inactive", "label" => false, "url" => false),
  "reg41" => array("status" => "inactive", "label" => false, "url" => false),
  "reg42" => array("status" => "inactive", "label" => false, "url" => false),
  "reg43" => array("status" => "inactive", "label" => false, "url" => false),
  "reg44" => array("status" => "inactive", "label" => false, "url" => false),
  "reg45" => array("status" => "inactive", "label" => false, "url" => false),
  "reg46" => array("status" => "inactive", "label" => false, "url" => false),
  "reg47" => array("status" => "inactive", "label" => false, "url" => false),
  "reg48" => array("status" => "inactive", "label" => false, "url" => false),
  "reg49" => array("status" => "inactive", "label" => false, "url" => false),
  "reg50" => array("status" => "inactive", "label" => false, "url" => false),
  "reg51" => array("status" => "inactive", "label" => false, "url" => false),
);

$ret_array = array_merge($region_array_base, $region_array_db);

$label = array();
foreach ($ret_array as $key => $row) {
  $label[$key] = $row['label'];
}
array_multisort($label, SORT_ASC, $ret_array);

header('Content-Type: application/json');
echo (json_encode($ret_array));
