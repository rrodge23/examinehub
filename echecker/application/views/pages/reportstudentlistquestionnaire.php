<?php
   /*
   echo "<pre>";
   print_r($data);
   echo "</pre>";
   */
   
?>


<?php 
    if(isset($data[0]['subject_code'])){ 
?>
<h6><i><b>SUBJECT CODE :</b> <?=$data[0]['subject_code'];?></i></h6>
<h6><i><b>DESCRIPTION :</b> <?=$data[0]['subject_description'];?></i></h6>
<h6><i><b>TOTAL ITEM :</b> 
<?php
    if(isset($data[0]["questionaire_total_score"])){
        echo $data[0]["questionaire_total_score"];
    }else{
        echo "0";
    }
?>
</i></h6>



<?php 
    }//dont erase this
?>
<div class="row">
<h5><b>STUDENT LIST:</b></h5>

<table id="table-studentslist" class="table table-striped" style='width:100%;'>        
    <thead>
        <tr>
            
            <td class="text-center font-roboto color-a2"></td>
            <td class="text-center font-roboto color-a2">CODE</td>
            <td class="text-center font-roboto color-a2">NAME</td>
            <td class="text-center font-roboto color-a2">COURSE</td>
            <td class="text-center font-roboto color-a2">SCORE</td>
            <td class="text-center font-roboto color-a2">PERCENTAGE</td>
            <td class="text-center font-roboto color-a2">REMARK</td>
            <td class="text-center font-roboto color-a2">ACTION</td>
        </tr>
    </thead>
    <tbody class="student-list-tablebody">
        <?php
            if($data){
                foreach($data as $key => $u){
                  
                    $id = $u['UID'];
                    $idsubject = $u["idsubject"];
                    $code = $u['code'];
                    $firstname = $u['firstname'];
                    $middlename = $u['middlename'];
                    $lastname = $u['lastname'];
                    $course = $u['course_name'];
                    $user_level = $u['user_level'];
                    $score = $u['user_total_score'];
                    $idquestionaire = $u["idquestionaire"];
                    $image = (($u['image'] == "") ? "default.png" : $u['image']);
                    if($idquestionaire != "0"){
                        if(isset($data[0]["questionaire_total_score"])){
                            if($score != "" || $score != null){
                                $percentage = number_format(((($score)/($data[0]["questionaire_total_score"]))*80)+(20),2);
                                if($percentage >= '75'){
                                    $remark = "Passed";
                                }else{
                                    $remark = "failed";
                                }
                            }else{
                                $percentage = "0";
                                $remark = "Invalid";
                            }
                        }else{
                            $percentage = "0";
                            $remark = "Invalid";
                        }
                    }else{
                        $percentage = "0";
                        $remark = "Invalid";
                    }
                    
                    if($user_level == '1'){
                        echo "
                            <tr>
                                <td class='text-center'><img src='assets/uploads/" . $image .  "' style='height:100px;width:100px;margin:5px;'></td>
                                <td class='text-center'>$code</td>
                                <td class='text-center'>$lastname, $firstname $middlename</td>
                                <td class='text-center'>$course</td>
                                <td class='text-center'>$score</td>
                                <td class='text-center'>$percentage%</td>
                                <td class='text-center'>$remark</td>
                                <td class='text-center'>";
                                if($idquestionaire == "0"){
                                    echo "
                                    <form action='reports/reportstudentquestionnaireinfo' id='frm-reportstudentquestionnaireinfo$id'>
                                        <input type='hidden' name='idquestionaire' value='$idquestionaire'>
                                        <input type='hidden' name='idusers' value='$id'>
                                        <button disabled data-toggle='tooltip' data-placement='top' title='Unable to view, havent take examination yet.' class='btn-view-student-subject-questionnaires btn btn-danger' type='submit' form='frm-reportstudentquestionnaireinfo$id'>
                                            <i class='material-icons'>close</i>
                                        </button>
                                    </form>";
                                }else{
                                    echo "<div>
                                    <form action='reports/reportstudentquestionnaireinfo' id='frm-reportstudentquestionnaireinfo$id'>
                                        <input type='hidden' name='idquestionaire' value='$idquestionaire'>
                                        <input type='hidden' name='idusers' value='$id'>
                                        <button data-toggle='tooltip' data-placement='top' title='View Questionnaire' class='btn-view-student-subject-questionnaires btn btn-info' type='submit' form='frm-reportstudentquestionnaireinfo$id'>
                                            <i class='material-icons'>remove_red_eye</i>
                                        </button>
                                    </form>";
                                    
                                    if($_SESSION["users"]["user_level"] == "2"){

                                        if(in_array($_SESSION["users"]["idusers"],$data[0]["teachersID"])){
                                            echo "
                                            <form id='frm-retakeexamination$id' onsubmit='return false;'>
                                                <input type='hidden' name='idquestionaire' value='$idquestionaire'>
                                                <input type='hidden' name='idusers' value='$id'>
                                                <button data-toggle='tooltip' data-placement='top' title='Retake Examination' class=' btn btn-success btn-retakeexamination' type='submit' form='frm-retakeexamination$id'>
                                                    <i class='material-icons'>refresh</i>
                                                </button>
                                            </form>";
                                        }
                                        
                                    }
                                    
                                    echo    "</div>";
                                }
                                    
                        echo "  </td>
                            </tr>
                        ";
                    }
                }
            }
         ?>
     </tbody>
</table>
</div>
<div class="row">
    
</div>