<?php

$db_handle = new SQLite3("relic_db.db");
$json_folderpath = "../relics-json/";
$max_file_no = 100000;


function adjust_to_query(&$str) {
    if($str === "") $str = "NULL";
    else $str = "'$str'";
}

function print_msg($msg) {
    echo ($GLOBALS["folderless_filename"]).": ".$msg."\n";
}

function add_to_db($db_handle, $decoded_object, $parent_str) {
    $relic_id = $decoded_object->id;
    $relic_name_pre = "$parent_str".($decoded_object->identification);
    $relic_name = $relic_name_pre; adjust_to_query($relic_name);
    $relic_dating = $decoded_object->dating_of_obj; adjust_to_query($relic_dating);
    $relic_street = $decoded_object->street; adjust_to_query($relic_street);
    $relic_desc = $decoded_object->description; adjust_to_query($relic_desc);
    $relic_reg_no = $decoded_object->register_number; adjust_to_query($relic_reg_no);
    $place_id = $decoded_object->place_id;
    $descendants = $decoded_object->descendants;
    
    if(NULL === ($db_handle->querySingle("SELECT place_id FROM place WHERE place_id == $place_id;"))) {
        print_msg("Found a new place");
        $place_name = $decoded_object->place_name;
        if($place_name == "") {
            print_msg("Place has empty name");
            return;
        }
        adjust_to_query($place_name);
        $commune_name = $decoded_object->commune_name; adjust_to_query($commune_name);
        $district_name = $decoded_object->district_name; adjust_to_query($district_name);
        $voivodeship_name = $decoded_object->voivodeship_name; adjust_to_query($voivodeship_name);
        $db_handle->exec("INSERT INTO place
            (place_id, place_name, commune_name, district_name, voivodeship_name) VALUES
            ($place_id, $place_name, $commune_name, $district_name, $voivodeship_name);");
    }
    
    if($relic_reg_no !== "NULL") {
        print_msg("Adding relic with id = $relic_id");
        
        $db_handle->exec("INSERT INTO relic 
        (relic_id, relic_name, relic_dating, relic_street, relic_desc, relic_reg_no, place_id) VALUES
        ($relic_id, $relic_name, $relic_dating, $relic_street, $relic_desc, $relic_reg_no, $place_id);");
    }
    
    foreach($descendants as $descendant) {
        add_to_db($db_handle, $descendant, "$relic_name_pre : ");
    }
}

for($i = 1; $i < $max_file_no; ++$i) {
    $folderless_filename = "$i.json";
    $current_filename = "$json_folderpath"."$folderless_filename";
    print_msg("Analyzing $current_filename");
    if(!file_exists($current_filename)) {
        print_msg("File '$current_filename' NOT found");
    }
    else {
        print_msg("File $current_filename found");
        $file_content = file_get_contents($current_filename);
        $decoded_object = json_decode($file_content);
        add_to_db($db_handle, $decoded_object, "");
    }
}


?>