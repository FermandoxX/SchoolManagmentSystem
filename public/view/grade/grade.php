<main id="main" class="main">

    <section class="section">

      <div class="row">
        <div class="col-lg-12" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">

          <div class="card w-100">
            <div class="card-body">
              <div class="d-flex">
                <h3 class="mt-3 mr-5 card-title col-9">Grade</h3>
              </div>
            </div>
          </div>

          <div class="container mb-5 d-flex justify-content-end align-items-center w-100">
            <div class="row height d-flex justify-content-end align-items-center w-75">
              <div class="col-md-8 w-50">
                <form class="search" method="get" action="/grade">
                   <i class="fa fa-search"></i>
                   <input type="text" name="search" class="form-control" placeholder="Search Now">
                   <button class="btn btn-primary">Search</button>
                </form>         
              </div>        
            </div>
          </div>
          <div class="d-flex row gap-5 justify-content-center align-items-center w-100">
            <?php foreach($studentsData as $studentData): ?>
                <div class="col-xl-4" style=" width:275px; height:310px">
                  <div class="card w-100 h-100">
                    <div class="card-body profile-card pt-4 d-flex flex-column justify-content-center align-items-center">
                      <img src="/../image/<?php echo $studentData->image ?? 'Sad photo icon.jpg'; ?>" alt="Profile" class="rounded-circle w-50" width="112" height="112" style="object-fit:cover">
                      <h3><?php echo $studentData->name.' '.$studentData->surename?></h3>
                      <h6><?php echo "Class: "; echo strlen($studentData->class_name) < 23 ? ucfirst($studentData->class_name) : substr($studentData->class_name,0,20).'...' ?></h6>
                      <h6><?php echo "Email: "; echo strlen($studentData->email) < 23 ? ucfirst($studentData->email) : substr($studentData->email,0,20).'...' ?></h6>
                      <?php if($studentData->user_id != getUserId()): ?>
                      <div class="d-flex gap-2">
                        <a class="editDeleteButton mb-3 mr-3 bg-primary text-white" href=<?php echo isAdmin() == true ? "grade/supject?student_id=$studentData->user_id" : "grade/supject/add?student_id=$studentData->user_id&teacher_id=".getUserId();?>>Add Grade</a>
                      </div>
                      <?php endif; ?> 
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
            </div>
              <?php
                $currentPage = isset($data['pageNr']) ? $data['pageNr'] : 1 ;
                unset($data['pageNr']);
                $url = false;
                if (count($data) > 0) {
                  $url =  '&' . http_build_query($data);
                }
              ?>
              <?php if($params['pages']): ?>
                <div class="mt-5">
                  <nav class="pagination-outer" aria-label="Page navigation">
                    <ul class="pagination">
                      <?php if($currentPage > 1): ?>
                        <li class="page-item">
                          <a href="?pageNr=<?= $currentPage - 1?><?= $url ?? ''?>" class="page-link" aria-label="Previous">
                            <span aria-hidden="true">Prev</span>
                          </a>
                        </li>  
                      <?php endif; ?>  
                      
                      <?php for($i = 1;$i <= $params['pages'];$i++): ?>
                          <li class="page-item"><a class="page-link" href="?pageNr=<?=$i?><?= $url ?? ''?>"><?= $i ?></a></li>
                      <?php endfor; ?>
                      
                      <?php if($params['pages'] > $currentPage): ?>
                        <li class="page-item">
                          <a href="?pageNr=<?=$currentPage + 1?><?= $url ?? ''?>" class="page-link" aria-label="Previous">
                            <span aria-hidden="true">Next</span>
                          </a>
                        </li>  
                      <?php endif; ?>        
                    </ul>
                  </nav>
                </div>
              <?php endif; ?>
            </div>

  </div>
</div>
    </section>

</main>




            <!-- <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Job Position</th>
                            <th>Since</th>
                            <th class="text-right">Salary</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>Andrew Mike</td>
                            <td>Develop</td>
                            <td>2013</td>
                            <td class="text-right">€ 99,225</td>
                            <td class="td-actions text-right">
                                <button type="button" rel="tooltip" class="btn btn-success btn-round btn-just-icon btn-sm" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                </button>
                            </td>
                        </tr>
                        <tr>
    
                            <td class="text-center">2</td>
                            <td>John Doe</td>
                            <td>Design</td>
                            <td>2012</td>
                            <td class="text-right">€ 89,241</td>
                            <td class="td-actions text-right">
                                <button type="button" rel="tooltip" class="btn btn-success btn-round btn-just-icon btn-sm" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">3</td>
                            <td>Alex Mike</td>
                            <td>Design</td>
                            <td>2010</td>
                            <td class="text-right">€ 92,144</td>
                            <td class="td-actions text-right">
                                <button type="button" rel="tooltip" class="btn btn-success btn-round btn-just-icon btn-sm" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">4</td>
                            <td>Mike Monday</td>
                            <td>Marketing</td>
                            <td>2013</td>
                            <td class="text-right">€ 49,990</td>
                            <td class="td-actions text-right">
                                <button type="button" rel="tooltip" class="btn btn-success btn-round btn-just-icon btn-sm" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">5</td>
                            <td>Paul Dickens</td>
                            <td>Communication</td>
                            <td>2015</td>
                            <td class="text-right">€ 69,201</td>
                            <td class="td-actions text-right">
                                <button type="button" rel="tooltip" class="btn btn-success btn-round btn-just-icon btn-sm" data-original-title="" title="">
                                    <i class="material-icons">edit</i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div> -->