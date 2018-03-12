
<?php
  if($_SESSION['users']['user_level'] != "99"){
   
?>

<div class="user-subject-list" style="margin-top:50px;">
    <span class="category" style="font-size:20px;">SUBJECT LIST:</span>
    <div style="margin-top:25px;">
        <table id="table-subjectList" class="table table-striped">        
            <thead>
                <tr>
                    
                    <td class="text-center font-roboto color-a2">SUBJECT CODE</td>
                    <td class="text-center font-roboto color-a2">DESCRIPTION</td>
                    <td class="text-center font-roboto color-a2">UNITS</td>
                    <td class="text-center font-roboto color-a2">TIME</td>
                    <td class="text-center font-roboto color-a2">ACTION</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($data as $subj){
                        $id = $subj['idsubject'];
                        $code = $subj['subject_code'];
                        $description = $subj['subject_description'];
                        $units = $subj['units'];
                        $time_start = $subj['time_start'];
                        $time_end = $subj['time_end'];
                    
                    echo "
                        <tr>
                            
                            <td class='text-center font-roboto color-a2'>$code</td>
                            <td class='text-center font-roboto color-a2'>$description</td>
                            <td class='text-center font-roboto color-a2'>$units</td>
                            <td class='text-center font-roboto color-a2' id='sample'>$time_start-$time_end</td>
                            <td class='text-center font-roboto color-a2'>
                            <a data-toggle='tooltip' data-placement='top' title='Questionnaire List' class='btn btn-success' href='examinations/userquestionairelist/$id'>
                                <i class='material-icons'>list</i>
                            </a>";
                    if($_SESSION['users']['user_level'] == "2"){
                        
                        echo "<a data-toggle='tooltip' data-placement='top' title='Student List' class='btn btn-warning' href='examinations/subjectclassinformation/$id' style='width:77px;'>
                                <i class='material-icons'>person_box</i>
                            </a>"; 
                    }    
                        echo "<a data-toggle='tooltip' data-placement='top' title='Examination History' class='btn btn-info' href='reports/questionnairelistreports/$id'>
                            <i class='material-icons'>history</i>
                        </a>";           
                    echo "  </td>
                        </tr>
                        ";       
                            
                    }
                ?>
                    
            </tbody>
        </table>
    </div>
</div> <!-- end user sujbect list div -->
<?php
  }//end if condition for !admin
?>