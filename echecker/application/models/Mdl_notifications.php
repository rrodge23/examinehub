
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_notifications extends CI_Model {

    function __construct(){
        parent::__construct();
    }
    

    public function unapprovedQuestionaireList(){
       
        $questionairesData = array();
        
        $query=$this->db->join('subjecttbl','questionairetbl.idsubject = subjecttbl.idsubject','left')
                    ->join('user_subjecttbl','subjecttbl.idsubject = user_subjecttbl.idsubject','left')
                    ->join('users','user_subjecttbl.UID = users.idusers','left')
                    ->join('teacher_informationtbl','users.idusers = teacher_informationtbl.id','left')
                    ->where('users.user_level','2')
                    ->where('teacher_informationtbl.department',$_SESSION["users"][0]["department"])
                    ->where('teacher_informationtbl.position','2')
                    ->where('questionairetbl.questionaire_status','unapproved')
                    ->get('questionairetbl');

       if($questionairesData = $query->result_array()){
           
           for($q=0;$q<count($questionairesData);$q++){
                $query=$this->db->where('idquestionaire',$questionairesData[$q]["idquestionaire"])
                ->get('questionaire_typetbl');
                if($questionairesData[$q]["questionaire_type"] = $query->result_array()){
                   for($i=0;$i<count($questionairesData[$q]["questionaire_type"]);$i++){
                        $query=$this->db->where('idquestionaire_type',$questionairesData[$q]["questionaire_type"][$i]["idquestionairetype"])
                        ->get('questiontbl');
                        if($questionairesData[$q]["questionaire_type"][$i]["question"] = $query->result_array()){
                            
                                for($j=0;$j<count($questionairesData[$q]["questionaire_type"][$i]["question"]);$j++){
                                    if($questionairesData[$q]["questionaire_type"][$i]["questionaire_type"] == 0){
                                        $query=$this->db->where('idquestion',$questionairesData[$q]["questionaire_type"][$i]["question"][$j]["idquestion"])
                                        ->get('question_choicestbl');
                                        if($questionairesData[$q]["questionaire_type"][$i]["question"][$j]["choices"] = $query->result_array()){
    
                                        }
                                    }
                                    
                                    $query=$this->db->where('idquestion',$questionairesData[$q]["questionaire_type"][$i]["question"][$j]["idquestion"])
                                    ->get('question_answertbl');

                                    if($questionairesData[$q]["questionaire_type"][$i]["question"][$j]["answer"] = $query->result_array()){
                                        
                                    }
                                }
                            

                            
                            
                        }
                   }
                }
           }
       }
            
        return $questionairesData;
    }

    public function viewquestionnaireById($data=false){
       
        $questionairesData = array();
        
        $query=$this->db->join('subjecttbl','questionairetbl.idsubject = subjecttbl.idsubject','left')
                    ->where('questionaire_status','unapproved')
                    ->where('idquestionaire',$data)
                    ->get('questionairetbl');

       if($questionairesData = $query->result_array()){
           
           for($q=0;$q<count($questionairesData);$q++){
                $query=$this->db->where('idquestionaire',$questionairesData[$q]["idquestionaire"])
                ->get('questionaire_typetbl');
                if($questionairesData[$q]["questionaire_type"] = $query->result_array()){
                   for($i=0;$i<count($questionairesData[$q]["questionaire_type"]);$i++){
                        $query=$this->db->where('idquestionaire_type',$questionairesData[$q]["questionaire_type"][$i]["idquestionairetype"])
                        ->get('questiontbl');
                        if($questionairesData[$q]["questionaire_type"][$i]["question"] = $query->result_array()){
                            
                            for($j=0;$j<count($questionairesData[$q]["questionaire_type"][$i]["question"]);$j++){
                                if($questionairesData[$q]["questionaire_type"][$i]["questionaire_type"] == 0){
                                    $query=$this->db->where('idquestion',$questionairesData[$q]["questionaire_type"][$i]["question"][$j]["idquestion"])
                                    ->get('question_choicestbl');
                                    if($questionairesData[$q]["questionaire_type"][$i]["question"][$j]["choices"] = $query->result_array()){

                                    }
                                }
                                
                                $query=$this->db->where('idquestion',$questionairesData[$q]["questionaire_type"][$i]["question"][$j]["idquestion"])
                                ->get('question_answertbl');

                                if($questionairesData[$q]["questionaire_type"][$i]["question"][$j]["answer"] = $query->result_array()){
                                    
                                }
                            }
                        }
                   }
                }
           }
       }
        if($questionairesData){
            return $questionairesData[0];
        }else{
            return $questionairesData;
        }
        
    }

    public function approvequestionnaire($id=false){
        
        $isQuestionnaireUpdated = $this->db->set('questionaire_status','approved')
                        ->where('idquestionaire',$id)
                        ->update('questionairetbl');
        if($isQuestionnaireUpdated){
            return array("Approved",true);
        }
        return array("Fail to Approve",false);
    }

}

?>