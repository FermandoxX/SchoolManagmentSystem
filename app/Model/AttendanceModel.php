<?php 

namespace App\Model;

use Core\Model;

class AttendanceModel extends Model {
    public $tableName = 'attendance';
    public $primaryKey = 'attendance_id';

    public function attendanceRules(){
        return [
          'attendance_datetime'=>[self::RULE_REQUIRED],
          'month'=>[self::RULE_REQUIRED],
          'year'=>[self::RULE_REQUIRED]
        ];
    }
}