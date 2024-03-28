<?php 

namespace App\Model;

use Core\Model;

class AttendanceModel extends Model {
    public $tableName = 'attendance';
    public $primaryKey = 'attendance_id';

    public function attendanceRules(){
        return [
          'attendance_date'=>[self::RULE_REQUIRED,self::RULE_DATE],
          'month'=>[self::RULE_REQUIRED],
          'year'=>[self::RULE_REQUIRED],
        ];
    }

    public function existAttendance($data){
      foreach($data['checkbox'] as $studentId){
        $studentsData = $this->getData(['student_id'=>$studentId]);

        foreach($studentsData as $studentData){
          if(strtotime($studentData->attendance_date) == strtotime($data['attendance_date'])){
            return ['studentId'=>$studentId,'value'=>false];
          } 

        }
      }

      return ['value'=>true];
    }

    public function pages($condition = [],$rowsPerPage,$pattern,$query){

      $numberOfRows = count($this->getData($condition,$pattern,[],false,$query));

      $pages = ceil($numberOfRows/$rowsPerPage);
      return $pages;
    }
  
    public function pagination($condition = [],$limit,$offset,$pattern,$requestData,$query){
        if(isset($requestData['pageNr'])){
            $page = $requestData['pageNr'] - 1;
            $offset = $page * $limit;
        }

        $data = $this->getData($condition,$pattern,['limit'=>$limit,'offset'=>$offset],false,$query);
        return $data;
    }
}