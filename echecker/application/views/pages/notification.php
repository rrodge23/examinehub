<?php
    if($_SESSION["users"][0]["position"] == "2"){
        /*echo "<pre>";
        print_r($data);
        echo "</pre>";*/
?>
<div class="title">
    <h3>Confirmation List</h3>
</div>
<table id="table-departmentlist" class="table table-striped">        
    <thead>
        <tr>
            
            <td class="text-center font-roboto color-a2">TITLE</td>
            <td class="text-center font-roboto color-a2">DESCRIPTION</td>
            <td class="text-center font-roboto color-a2">DATE</td>
            <td class="text-center font-roboto color-a2">TIME</td>
            <td class="text-center font-roboto color-a2">SUBJECT CODE</td>
            <td class="text-center font-roboto color-a2">TEACHER</td>
            <td class="text-center font-roboto color-a2">ACTION</td>

        </tr>
    </thead>
    <tbody>
        <?php
            if($data){
                foreach($data as $i => $questionnaire){
                    $id = $questionnaire['idquestionaire'];
                    $title = $questionnaire['questionaire_title'];
                    $description = $questionnaire['questionaire_description'];
                    $date = $questionnaire['questionaire_date'];    
                    $time = $questionnaire['questionaire_time'];
                    $subject = $questionnaire['subject_code'];
                    $firstname = $questionnaire['firstname'];
                    $middlename = $questionnaire['middlename'];
                    $lastname = $questionnaire['lastname'];
                    
                echo "
                    <tr>
                        
                        <td class='text-center font-roboto color-a2'>$title</td>
                        <td class='text-center font-roboto color-a2'>$description</td>
                        <td class='text-center font-roboto color-a2'>$date</td>
                        <td class='text-center font-roboto color-a2'>$time</td>
                        <td class='text-center font-roboto color-a2'>$subject</td>
                        <td class='text-center font-roboto color-a2'>$lastname, $firstname $middlename</td>
                        <td class='text-center font-roboto color-a2'>
                            
                            <a href='notifications/viewquestionnaire/$id' data-toggle='tooltip' data-placement='top' title='View Questionnaires' class='btn-view-questionnaire btn btn-info' name='view'>
                                <i class='material-icons'>remove_red_eye</i>
                            </a>
                          
                        </td>
                    </tr>
                    ";
                }
            }
        ?>
            
    </tbody>
</table>
<?php
    }else if ($_SESSION["users"][0]['position'] == "1"){//end if dean
?>
<div class="title">
    <h3>Disapproved List</h3>
</div>
<table id="table-departmentlist" class="table table-striped">        
    <thead>
        <tr>
            
            <td class="text-center font-roboto color-a2">TITLE</td>
            <td class="text-center font-roboto color-a2">DESCRIPTION</td>
            <td class="text-center font-roboto color-a2">DATE</td>
            <td class="text-center font-roboto color-a2">TIME</td>
            <td class="text-center font-roboto color-a2">SUBJECT CODE</td>
            <td class="text-center font-roboto color-a2">ACTION</td>

        </tr>
    </thead>
    <tbody>
        <?php
            if($data){
                foreach($data as $i => $questionnaire){
                    $id = $questionnaire['idquestionaire'];
                    $title = $questionnaire['questionaire_title'];
                    $description = $questionnaire['questionaire_description'];
                    $date = $questionnaire['questionaire_date'];    
                    $time = $questionnaire['questionaire_time'];
                    $subject = $questionnaire['subject_code'];
                    $firstname = $questionnaire['firstname'];
                    $middlename = $questionnaire['middlename'];
                    $lastname = $questionnaire['lastname'];
                    
                echo "
                    <tr>
                        
                        <td class='text-center font-roboto color-a2'>$title</td>
                        <td class='text-center font-roboto color-a2'>$description</td>
                        <td class='text-center font-roboto color-a2'>$date</td>
                        <td class='text-center font-roboto color-a2'>$time</td>
                        <td class='text-center font-roboto color-a2'>$subject</td>
                        <td class='text-center font-roboto color-a2'>
                            
                            <a href='notifications/viewquestionnaire/$id' data-toggle='tooltip' data-placement='top' title='View Questionnaires' class='btn-view-questionnaire btn btn-info' name='view'>
                                <i class='material-icons'>remove_red_eye</i>
                            </a>
                          
                        </td>
                    </tr>
                    ";
                }
            }
        ?>
            
    </tbody>
</table>

<?php
    } // end else
?>

