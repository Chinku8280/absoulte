

 @extends('theme.default')
@section('content')
   
    
        <div class="page-wrapper">
          <!-- Page header -->
          <div class="page-header d-print-none">
            <div class="container-xl">
              <div class="row g-2 align-items-center">
                <div class="col">
                  <h2 class="page-title"> Settings </h2>
                </div>
              </div>
            </div>
          </div>
          <!-- Page body -->
          <div class="page-body">
            <div class="container-xl">
              <div class="card">
                <div class="card-header">
                  <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a href="#constants-settings" class="nav-link active" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <!-- Download SVG icon from http://tabler-icons.io/i/home -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M4 8h4v4h-4z" />
                          <path d="M6 4l0 4" />
                          <path d="M6 12l0 8" />
                          <path d="M10 14h4v4h-4z" />
                          <path d="M12 4l0 10" />
                          <path d="M12 18l0 2" />
                          <path d="M16 5h4v4h-4z" />
                          <path d="M18 4l0 1" />
                          <path d="M18 9l0 11" />
                        </svg> Constants
                      </a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a href="#zone-settings" class="nav-link" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">
                        <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                          <path d="M12.005 21.485a1.994 1.994 0 0 1 -1.418 -.585l-4.244 -4.243a8 8 0 1 1 13.634 -5.05" />
                          <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                          <path d="M19.001 15.5v1.5" />
                          <path d="M19.001 21v1.5" />
                          <path d="M22.032 17.25l-1.299 .75" />
                          <path d="M17.27 20l-1.3 .75" />
                          <path d="M15.97 17.25l1.3 .75" />
                          <path d="M20.733 20l1.3 .75" />
                        </svg> Zone Settings
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active show" id="constants-settings" role="tabpanel">
                      <div class="card">
                        <div class="row g-0">
                          <div class="col-3 d-none d-md-block border-end">
                            <div class="card-body py-0">
                              <!-- <h4 class="subheader">constants settings</h4> -->
                              <div class="list-group list-group-transparent tab">
                                <a href="javascript:void(0);" class="tablinks list-group-item list-group-item-action d-flex align-items-center active" onclick="opensettings(event, 'one')">Salutation</a>
                                <!-- <a href="javascript:void(0);"
                                                                 class="tablinks list-group-item list-group-item-action d-flex align-items-center"
                                                                 onclick="opensettings(event, 'two')">two</a><a href="javascript:void(0);"
                                                                 class="tablinks list-group-item list-group-item-action d-flex align-items-center"
                                                                 onclick="opensettings(event, 'three')">three</a><a href="javascript:void(0);"
                                                                 class="tablinks list-group-item list-group-item-action d-flex align-items-center"
                                                                 onclick="opensettings(event, 'four')">four</a><a href="javascript:void(0);"
                                                                 class="tablinks list-group-item list-group-item-action d-flex align-items-center"
                                                                 onclick="opensettings(event, 'five')">five</a> -->
                              </div>
                            </div>
                          </div>
                          <div class="col d-flex flex-column">
                            <div id="one" class="tabcontent">
                              <div class="card-body">
                                <h5 class="modal-title">Add Salutation</h5>
                                <div class="add-form my-3">
                                  <form id="salutation-form">
                                    <div class="row">
                                      <div class="col-auto">
                                        <div class="mb-3">
                                          <label class="form-label">Salutation Name</label>
                                          <input type="text" name="name" class="form-control" value="Mr" required="">
                                        </div>
                                      </div>
                                      <div class="col-auto">
                                        <label class="form-label" style="visibility: hidden;">Salutation Name</label>
                                        <button type="submit" class="btn btn-primary save-btn">Save</button>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                                <div class="add-form-table">
                                  <div class="table-responsive">
                                    <table id="salutation-table" class="table card-table table-vcenter text-center text-nowrap datatable data-table">
                                      <thead>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th width="200px">Action</th>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td> Mr </td>
                                          <td>
                                            <span class="badge bg-red">Deactive</span>
                                          </td>
                                          <td>
                                            <i class='fa-solid fa-eye cursor-pointer me-2 text-blue btn-edit'></i>
                                            <i class='fa-solid fa-pencil cursor-pointer me-2 text-yellow btn-edit'></i>
                                            <i class='fa-solid cursor-pointer fa-trash me-2 text-red btn-delete'></i>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                              <div class="card-footer bg-transparent mt-auto">
                                <div class="btn-list justify-content-end">
                                  <a href="#" class="btn"> Cancel </a>
                                  <a href="#" class="btn btn-primary"> Submit </a>
                                </div>
                              </div>
                            </div>
                            <!-- <div id="two" class="tabcontent" style="display: none;"><div class="card-body"><h4>content-2</h4></div><div class="card-footer bg-transparent mt-auto"><div class="btn-list justify-content-end"><a href="#" class="btn">
                                                                     Cancel
                                                                 </a><a href="#" class="btn btn-primary">
                                                                     Submit
                                                                 </a></div></div></div> -->
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="zone-settings" role="tabpanel">
                      <div class="row g-2 align-items-center mb-3">
                        <div class="col">
                          <h5 class="modal-title">Add Zone</h5>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                          <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-report">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                              <path d="M12 5l0 14"></path>
                              <path d="M5 12l14 0"></path>
                            </svg> Add New
                          </a>
                        </div>
                      </div>
                      <div class="add-zone-table">
                        <div class="table-responsive">
                          <table id="zone-table" class="table card-table table-vcenter text-center text-nowrap datatable zone-table">
                            <thead>
                              <th>Zone Name</th>
                              <th>P/C Begins With</th>
                              <th>Zone Color</th>
                              <th>Status</th>
                              <th width="200px">Action</th>
                            </thead>
                            <tbody>
                              <tr>
                                <td> South </td>
                                <td>3800014</td>
                                <td>#fff</td>
                                <td>
                                  <span class="badge bg-red">Deactive</span>
                                </td>
                                <td>
                                  <i class='fa-solid fa-eye cursor-pointer me-2 text-blue btn-edit'></i>
                                  <i class='fa-solid fa-pencil cursor-pointer me-2 text-yellow btn-edit'></i>
                                  <i class='fa-solid cursor-pointer fa-trash me-2 text-red btn-delete'></i>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <footer class="footer footer-transparent d-print-none">
            <div class="container-xl">
              <div class="row text-center align-items-center flex-row-reverse">
                <div class="col-12 mt-3 mt-lg-0">
                  <ul class="list-inline list-inline-dots mb-0">
                    <li class="list-inline-item"> Copyright © 2023 <a href="#" class="link-secondary">Absolute</a>. All rights reserved. </li>
                  </ul>
                </div>
              </div>
            </div>
          </footer>
        </div>
      </div>
      <div class="modal modal-blur fade" id="modal-report" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">New report</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="add-zone my-3">
                <form id="zone-form">
                  <div class="row">
                    <div class="col-md-5">
                      <div class="mb-3">
                        <label class="form-label"> Zone Name:</label>
                        <input type="text" name="zone-name" class="form-control" value="" required="">
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="mb-3">
                        <label class="form-label">P/C Begins With:</label>
                        <input type="number" name="zone-number" class="form-control" value="one,two,three" placeholder="380004" required="" multiple>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="mb-3">
                        <label class="form-label">Color:</label>
                        <input type="color" name="zone-color" class="form-control form-control-color" value="#206bc4" title="Choose your color">
                      </div>
                    </div>
                  </div>
                  <!-- <button type="submit" class="btn btn-success save-btn">Save</button> -->
                </form>
              </div>
            </div>
            <div class="modal-footer">
              <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal"> Cancel </a>
              <a href="#" class="btn btn-primary ms-auto save-btn" data-bs-dismiss="modal">
                <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M12 5l0 14"></path>
                  <path d="M5 12l14 0"></path>
                </svg> Save
              </a>
            </div>
          </div>
        </div>
      
    <!-- Tabler Core -->
  
    <script>
        function opensettings(evt, tabno) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabno).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
    <!-- <script type="text/javascript">

        $("#salutation-form").submit(function (e) {
            e.preventDefault();
            var name = $("input[name='name']").val();
            var email = '<span class="badge bg-red">Deactive</span>';

            $(".data-table tbody").append("<tr data-name='" + name + "' data-email='" + email + "'><td>" + name + "</td><td>" + email + "</td><td><i class='fa-solid fa-pencil cursor-pointer me-2 text-yellow btn-edit'></i><i class='fa-solid cursor-pointer fa-trash me-2 text-red btn-delete'></i></td></tr>");

            $("input[name='name']").val('');
            // $("input[name='email']").val('');
        });

        $("#constants-settings").on("click", ".btn-delete", function () {
            $(this).parents("tr").remove();
        });

        $("#constants-settings").on("click", ".btn-edit", function () {
            var name = $(this).parents("tr").attr('data-name');
            // var email = $(this).parents("tr").attr('data-email');

            $(this).parents("tr").find("td:eq(0)").html('<input name="edit_name" class="form-control" value="' + name + '">');
            // $(this).parents("tr").find("td:eq(1)").html('<input name="edit_email" value="'+email+'">');

            $(this).parents("tr").find("td:eq(2)").prepend("<i class='fa-solid cursor-pointer fa-check me-2 text-green btn-update'></i><i class='fa-solid cursor-pointer fa-xmark me-2 text-yellow btn-cancel'></i>")
            $(this).hide();
        });

        $("#constants-settings").on("click", ".btn-cancel", function () {
            var name = $(this).parents("tr").attr('data-name');
            $(this).parents("tr").find("td:eq(0)").text(name);
            $(this).parents("tr").find("td:eq(1)").text(email);

            $(this).parents("tr").find(".btn-edit").show();
            $(this).parents("tr").find(".btn-update").remove();
            $(this).parents("tr").find(".btn-cancel").remove();
        });

        $("#constants-settings").on("click", ".btn-update", function () {
            var name = $(this).parents("tr").find("input[name='edit_name']").val();

            $(this).parents("tr").find("td:eq(0)").text(name);

            $(this).parents("tr").find(".btn-edit").show();
            $(this).parents("tr").find(".btn-cancel").remove();
            $(this).parents("tr").find(".btn-update").remove();
        });

    </script>
    <script type="text/javascript">

        $("#zone-form").submit(function (e) {
            e.preventDefault();
            var name = $("input[name='zone-name']").val();
            var number = $("input[name='zone-number']").val();
            var color = $("input[name='zone-color']").val();
            var status = '<span class="badge bg-red">Deactive</span>';
            $(".zone-table tbody").append("<tr data-name='" + name + "' data-number='" + number + "' data-color='" + color + "'><td>" + name + "</td><td>" + number + "</td><td>" + color + "</td><td>" + status + "</td><td><i class='fa-solid fa-pencil cursor-pointer me-2 text-yellow btn-edit'></i><i class='fa-solid cursor-pointer fa-trash me-2 text-red btn-delete'></i></td></tr>");
            $("input[name='zone-name']").val('');
            $("input[name='zone-number']").val('');
            $("input[name='zone-color']").val('');

        });

        $("#zone-settings").on("click", ".btn-delete", function () {
            $(this).parents("tr").remove();
        });

        $("#zone-settings").on("click", ".btn-edit", function () {
            var name = $(this).parents("tr").attr('data-name');
            var number = $(this).parents("tr").attr('data-number');
            var color = $(this).parents("tr").attr('data-color');

            $(this).parents("tr").find("td:eq(0)").html('<input type="text" name="edit_name" value="' + name + '">');
            $(this).parents("tr").find("td:eq(1)").html('<input type="number" name="edit_number" value="' + number + '">');
            $(this).parents("tr").find("td:eq(2)").html('<input type="color" name="edit_color" value="' + color + '">');

            $(this).parents("tr").find("td:eq(4)").prepend("<i class='fa-solid cursor-pointer fa-check me-2 text-green btn-update'></i><i class='fa-solid cursor-pointer fa-xmark me-2 text-yellow btn-cancel'></i>")
            $(this).hide();
        });

        $("#zone-settings").on("click", ".btn-cancel", function () {
            var name = $(this).parents("tr").attr('data-name');
            var number = $(this).parents("tr").attr('data-number');
            var color = $(this).parents("tr").attr('data-color');

            $(this).parents("tr").find("td:eq(0)").text(name);
            $(this).parents("tr").find("td:eq(1)").text(number);
            $(this).parents("tr").find("td:eq(2)").text(color);

            $(this).parents("tr").find(".btn-edit").show();
            $(this).parents("tr").find(".btn-update").remove();
            $(this).parents("tr").find(".btn-cancel").remove();
        });

        $("#zone-settings").on("click", ".btn-update", function () {
            var name = $(this).parents("tr").find("input[name='edit_name']").val();
            var number = $(this).parents("tr").find("input[name='edit_number']").val();
            var color = $(this).parents("tr").find("input[name='edit_color']").val();

            $(this).parents("tr").find("td:eq(0)").text(name);
            $(this).parents("tr").find("td:eq(1)").text(number);
            $(this).parents("tr").find("td:eq(2)").text(color);

            $(this).parents("tr").attr('data-name', name);
            $(this).parents("tr").attr('data-number', number);
            $(this).parents("tr").attr('data-color', color);

            $(this).parents("tr").find(".btn-edit").show();
            $(this).parents("tr").find(".btn-cancel").remove();
            $(this).parents("tr").find(".btn-update").remove();
        });

    </script> -->
    <script>
        $('#salutation-table').DataTable();
        $('#zone-table').DataTable();

    </script>
@endsection