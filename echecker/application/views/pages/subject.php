<?php
  
?>
<button data-toggle='tooltip' data-placement='top' title='Add Subject' class='pull-right btn-add-subject btn btn-success' type='button' name='create' onclick='return false;'>
    <i class='material-icons'>add</i>
</button>
<table id="table-subjectList" class="table table-striped">        
    <thead>
        <tr>
            
            <td class="text-center font-roboto color-a2">SUBJECT CODE</td>
            <td class="text-center font-roboto color-a2">DESCRIPTION</td>
            <td class="text-center font-roboto color-a2">UNITS</td>
            <td class="text-center font-roboto color-a2">SCHEDULE</td>
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
                $schedule = $subj['schedule_code'];
                
            
            echo "
                <tr>
                    
                    <td class='text-center font-roboto color-a2'>$code</td>
                    <td class='text-center font-roboto color-a2'>$description</td>
                    <td class='text-center font-roboto color-a2'>$units</td>
                    <td class='text-center font-roboto color-a2' id='sample'>$schedule</td>
                    <td class='text-center font-roboto color-a2'>
                        
                        <button data-id='$id' data-toggle='tooltip' data-placement='top' title='Update' class='btn-update-subject btn btn-success' type='button' name='update' onclick='return false;'>
                            <i class='material-icons'>update</i>
                        </button>
                        <button data-id='$id' data-code='$code' rel='tooltip' data-original-title='View' class='mdl-btn-view-classes-subject btn btn-info' type='button' name='view'>
                            <i class='material-icons'>remove_red_eye</i>
                        </button>
                        <button href='subjects/deletesubject' data-id='$id' data-toggle='tooltip' data-placement='top' title='Delete' class='btn-delete-subject btn btn-danger' type='submit' name='deleteSubject' onclick='return false;'>
                            <i class='material-icons'>delete</i>
                        </button>
                    
                    </td>
                </tr>
                ";
            }
        ?>
            
    </tbody>
</table>