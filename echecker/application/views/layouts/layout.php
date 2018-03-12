
<?php
    
    $dashboard="";$subjects="";$users="";$reports="";$departments="";$examinations="";$courses="";$schedules="";$classes="";$notifications="";$profiles="";
    $m_subjects="";$m_users="";$m_reports="";$m_departments="";$m_courses="";$m_examination;$m_schedules="";$m_classes="";$m_notifications="";$m_profiles="";
    switch($_SESSION['users']['user_level']){
        case '1':
            $m_subjects="";
            $m_users="";
            $m_reports="";
            $m_departments="";
            $m_courses="";
            $m_examination="1";
            $m_schedules="";
            $m_classes="";
            $m_notifications="";
            $m_profiles="1";
            break;
        case '2':
            $m_subjects="";
            $m_users="";
            $m_reports="1";
            $m_departments="";
            $m_courses="";
            $m_examination="1";
            $m_schedules="";
            $m_classes="";
            $m_notifications="1";
            $m_profiles="1";
            break;

        case '3':
            $m_subjects="";
            $m_users="";
            $m_reports="1";
            $m_departments="";
            $m_courses="";
            $m_examination="";
            $m_schedules="";
            $m_classes="";
            $m_notifications="";
            $m_profiles="1";
            break;
        case '99':  
            $m_subjects="1";
            $m_users="1";
            $m_reports="";
            $m_departments="1";
            $m_courses="1";
            $m_examination="";
            $m_schedules="1";
            $m_classes="";
            $m_notifications="";
            $m_profiles="1";
            break;
    };

    switch($currentPath){
        case 'dashboard':
            $dashboard='active';
            break;
        case 'subjects':
            $subjects='active';
            break;
        case 'users':
            $users='active';
            break;
        case 'reports':
            if($_SESSION["users"]["user_level"] != "99"){
                if($_SESSION["users"]["user_level"] == "1"){
                    $examinations='active';
                }
                if($_SESSION["users"]["user_level"] == "2"){
                    if($_SESSION["users"][0]["position"] == "1"){
                        $examinations='active';
                    }
                    
                }
                
            }
            $reports='active';
            break;
        case 'departments':
            $departments='active';
            break; 
         case 'schedules':
            $schedules='active';
            break;   
        case 'courses':
            $courses='active';
            break;
        case 'examinations':
            $examinations='active';
            break;   
        case 'classes':
            $classes='active';
            break;   
        case 'notifications':
            $notifications='active';
            break;
        case 'profiles':
            $profiles='active';
            break;    
        default:
    };
    
?>

