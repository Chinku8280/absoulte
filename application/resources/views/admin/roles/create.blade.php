@extends('theme.default') @section('content') <style>
  .plus,
  .minus {
    display: inline-block;
    background-repeat: no-repeat;
    background-size: 16px 16px !important;
    width: 16px;
    height: 16px;
    /*vertical-align: middle;*/
  }

  .plus {
    background-image: url(https://img.icons8.com/color/48/000000/plus.png);
  }

  .minus {
    background-image: url(https://img.icons8.com/color/48/000000/minus.png);
  }

  ul {
    list-style: none;
    padding: 0px 0px 0px 20px;
  }

  ul.inner_ul li:before {
    content: "├";
    font-size: 18px;
    margin-left: -11px;
    margin-top: -5px;
    vertical-align: middle;
    float: left;
    width: 8px;
    color: #41424e;
  }

  ul.inner_ul li:last-child:before {
    content: "└";
  }

  .inner_ul {
    padding: 0px 0px 0px 35px;
  }
</style>
<div class="page-wrapper">
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <!-- Page title actions -->
        <div class="col-auto ms-auto d-print-none">
          <div class="btn-list">
            <a href="{{route('roles.index')}}" class="btn btn-primary d-none d-sm-inline-block">
              <!-- Download SVG icon from http://tabler-icons.io/i/plus --> Back
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div class="row"></div> @if (count($errors) > 0) <div class="alert alert-danger">
        <strong>Whoops!</strong> Something went wrong. <br>
        <br>
        <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
      </div> @endif <div class="page-body">
        <div class="container-xl">
          <div class="row">
            <div class="col-lg-12">
              <div class="tab-content">
                <div class="tab-pane active show" id="residential" role="tabpanel">
                  <div class="card">
                    <div class="card-header">
                      <div class="text-muted">
                        <div class="col-lg-12 margin-tb">
                          <div class="pull-left">
                            <h2>Create New Role</h2>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                     <form action="{{route('roles.store')}}" method="post">
                       @csrf
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group mb-3">
                            <strong>Name:</strong> 
                            <input type="text" name="name" class="form-control" placeholder="Name">
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                          <div class="form-group mb-3">
                            <strong>Permission:</strong>
                            <label>
                              <input class="name" name="" type="checkbox" value="1"> Select All </label>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                          <div class="tree_main">
                            <ul id="bs_main" class="main_ul">
                              <li id="bs_1">
                                <span class="plus">&nbsp;</span>
                                <input type="checkbox" id="c_bs_1" />
                                <span>Role</span>
                                 <ul id="bs_l_1" style="display: none" class="inner_ul">
                                      <li id="io_1">
                                        <input type="checkbox" id="c_io_1" name="permission[]" value="1"/>
                                        <span>role-list</span>
                                      </li>
                                      <li id="io_2">
                                        <input type="checkbox" id="c_io_2" name="permission[]" value="2"/>
                                        <span>role-create </span>
                                      </li>
                                      <li id="io_3">
                                        <input type="checkbox" id="c_io_3" name="permission[]" value="3"/>
                                        <span>role-edit </span>
                                      </li>
                                      <li id="io_4">
                                        <input type="checkbox" id="c_io_4" name="permission[]" value="4"/>
                                        <span>role-delete </span>
                                      </li>
                                    </ul>
                              </li>
                              <li id="bs_2">
                                <span class="plus">&nbsp;</span>
                                <input type="checkbox" id="c_bs_2" />
                                <span>Products</span>
                                   <ul id="bf_l_2" style="display: none" class="inner_ul">
                                      <li id="io_5">
                                        <input type="checkbox" id="c_io_5" name="permission[]" value="5"/>
                                        <span>product-list </span>
                                      </li>
                                      <li id="io_6">
                                        <input type="checkbox" id="c_io_6" name="permission[]" value="6"/>
                                        <span>product-create </span>
                                      </li>
                                      <li id="io_7">
                                        <input type="checkbox" id="c_io_7" name="permission[]" value="7"/>
                                        <span>product-edit </span>
                                      </li>
                                      <li id="io_8">
                                        <input type="checkbox" id="c_io_8" name="permission[]" value="8"/>
                                        <span>product-delete </span>
                                      </li>
                                    </ul>
                              </li>
                            </ul>
                          </div>
                          <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                 </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
      <script>
        $(document).ready(function() {
          $(".plus").click(function() {
            $(this).toggleClass("minus").siblings("ul").toggle();
          })
          $("input[type=checkbox]").click(function() {
            //alert($(this).attr("id"));
            //var sp = $(this).attr("id");
            //if (sp.substring(0, 4) === "c_bs" || sp.substring(0, 4) === "c_bf") {
            $(this).siblings("ul").find("input[type=checkbox]").prop('checked', $(this).prop('checked'));
            //}
          })
          $("input[type=checkbox]").change(function() {
            var sp = $(this).attr("id");
            if (sp.substring(0, 4) === "c_io") {
              var ff = $(this).parents("ul[id^=bf_l]").attr("id");
              if ($('#' + ff + ' > li input[type=checkbox]:checked').length == $('#' + ff + ' > li input[type=checkbox]').length) {
                $('#' + ff).siblings("input[type=checkbox]").prop('checked', true);
                check_fst_lvl(ff);
              } else {
                $('#' + ff).siblings("input[type=checkbox]").prop('checked', false);
                check_fst_lvl(ff);
              }
            }
            if (sp.substring(0, 4) === "c_bf") {
              var ss = $(this).parents("ul[id^=bs_l]").attr("id");
              if ($('#' + ss + ' > li input[type=checkbox]:checked').length == $('#' + ss + ' > li input[type=checkbox]').length) {
                $('#' + ss).siblings("input[type=checkbox]").prop('checked', true);
                check_fst_lvl(ss);
              } else {
                $('#' + ss).siblings("input[type=checkbox]").prop('checked', false);
                check_fst_lvl(ss);
              }
            }
          });
        })

        function check_fst_lvl(dd) {
          //var ss = $('#' + dd).parents("ul[id^=bs_l]").attr("id");
          var ss = $('#' + dd).parent().closest("ul").attr("id");
          if ($('#' + ss + ' > li input[type=checkbox]:checked').length == $('#' + ss + ' > li input[type=checkbox]').length) {
            //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', true);
            $('#' + ss).siblings("input[type=checkbox]").prop('checked', true);
          } else {
            //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', false);
            $('#' + ss).siblings("input[type=checkbox]").prop('checked', false);
          }
        }

        function pageLoad() {
          $(".plus").click(function() {
            $(this).toggleClass("minus").siblings("ul").toggle();
          })
        }
      </script> @endsection