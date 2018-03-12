
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mdl_Users extends CI_Model {

    function __construct(){
        parent::__construct();
    }
    public function validateLogin($data=array()){
        
        $queryHasAdmin = $this->db->where('user_level',"99")
                        ->limit(1)
                        ->get('users');
        $hasAdmin = $queryHasAdmin->row_array();
        
        if($hasAdmin){
            $query = $this->db->where('user', $data['username'])
            ->where('pass', md5($data['password']))
            ->join('user_leveltbl', 'users.user_level = user_leveltbl.user_level')
            ->get('users');
            $usersData = $query->first_row('array');
            
            if(($usersData['user_level'] == "99") || ($usersData['user_level'] == "3")){
                $query = $this->db->where('id',$usersData['idusers'])->get('admin_informationtbl');
                $result = $query->first_row('array');
            
            }else if($usersData['user_level'] == "1"){
                $query = $this->db->where('id',$usersData['idusers'])->get('student_informationtbl');
                $result = $query->first_row('array');
            
            }else if($usersData['user_level'] == "2"){

                $query = $this->db->where('id',$usersData['idusers'])->get('teacher_informationtbl');
                $result = $query->first_row('array');
            }else{
                $result = array();
            }
            
            if($usersData){
                
                $_SESSION['users'] = $usersData;
                array_push($_SESSION['users'], $result);
                
                return array($usersData,true);//has admin / has user // corect input
            }else{
                return array(array('status'=>'invalid'),true);//has admin / has user // invalid input
            }
            
        }else{
            return array(array('status'=>'invalid'),false);//no admin
        }
        
       
        return array("",false);
    }       

    public function getAllUserList(){
        $query=$this->db->join('user_leveltbl','user_leveltbl.user_level = users.user_level')
                    ->get('users');
        return $query->result_array();
    }
    public function isAdminExist(){
        $query=$this->db->where('users.user_level', "99")
                    ->get('users');
        return $query->result_array();
    }

    public function getAllStudentsList(){
        $query=$this->db->join('user_leveltbl','user_leveltbl.user_level = users.user_level','left')
                    ->join('user_coursetbl','users.idusers = user_coursetbl.iduser_course','left')
                    ->join('coursetbl','user_coursetbl.idcourse = coursetbl.idcourse','left')
                    ->join('student_informationtbl','student_informationtbl.id = users.idusers','left')
                    ->join('user_departmenttbl','users.idusers = user_departmenttbl.UID','left')
                    ->join('departmenttbl','user_departmenttbl.iddepartment = departmenttbl.iddepartment','left')
                    ->group_by('users.idusers')
                    ->where('users.user_level','1')
                    ->get('users');
        return $query->result_array();
    }

    public function getAllProfessorsList(){
        $query=$this->db->join('user_leveltbl','user_leveltbl.user_level = users.user_level')
                    ->join('teacher_informationtbl','teacher_informationtbl.id = users.idusers')
                    ->join('user_departmenttbl','users.idusers = user_departmenttbl.UID','left')
                    ->join('departmenttbl','user_departmenttbl.iddepartment = departmenttbl.iddepartment','left')
                    ->where('users.user_level', '2')
                    ->group_by('users.idusers')
                    ->get('users');
        return $query->result_array();
    }
    
    public function postregisteradmin($data=false){
       
        $userLevelData = array(
                            0 => array(
                                        'user_level'=>"99",
                                        'userlevel_name'=>'admin'
                            ),
                            1 => array(
                                        'user_level'=>"1",
                                        'userlevel_name'=>'student'
                            ),
                            2 => array(
                                        'user_level'=>"2",
                                        'userlevel_name'=>'teacher'
                            ),
                            3 => array(
                                'user_level'=>"3",
                                'userlevel_name'=>'vpaa'
                            )
                        );
        foreach($userLevelData as $key=>$value){
            $isUserLevelInserted = $this->db->insert('user_leveltbl',$value);
            if(!$isUserLevelInserted){
                return array("error inserting userlevel",false);
            }
        }
        //FOR SCHEDULE (AUTO GENERATED)
        $dataSchedule = array(
                            0 => array(
                                    "schedule_code" => "MORNING MWF DEFAULT 1",
                                    "day" => "Monday,Wednesday,Friday",
                                    "time_start" => "07:30",
                                    "time_end" => "09:00",
                                    "status" => "available"
                            ),
                            1 => array(
                                    "schedule_code" => "MORNING MWF DEFAULT 2",
                                    "day" => "Monday,Wednesday,Friday",
                                    "time_start" => "09:00",
                                    "time_end" => "10:30",
                                    "status" => "available"
                            ),
                            2 => array(
                                    "schedule_code" => "MORNING MWF DEFAULT 3",
                                    "day" => "Monday,Wednesday,Friday",
                                    "time_start" => "10:30",
                                    "time_end" => "12:00",
                                    "status" => "available"
                            ),
                            3 => array(
                                    "schedule_code" => "AFTERNOON MWF DEFAULT 1",
                                    "day" => "Monday,Wednesday,Friday",
                                    "time_start" => "13:00",
                                    "time_end" => "14:30",
                                    "status" => "available"
                            ),
                            4 => array(
                                    "schedule_code" => "AFTERNOON MWF DEFAULT 2",
                                    "day" => "Monday,Wednesday,Friday",
                                    "time_start" => "14:30",
                                    "time_end" => "16:00",
                                    "status" => "available"
                            ),
                            5 => array(
                                    "schedule_code" => "AFTERNOON MWF DEFAULT 3",
                                    "day" => "Monday,Wednesday,Friday",
                                    "time_start" => "16:00",
                                    "time_end" => "17:30",
                                    "status" => "available"
                            ),
                            6 => array(
                                    "schedule_code" => "AFTERNOON MWF DEFAULT 4",
                                    "day" => "Monday,Wednesday,Friday",
                                    "time_start" => "18:00",
                                    "time_end" => "19:30",
                                    "status" => "available"
                            ),
                            7 => array(
                                    "schedule_code" => "AFTERNOON MWF DEFAULT 5",
                                    "day" => "Monday,Wednesday,Friday",
                                    "time_start" => "19:30",
                                    "time_end" => "21:00",
                                    "status" => "available"
                            ),
                            8 => array(
                                "schedule_code" => "MORNING TTH DEFAULT 1",
                                "day" => "Tuesday,Thursday",
                                "time_start" => "07:30",
                                "time_end" => "09:00",
                                "status" => "available"
                            ),
                            9 => array(
                                "schedule_code" => "MORNING TTH DEFAULT 2",
                                "day" => "Tuesday,Thursday",
                                "time_start" => "09:00",
                                "time_end" => "10:30",
                                "status" => "available"
                            ),
                            10 => array(
                                "schedule_code" => "MORNING TTH DEFAULT 3",
                                "day" => "Tuesday,Thursday",
                                "time_start" => "10:30",
                                "time_end" => "12:00",
                                "status" => "available"
                            ),
                            11 => array(
                                "schedule_code" => "AFTERNOON TTH DEFAULT 1",
                                "day" => "Tuesday,Thursday",
                                "time_start" => "13:00",
                                "time_end" => "14:30",
                                "status" => "available"
                            ),
                            12 => array(
                                "schedule_code" => "AFTERNOON TTH DEFAULT 2",
                                "day" => "Tuesday,Thursday",
                                "time_start" => "14:30",
                                "time_end" => "16:00",
                                "status" => "available"
                            ),
                            13 => array(
                                "schedule_code" => "AFTERNOON TTH DEFAULT 3",
                                "day" => "Tuesday,Thursday",
                                "time_start" => "16:00",
                                "time_end" => "17:30",
                                "status" => "available"
                            ),
                            14 => array(
                                "schedule_code" => "AFTERNOON TTH DEFAULT 4",
                                "day" => "Tuesday,Thursday",
                                "time_start" => "18:00",
                                "time_end" => "19:30",
                                "status" => "available"
                            ),
                            15 => array(
                                "schedule_code" => "AFTERNOON TTH DEFAULT 5",
                                "day" => "Tuesday,Thursday",
                                "time_start" => "19:30",
                                "time_end" => "21:00",
                                "status" => "available"
                            ),
                            
                        );
        foreach($dataSchedule as $key => $value){
            $isScheduleInserted = $this->db->insert('subject_scheduletbl',$value);
            if(!$isScheduleInserted){
                return array('fail to auto generate schedules', false);
            }
        }

        //FOR SCHEDULE END (AUTO GENERATED)

        unset($data["confirmPass"]);
        $isVpaaInserted = $this->db->insert('users',array('code' => "1234",'user'=>"vpaa",'pass'=>md5('1234'),'user_level'=>'3','status'=>'inactive'));
        if($isVpaaInserted){
            $last_insert = $this->db->insert_id();
            $isAdminInfoInserted = $this->db->insert('admin_informationtbl',array('firstname'=>'vpaa','id'=>$last_insert));
            if(!$isAdminInfoInserted){
                return array("error inserting Vpaa information",false);
            }
        }else{
            return array("error inserting Vpaa",false);
        }
        $data["user_level"] = "99";
        $data["status"] = "active";
        $data["code"] = "administrator";
        $data["pass"] = md5($data["pass"]);
        $isUserAdminInserted = $this->db->insert('users',$data);
        if($isUserAdminInserted){
            $last_insert = $this->db->insert_id();
            $admininfodata["firstname"] = "administrator";
            $admininfodata["id"] = $last_insert;
            $isAdminInfoInserted = $this->db->insert('admin_informationtbl',$admininfodata);
            if($isAdminInfoInserted){
                $data["idusers"] = $last_insert;
                $data["firstname"] = 'administrator';
                $_SESSION["users"] = $data;
                $_SESSION["users"]['image']="";
                return array("welcome admin !", true);
            }else{
                return array("Error Inserting admininfo",false);
            }
        }else{
            return array("error in inserting user admin", false);
        }
    }

    public function insertUsers($data=array()){
        
        $query = $this->db->where('user',$data['user'])
                        ->limit(1)
                        ->get('users');
        if($isUserExist = $query->row_array()){
            return false;
        }

        if((array_key_exists('department',$data)) && (array_key_exists('position',$data)) && (array_key_exists('user_level',$data)) ){
            if($data["user_level"] == "2" && $data["position"] == "2"){// 2 if dean
                $query = $this->db->join('teacher_informationtbl','users.idusers = teacher_informationtbl.id')
                                ->where('teacher_informationtbl.position', $data["position"])
                                ->where('teacher_informationtbl.department', $data["department"])
                                ->get('users');
                $isDeanDepartmentExist = $query->result_array();
                if(count($isDeanDepartmentExist) > 0){
                    return false;
                }
            }
            
        }
        
        $data['idsubject'] = explode(",", $data['idsubject']);
        $isDataValid = false;
        $studentDataIndex = array('firstname','middlename','lastname','course','year_level','department');
        $teacherDataIndex = array('firstname','middlename','lastname','position','department');
        
        if($data['user_level'] == 1 || $data['user_level'] == 2){
            
            if($data['user'] != "" && $data['pass'] != "" && $data['firstname'] != "" && $data['lastname'] != ""){
                
                if($this->db->where('user_level', $data['user_level'])->get('user_leveltbl')){
                    
                    $isUserCodeDuplicate = $this->db->where('code',$data['code'])->get('users');
                    
                    if($isUserCodeDuplicate->num_rows > 0){
                        return false;
                    }else{
                        
                        $isDataValid = true;
                        $userInfo = array(  'code'=>$data['code'],
                                            'user'=>$data['user'],
                                            'pass'=>md5($data['pass']),
                                            'user_level'=>$data['user_level'],
                                            'status'=>'inactive'
                                        );

                                        
                        $this->db->insert('users',$userInfo);
                        $last_insert = $this->db->insert_id();
                        
                        if($data['user_level'] == 1){
                            $studentInfo['id'] = $last_insert;
                            for($i = 0; $i< count($studentDataIndex); $i++){
                               
                                    $studentInfo[$studentDataIndex[$i]] = $data[$studentDataIndex[$i]];
                                
                            }
                            
                            if(!($this->db->insert('student_informationtbl',$studentInfo))){
                                return false;
                            }
                        
                        }else if($data['user_level'] == 2){
                            $teacherInfo['id'] = $last_insert;
                            for($i = 0; $i < count($teacherDataIndex); $i++){
                               
                                    $teacherInfo[$teacherDataIndex[$i]] = $data[$teacherDataIndex[$i]];
                            }
                            if(!($this->db->insert('teacher_informationtbl',$teacherInfo))){
                                return false;
                            }
                            
                        }
                        
                        $isUserSubjectInsertError = false;
                        if($data["idsubject"]){
                            if(isset($data["idsubject"]) && $data["idsubject"] !== null && $data["idsubject"] !== "" && !empty($data["idsubject"])){
                                foreach($data['idsubject'] as $value){
                                    if($value == ''){
                                        break;
                                    }
                                    $userClassId = array("UID" => $last_insert, "idsubject" => $value);
                                    if(!($this->db->insert('user_subjecttbl', $userClassId))){
                                        $isUserSubjectInsertError = true;
                                    }
                                }
                                if($isUserSubjectInsertError == true){
                                    return false;
                                }
                            }
                        }
                        
                        

                        if(array_key_exists('department',$data)){

                            $getDepartmentInfo = $this->db->where('department_name',$data['department'])->limit(1)->get('departmenttbl');
                            
                            if($departmentData = $getDepartmentInfo->row_array()){
                                $userDepartment = array('iddepartment' => $departmentData['iddepartment'],'UID' => $last_insert);
                                $result = $this->db->insert('user_departmenttbl',$userDepartment);
                            }
                            
                        }
                        
                        if(array_key_exists('course',$data)){
                            
                            $getCourseInfo = $this->db->where('course_name',$data['course'])->limit(1)->get('coursetbl');
                           
                            if($courseData = $getCourseInfo->row_array()){
                                
                                $userCourse = array('iduser_course'=>$last_insert, 'idcourse' => $courseData['idcourse']);
                                
                                $result = $this->db->insert('user_coursetbl',$userCourse);
                                
                            }
                        }
                    }
                }
            }
        }
        
         return $isDataValid;
    }

    public function deleteUserById($id=false){
        $getQuery = $this->db->where('idusers',$id)->get('users');
        $userInfo = $getQuery->row_array();
        if($userInfo['user_level'] == 1){
            $deleteQuery = $this->db->where('id',$id)->delete('student_informationtbl');
            if(!($deleteQuery)){
                return false;
            }
            $deleteQuery = $this->db->where('iduser_course',$id)->delete('user_coursetbl');
            if(!($deleteQuery)){
                return false;
            }
            
        }
        if($userInfo['user_level'] == 2){
            $deleteQuery = $this->db->where('id',$id)->delete('teacher_informationtbl');
        }
        $deleteUserDepartment = $this->db->where('UID',$id)->delete('user_departmenttbl');
        if(!($deleteUserDepartment)){
            return false;
        }
        $deleteUserSubject = $this->db->where('UID',$id)->delete('user_subjecttbl');
        if(!($deleteUserSubject)){
            return false;
        }
        $deleteUser = $this->db->where('idusers', $id)->delete('users');
        if($deleteQuery){
            return true;
        }
        return false;
    }

    public function getUserInfoById($data=false){
        $getQuery = $this->db->where('idusers',$data)
                            ->join('user_coursetbl','users.idusers = user_coursetbl.iduser_course','left')
                            ->join('coursetbl','user_coursetbl.idcourse = coursetbl.idcourse','left')
                            ->join('user_departmenttbl','users.idusers = user_departmenttbl.UID','left')
                            ->join('user_subjecttbl','users.idusers = user_subjecttbl.UID','left')
                            ->group_by('users.idusers')
                            ->get('users');
        $userInfo = $getQuery->row_array();
        if($userInfo['user_level'] == 1){
        
            if(!$userQuery = $this->db->where('id',$userInfo['idusers'])->get('student_informationtbl')){
                return false;
            }
        }else if($userInfo['user_level'] == 2){
            if(!$userQuery = $this->db->where('id',$userInfo['idusers'])->get('teacher_informationtbl')){
                return false;
            }
        }else{
            return false;
        }
        $getDataItems = $userInfo;
        $userInformationQuery = $userQuery->row_array();
        foreach($userInformationQuery as $k => $v){
            $getDataItems[$k] = $v;
        }
        if($getDataItems){
            return $getDataItems;
        }
        return false;
    }

    
    public function getTeacherSubjectByUID($data=false){
        $query=$this->db->join('subject_scheduletbl','subjecttbl.schedule = subject_scheduletbl.idschedule')
                        ->get('subjecttbl');
        $subjectList = $query->result_array();
        
        if($subjectList){
            
            for($i=0;$i<count($subjectList);$i++){
                $query = $this->db->join('users','user_subjecttbl.UID = users.idusers','left')
                                    ->where('users.user_level','2')
                                    ->where('idsubject',$subjectList[$i]['idsubject'])
                                    ->where('UID',$data)
                                    ->limit(1)
                                    ->get('user_subjecttbl');
                if($userSubjectList = $query->row_array()){
                    $subjectList[$i]["state"] = "subjectsList";
                }else{
                    $query = $this->db->join('users','user_subjecttbl.UID = users.idusers','left')
                                    ->where('users.user_level','2')
                                    ->where('idsubject',$subjectList[$i]['idsubject'])
                                    ->where('UID !=',$data)
                    ->limit(1)
                    ->get('user_subjecttbl');
                    if($isSubjectExist = $query->row_array()){
                        array_splice($subjectList,$i,1);        
                        $i--;
                    }else{
                        $subjectList[$i]['state'] = "availableSubjects";
                    }
                }
            }
        }
        
        return $subjectList;
    }

    public function getTeacherAvailableSubjects(){
        $query=$this->db->join('subject_scheduletbl','subjecttbl.schedule = subject_scheduletbl.idschedule')
                        ->get('subjecttbl');
        $subjectList = $query->result_array();
        
        if($subjectList){
            for($i=0;$i<count($subjectList);$i++){
                $query = $this->db->join('users','user_subjecttbl.UID = users.idusers','left')
                                    ->where('idsubject',$subjectList[$i]['idsubject'])
                                    ->where('users.user_level','2')
                                    ->limit(1)
                                    ->get('user_subjecttbl');
                if($userSubjectList = $query->row_array()){
                    array_splice($subjectList,$i,1);
                    $i--;
                }else{
                    $subjectList[$i]['state'] = "availableSubjects";
                }
            }
        }

        return $subjectList;
    }
    public function getStudentAvailableSubjects(){
        $query=$this->db->join('subject_scheduletbl','subjecttbl.schedule = subject_scheduletbl.idschedule')
                        ->get('subjecttbl');
        $subjectList = $query->result_array();
        
        if($subjectList){
            for($i=0;$i<count($subjectList);$i++){
                
                $subjectList[$i]['state'] = "availableSubjects";
                
            }
        }

        return $subjectList;
    }

    public function getStudentSubjectByUID($data=false){
        $query=$this->db->join('subject_scheduletbl','subjecttbl.schedule = subject_scheduletbl.idschedule')
                        ->get('subjecttbl');
        $subjectList = $query->result_array();

        
        if($subjectList){
            for($i=0;$i<count($subjectList);$i++){
                $query = $this->db->where('idsubject',$subjectList[$i]['idsubject'])
                                    ->where('UID',$data)
                                    ->limit(1)
                                    ->get('user_subjecttbl');
                if($userSubjectList = $query->row_array()){
                    $subjectList[$i]["state"] = "subjectsList";
                }else{
                    
                    $subjectList[$i]['state'] = "availableSubjects";
                    
                }
            }
        }

        return $subjectList;
    }






    public function getUserAvailableSujbects($data=false){
      
        $query=$this->db->join('subject_scheduletbl','subjecttbl.schedule = subject_scheduletbl.idschedule')
                            ->get('subjecttbl');
        $subjectList = $query->result_array();
    
        $userSubjectQuery = $this->db->where('UID',$data)
                            ->get('user_subjecttbl');

        $userSubjectData = $userSubjectQuery->result_array();
        $isSubjectAcquiredQuery = $this->db->join('users','user_subjecttbl.UID = users.idusers')
                                    ->get('user_subjecttbl');
        $isSubjectAcquired = $isSubjectAcquiredQuery->result_array();
        if($userSubjectData){
            if(count($isSubjectAcquired) > 0){
                foreach($isSubjectAcquired as $key=>$value){
                    $userSubjectAcquiredId['idsubject'][$key] = $value['idsubject'];
                    $userSubjectAcquiredId['user_level'][$key] = $value['user_level'];
                }
                
            }
            if(count($userSubjectData) > 0){
                $userSubjectsID = array();
                for($i=0;$i<count($userSubjectData);$i++){
                    $userSubjectsID[$i] = $userSubjectData[$i]['idsubject'];
                  
                }
                
                for($i = 0; $i < count($subjectList); $i++){
                    
                    if(in_array($subjectList[$i]['idsubject'],$userSubjectsID)){
                        $subjectList[$i]['state'] = "subjectsList";
                    }else{
                        
                        if(!in_array($subjectList[$i]['idsubject'],$userSubjectAcquiredId['idsubject'])){
                        
                            $subjectList[$i]['state'] = "availableSubjects";
                
                        }else{
                            if($userSubjectAcquiredId['user_level'][$i] == "2"){

                                //array_splice($subjectList,$i,1);
                                //$i--;
                                $subjectList[$i]['state'] = "availableSubjects";
                            }else{
                                $subjectList[$i]['state'] = "availableSubjects";
                            }
                            
                        }
                        
                        
                    }
                }
               
            }else{
                for($i=0;$i < count($subjectList);$i++){
                    if(!in_array($subjectList[$i]['idsubject'],$userSubjectAcquiredId)){
                        
                            $subjectList[$i]['state'] = "availableSubjects";
                
                        }else{
                            if($userSubjectAcquiredId['user_level'][$i] == "2"){
                                //array_splice($subjectList,$i,1);
                                //$i--;
                                $subjectList[$i]['state'] = "availableSubjects";
                            }else{
                                $subjectList[$i]['state'] = "availableSubjects";
                            }
                            
                        }
                }
                
            }
        }else{
            if(count($isSubjectAcquired) > 0){
                foreach($isSubjectAcquired as $key=>$value){
                    $userSubjectAcquiredId['idsubject'][$key] = $value['idsubject'];
                    $userSubjectAcquiredId['user_level'][$key] = $value['user_level'];
                }
                
            }
            if(count($userSubjectData) > 0){
                $userSubjectsID = array();
                for($i=0;$i<count($userSubjectData);$i++){
                    $userSubjectsID[$i] = $userSubjectData[$i]['idsubject'];
                  
                }
                
                for($i = 0; $i < count($subjectList); $i++){
                    
                    if(in_array($subjectList[$i]['idsubject'],$userSubjectsID)){
                        $subjectList[$i]['state'] = "availableSubjects";
                    }else{
                        
                        if(!in_array($subjectList[$i]['idsubject'],$userSubjectAcquiredId['idsubject'])){
                        
                            $subjectList[$i]['state'] = "availableSubjects";
                
                        }else{
                            if($userSubjectAcquiredId['user_level'][$i] == "2"){
                                //array_splice($subjectList,$i,1);
                                //$i--;
                                $subjectList[$i]['state'] = "availableSubjects";
                            }else{
                                $subjectList[$i]['state'] = "availableSubjects";
                            }
                            
                        }
                        
                        
                    }
                }
               
            }else{
                for($i=0;$i < count($subjectList);$i++){
                    if(!in_array($subjectList[$i]['idsubject'],$userSubjectAcquiredId)){
                        
                            $subjectList[$i]['state'] = "availableSubjects";
                
                        }else{
                            if($userSubjectAcquiredId['user_level'][$i] == "2"){
                                array_splice($subjectList,$i,1);
                                $i--;
                            }else{
                                $subjectList[$i]['state'] = "availableSubjects";
                            }
                            
                        }
                }
                
            }
        }
        
        
        return $subjectList;
    }

    public function updateUser($data=false){
        
        $query = $this->db->where('idusers !=',$data["idusers"])
                        ->where('user',$data['user'])
                        ->get('users');
        if($query->result_array()){
            return false;
        }

        if(isset($data['idsubject']) && isset($data['idsubject_available'])){
            $subjectIdData = explode(',', $data['idsubject']);
            
            $subjectAvailableIdData = explode(',', $data['idsubject_available']);
        }
        
        
        if($getQuery = $this->db->where('idusers',$data['idusers'])->get('users')){            
            $userData = $getQuery->row_array();
            $this->db->set('code',$data['code'])
                    ->where('idusers',$data['idusers'])
                    ->update('users');
            if(array_key_exists('department',$data)){
                $isUpdated = $this->db->set('iddepartment',$data['department'])
                    ->where('UID',$data['idusers'])
                    ->update('user_departmenttbl');
                if(!($isUpdated)){
                    return false;
                }
            }
            $getDepartment = $this->db->where('iddepartment',$data['department'])
                        ->limit(1)
                        ->get('departmenttbl');
            $getDepartmentData = $getDepartment->row_array();

            $data['department_name'] = $getDepartmentData['department_name'];
            if($userData['user_level'] == 1){
                
                if(array_key_exists('course',$data)){
                    
                    $courseData = $this->getUserInfoById($data['idusers']);
                    if($courseData){
                        $setCourseData = array('iduser_course' => $data['idusers'],'idcourse'=>$courseData['idcourse']);
                        $isUpdated = $this->db->set('idcourse',$data['course'])
                        ->where($setCourseData)
                        ->update('user_coursetbl');
                        //->get_compiled_update('user_coursetbl');
           
                        if(!($isUpdated)){
                            return false;
                        }
                    }
                    
                }

                $getCourse = $this->db->where('idcourse',$data['course'])
                                    ->limit(1)
                                    ->get('coursetbl');
                $getCourseData = $getCourse->row_array();

                
                $data['course_name'] = $getCourseData['course_name'];
                

                $setStudentInformation = array( 
                                    'firstname'=>$data['firstname'],
                                    'middlename' => $data['middlename'],
                                    'lastname' => $data['lastname'],
                                    'year_level' => $data['year_level'],
                                    'department' => $data['department_name'],
                                    'course' => $data['course_name']
                            );
                $isUpdated = $this->db->set($setStudentInformation)->where('id',$data['idusers'])->update('student_informationtbl');
            }else if($userData['user_level'] == 2){
                $setTeacherInformation = array('firstname'=>$data['firstname'],
                                    'middlename' => $data['middlename'],
                                    'lastname' => $data['lastname'],
                                    'position' => $data['position'],
                                    'department' => $data['department_name']
                            );
                $isUpdated = $this->db->set($setTeacherInformation)->where('id',$data['idusers'])->update('teacher_informationtbl');
            }
            
            if($isUpdated){
                
                $userSubjectQuery = $this->db->where('UID',$data['idusers'])
                    ->get('user_subjecttbl');
                $userSubjectData = $userSubjectQuery->result_array();
                
                if($userSubjectData){
                    foreach($userSubjectData as $key => $valueUser){
                        
                        if(count($subjectIdData) > 0){
                            foreach($subjectIdData as $key=> $valueSubjectId){
                                if($valueUser['idsubject'] != $valueSubjectId){
                                    if($valueUser['UID'] == $data['idusers']){
                                        if($valueSubjectId == "" || $valueSubjectId == null){
                                            
                                        }else{
                                            $query = $this->db->where('idsubject',$valueSubjectId)
                                                            ->where('UID',$data["idusers"])
                                                            ->get('user_subjecttbl');
                                            if($isUserSubjectExist = $query->result_array()){

                                            }else{
                                                $isSubjectInserted = $this->db->insert('user_subjecttbl',array('idsubject'=>$valueSubjectId,
                                                'UID'=>$data['idusers'])
                                                );
                                            }

                                        }
                                        
                                    }
                                }
                            }
                        }
                        
                        if(count($subjectAvailableIdData) > 0){
                            
                            foreach($subjectAvailableIdData as $key => $valueSubjectId){
                                if($valueUser['idsubject'] == $valueSubjectId){
                                    if($valueUser['UID'] == $data['idusers']){
                                        $isSubjectDeleted = $this->db->where('UID',$data['idusers']) 
                                        ->where('idsubject',$valueSubjectId)
                                        ->delete('user_subjecttbl');
                                    }
                                }
                            }
                        }
                        
                    }
                }else{
                    foreach($subjectIdData as $key=> $valueSubjectId){
                        if($valueSubjectId == "" || $valueSubjectId == null){

                        }else{
                            $query = $this->db->where('idsubject',$valueSubjectId)
                            ->where('UID',$data["idusers"])
                            ->get('user_subjecttbl');
                            if($isUserSubjectExist = $query->result_array()){

                            }else{
                                $isSubjectInserted = $this->db->insert('user_subjecttbl',array('idsubject'=>$valueSubjectId,
                                'UID'=>$data['idusers'])
                                );
                            }
                         
                        }
                        
                    }
                    foreach($subjectAvailableIdData as $key => $valueSubjectId){
                        if($valueSubjectId == "" || $valueSubjectId == null){
                            $isSubjectDeleted = $this->db->where('UID',$data['idusers']) 
                            ->where('idsubject',$valueSubjectId)
                            ->delete('user_subjecttbl');
                        }
                        
                    }
                }
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public function changePassword($data=array()){
        
        $query = $this->db->set('pass',md5($data['newPassword']))->set('status','active')->where('idusers',$data['idusers'])->update('users');
        if($query){ 
            return md5($data['newPassword']);
        }else
        {
            return false;
        }
    }
    
}


?>