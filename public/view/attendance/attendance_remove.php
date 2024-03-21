
<main id="main" class="main">
  
    <section class="section">

      <div class="row">
        <div class="col-lg-12" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">

          <div class="card w-100">
            <div class="card-body">
              <div class="d-flex">
                <h3 class="mt-3 mr-5 card-title col-9">Attendance</h3>
              </div>
            </div>
          </div>

          <div class="container mb-5 w-100">
            <div class="row w-100">
              <div class="col-md-8 w-100">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center" scope="col">Name Surename</th>
                    <th class="text-center" scope="col">Attendance</th>
                    <th class="text-center" scope="col">Date</th>
                    <th class="text-center" scope="col">Remove</th>
                  </tr>
                </thead>
                <tbody>
                    <?php foreach($attendancesData as $attendanceData): ?>
                        <tr>
                          <td class="text-center"><?=$attendanceData->name.' '.$attendanceData->surename?></td>
                          <td class="text-center"><?=$attendanceData->attendance == 1 ? 'Missing' : 'In Class'?></td>
                          <td class="text-center"><?=$attendanceData->attendance_date?></td>
                          <td class="text-center"><a href="/attendance/delete?attendance_id=<?=$attendanceData->attendance_id?>&subject_id=<?=$attendanceData->subject_id?>"><i class="bi bi-trash3-fill text-danger"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
              </table>
              </div>        
            </div>
          </div>
      </div>
    </section>

</html>