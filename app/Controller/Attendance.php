<?php
namespace App\Controller;

use App\Model\AttendanceModel;
use App\Model\SubjectModel;
use App\Model\UserModel;
use Core\Request;
use Core\Response;
use Core\Validation;

class Attendance {

    public Request $request;
    public Response $response;
    public Validation $validation;
    public SubjectModel $subjectModel;
    public UserModel $userModel;
    public AttendanceModel $attendanceModel;

    public function __construct(Request $request, Response $response, Validation $validation, SubjectModel $subjectModel, UserModel $userModel, AttendanceModel $attendanceModel){
        $this->request = $request;
        $this->response = $response;
        $this->validation = $validation;
        $this->subjectModel = $subjectModel;
        $this->userModel = $userModel;
        $this->attendanceModel = $attendanceModel;
    }

    public function index(){
        $data = $this->request->getBody();
        $rowPerPage = 3;
        $offset = 0;
        $pattern = [];
        $condition = [];
        $query = null;

        if(isset($data['search'])){
            $pattern['subject_name'] = $data['search'];
        }

        if(isTeacher()){
            $condition['teacher_id'] = getUserId();
        }
        
        if(isStudent()){
            $query = 'inner join users u on s.class_id = u.class_id';
            $condition['user_id'] = getUserId();
        }

        $pageSum = $this->subjectModel->pages($condition,$rowPerPage,$pattern,$query);
        $subjectsData = $this->subjectModel->pagination($condition,$rowPerPage,$offset,$pattern,$data,$query);

        return view('attendance/attendance',['pages'=>$pageSum,'subjectsData'=>$subjectsData,'data'=>$data]);
    }

    public function attendanceSubject(){
        $data = $this->request->getBody();
        $query = 'inner join subjects s on u.class_id = s.class_id';
        $year = date('Y') - 2;
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        if(isTeacher()){
            $teacherSubjectsId = $this->subjectModel->teacherSubjectsId(getUserId());

            if(!in_array($data['subject_id'],$teacherSubjectsId)){
                setFlashMessage('error','You do not have permission to add attendance at subjects who are not assigned to you');
                redirect('/attendance');
                exit;
            }
        }

        if(isStudent()){
            $studentSubjects = $this->subjectModel->studentSubjectsId(getUserId());

            if(!in_array($data['subject_id'],$studentSubjects)){
                setFlashMessage('error','You do not have permission to view attrndance to other subjects that arent assigned to you');
                redirect('/attendance');
                exit;
            }
        }

        $studentsData = $this->userModel->getData(['role_name'=>'student','subject_id'=>$data['subject_id']],[],[],false,$query);

        if(!$studentsData){
            setFlashMessage('error','Subject dont exist');
            redirect('/attendance');
            exit;
        }
      
        return view('attendance/attendance_subject',['studentsData'=>$studentsData, 'year'=>$year, 'months'=>$months, 'data'=>$data]);
    }

    public function showAttendance(){
        $data = $this->request->getBody();
        $date = strtotime($data['month'].' '.$data['year']);
        $monthByNumber = date_parse($data['month'])['month'];
        $formattedDate = date('Y-m', $date);
        $attendanceCondition = ['subject_id'=>$data['subject_id']];
        $attendancesQuery = 'inner join users u on u.user_id = a.student_id';
        $attendancesStudent = [];
        $studentsQuery = 'inner join subjects s on u.class_id = s.class_id';
        $year = date('Y') - 2;
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $getRules = $this->attendanceModel->attendanceRules();

        if(!empty($data['student_id'])){
            $attendanceCondition['student_id'] = $data['student_id'];
        }

        if(isStudent()){
            $attendanceCondition = ['student_id'=>getUserId()];
        }

        $studentsData = $this->userModel->getData(['role_name'=>'student','subject_id'=>$data['subject_id']],[],[],false,$studentsQuery);

        if($this->validation->validate($data,$getRules)){
            $day = cal_days_in_month(CAL_GREGORIAN, $monthByNumber, $data['year']); 

            $attendancesData = $this->attendanceModel->getData($attendanceCondition,['attendance_date'=>$formattedDate],[],false,$attendancesQuery);
            foreach($attendancesData as $attendanceData){
                $attendancesStudent[$attendanceData->name.' '.$attendanceData->surename][] = ['attendance_date'=> date("d", strtotime($attendanceData->attendance_date)),'attendance'=>$attendanceData->attendance ];
            }
            return view('attendance/attendance_subject',['attendancesStudent'=>$attendancesStudent,'studentsData'=>$studentsData, 'year'=>$year, 'months'=>$months, 'day'=>$day, 'data'=>$data]);
        }
        return view('attendance/attendance_subject',['validation'=>$this->validation,'studentsData'=>$studentsData, 'year'=>$year, 'months'=>$months, 'data'=>$data]);
    }


