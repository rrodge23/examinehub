
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_examinations extends CI_Model {

    function __construct(){
        parent::__construct();
    }
   
    public function getAllCurrentUserSubjects($data=false){
        $query=$this->db->join('subjecttbl','user_subjecttbl.idsubject = subjecttbl.idsubject')
                    ->join('subject_scheduletbl','subjecttbl.schedule = subject_scheduletbl.idschedule','left')
                ->where('UID',$data)
            ->get('user_subjecttbl');
        return $query->result_array();
    }
    
    
    public function userQuestionaireList($data=false){
        $userID = $_SESSION["users"]["idusers"];
        $dateNow = Date('m-d-y');
        if($_SESSION["users"]["user_level"] == "1"){ 
            $query=$this->db->join('user_questionairetbl','questionairetbl.idquestionaire = user_questionairetbl.questionaire_id','left')
                ->join('subjecttbl','questionairetbl.idsubject = subjecttbl.idsubject','left')
                ->where("questionairetbl.idquestionaire NOT IN (SELECT user_questionairetbl.questionaire_id FROM user_questionairetbl WHERE user_questionairetbl.idusers = $userID)")
                ->where('questionairetbl.questionaire_status','approved')
                ->where('questionairetbl.idsubject',$data)
                ->group_by('questionairetbl.idquestionaire')
                ->get('questionairetbl');
        }else if($_SESSION["users"]["user_level"] == "2"){
            $query=$this->db->join('user_questionairetbl','questionairetbl.idquestionaire = user_questionairetbl.questionaire_id','left')
            ->join('subjecttbl','questionairetbl.idsubject = subjecttbl.idsubject','left')
            //->where('user_questionairetbl.idusers !=',$_SESSION["users"]["idusers"])
            ->where('questionairetbl.idsubject',$data)
            ->or_where("(questionairetbl.idquestionaire NOT IN ('SELECT user_questionairetbl.questionaire_id FROM user_questionairetbl') AND questionairetbl.idsubject = $data)",NULL,FALSE)
            ->group_by('questionairetbl.idquestionaire')
                ->get('questionairetbl');
        } 
        return $query->result_array();
    }
   
    public function deleteQuestionaire($data=false){

        $query=$this->db->select('questionaire_typetbl.idquestionairetype')
                        ->where('questionaire_typetbl.idquestionaire',$data)
                ->get('questionaire_typetbl');
        
        if($questionaireTypeIdData = $query->result_array()){
           
            for($i=0;$i<count($questionaireTypeIdData);$i++){
                
                $query = $this->db->select('idquestion')
                                ->where('idquestionaire_type',$questionaireTypeIdData[$i]["idquestionairetype"])
                        ->get('questiontbl');
                if($questionIdData = $query->result_array()){
                    for($j=0;$j<count($questionIdData);$j++){

                        $query = $this->db->select('idquestion_choices')
                                ->where('idquestion',$questionIdData[$j]["idquestion"])
                                ->get('question_choicestbl');
                        if($questionChoicesIdData = $query->result_array()){
                           for($k=0;$k<count($questionChoicesIdData);$k++){
                                $isChoicesDeleted = $this->db->where('idquestion_choices',$questionChoicesIdData[$k]["idquestion_choices"])  
                                        ->delete('question_choicestbl');
                                if(!$isChoicesDeleted){
                                    return array("Error in Dropping Choices Data",false);
                                }
                           }
                        }

                        $query = $this->db->select('idquestion_answer')
                                    ->where('idquestion',$questionIdData[$j]["idquestion"])
                                    ->get('question_answertbl');
                        if($questionAnswerIdData = $query->result_array()){
                            for($k=0;$k<count($questionAnswerIdData);$k++){
                                $isAnswerDeleted = $this->db->where('idquestion_answer',$questionAnswerIdData[$k]["idquestion_answer"])  
                                        ->delete('question_answertbl');
                                if(!$isAnswerDeleted){
                                    return array("Error in Dropping Answer Data",false);
                                }
                            }
                        }
                        $isUserQuestionnaireDelete = $this->db->where('user_questionairetbl.questionaire_id',$data)->delete('user_questionairetbl');
                        if(!$isUserQuestionnaireDelete){
                            return array("Error in Dropping User Questionaire Data",false);
                        }

                        $isQuestionDeleted = $this->db->where('idquestion',$questionIdData[$j]["idquestion"])  
                                ->delete('questiontbl');
                        if(!$isQuestionDeleted){
                            return array("Error in Dropping Question Data",false);
                        }

                    }
                }
                
                $isQuestionTypeDeleted = $this->db->where('idquestionairetype',$questionaireTypeIdData[$i]["idquestionairetype"])  
                        ->delete('questionaire_typetbl');
                if(!$isQuestionTypeDeleted){
                    return array("Error in Dropping Question Type Data",false);
                }
            }
        }
        $isQuestionaireDeleted = $this->db->where('idquestionaire',$data)  
                ->delete('questionairetbl');
        if(!$isQuestionaireDeleted){
            return array("Error in Dropping Questionaire Data",false);
        }

        return array("",true);
    }

    public function subjectclassinformation($data=false){
        $query=$this->db->join('users','user_subjecttbl.UID = users.idusers','left')
                        ->join('student_informationtbl','users.idusers = student_informationtbl.id','left')
                        ->join('user_coursetbl','users.idusers = user_coursetbl.iduser_course','left')
                        ->join('coursetbl','user_coursetbl.idcourse = coursetbl.idcourse','left')
                        ->join('user_departmenttbl','users.idusers = user_departmenttbl.UID','left')
                        ->join('departmenttbl','user_departmenttbl.iddepartment = departmenttbl.iddepartment','left')
                        ->join('subjecttbl','user_subjecttbl.idsubject = subjecttbl.idsubject','left')
                        ->where('user_subjecttbl.idsubject',$data)
                        ->where('users.user_level','1')
            ->get('user_subjecttbl');
        return $query->result_array();
    }

    public function submitQuestionnaire($data=false){//IDQUESTIONAIREE
        
        if($_SESSION["users"][0]["position"] == "1"){
            $status = "waiting for confirmation";
        }else{
            $status = "approved";
        }
        $query = $this->db->set('questionaire_status',$status)
                        ->where('idquestionaire',$data["idquestionaire"])
                        ->update('questionairetbl');
        if($query){
            return array("",true);
        }else{
            return array("error in update status",false);
        }
    }

    public function examinestart($data=false){
        
        $userQuestionnaireInitialData = array('questionaire_id'=>$data["idquestionaire"],
                                              'idusers'=>$_SESSION['users']['idusers']
                                            );
        $query = $this->db->insert('user_questionairetbl',$userQuestionnaireInitialData);
        
        return $query;
        
    }

    public function updatequestion($data=false){
        
        $answerID = array();
        if(isset($data["questionaire"]["idquestionaire"])){
            $idquestionaire = $data["questionaire"]["idquestionaire"];
            unset($data['questionaire']['idquestionaire']);
            $query = $this->db->set($data['questionaire'])
                        ->where('idquestionaire',$idquestionaire)
                        ->update('questionairetbl');
            if(!$query){
                return array('failed to update questionnaire',false);
            }
        }
        
        $query = $this->db->set('question_title',$data["question"])
                        ->where('idquestion',$data["idquestion"])
                        ->update('questiontbl');
        if($query){
            if($data["question_type"] == 0){
                for($i=0;$i<count($data["choices"]);$i++){
                    $query = $this->db->set('choices_description',$data["choices"][$i]["choices"])
                                    ->where('idquestion_choices',$data['choices'][$i]["idchoices"])
                                    ->update('question_choicestbl');
                    if(!$query){
                        return array('error choices',false);
                    }
                }
            }
            
            for($i=0;$i<count($data["answer"]);$i++){
                if(count($data["answer"] > 0)){
                    if(isset($data["answer"][$i]["answer"])){
                        
                        if($data["answer"][$i]["idanswer"] == ""){
                            $answerData["idquestion"] = $data["idquestion"];
                            $answerData["answer"] = $data["answer"][$i]["answer"];
                            $query = $this->db->insert('question_answertbl',$answerData);
                            $answerID[$i] = $this->db->insert_id();
                        }else{
                            $query = $this->db->set('answer',$data["answer"][$i]["answer"])
                                                ->where('idquestion_answer',$data["answer"][$i]["idanswer"])
                                                ->update('question_answertbl');
                            $answerID[$i] = $data["answer"][$i]["idanswer"];
                            if(!$query){
                                return array("error answer",false);
                            }
                        }
                        
                    }
                }
                
            }

            return array($answerID,true);
        }else{
            return array("error question",false);
        }
    }

    public function validate($data=false){
        
        $userQuestionnaireInitialData = array('questionaire_id'=>$data["idquestionaire"],
                                              'idusers'=>$_SESSION['users']['idusers']
                                            );
        $query = $this->db->insert('user_questionairetbl',$userQuestionnaireInitialData);
        
        return $query;
        
    }

    public function addQuestionnaireType($data=false){
       
        $questionnaireData = array();
        $questionaireDataID = array();
        if($data["data"]["idquestionaire"] == ""){
            foreach($data["data"] as $key => $value){
                $questionnaireData[$key] = $value;
            }

            $questionnaireData['questionaire_status'] = "draft";
            $questionnaireData['questionaire_total_score'] = $data[0]["data"]["questionaire_type_total_item"];
            $isQuestionaireDataInserted = $this->db->insert('questionairetbl',$questionnaireData);
            if($isQuestionaireDataInserted){
                $questionaireID = $this->db->insert_id();
                $questionaireDataID["idquestionaire"] = $questionaireID;
                $data[0]["data"]["idquestionaire"] = $questionaireID;
                $isQuestionaireTypeDataInserted = $this->db->insert('questionaire_typetbl',$data[0]["data"]);
                if($isQuestionaireTypeDataInserted){
                    $questionaireTypeID = $this->db->insert_id();
                    $questionaireDataID["idquestionaire_type"] = $questionaireTypeID;
                    for($j=0;$j<(count($data[0])-1);$j++){
                        $questionData["question_title"] = $data[0][$j]["question"];
                        $questionData["idquestionaire_type"] = $questionaireTypeID;
                        $isQuestionDataInserted = $this->db->insert('questiontbl',$questionData);
                        if($isQuestionDataInserted){
                            $questionID = $this->db->insert_id();
                            $questionaireDataID["idquestion"][$j] = $questionID;
                            if($data[0]["data"]["questionaire_type"] == 0){
                                $choicesData["idquestion"] = $questionID;
                                for($k=0;$k<(count($data[0][$j])-2);$k++){ //-2 for answer and question array
                                    $choicesData["choices_description"] = $data[0][$j][$k];
                                    $isChoicesDataInserted = $this->db->insert('question_choicestbl',$choicesData);
                                    if($isChoicesDataInserted){
                                        $choicesID = $this->db->insert_id();
                                        $questionaireDataID["choices"][$j]["idquestion_choices"][$k] = $choicesID;
                                    }else{
                                        return array("Error in Inserting choices table",false);
                                    }
                                }

                                $answerData["idquestion"] = $questionID;
                                $answerData["answer"] = $data[0][$j]["answer"];
                                $isAnswerDataInserted = $this->db->insert('question_answertbl',$answerData);
                                if($isAnswerDataInserted){
                                    $answerID = $this->db->insert_id();
                                    $questionaireDataID["answer"][$j]["idquestion_answer"][0] = $answerID;
                                }else{
                                    return array("Error in Inserting answer table",false);

                                }
                                

                            }else if($data[0]["data"]["questionaire_type"] == 1){
                                for($k=0;$k<(count($data[0][$j])-1);$k++){
                                    $answerData["idquestion"] = $questionID;
                                    $answerData["answer"] = $data[0][$j][$k];

                                    $isAnswerDataInserted = $this->db->insert('question_answertbl',$answerData);
                                    if($isAnswerDataInserted){
                                        $answerID = $this->db->insert_id();
                                        $questionaireDataID["answer"][$j]["idquestion_answer"][$k] = $answerID;
                                    }else{
                                        return array("Error in Inserting answer table",false);
                                        
                                    }
                                }
                            }
                            
                        }else{
                            return array("Error in Inserting question table",false);
                        }

                        
                    }
                }else{
                    return array("Error in Inserting questionaire type table",false);
                }
            }else{
                return array("Error in Inserting questionaire table",false);
                
            }
            
        }else{
            $data[0]["data"]["idquestionaire"] = $data["data"]["idquestionaire"];
            $isQuestionaireTypeDataInserted = $this->db->insert('questionaire_typetbl',$data[0]["data"]);
            if($isQuestionaireTypeDataInserted){
                $questionaireTypeID = $this->db->insert_id();
                $questionaireDataID["idquestionaire_type"] = $questionaireTypeID;
                for($j=0;$j<(count($data[0])-1);$j++){
                    $questionData["question_title"] = $data[0][$j]["question"];
                    $questionData["idquestionaire_type"] = $questionaireTypeID;
                    $isQuestionDataInserted = $this->db->insert('questiontbl',$questionData);
                    if($isQuestionDataInserted){
                        $questionID = $this->db->insert_id();
                        $questionaireDataID["idquestion"][$j] = $questionID;
                        if($data[0]["data"]["questionaire_type"] == 0){
                            $choicesData["idquestion"] = $questionID;
                            for($k=0;$k<(count($data[0][$j])-2);$k++){ //-2 for answer and question array
                                $choicesData["choices_description"] = $data[0][$j][$k];
                                $isChoicesDataInserted = $this->db->insert('question_choicestbl',$choicesData);
                                if($isChoicesDataInserted){
                                    $choicesID = $this->db->insert_id();
                                    $questionaireDataID["choices"][$j]["idquestion_choices"][$k] = $choicesID;
                                }else{
                                    return array("Error in Inserting choices table",false);
                                }
                            }

                            $answerData["idquestion"] = $questionID;
                            $answerData["answer"] = $data[0][$j]["answer"];
                            $isAnswerDataInserted = $this->db->insert('question_answertbl',$answerData);
                            if($isAnswerDataInserted){
                                $answerID = $this->db->insert_id();
                                $questionaireDataID["answer"][$j]["idquestion_answer"][0] = $answerID;
                            }else{
                                return array("Error in Inserting answer table",false);

                            }
                            

                        }else if($data[0]["data"]["questionaire_type"] == 1){
                            for($k=0;$k<(count($data[0][$j])-1);$k++){
                                $answerData["idquestion"] = $questionID;
                                $answerData["answer"] = $data[0][$j][$k];

                                $isAnswerDataInserted = $this->db->insert('question_answertbl',$answerData);
                                if($isAnswerDataInserted){
                                    $answerID = $this->db->insert_id();
                                    $questionaireDataID["answer"][$j]["idquestion_answer"][$k] = $answerID;
                                }else{
                                    return array("Error in Inserting answer table",false);
                                    
                                }
                            }
                        }
                        
                    }else{
                        return array("Error in Inserting question table",false);
                    }

                    
                }
            }else{
                return array("Error in Inserting questionaire type table",false);
            }
            $query = $this->db->where('idquestionaire',$data["data"]["idquestionaire"])->limit(1)->get('questionairetbl');
            if($isQuestionnaire = $query->row_array()){
                $questionnaireTotalScore = $data[0]["data"]["questionaire_type_total_item"]+$isQuestionnaire["questionaire_total_score"];
                $query = $this->db->set('questionaire_total_score',$questionnaireTotalScore)
                                    ->where('idquestionaire',$data["data"]["idquestionaire"])
                                    ->update('questionairetbl');
            }
        }

        return array("",$questionaireDataID);
    }

    public function postQuestionnaireInformation($data=false){
      
        $questionnaireData = array();
        

        foreach($data["data"] as $key => $value){
            $questionnaireData[$key] = $value;
        }
        
        if(isset($_SESSION["users"]["position"])){
            if($_SESSION["users"][0]["positioan"] == "1"){
                $questionnaireData["questionaire_status"] = "waiting for confirmation";
            }else if($_SESSION["users"][0]["position"] == "2"){
                $questionnaireData["questionaire_status"] = "approved";
            }
        }
        
        $questionnaireTotalScore = 0;
        $questionaireID = 0;
        $dataIdSubject = $questionnaireData["idsubject"];
        $isQuestionaireDataInserted = $this->db->insert('questionairetbl',$questionnaireData);
       
            if($isQuestionaireDataInserted){
                $questionaireID = $this->db->insert_id();
                $questionnaireTypeData["idquestionaire"] = $questionaireID;
                for($i=0;$i < (count($data)-1);$i++){
                    $questionnaireTotalScore += ($data[$i]["data"]["questionaire_type_total_item"]);//
                    foreach($data[$i]["data"] as $key => $value){
                        $questionnaireTypeData[$key] = $value;
                    }
                    
                    $isQuestionaireTypeDataInserted = $this->db->insert('questionaire_typetbl',$questionnaireTypeData);
                    
                    if($isQuestionaireTypeDataInserted){
                        $questionaireTypeID = $this->db->insert_id();
                        
                        for($j=0;$j<(count($data[$i])-1);$j++){
                            $questionData["question_title"] = $data[$i][$j]["question"];
                            $questionData["idquestionaire_type"] = $questionaireTypeID;
                            $isQuestionDataInserted = $this->db->insert('questiontbl',$questionData);
                            if($isQuestionDataInserted){
                                $questionID = $this->db->insert_id();

                                if($data[$i]["data"]["questionaire_type"] == 0){
                                    $choicesData["idquestion"] = $questionID;
                                    for($k=0;$k<(count($data[$i][$j])-2);$k++){
                                        $choicesData["choices_description"] = $data[$i][$j][$k];
                                        $isChoicesDataInserted = $this->db->insert('question_choicestbl',$choicesData);
                                        if($isChoicesDataInserted){
                                            
                                        }else{
                                            return array("Error in Inserting choices table",false);
                                        }
                                    }

                                    $answerData["idquestion"] = $questionID;
                                    $answerData["answer"] = $data[$i][$j]["answer"];
                                    $isAnswerDataInserted = $this->db->insert('question_answertbl',$answerData);
                                    if(!$isAnswerDataInserted){
                                        return array("Error in Inserting answer table",false);
                                    }
                                    

                                }else if($data[$i]["data"]["questionaire_type"] == 1){
                                    for($k=0;$k<(count($data[$i][$j])-1);$k++){
                                        $answerData["idquestion"] = $questionID;
                                        $answerData["answer"] = $data[$i][$j][$k];

                                        $isAnswerDataInserted = $this->db->insert('question_answertbl',$answerData);
                                        if(!$isAnswerDataInserted){
                                            return array("Error in Inserting answer table",false);
                                        }
                                    }
                                }
                                
                            }else{
                                return array("Error in Inserting question table",false);
                            }

                            
                        }
                    }else{
                        return array("Error in Inserting questionaire type table",false);
                    }
                }
            }else{
                return array("Error in Inserting questionaire table",false);
                
            } // END else

         // END IF 
        
        $isUpdated = $this->db->set('questionaire_total_score',$questionnaireTotalScore)
                ->where('idquestionaire',$questionaireID)
                ->update('questionairetbl');
       if($isUpdated){
            return array("Record Successfully Added",true,$dataIdSubject);
       }else{
            return array("Error in Inserting questionaire table",false);
       }
        
        
    }

    //SESSION BASE 
    public function getQuestionnaireInfoById($data=false){
        
        $query = $this->db->where('user_questionairetbl.questionaire_id',$data)
                        ->where('user_questionairetbl.idusers',$_SESSION["users"]["idusers"])
                        ->get('user_questionairetbl');
        if($isUserQuestionnaireRecordExist = $query->result_array()){
            redirect('NOT FOUND', 'refresh');
        }

        $examData = array();

        $query=$this->db->join('user_questionairetbl','questionairetbl.idquestionaire = user_questionairetbl.questionaire_id','left')
            ->where('idquestionaire',$data)
            ->get('questionairetbl');
        if($questionaireData = $query->result_array()){
           
            foreach($questionaireData[0] as $key => $value){
                $examData[$key] = $value;
            }
            $query = $this->db->where('idquestionaire',$data)
                ->get('questionaire_typetbl');
            if($questionaireTypeData = $query->result_array()){ 

                foreach($questionaireTypeData as $key => $value){
                    $examData["questionaire_type"][$key] = $value;

                    $query = $this->db->where('idquestionaire_type',$examData["questionaire_type"][$key]["idquestionairetype"])
                    ->get('questiontbl');
                    if($questionData = $query->result_array()){
                        for($i=0;$i<count($questionData);$i++){
                           
                            
                            $examData["questionaire_type"][$key]["question"][$i] = $questionData[$i];

                            $query = $this->db->join('user_answertbl','question_user_answertbl.iduseranswer = user_answertbl.iduseranswer')
                                                  ->where('question_user_answertbl.idquestion',$questionData[$i]["idquestion"])
                                                  ->where('user_answertbl.iduser',$_SESSION["users"]["idusers"])
                                                ->get("question_user_answertbl");
                            
                            if($userAnswer = $query->result_array()){
                                
                                $examData["questionaire_type"][$key]["question"][$i]["user_answer"] = $userAnswer;
                                
                            }

                            if($examData["questionaire_type"][$key]["questionaire_type"] == "0"){
                                $query = $this->db->where('idquestion',$examData["questionaire_type"][$key]["question"][$i]["idquestion"])
                                ->get('question_choicestbl');
                                
                                if($choicesData = $query->result_array()){
                                    for($j=0;$j<count($choicesData);$j++){
                                        $examData["questionaire_type"][$key]["question"][$i]["choices"][$j] = $choicesData[$j];
                                        
                                    }
                                }

                            }
                            
                            $query = $this->db->where('idquestion',$examData["questionaire_type"][$key]["question"][$i]["idquestion"])
                            ->get('question_answertbl');
                            if($answerData = $query->result_array()){
                                
                                for($j=0;$j<count($answerData);$j++){
                                    $examData["questionaire_type"][$key]["question"][$i]["answer"][$j] = $answerData[$j];
                                }
                                
                            }else{
                                $examData["questionaire_type"][$key]["question"][$i]["answer"][0]["answer"] = "";
                                $examData["questionaire_type"][$key]["question"][$i]["answer"][0]["idquestion_answer"] = "";
                            }
                            
                        }
                    }
                }
                
            }
           
        }  
        
        return $examData;
    }
    //WITHOUT REDIRECT IDQUESTIONAIRE SESSION BASE
    public function getQuestionnaireInfoByIdNoRedirect($data=false){
        
        
        $examData = array();

        $query=$this->db->join('user_questionairetbl','questionairetbl.idquestionaire = user_questionairetbl.questionaire_id','left')
            ->where('idquestionaire',$data)
            ->get('questionairetbl');
        if($questionaireData = $query->result_array()){
           
            foreach($questionaireData[0] as $key => $value){
                $examData[$key] = $value;
            }
            $query = $this->db->where('idquestionaire',$data)
                ->get('questionaire_typetbl');
            if($questionaireTypeData = $query->result_array()){ 

                foreach($questionaireTypeData as $key => $value){
                    $examData["questionaire_type"][$key] = $value;

                    $query = $this->db->where('idquestionaire_type',$examData["questionaire_type"][$key]["idquestionairetype"])
                    ->get('questiontbl');
                    if($questionData = $query->result_array()){
                        for($i=0;$i<count($questionData);$i++){
                           
                            
                            $examData["questionaire_type"][$key]["question"][$i] = $questionData[$i];

                            $query = $this->db->join('user_answertbl','question_user_answertbl.iduseranswer = user_answertbl.iduseranswer')
                                                  ->where('question_user_answertbl.idquestion',$questionData[$i]["idquestion"])
                                                  ->where('user_answertbl.iduser',$_SESSION["users"]["idusers"])
                                                ->get("question_user_answertbl");
                            
                            if($userAnswer = $query->result_array()){
                                
                                $examData["questionaire_type"][$key]["question"][$i]["user_answer"] = $userAnswer;
                                
                            }

                            if($examData["questionaire_type"][$key]["questionaire_type"] == "0"){
                                $query = $this->db->where('idquestion',$examData["questionaire_type"][$key]["question"][$i]["idquestion"])
                                ->get('question_choicestbl');
                                
                                if($choicesData = $query->result_array()){
                                    for($j=0;$j<count($choicesData);$j++){
                                        $examData["questionaire_type"][$key]["question"][$i]["choices"][$j] = $choicesData[$j];
                                        
                                    }
                                }

                            }
                            
                            $query = $this->db->where('idquestion',$examData["questionaire_type"][$key]["question"][$i]["idquestion"])
                            ->get('question_answertbl');
                            if($answerData = $query->result_array()){
                                
                                for($j=0;$j<count($answerData);$j++){
                                    $examData["questionaire_type"][$key]["question"][$i]["answer"][$j] = $answerData[$j];
                                }
                                
                            }else{
                                $examData["questionaire_type"][$key]["question"][$i]["answer"][0]["answer"] = "";
                                $examData["questionaire_type"][$key]["question"][$i]["answer"][0]["idquestion_answer"] = "";
                            }
                            
                        }
                    }
                }
                
            }
           
        }  
        
        return $examData;
    }
    //NO SESSION ARRAY PARAMETER USER ID AND QUESTIONNAIRE ID
    public function getQuestionnaireInfoByIdQuestionnaireIdUser($data=false){
        
        $examData = array();

        $query=$this->db->join('user_questionairetbl','questionairetbl.idquestionaire = user_questionairetbl.questionaire_id','left')
            ->where('user_questionairetbl.idusers',$data["idusers"])
            ->where('idquestionaire',$data["idquestionaire"])
            ->get('questionairetbl');
        if($questionaireData = $query->result_array()){
           
            foreach($questionaireData[0] as $key => $value){
                $examData[$key] = $value;
            }
            $query = $this->db->where('idquestionaire',$data["idquestionaire"])
                ->get('questionaire_typetbl');
            if($questionaireTypeData = $query->result_array()){ 

                foreach($questionaireTypeData as $key => $value){
                    $examData["questionaire_type"][$key] = $value;

                    $query = $this->db->where('idquestionaire_type',$examData["questionaire_type"][$key]["idquestionairetype"])
                    ->get('questiontbl');
                    if($questionData = $query->result_array()){
                        for($i=0;$i<count($questionData);$i++){
                           
                            
                            $examData["questionaire_type"][$key]["question"][$i] = $questionData[$i];

                            $query = $this->db->join('user_answertbl','question_user_answertbl.iduseranswer = user_answertbl.iduseranswer')
                                                  ->where('question_user_answertbl.idquestion',$questionData[$i]["idquestion"])
                                                  ->where('user_answertbl.iduser',$data["idusers"])
                                                ->get("question_user_answertbl");
                            
                            if($userAnswer = $query->result_array()){
                                
                                $examData["questionaire_type"][$key]["question"][$i]["user_answer"] = $userAnswer;
                                
                            }

                            if($examData["questionaire_type"][$key]["questionaire_type"] == "0"){
                                $query = $this->db->where('idquestion',$examData["questionaire_type"][$key]["question"][$i]["idquestion"])
                                ->get('question_choicestbl');
                                
                                if($choicesData = $query->result_array()){
                                    for($j=0;$j<count($choicesData);$j++){
                                        $examData["questionaire_type"][$key]["question"][$i]["choices"][$j] = $choicesData[$j];
                                        
                                    }
                                }

                            }
                            
                            $query = $this->db->where('idquestion',$examData["questionaire_type"][$key]["question"][$i]["idquestion"])
                            ->get('question_answertbl');
                            if($answerData = $query->result_array()){
                                
                                for($j=0;$j<count($answerData);$j++){
                                    $examData["questionaire_type"][$key]["question"][$i]["answer"][$j] = $answerData[$j];
                                }
                                
                            }else{
                                $examData["questionaire_type"][$key]["question"][$i]["answer"][0]["answer"] = "";
                                $examData["questionaire_type"][$key]["question"][$i]["answer"][0]["idquestion_answer"] = "";
                            }
                            
                        }
                    }
                }
                
            }
           
        }  
        
        return $examData;
    }
    /**
     * Array
(
    [0] => Array
        (
            [0] => Array
                (
                    [0] => asfsdf
                    [idquestion] => 79
                )

            [1] => Array
                (
                    [0] => sfsdf
                    [idquestion] => 80
                )

        )

    [1] => Array
        (
            [0] => Array
                (
                    [0] => asdfasdf 
                    [idquestion] => 81
                )

            [1] => Array
                (
                    [0] => asdfasdfsd asdf asdf asdf 
                    [idquestion] => 82
                )

        )

    [idquestionaire] => 41
)
     */
    public function submitexamine($data=false){
        
        $questionnaireInfo = $this->getQuestionnaireInfoByIdNoRedirect($data["idquestionaire"]);
        
        $totalScore = 0;
        for($i=0;$i<count($data)-2;$i++){ // minus loop idquestion and duration data
            
            for($j=0;$j<(count($data[$i]));$j++){ //
                $isAnswerCorrect = false;
                if($questionnaireInfo["questionaire_type"][$i]["questionaire_type"] == 0){
                    $questionScore = 0;
                    if($questionnaireInfo["questionaire_type"][$i]["question"][$j]["answer"][0]["answer"] == $data[$i][$j][0]){
                        $questionScore = $questionnaireInfo["questionaire_type"][$i]["questionaire_type_item_points"];
                        $isAnswerCorrect = true;
                        
                    }
                    
                }else if($questionnaireInfo["questionaire_type"][$i]["questionaire_type"] == 1){
                        $questionScore = 0;
                        $isAnswerCorrect = false;
                        $questionPoints = ($questionnaireInfo["questionaire_type"][$i]["questionaire_type_item_points"])/(count($questionnaireInfo["questionaire_type"][$i]["question"][$j]["answer"])); 
                    for($k=0;$k<count($questionnaireInfo["questionaire_type"][$i]["question"][$j]["answer"]);$k++){
                        $givenAnswer = $questionnaireInfo["questionaire_type"][$i]["question"][$j]["answer"][$k]["answer"];
                        if(preg_match("/\b($givenAnswer)\b/",$data[$i][$j][0])){
                            $questionScore += $questionPoints;
                            $isAnswerCorrect = true;
                        }
                        /*
                        $arrAnswer = explode(" ", );
                        if(in_array(, $arrAnswer)){
                            $questionScore += $questionPoints;
                        } 
                        */
                    }
                  
                    
                    
                }
                if($isAnswerCorrect == true){
                    $totalScore += ceil($questionScore);
                }else{
                    $totalScore += 0;
                }
                
                $answerData = array('answer' => $data[$i][$j][0], 'iduser' => $_SESSION["users"]["idusers"],'question_score'=>$questionScore);
                
                if($isAnswerInserted = $this->db->insert('user_answertbl',$answerData)){

                    $userAnswerLastInsertId = $this->db->insert_id();
                    $questionAnswerIdData = array('idquestion'=>$data[$i][$j]["idquestion"],'iduseranswer'=>$userAnswerLastInsertId);
                    $isQuestionAnswerIdDataInserted = $this->db->insert('question_user_answertbl',$questionAnswerIdData);
                    if($isQuestionAnswerIdDataInserted){
                       
                    }else{
                        return array('Error in Inserting anwer', false);
                    }
                }else{
                    return array('Error in Inserting anwer', false);
                }
            }
        }
        
        $userQuestionaireData = array(
                'questionaire_id'=>$data["idquestionaire"],
                'idusers' => $_SESSION["users"]["idusers"],
                'user_total_score' => (string)$totalScore
                                );
        if($isQuestionaireUserInserted = $this->db->set(array('user_total_score'=>(string)$totalScore,'time_consume'=>(string)$data["userduration"]))
                    ->where('user_questionairetbl.questionaire_id',$data["idquestionaire"])
                    ->where('user_questionairetbl.idusers',$_SESSION["users"]["idusers"])
                    ->update('user_questionairetbl')
                    ){
            return array('Sucessfully Submitted', true);
        }
        return array('', false);
    }


    public function postUpdateQuestionnaire($data=false){
       
        $questionaireId = $data["data"]["idquestionaire"];
        $subjectId = $data["data"]["idsubject"];
        unset($data["data"]["idquestionaire"]);
        unset($data["data"]["idsubject"]);
        $initialCount = $data["data"]["initialCount"];
        if(isset($_SESSION["users"][0]["position"])){
            if($_SESSION["users"][0]["position"] == "1"){
                $data["data"]["questionaire_status"] = "waiting for confirmation";
            }
        }
        unset($data["data"]["initialCount"]);
        

        $isQuestionaireDataUpdated = $this->db->set($data["data"])
                    ->where('idquestionaire',$questionaireId)
                    ->update('questionairetbl');

            if($isQuestionaireDataUpdated){
                for($i=0;$i < (count($data)-1);$i++){
                    if(($initialCount) > $i){
                        $questionnaireTypeId = $data[$i]["data"]["idquestionairetype"];
                        unset($data[$i]["data"]["idquestionairetype"]);
                       
                        $isQuestionaireTypeDataUpdated = $this->db->set($data[$i]["data"])
                                    ->where('idquestionairetype',$questionnaireTypeId)
                                    ->update('questionaire_typetbl');
                        
                        if($isQuestionaireTypeDataUpdated){
                            
                            for($j=0;$j<(count($data[$i])-1);$j++){                    
    
                                $isQuestionDataUpdated = $this->db->set('question_title',$data[$i][$j]["data"]["question"])
                                                    ->where('idquestion',$data[$i][$j]["data"]["idquestion"])
                                                    ->update('questiontbl');
                                if($isQuestionDataUpdated){
    
                                    if($data[$i]["data"]["questionaire_type"] == 0){
                                        for($k=0;$k<(count($data[$i][$j])-1);$k++){
                                            $choicesId = $data[$i][$j][$k]["idchoices"];
                                            unset($data[$i][$j][$k]["idchoices"]);
                                            $isChoicesDataUpdated = $this->db->set($data[$i][$j][$k])
                                                            ->where('idquestion_choices',$choicesId)
                                                            ->update('question_choicestbl');
                                            if($isChoicesDataUpdated){
                                                
                                                $isAnswerDataUpdated = $this->db->set('answer',$data[$i][$j]["data"]["answer"])
                                                                ->where('idquestion_answer',$data[$i][$j]["data"]["idanswer"])
                                                                ->update('question_answertbl');
                                                if(!$isAnswerDataUpdated){
                                                    return array("Error in Updating answer table",false);
                                                }
                                            }else{
                                                return array("Error in Updating choices table",false);
                                            }
                                        }
                                        
                                        
    
                                    }else if($data[$i]["data"]["questionaire_type"] == 1){
                                        for($k=0;$k<(count($data[$i][$j])-1);$k++){
                                            if($data[$i][$j]["data"]["initialAnswerCount"] > $k){
                                                $isAnswerDataUpdated = $this->db->set('answer',$data[$i][$j][$k]["data"]["answer"])
                                                ->where('idquestion_answer',$data[$i][$j][$k]["data"]["idanswer"])
                                                ->update('question_answertbl');
                                                if(!$isAnswerDataUpdated){
                                                    return array("Error in Updating answer table",false);
                                                }
                                            }else{
                                                
                                                $isAnswerDataInserted = $this->db->insert('question_answertbl',array('answer'=>$data[$i][$j][$k]["data"]["answer"],'idquestion'=>$data[$i][$j]["data"]["idquestion"]));
                                                if(!$isAnswerDataInserted){
                                                    return array("Error in Inserting answer table",false);
                                                }
                                            }
                                            
                                        }
                                    }
                                    
                                }else{
                                    return array("Error in Updating question table",false);
                                }
                            }
                        }else{
                            return array("Error in Updating questionaire type table",false);
                        }
                    }else{
                        /*
                        echo "<pre>";
                        
                        print_r($data[$i]);
                        echo "</pre>";
                        return false;
                        */
                        $data[$i]["data"]["idquestionaire"] = $questionaireId;
                        
                        $isQuestionnaireTypeInserted = $this->db->insert('questionaire_typetbl',$data[$i]["data"]);
                        if($isQuestionnaireTypeInserted){
                            
                            $questionaireTypeLastInsert = $this->db->insert_id();
                            
                            $getQuestionaireData = $this->db->where('questionairetbl.idquestionaire',$questionaireId)
                                                        ->order_by('questionairetbl.idquestionaire')
                                                        ->limit(1)
                                                        ->get('questionairetbl');
                            if($questionaireData = $getQuestionaireData->row_array()){
                                
                                if($questionaireData["questionaire_total_score"] == "0"){
                                    $newTotalScore = 0 + $data[$i]["data"]["questionaire_type_total_item"];
                                }else{
                                    $newTotalScore = $questionaireData["questionaire_total_score"] + $data[$i]["data"]["questionaire_type_total_item"];
                                }

                                $isQuestionaireTotalScoreUpdated = $this->db->set('questionaire_total_score', $newTotalScore)
                                ->where('questionairetbl.idquestionaire',$questionaireId)
                                ->update('questionairetbl');
                            }

                            
                            for($j=0;$j<count($data[$i])-1;$j++){
                                
                                if($data[$i]["data"]["questionaire_type"] == "0"){
                                    $isQuestionDataInserted = $this->db->insert('questiontbl',array('idquestionaire_type'=>$questionaireTypeLastInsert,'question_title'=>$data[$i][$j]["data"]["question"]));
                                    if($isQuestionDataInserted){
                                        $idquestion = $this->db->insert_id();
                                        for($k=0;$k<count($data[$i][$j])-1;$k++){
                                            $data[$i][$j][$k]["idquestion"] = $idquestion;
                                            $isQuestionChoicesInserted = $this->db->insert('question_choicestbl',$data[$i][$j][$k]);
                                            if(!$isQuestionChoicesInserted){
                                                return array('fail to add question choices', false);
                                            }
                                        }
                                        $isQuestionAnswerInserted = $this->db->insert('question_answertbl',array('answer'=>$data[$i][$j]["data"]["answer"],'idquestion'=>$idquestion));
                                        if(!$isQuestionAnswerInserted){
                                            return array("fail to add question answer", false);
                                        }
                                    }else{
                                        return array("fail to add question", false);
                                    }
                                    
                                    
                                }else if($data[$i]["data"]["questionaire_type"] == "1"){
                                    $isQuestionDataInserted = $this->db->insert('questiontbl',array('idquestionaire_type'=>$questionaireTypeLastInsert,'question_title'=>$data[$i][$j]["data"]["question"]));
                                    if($isQuestionDataInserted){
                                        $idquestion = $this->db->insert_id();
                                        for($k=0;$k<count($data[$i][$j])-1;$k++){
                                            $isQuestionAnswerInserted = $this->db->insert('question_answertbl',array('answer'=>$data[$i][$j][$k]["data"]["answer"],'idquestion'=>$idquestion));
                                            if(!$isQuestionAnswerInserted){
                                                return array("fail to add question answer", false);
                                            }
                                        }
                                        
                                    }else{
                                        return array("fail to add question", false);
                                    }
                                }

                            }
                            
                        }else{
                            return array('fail to add questionnaire type', false);
                        }
                        
                    }
                }
            }else{
                return array("Error in Updating questionaire table",false);
                
            } // END else

         // END IF 

        
       
        return array("Record Successfully Added",true,$subjectId);
        
    }//endupdatequestionnaire


} //end class



?>