<div class="wrapper">
    <div class="sidebar" data-color="purple" data-image="assets/img/sidebarbg.jpg" data-active-color="danger">

    	
            <div class="logo">
                <a href="dashboard" class="simple-text">
                    E-xaminationHUB
                </a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    
                    <li class="<?=$dashboard?>">
                        <a href="dashboard">
                            <i class="material-icons">dashboard</i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <?php

                    if($_SESSION["users"]["user_level"] == "99"){
                        if(isset($identifier)){
                            if($identifier == false){
                                $m_users = '0';
                                $m_classes = '0';
                            }
                        }
                    }

                    if($m_subjects == '1'){
                    
                    echo '<li class="'.$subjects.'">
                            <a href="subjects">
                                <i class="material-icons">subject</i>
                                <p>Subjects</p>
                            </a>
                        </li>';

                        }
            
                    if($m_schedules == '1'){
                    echo '<li class="'.$schedules.'">
                                <a href="schedules">
                                    <i class="material-icons">schedule</i>
                                    <p>Schedules</p>
                                </a>
                            </li>';
                        }
                
                        if($m_courses == '1'){
                            echo '<li class="'.$courses.'">
                                    <a href="courses">
                                        <i class="material-icons">book</i>
                                        <p>Courses</p>
                                    </a>
                                </li>';
                        }
                        
                    if($m_departments == '1'){
                        echo '<li class="'.$departments.'">
                                    <a href="departments">
                                        <i class="material-icons">view_quilt</i>
                                        <p>Program</p>
                                    </a>
                                </li>';
                    }
                    
                
                    
                    if($m_classes == '1'){
                        echo '<li class="'.$classes.'">
                                <a href="classes">
                                    <i class="material-icons">apps</i>
                                    <p>Classes</p>
                                </a>
                            </li>';
                    }

                    if($m_examination == '1'){
                        
                        echo '<li class="'.$examinations.'">
                                    <a href="examinations">
                                        <i class="material-icons">library_books</i>
                                        <p>Subjects</p>
                                    </a>
                                </li>';
                    }

                    if($m_users == '1'){
                        echo '<li class="'.$users.'">
                                    <a href="users">
                                        <i class="material-icons">account_box</i>
                                        <p>Users</p>
                                    </a>
                                </li>';
                    }
                    
                    if($_SESSION['users']["user_level"] != "3"){
                        if(($m_reports == '1') && ($_SESSION['users'][0]['position'] == "2")){
                            
                            echo '<li class="'.$reports.'">
                                        <a href="reports">
                                            <i class="material-icons">history</i>
                                            <p>Reports</p>
                                        </a>
                                    </li>';
                        }
                    }
                     

                    if(($m_reports == '1') && ($_SESSION['users']["user_level"] == "3")){
                        
                        echo '<li class="'.$reports.'">
                                    <a href="reports/reportsdepartmentlist">
                                        <i class="material-icons">history</i>
                                        <p>Reports</p>
                                    </a>
                                </li>';
                    }

                    if($m_notifications == '1'){
                        
                        echo '<li class="'.$notifications.'">
                                    <a href="notifications">
                                        <i class="material-icons">notifications</i>
                                        <p>Notifications</p>
                                    </a>
                                </li>';
                    }
                    if(($m_profiles == '1')){
                        
                        echo '<li class="'.$profiles.'">
                                    <a href="profiles">
                                        <i class="material-icons">person_box</i>
                                        <p>User Profile</p>
                                    </a>
                                </li>';
                    }
                    
                    ?>
                </ul>
    	    </div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-transparent navbar-absolute" style="width:100%;">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" style="cursor:auto;" onclick="history.go(-1); return false;"><i class="material-icons">arrow_back</i></a>
                </div>
                
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" style="font-size:26px !important;" onclick="javascript:void(0);"><?=ucwords($currentPath);?></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="dashboard">
                                <i class="material-icons">dashboard</i>
                                <p class="hidden-lg hidden-md">Dashboard</p>
                            </a>
                        </li>
                        
                        <!-- notification DEAN QUESTIONNAIRE VALIDATION -->
                        <?php
                            if($_SESSION["users"]["user_level"] == "2" && $_SESSION["users"][0]["position"] == "2"){
                            
                        ?>
                        <li>
                            <a href="notifications">
                                <i class="material-icons">notifications</i>
                                    <span class="notification"><?= $validationCount;?></span>
                                <p class="hidden-lg hidden-md">Notifications</p>
                            </a>
                        </li>
                        <?php
                            }
                        ?>
                        <?php
                            if($_SESSION["users"]["user_level"] == "2" && $_SESSION["users"][0]["position"] == "1"){
                        ?>
                        <li>
                            <a href="notifications">
                                <i class="material-icons">notifications</i>
                                    <span class="notification"><?= $validationDisapprovedCount;?></span>
                                <p class="hidden-lg hidden-md">Notifications</p>
                            </a>
                        </li>
                        <?php
                            }
                        ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="assets/uploads/<?=(($_SESSION["users"]["image"] == "") ? "default.png" : $_SESSION["users"]["image"])?>" alt="" style="height:20px;width:20px;">
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" style="width:350px;">
                                <li class="text-center">
                                    <?php
                                            
                                        echo '<a href="javascript:void(0)">USER PROFILE</a>';
                                    ?>
                                    
                                </li>
                               
                                <li>
                                    <?php
                                    
                                        if($_SESSION["users"]["user_level"] == "1" || $_SESSION["users"]["user_level"] == "2"){
                                            if(isset($_SESSION["users"]["firstname"])){
                                                $name = $_SESSION["users"]["firstname"]. " " . $_SESSION["users"]["middlename"] . " " . $_SESSION["users"]["lastname"];
                                            }else if(isset($_SESSION['users'][0]["firstname"])){
                                                $name = $_SESSION["users"][0]["firstname"]. " " . $_SESSION["users"][0]["middlename"] . " " . $_SESSION["users"][0]["lastname"];
                                            }
                                            
                                        }else{

                                            if(isset($_SESSION["users"]["firstname"])){
                                                $name = $_SESSION["users"]["firstname"];
                                            }else if(isset($_SESSION['users'][0]["firstname"])){
                                                $name = $_SESSION["users"][0]["firstname"];
                                            }
                                        }
                                        echo '<a href="javascript:void(0)"><i class="material-icons" style="margin-right:30px;">code</i>'.$_SESSION["users"]["code"].'</a>';
                                        echo '<a href="javascript:void(0)"><i class="material-icons" style="margin-right:30px;">face</i>'.ucfirst($name).'</a>';
                                        
                                    ?>
                            
                                    <?php
                                        if($_SESSION['users']['user_level'] == "99"){
                                            $displayUserLevel = "admin";
                                        }else if($_SESSION['users']['user_level'] == "1"){
                                            $displayUserLevel = "Student";
                                        } else if($_SESSION['users']['user_level'] == "2"){
                                            if($_SESSION['users'][0]["position"] == "1"){
                                                $displayUserLevel = "Faculty";
                                            }else{
                                                $displayUserLevel = "Dean";
                                            }
                                    
                                        }else if($_SESSION['users']['user_level'] == "3"){
                                            $displayUserLevel = "Vpaa";
                                        }else{
                                            $displayUserLevel = "Guest";
                                        }
                                        echo '<a href="javascript:void(0)"><i class="material-icons" style="margin-right:30px;">supervisor_account</i>'.ucfirst($displayUserLevel).'</a>';
                                    ?>
                                    
                                    <?php
                                        if($_SESSION["users"]["user_level"] == "1" || $_SESSION["users"]["user_level"] == "2"){
                                            echo '<a href="javascript:void(0)"><i class="material-icons" style="margin-right:30px;">view_quilt</i>'.ucfirst($_SESSION["users"][0]["department"]).'</a>';

                                        }
                                    ?>
                                
                          
                                <?php     

                                        if($_SESSION["users"]["user_level"] == "1"){
                                            $course = $_SESSION["users"][0]["course"];
                                            $yearlevel = $_SESSION["users"][0]["year_level"];
                                            
                                            echo '
                                                    <a href="javascript:void(0)"><i class="material-icons" style="margin-right:30px;">book</i>'.ucfirst($course).'</a>
                                                ';
                                                
                                            echo '
                                                    <a href="javascript:void(0)"><i class="material-icons" style="margin-right:30px;">show_chart</i>'.ucfirst($yearlevel).'</a>
                                                ';
                                        }
                                    ?>   
                                </li>
                            </ul>
                        </li>
                        
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="material-icons">settings</i>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="logout/changepassword"><i class="material-icons">autorenew</i>Change Password</a>
                                </li>
                                <li>
                                    <a href="logout"><i class="material-icons">exit_to_app</i>Logout</a>
                                </li>
                            </ul>
                        </li>
                        
                    </ul>
                    
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container-fluid">
            