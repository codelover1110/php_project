<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "frequency";
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error)
    {
        die("Connection failed: " . $conn->connect_error);
    }



    if (!empty($_REQUEST['flag'])){
        if($_REQUEST['flag'] == 'get_addModalData') {
            $tbl_array = array("tb_antennas", "tb_discoverers", "tb_modulations", "tb_groups");
            $tbl_id = array("antenna", "discoverer", "modulation", "group");
            $idx = 0;
            
            $data = "{";
            foreach ($tbl_array as $tbl_name)
            {
                $query = "select * from ".$tbl_name;
                $result = $conn->query($query);
                $rows = array();
            
                while($row = $result->fetch_assoc())
                {
                    $rows[] = $row;
                }
                $data .= "\"".$tbl_id[$idx]."\"".":".json_encode($rows).",";
                $idx++;
            }
            
            $data = rtrim($data, ",");
            $data .= "}";
            
            echo $data;
        }

        else if($_REQUEST['flag'] == 'get_editModalData') {
            $tbl_array = array("tb_antennas", "tb_discoverers", "tb_modulations", "tb_groups");
            $tbl_id = array("antenna", "discoverer", "modulation", "group");
            $idx = 0;
            
            $data = "{";
            foreach ($tbl_array as $tbl_name)
            {
                $query = "select * from ".$tbl_name;
                $result = $conn->query($query);
                $rows = array();
            
                while($row = $result->fetch_assoc())
                {
                    $rows[] = $row;
                }
                $data .= "\"".$tbl_id[$idx]."\"".":".json_encode($rows).",";
                $idx++;
            }
            
            //$data = rtrim($data, ",");
            $discovery_id = $_REQUEST['discovery_id'];
            $sql1 = "select note from tb_discovery where id = '$discovery_id'";
            $result1 = $conn->query($sql1);
            $rows = array();
            while($discovery_data = $result1->fetch_assoc())
            {
                $rows[] = $discovery_data;
            }
            $data .= "\"discovery\":".json_encode($rows)."}";
            
            echo $data;
        }

        else if($_REQUEST['flag'] == 'add_modalData') {
            $frequency = $_REQUEST['frequency'];
            $group = $_REQUEST['group'];
            $antenna = $_REQUEST['antenna'];
            $discoverer = $_REQUEST['discoverer'];
            $signal = "'".$_REQUEST['signal']."'";
            $location_name = "'".$_REQUEST['location_name']."'";
            $note = "'".$_REQUEST['note']."'";
            $modulation = $_REQUEST['modulation'];
            $frequency = $_REQUEST['frequency'];
            $date_time = "'".date('Y-m-d H:i:s')."'";
            $sql = "INSERT INTO tb_discovery (`group_id`, `frequency`, `modulation_id`, `antenna_id`, `signal`, `location_name`, `discoverer_id`, `note`, `date_time`)
            VALUES ($group, $frequency, $modulation, $antenna, $signal, $location_name, $discoverer, $note, $date_time)";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            echo $date_time;
        }

        else if($_REQUEST['flag'] == 'update_modalData') {
            $frequency = $_REQUEST['frequency'];
            $group = $_REQUEST['group'];
            $antenna = $_REQUEST['antenna'];
            $discoverer = $_REQUEST['discoverer'];
            $signal = "'".$_REQUEST['signal']."'";
            $location_name = "'".$_REQUEST['location_name']."'";
            $note = "'".$_REQUEST['note']."'";
            $modulation = $_REQUEST['modulation'];
            $frequency = $_REQUEST['frequency'];
            $date_time = "'".date('Y-m-d H:i:s')."'";
            $discovery_id = $_REQUEST['discovery_id'];
            $sql = "UPDATE tb_discovery SET `group_id`=".$group.", `frequency`=".$frequency.", `modulation_id`=".$modulation.", `antenna_id`=".$antenna.", `signal`=".$signal.", `location_name`=".$location_name.", `discoverer_id`=".$discoverer.", `note`=".$note.", `date_time`=".$date_time;
            $sql .= " WHERE `id`=".$discovery_id;
            if ($conn->query($sql) === TRUE) {
                echo "Selected record updated successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            echo $date_time;
        }

        else if($_REQUEST['flag'] == 'delete_modalData') {
            $delete_id = $_REQUEST['delete_id'];
            $sql = "DELETE FROM tb_discovery WHERE id='".$delete_id."'";
            if ($conn->query($sql) === TRUE) {
                echo "Selected record deleted successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
            
        return true;
    }
        

    $sql = "SELECT * FROM tb_discovery";
        
    $result = $conn->query($sql);

    function insertAtPosition($string, $insert, $position) {
        return implode($insert, str_split($string, $position));
    }

    function Frequency($oldString)
    {
        $oldString = strrev($oldString);
        $str_new = "";
        $array = str_split($oldString);
        $index = 0;
        foreach ($array as $char) {
            
            if($index == 3)
            {
            $str_new=$str_new.".";
            $index = 0;
            }
            $str_new = $str_new.$char;
            $index ++;
        }
        $str_new = strrev($str_new);
        return $str_new;
    }

    if ($result->num_rows > 0)
    {
        class A
        {
            public $data = array();

            function __construct()
            {
                global $result;
                global $conn;
                while ($row = $result->fetch_assoc())
                {
                    $id_antenna = $row["antenna_id"];
                    $id_modulation = $row["modulation_id"];
                    $id_group = $row["group_id"];
                    $id_discoverer = $row["discoverer_id"];

                    $sql1 = "select antenna from tb_antennas where id = '$id_antenna'";
                    $result1 = $conn->query($sql1);
                    $antenna_data = $result1->fetch_assoc();
                    if($antenna_data)
                        $antenna = $antenna_data["antenna"];
                    else
                        $antenna = "null";
                    
                    $sql1 = "select discoverer from tb_discoverers where id = '$id_discoverer'";
                    $result1 = $conn->query($sql1);
                    $discoverer_data = $result1->fetch_assoc();
                    if($discoverer_data)
                        $disoverer = $discoverer_data["discoverer"];
                    else
                        $disoverer = "null";
                    
                    $sql1 = "select group_name from tb_groups where id = '$id_group'";
                    $result1 = $conn->query($sql1);
                    $group_data = $result1->fetch_assoc();
                    if($group_data)
                        $group = $group_data["group_name"];
                    else
                        $group = "null";
                    
                    $sql1 = "select modulation from tb_modulations where id = '$id_modulation'";
                    $result1 = $conn->query($sql1);
                    $modulation_data = $result1->fetch_assoc();
                    if($modulation_data)
                        $modulation = $modulation_data["modulation"];
                    else
                        $modulation = "null";


                    //// frequency 
                    $freq = Frequency($row["frequency"]);

                    array_push($this->data, new B($row["id"], $freq, $modulation, $group, $antenna, $row["signal"], $row["location_name"], $disoverer, $row["note"], $row["date_time"]));

                    //array_push($this->data, new B($row["id"], $freq, $row["modulation_id"], $row["group_id"], $row["antenna_id"], $row["signal"], $row["location_name"], $row["discoverer_id"], $row["note"], $row["date_time"]));
                    
                }
            }
        }

        class B
        {

            public $id;
            public $frequency;
            public $modulation_id;
            public $group_id;
            public $antenna_id;
            public $signal;
            public $location_name;
            public $discoverer_id;
            public $note;
            public $date_time;

            function __construct($id, $frequency, $modulation_id, $group_id, $antenna_id, $signal, $location_name, $discoverer_id, $note, $date_time)
            {
                $this->id = $id;
                $this->frequency = $frequency;
                $this->modulation_id = $modulation_id;
                $this->group_id = $group_id;
                $this->antenna_id = $antenna_id;
                $this->signal = $signal;
                $this->location_name = $location_name;
                $this->discoverer_id = $discoverer_id;
                $this->note = $note;
                $this->date_time = $date_time;
            }
        }

        echo json_encode(new A);
    }
    else
    {
        echo json_encode(array());
    }

    $conn->close();
?>