    public function attendanceStudents(){    
        $data = $this->request->getBody();
        $query = 'inner join subjects s on u.class_id = s.class_id';
        $condition = ['subject_id' => $data['subject_id']];
        $rowPerPage = 5;
        $offset = 0;
        $pattern = [];

        if(isTeacher()){
            $teacherSubjectsId = $this->subjectModel->teacherSubjectsId(getUserId());

            if(!in_array($data['subject_id'],$teacherSubjectsId)){
                setFlashMessage('error','You do not have permission to add attendance at subjects who are not assigned to you');
                redirect('/attendance');
                exit;
            }
        }

        if(isset($data['search'])){
            $pattern['name'] = $data['search'];
        }

        $pageSum = $this->userModel->pages($condition,$rowPerPage,$pattern,$query);
        $studentsData = $this->userModel->pagination($condition,$rowPerPage,$offset,$pattern,$data,$query);

        return view('attendance/attendance_students',['pages'=>$pageSum,'studentsData'=>$studentsData,'data'=>$data]);
    }

    public function addAttendance(){
        $data = $this->request->getBody();

        if(isTeacher()){
            $teacherSubjectsId = $this->subjectModel->teacherSubjectsId(getUserId());

            if(!in_array($data['subject_id'],$teacherSubjectsId)){
                setFlashMessage('error','You do not have permission to add attendance at subjects who are not assigned to you');
                redirect('/attendance');
                exit;
            }
            
            $teacherStudentsId = $this->subjectModel->teacherStudentsId(getUserId(),$data['subject_id']);

            if(!in_array($data['student_id'],$teacherStudentsId)){
                setFlashMessage('error','You do not have permission to add attendance students who are not assigned to you or arent in this class');
                redirect('/attendance');
                exit;
            }
        }

        return view('attendance/attendance_add',['data'=>$data]);
    }

    public function insertAttendance(){
        $data = $this->request->getBody();
        $getRules = $this->attendanceModel->attendanceRules();

        if($this->validation->validate($data,$getRules)){
            $date = strtotime($data['attendance_date']);
            $date = date('Y-m-d', $date);
            $data['attendance_date'] = $date;
            
            $this->attendanceModel->insertData($data);
            setFlashMessage('success','Attendance inserted successfully');
            redirect("/attendance/students?subject_id=".$data['subject_id']);
            exit;
        }
        return view('attendance/attendance_add',['validation'=>$this->validation,'data'=>$data]);
    }

    public function removeAttendance(){
        $data = $this->request->getBody();
        $query = 'inner join users u on u.user_id = a.student_id';
        $condition = ['student_id'=>$data['student_id'],'subject_id'=>$data['subject_id']];

        $attendancesData = $this->attendanceModel->getData($condition,[],[],false,$query);

        return view('attendance/attendance_remove',['attendancesData'=>$attendancesData,'data'=>$data]);
    }

    public function deleteAttendance(){
        $data = $this->request->getBody();
        $checkingAttendance = $this->attendanceModel->getDataById($data['attendance_id']);

        if(!$checkingAttendance){
            setFlashMessage('error','Attendance dont exist');
            redirect('/attendance/students?subject_id='.$data['subject_id']);
            exit;
        }

        $this->attendanceModel->deleteData($data['attendance_id']);
        setFlashMessage('success','Attendance deleted successfully');
        redirect("/attendance/students?subject_id=".$data['subject_id']);

    }

}