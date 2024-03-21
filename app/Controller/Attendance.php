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

        $studentsData = $this->userModel->getData(['role_name'=>'student','subject_id'=>$data['subject_id']],[],[],false,$query);
      
        return view('attendance/attendance_subject',['studentsData'=>$studentsData, 'year'=>$year, 'months'=>$months, 'data'=>$data]);
    }

    public function showAttendance(){
        $data = $this->request->getBody();
        $date = strtotime($data['month'].' '.$data['year']);
        $formattedDate = date('Y-m', $date);
        $condition = [];
        $attendancesStudent = [];
        $attendancesQuery = 'inner join users u on u.user_id = a.student_id';
        $studentsQuery = 'inner join subjects s on u.class_id = s.class_id';
        $year = date('Y') - 2;
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $getRules = $this->attendanceModel->attendanceRules();

        if(!empty($data['student_id'])){
            $condition = ['student_id'=>$data['student_id']];
        }

        if(isStudent()){
            $condition = ['student_id'=>getUserId()];
        }

        $studentsData = $this->userModel->getData(['role_name'=>'student','subject_id'=>$data['subject_id']],[],[],false,$studentsQuery);

        if($this->validation->validate($data,$getRules)){
            $attendancesData = $this->attendanceModel->getData($condition,['attendance_date'=>$formattedDate],[],false,$attendancesQuery);
    
            foreach($attendancesData as $attendanceData){
                $attendancesStudent[$attendanceData->name.' '.$attendanceData->surename][] = ['attendance_date'=> date("d", strtotime($attendanceData->attendance_date)),'attendance'=>$attendanceData->attendance ];
            }

            return view('attendance/attendance_subject',['attendancesStudent'=>$attendancesStudent,'studentsData'=>$studentsData, 'year'=>$year, 'months'=>$months, 'data'=>$data]);
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

        if(isset($data['search'])){
            $pattern['name'] = $data['search'];
        }

        $pageSum = $this->userModel->pages($condition,$rowPerPage,$pattern,$query);
        $studentsData = $this->userModel->pagination($condition,$rowPerPage,$offset,$pattern,$data,$query);

        return view('attendance/attendance_students',['pages'=>$pageSum,'studentsData'=>$studentsData,'data'=>$data]);
    }

    public function addAttendance(){
        $data = $this->request->getBody();

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