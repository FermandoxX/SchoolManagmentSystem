<main id="main" class="main">

<div class="pagetitle">
  <h1>Attendance</h1>
</div>

<section class="section profile">
  <div class="row">
    <div class="col-xl-2">


    </div>

    <div class="col-xl-8">

      <div class="card">
        <div class="card-body pt-3">

            <div class="tab-pane fade show active profile-edit pt-3" id="profile-edit">

              <!-- Profile Edit Form -->
              <form action="/attendance/insert?student_id=<?=$data['student_id']?>&subject_id=<?=$data['subject_id']?>" method="post" enctype="multipart/form-data">

                <div class="row mb-3">
                    <label class="col-md-4 col-lg-3 col-form-label">Attendance</label>
                    <div class="col-md-8 col-lg-9">
                        <select name='attendance' class="form-select <?php echo isset($params['validation']) && $params['validation']->hasError('class_id') ? ' is-invalid' : ' ';  ?>" id="floatingSelect">
                            <option selected value=1 >Missing</option>
                                <option value=0 >In Class</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo isset($params['validation']) ? $params['validation']->getFirstError('class_id') : ' '?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="datepicker" class="col-md-4 col-lg-3 col-form-label">Date</label>
                    <div class="col-md-8 col-lg-9">
                        <input type="text" name="attendance_date" id="datepicker" class="form-control <?php echo isset($params['validation']) && $params['validation']->hasError('attendance_date') ? ' is-invalid' : ' ';  ?>" readonly>
                        <div class="invalid-feedback">
                            <?php echo isset($params['validation']) ? $params['validation']->getFirstError('attendance_date') : ' '?>
                        </div>
                    </div>

                </div>
                
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form><!-- End Profile Edit Form -->

            </div>

          </div><!-- End Bordered Tabs -->

        </div>
      </div>

    </div>
  </div>
</section>

</main>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#datepicker').datepicker({
        format: "mm/dd/yyyy",
        todayHighlight: true,
        autoclose: true,
        orientation: "bottom",
        endDate: "today"      
      });
    });
  </script>