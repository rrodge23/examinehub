
<?php
  if($_SESSION['users']['user_level'] != "99"){
    /*
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    */
    
?>

<div class="user-subject-list" style="margin-top:50px;">

    <?php 
        if($data["data"]){
            if(isset($data["data"][0]["subject_code"]) && isset($data["data"][0]["subject_description"])){
                echo '<div class="col-md-12" style="padding-left:0px;margin-bottom:50px;">
                    <div class="row">
                        <span class="roboto category col-md-3">SUBJECT CODE</span><span class="roboto">:'.$data["data"][0]["subject_code"].'</span>
                    </div>
                    <div class="row">
                            <span class="roboto category col-md-3">SUBJECT DESCRIPTION</span><span class="roboto">:'.$data["data"][0]["subject_description"].'</span>
                    </div></div>';
            }
        }
        if($_SESSION['users']['user_level'] == "2"){ 
        echo "
            <div class='row'>
                <a data-toggle='tooltip' data-placement='top' title='Add Questionnaire' class='pull-right btn btn-success' type='button' name='create' href='examinations/addQuestionaire/".$data["idsubject"]."'>
                    <i class='material-icons'>add</i>
                </a>
            </div>
            ";
        }
    ?>
    
    <div class="row" style="margin-top:50px;">
        <span class="brand roboto" style="font-size:20px;">QUESTIONNAIRE LIST:</span>
    </div>

    <div class="row">
        <table id="table-subjectList" class="table table-striped">        
            <thead>
                <tr>
                    
                    <td class="text-center font-roboto color-a2">CODE</td>
                    <td class="text-center font-roboto color-a2">DESCRIPTION</td>
                    <td class="text-center font-roboto color-a2">DATE</td>
                    <td class="text-center font-roboto color-a2">TIME</td>
                    <?php
                        if($_SESSION['users']['user_level'] == "2"){
                            echo '<td class="text-center font-roboto color-a2">STATUS</td>';
                        }
                    ?>
                    
                    <td class="text-center font-roboto color-a2">ACTION</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    if($data["data"]){
                        
                        foreach($data["data"] as $key=>$questionaire){
                        
                                $id = $questionaire['idquestionaire'];
                                $title = $questionaire['questionaire_title'];
                                $description = $questionaire['questionaire_description'];
                                $date = $questionaire['questionaire_date'];
                                $time = $questionaire['questionaire_time'];
                                $status = $questionaire['questionaire_status'];
                                if(($status == "waiting for confirmation" || $status == "unapproved") && ($_SESSION["users"]["user_level"] == "1")){
                                    continue;
                                }
                                date_default_timezone_set('Asia/Manila');
                                $datetime = DateTime::createFromFormat('m-d-y',$date);
                                $datetimenow = DateTime::createFromFormat('m-d-y',Date('m-d-y'));
                                if($datetime != $datetimenow && $_SESSION["users"]["user_level"] == "1"){
                                    continue;
                                }
                                if($datetime == $datetimenow && $_SESSION["users"]["user_level"] == "1"){
                                    $datetimeTime = DateTime::createFromFormat('H:i',Date($time));
                                    $datetimeTimeNow = DateTime::createFromFormat('H:i',Date('H:i'));
                                    if($datetimeTime > $datetimeTimeNow){
                                        continue;
                                    }
                                }

                            echo "
                                <tr>
                                    
                                    <td class='text-center font-roboto color-a2'>$title</td>
                                    <td class='text-center font-roboto color-a2'>$description</td>
                                    <td class='text-center font-roboto color-a2'>$date</td>
                                    <td class='text-center font-roboto color-a2'>$time</td>";
                                    if($_SESSION['users']['user_level'] == "2"){
                                        echo "<td class='text-center font-roboto color-a2'>$status</td>";
                                    }
                                    
                            echo        "<td class='text-center font-roboto color-a2'>
                                        
                                    <form action='examinations/userquestionairelist' method='POST'>
                                        <input type='hidden' value=$id name='id'>";
                            if($_SESSION['users']['user_level'] == "1"){
                                echo "<a data-toggle='tooltip' data-placement='top' title='Take My Examination' class='btn-view-questionaire btn btn-info' href='examinations/examine/$id'>
                                        <i class='material-icons'>remove_red_eye</i>
                                    </a>";
                            }
                                        
                            if($_SESSION['users']['user_level'] == "2"){
                                if($status != "approved" || $_SESSION['users'][0]["position"] == "2"){
                                echo "<a href='examinations/updateQuestionnaire/$id' data-id='$id' rel='tooltip' data-toggle='tooltip' data-placement='top' title='Update' class='btn btn-info' name='update'>
                                        <i class='material-icons'>create</i>
                                    </a>";
                                }
                                echo "<button data-id='$id' data-toggle='tooltip' data-placement='top' title='Delete' class='btn-delete-questionaire btn btn-danger' type='submit' name='delete' onclick='return false;'>
                                        <i class='material-icons'>delete</i>
                                    </button>";
                            }  
        
                            echo "       </form>
                                        
                                    </td>
                                
                                </tr>
                                ";
                            
                        }
                    }
                ?>
                    
            </tbody>
        </table>
    </div>
</div> <!-- end user sujbect list div -->
<?php
  }//end if condition for !admin
?>
