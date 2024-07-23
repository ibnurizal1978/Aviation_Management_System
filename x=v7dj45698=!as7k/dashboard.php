<?php
require_once 'header.php';

function minutesToHours($minutes) 
{ 
    $hours = (int)($minutes / 60); 
    $minutes -= $hours * 60; 
    return sprintf("%02d:%02.0f", $hours, $minutes); 
}
?>

            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                <div class="content">
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Scroll Height 250px -->
                            <div class="block">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">Crew Hours</h3>
                                </div>
                                <div class="block-content" data-toggle="slimscroll" data-height="250px" data-color="#ffca28" data-opacity="1" data-always-visible="true">
                                <table class="table table-borderless table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">LIC No.</th>
                                    <th>Name</th>
                                    <th class="text-right">This Month Hrs</th>
                                    <th class="text-right">Total Hrs</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql  = "SELECT user_id,full_name, lic_no FROM tbl_user WHERE department_id = 4 AND user_active_status = 1 AND user_position LIKE '%pilot' AND client_id = '".$_SESSION['client_id']."'";
                                $h    = mysqli_query($conn,$sql);
                                while($row = mysqli_fetch_assoc($h)) {
                                
                                /*-------------------------------------------------------*/

                                    //hitung hrs untuk bulan ini - sebagai captain
                                    //$sql_mth1   = "SELECT SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id) = '".$row['user_id']."' AND month(b.afml_date) = 01 AND year(b.afml_date) = 2020 AND a.client_id = '".$_SESSION['client_id']."'";
                                    //$h_mth1     = mysqli_query($conn, $sql_mth1);
                                    //$row_mth1   = mysqli_fetch_assoc($h_mth1);

                                    //hitung hrs untuk bulan ini - sebagai copilot
                                    //$sql_mth2   = "SELECT SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_copilot_user_id) = '".$row['user_id']."' AND month(b.afml_date) = 01 AND year(b.afml_date) = 2020 AND a.client_id = '".$_SESSION['client_id']."'";
                                    //$h_mth2     = mysqli_query($conn, $sql_mth2);
                                    //$row_mth2   = mysqli_fetch_assoc($h_mth2);

                                    //$this_mth_flt_hrs      = $row_mth1['total_flt_hrs'] + $row_mth2['total_flt_hrs'];

                                    $sql_tot = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id = '".$row['user_id']."' OR b.afml_copilot_user_id = '".$row['user_id']."') AND month(a.afml_date) = month(NOW())  AND year(a.afml_date) = year(NOW()) AND a.client_id = '".$_SESSION['client_id']."'";
                                    $h_tot     = mysqli_query($conn, $sql_tot);
                                    $row_tot   = mysqli_fetch_assoc($h_tot);
                                    $this_mth_flt_hrs      = $row_tot['total_flt_hrs'];

                                /*---------------------------------------------------------*/

                                    //hitung total hrs untuk TOTAL - sebagai captain
                                    //$sql_tot1   = "SELECT SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id) = '".$row['user_id']."' AND a.client_id = '".$_SESSION['client_id']."'";
                                    //$h_tot1     = mysqli_query($conn, $sql_tot1);
                                    //$row_tot1   = mysqli_fetch_assoc($h_tot1);

                                    //hitung total hrs untuk  TOTAL - sebagai copilot
                                    //$sql_tot2   = "SELECT SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_copilot_user_id) = '".$row['user_id']."' AND a.client_id = '".$_SESSION['client_id']."'";
                                    //$h_tot2     = mysqli_query($conn, $sql_tot2);
                                    //$row_tot2   = mysqli_fetch_assoc($h_tot2);

                                    $sql_tot2 = "SELECT SUM(a.afml_block_hrs) AS total_block_hrs, SUM(a.afml_flt_hrs) AS total_flt_hrs FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) WHERE (b.afml_captain_user_id = '".$row['user_id']."' OR b.afml_copilot_user_id = '".$row['user_id']."') AND a.client_id = '".$_SESSION['client_id']."'";
                                    $h_tot2     = mysqli_query($conn, $sql_tot2);
                                    $row_tot2   = mysqli_fetch_assoc($h_tot2);


                                    $total_flt_hrs = $row_tot2['total_flt_hrs'];
                                    //$total_flt_hrs      = $row_tot1['total_flt_hrs'] + $row_tot2['total_flt_hrs'];

                                /*--------------------------------------------------------*/                                   
                                ?>                                
                                <tr>
                                    <td>
                                        <a class="font-w600" href="#"><?php echo $row['lic_no'] ?></a>
                                    </td>
                                    <td><?php echo $row['full_name'] ?></td> 
                                    <td class="text-right">
                                        <span class="text-black"><?php echo minutesToHours($this_mth_flt_hrs) ?></a></span>
                                    </td>
                                    <td class="text-right">
                                        <span class="text-black"><?php echo minutesToHours($total_flt_hrs) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="report-pilot-hours-detail.php?act=29dvi59&ntf=29dvi59-<?php echo $row["user_id"]?>-94dfvj!sdf-349ffuaw">Detail</a>
                                    </td>                                    
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>                                </div>
                            </div>
                            <!-- END Scroll Height 250px -->
                        </div>

                        <div class="col-lg-6">
                            <!-- Scroll Height 250px -->
                            <div class="block">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">EOB Hours</h3>
                                </div>
                                <div class="block-content" data-toggle="slimscroll" data-height="250px" data-color="#ffca28" data-opacity="1" data-always-visible="true">
                                    <p>Potenti elit lectus augue eget iaculis vitae etiam, ullamcorper etiam bibendum ad feugiat magna accumsan dolor, nibh molestie cras hac ac ad massa, fusce ante convallis ante urna molestie vulputate bibendum tempus ante justo arcu erat accumsan adipiscing risus, libero condimentum venenatis sit nisl nisi ultricies sed, fames aliquet consectetur consequat nostra molestie neque nullam scelerisque neque commodo turpis quisque etiam egestas vulputate massa, curabitur tellus massa venenatis congue dolor enim integer luctus, nisi suscipit gravida fames quis vulputate nisi viverra luctus id leo dictum lorem, inceptos nibh orci.</p>
                                    <p>Potenti elit lectus augue eget iaculis vitae etiam, ullamcorper etiam bibendum ad feugiat magna accumsan dolor, nibh molestie cras hac ac ad massa, fusce ante convallis ante urna molestie vulputate bibendum tempus ante justo arcu erat accumsan adipiscing risus, libero condimentum venenatis sit nisl nisi ultricies sed, fames aliquet consectetur consequat nostra molestie neque nullam scelerisque neque commodo turpis quisque etiam egestas vulputate massa, curabitur tellus massa venenatis congue dolor enim integer luctus, nisi suscipit gravida fames quis vulputate nisi viverra luctus id leo dictum lorem, inceptos nibh orci.</p>
                                    <p>Potenti elit lectus augue eget iaculis vitae etiam, ullamcorper etiam bibendum ad feugiat magna accumsan dolor, nibh molestie cras hac ac ad massa, fusce ante convallis ante urna molestie vulputate bibendum tempus ante justo arcu erat accumsan adipiscing risus, libero condimentum venenatis sit nisl nisi ultricies sed, fames aliquet consectetur consequat nostra molestie neque nullam scelerisque neque commodo turpis quisque etiam egestas vulputate massa, curabitur tellus massa venenatis congue dolor enim integer luctus, nisi suscipit gravida fames quis vulputate nisi viverra luctus id leo dictum lorem, inceptos nibh orci.</p>
                                </div>
                            </div>
                            <!-- END Scroll Height 250px -->
                        </div>
                    </div>
                        

                    <div class="row invisible" data-toggle="appear">



                        <!-- Row #1 -->
                        <div class="col-6 col-xl-3">
                            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                                <div class="block-content block-content-full clearfix">
                                    <div class="float-right mt-15 d-none d-sm-block">
                                        <i class="si si-bag fa-2x text-primary-light"></i>
                                    </div>
                                    <div class="font-size-h3 font-w600 text-primary" data-toggle="countTo" data-speed="1000" data-to="1500">0</div>
                                    <div class="font-size-sm font-w600 text-uppercase text-muted">Sales</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-xl-3">
                            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                                <div class="block-content block-content-full clearfix">
                                    <div class="float-right mt-15 d-none d-sm-block">
                                        <i class="si si-wallet fa-2x text-earth-light"></i>
                                    </div>
                                    <div class="font-size-h3 font-w600 text-earth">$<span data-toggle="countTo" data-speed="1000" data-to="780">0</span></div>
                                    <div class="font-size-sm font-w600 text-uppercase text-muted">Earnings</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-xl-3">
                            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                                <div class="block-content block-content-full clearfix">
                                    <div class="float-right mt-15 d-none d-sm-block">
                                        <i class="si si-envelope-open fa-2x text-elegance-light"></i>
                                    </div>
                                    <div class="font-size-h3 font-w600 text-elegance" data-toggle="countTo" data-speed="1000" data-to="15">0</div>
                                    <div class="font-size-sm font-w600 text-uppercase text-muted">Messages</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 col-xl-3">
                            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                                <div class="block-content block-content-full clearfix">
                                    <div class="float-right mt-15 d-none d-sm-block">
                                        <i class="si si-users fa-2x text-pulse"></i>
                                    </div>
                                    <div class="font-size-h3 font-w600 text-pulse" data-toggle="countTo" data-speed="1000" data-to="4252">0</div>
                                    <div class="font-size-sm font-w600 text-uppercase text-muted">Online</div>
                                </div>
                            </a>
                        </div>
                        <!-- END Row #1 -->
                    </div>
                    <div class="row invisible" data-toggle="appear">
                        <!-- Row #2 -->
                        <div class="col-md-6">
                            <div class="block block-rounded block-bordered">
                                <div class="block-header block-header-default border-b">
                                    <h3 class="block-title">
                                        Sales <small>This week</small>
                                    </h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                            <i class="si si-refresh"></i>
                                        </button>
                                        <button type="button" class="btn-block-option">
                                            <i class="si si-wrench"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content block-content-full">
                                    <div class="pull-all pt-50">
                                        <!-- Lines Chart Container functionality is initialized in js/pages/be_pages_dashboard.min.js which was auto compiled from _es6/pages/be_pages_dashboard.js -->
                                        <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                                        <canvas class="js-chartjs-dashboard-lines"></canvas>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="row items-push text-center">
                                        <div class="col-6 col-sm-4">
                                            <div class="font-w600 text-success">
                                                <i class="fa fa-caret-up"></i> +16%
                                            </div>
                                            <div class="font-size-h4 font-w600">720</div>
                                            <div class="font-size-sm font-w600 text-uppercase text-muted">This Month</div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="font-w600 text-danger">
                                                <i class="fa fa-caret-down"></i> -3%
                                            </div>
                                            <div class="font-size-h4 font-w600">160</div>
                                            <div class="font-size-sm font-w600 text-uppercase text-muted">This Week</div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="font-w600 text-success">
                                                <i class="fa fa-caret-up"></i> +9%
                                            </div>
                                            <div class="font-size-h4 font-w600">24.3</div>
                                            <div class="font-size-sm font-w600 text-uppercase text-muted">Average</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="block block-rounded block-bordered">
                                <div class="block-header block-header-default border-b">
                                    <h3 class="block-title">
                                        Earnings <small>This week</small>
                                    </h3>
                                    <div class="block-options">
                                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                            <i class="si si-refresh"></i>
                                        </button>
                                        <button type="button" class="btn-block-option">
                                            <i class="si si-wrench"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="block-content block-content-full">
                                    <div class="pull-all pt-50">
                                        <!-- Lines Chart Container functionality is initialized in js/pages/be_pages_dashboard.min.js which was auto compiled from _es6/pages/be_pages_dashboard.js -->
                                        <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                                        <canvas class="js-chartjs-dashboard-lines2"></canvas>
                                    </div>
                                </div>
                                <div class="block-content bg-white">
                                    <div class="row items-push text-center">
                                        <div class="col-6 col-sm-4">
                                            <div class="font-w600 text-success">
                                                <i class="fa fa-caret-up"></i> +4%
                                            </div>
                                            <div class="font-size-h4 font-w600">$ 6,540</div>
                                            <div class="font-size-sm font-w600 text-uppercase text-muted">This Month</div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="font-w600 text-danger">
                                                <i class="fa fa-caret-down"></i> -7%
                                            </div>
                                            <div class="font-size-h4 font-w600">$ 1,525</div>
                                            <div class="font-size-sm font-w600 text-uppercase text-muted">This Week</div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="font-w600 text-success">
                                                <i class="fa fa-caret-up"></i> +35%
                                            </div>
                                            <div class="font-size-h4 font-w600">$ 9,352</div>
                                            <div class="font-size-sm font-w600 text-uppercase text-muted">Balance</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END Row #2 -->
                    </div>
                    <div class="row invisible" data-toggle="appear">
                        <!-- Row #3 -->
                        <div class="col-md-6">
                            <div class="block block-rounded block-bordered">
                                <div class="block-header block-header-default border-b">
                                    <h3 class="block-title">Aircraft Summary</h3>
                                </div>
                                <div class="block-content">
                                    <table class="table table-borderless table-striped">
                                        <thead>
                                            <tr>
                                                <th>Total HRS</th>
                                                <th>Total LDG</th>
                                                <th>Reg Code</th>
                                                <th style="text-align: center">History</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          <?php
                                          $sql  = "SELECT aircraft_ac_total_hrs as total_hrs, aircraft_ac_total_ldg as total_ldg, aircraft_reg_code FROM tbl_aircraft_master group by aircraft_reg_code order by total_hrs DESC";
                                          $h    = mysqli_query($conn, $sql);
                                          while($row = mysqli_fetch_assoc($h)) {
                                          ?>
                                            <tr>
                                                <td><?php echo minutesToHours($row['total_hrs']) ?></td>
                                                <td><?php echo $row['total_ldg'] ?></td>
                                                <td class="d-none d-sm-table-cell"><?php echo $row['aircraft_reg_code'] ?></td>
                                                <td align="center"><a class="font-w600" href="dashboard-aircraft-afml.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_reg_code"]?>-94dfvj!sdf-349ffuaw">view detail</a></td>    
                                            </tr>
                                          <?php } mysqli_free_result($h) ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="block block-rounded block-bordered">
                                <div class="block-header block-header-default border-b">
                                    <h3 class="block-title">Crew Summary</h3>
                                </div>
                                <div class="block-content">
                                    <table class="table table-borderless table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Total HRS</th>
                                                <th style="text-align: center">History</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          <?php
                                          $sql  = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(afml_block_hrs))) AS total_block_hrs, SEC_TO_TIME(SUM(TIME_TO_SEC(afml_flt_hrs))) AS total_flt_hrs, full_name FROM tbl_afml_detail a INNER JOIN tbl_afml b USING (afml_page_no) INNER JOIN tbl_user c ON b.afml_captain_user_id = c.user_id WHERE a.client_id = '".$_SESSION['client_id']."'";
                                        echo $sql;
                                          $h    = mysqli_query($conn, $sql);
                                          while($row = mysqli_fetch_assoc($h)) {
                                          ?>
                                            <tr>
                                                <td><?php echo $row['full_name'] ?></td>
                                                <td><?php echo $row['total_flt_hrs'] ?></td>
                                                <td class="d-none d-sm-table-cell"><?php echo $row['aircraft_reg_code'] ?></td>
                                                <td align="center"><a class="font-w600" href="dashboard-aircraft-afml.php?act=29dvi59&ntf=29dvi59-<?php echo $row["aircraft_reg_code"]?>-94dfvj!sdf-349ffuaw">view detail</a></td>    
                                            </tr>
                                          <?php } mysqli_free_result($h) ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- END Row #3 -->
                    </div>
                </div>
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->
<?php require_once 'footer.php' ?>
<script src="assets/js/plugins/chartjs/Chart.bundle.min.js"></script>
<script src="assets/js/pages/be_pages_dashboard.min.js"></script>