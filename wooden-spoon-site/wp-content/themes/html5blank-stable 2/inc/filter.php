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
  "reg01" => ["status" => "inactive", "label" => false, "url" => false],
  "reg02" => ["status" => "inactive", "label" => false, "url" => false],
  "reg03" => ["status" => "inactive", "label" => false, "url" => false],
  "reg04" => ["status" => "inactive", "label" => false, "url" => false],
  "reg05" => ["status" => "inactive", "label" => false, "url" => false],
  "reg06" => ["status" => "inactive", "label" => false, "url" => false],
  "reg07" => ["status" => "inactive", "label" => false, "url" => false],
  "reg08" => ["status" => "inactive", "label" => false, "url" => false],
  "reg09" => ["status" => "inactive", "label" => false, "url" => false],
  "reg10" => ["status" => "inactive", "label" => false, "url" => false],
  "reg11" => ["status" => "inactive", "label" => false, "url" => false],
  "reg12" => ["status" => "inactive", "label" => false, "url" => false],
  "reg13" => ["status" => "inactive", "label" => false, "url" => false],
  "reg14" => ["status" => "inactive", "label" => false, "url" => false],
  "reg15" => ["status" => "inactive", "label" => false, "url" => false],
  "reg16" => ["status" => "inactive", "label" => false, "url" => false],
  "reg17" => ["status" => "inactive", "label" => false, "url" => false],
  "reg18" => ["status" => "inactive", "label" => false, "url" => false],
  "reg19" => ["status" => "inactive", "label" => false, "url" => false],
  "reg20" => ["status" => "inactive", "label" => false, "url" => false],
  "reg21" => ["status" => "inactive", "label" => false, "url" => false],
  "reg22" => ["status" => "inactive", "label" => false, "url" => false],
  "reg23" => ["status" => "inactive", "label" => false, "url" => false],
  "reg24" => ["status" => "inactive", "label" => false, "url" => false],
  "reg25" => ["status" => "inactive", "label" => false, "url" => false],
  "reg26" => ["status" => "inactive", "label" => false, "url" => false],
  "reg27" => ["status" => "inactive", "label" => false, "url" => false],
  "reg28" => ["status" => "inactive", "label" => false, "url" => false],
  "reg29" => ["status" => "inactive", "label" => false, "url" => false],
  "reg30" => ["status" => "inactive", "label" => false, "url" => false],
  "reg31" => ["status" => "inactive", "label" => false, "url" => false],
  "reg32" => ["status" => "inactive", "label" => false, "url" => false],
  "reg33" => ["status" => "inactive", "label" => false, "url" => false],
  "reg34" => ["status" => "inactive", "label" => false, "url" => false],
  "reg35" => ["status" => "inactive", "label" => false, "url" => false],
  "reg36" => ["status" => "inactive", "label" => false, "url" => false],
  "reg37" => ["status" => "inactive", "label" => false, "url" => false],
  "reg38" => ["status" => "inactive", "label" => false, "url" => false],
  "reg39" => ["status" => "inactive", "label" => false, "url" => false],
  "reg40" => ["status" => "inactive", "label" => false, "url" => false],
  "reg41" => ["status" => "inactive", "label" => false, "url" => false],
  "reg42" => ["status" => "inactive", "label" => false, "url" => false],
  "reg43" => ["status" => "inactive", "label" => false, "url" => false],
  "reg44" => ["status" => "inactive", "label" => false, "url" => false],
  "reg45" => ["status" => "inactive", "label" => false, "url" => false],
  "reg46" => ["status" => "inactive", "label" => false, "url" => false],
  "reg47" => ["status" => "inactive", "label" => false, "url" => false],
  "reg48" => ["status" => "inactive", "label" => false, "url" => false],
  "reg49" => ["status" => "inactive", "label" => false, "url" => false],
  "reg50" => ["status" => "inactive", "label" => false, "url" => false],
  "reg51" => ["status" => "inactive", "label" => false, "url" => false],
);

$ret_array = array_merge($region_array_base, $region_array_db);

header('Content-Type: application/json');
echo (json_encode($ret_array));
