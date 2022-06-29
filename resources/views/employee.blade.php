@extends('layouts.app')

@section("title", "รายชื่อพนักงาน")
@section("pageName", "รายชื่อพนักงาน")

@section("content")
    <div class="container-fluid">
        <!-- CONTENT -->
        <div class="card">
            <div class="card-header">
                <div class="row my-3">
                    <div class="col-md-6">
                        {{--<input type="text" class="form-control" placeholder="ค้นหา">--}}
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="javascript:void(0);" class="text-success" id="addNewEmpBtn"><i
                                class="nav-icon fas fa-plus-circle fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="emp-table">
                    <thead>
                        <th width="80">ประเภท</th>
                        <th>รหัสพนักงาน</th>
                        <th>ชื่อ</th>
                        <th>ธนาคาร/เลขบัญชี</th>
                        <th>เบอร์ติดต่อ</th>
                        <th width="180">จัดการ</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <div class="row">

                </div>
            </div>
        </div>
        <!-- /CONTENT -->
    </div><!-- /.container-fluid -->
@endsection

@section("modal")
    <div class="modal fade" id="modal-add-emp">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <input type="hidden" name="id">

                    <div class="modal-header">
                        <h4 class="modal-title">เพิ่มพนักงาน</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- general form elements -->
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label>รหัสพนักงาน</label>
                                <input type="text" class="form-control" name="code">
                            </div>

                            <div class="form-group col-sm-6">
                                <label>Enroll No.</label>
                                <input type="text" class="form-control" name="enroll_no">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>ชื่อภาษาไทย</label>
                            <input type="text" class="form-control" name="name_th">
                        </div>

                        <div class="form-group">
                            <label>ชื่อภาษาฃองอังกฤษ</label>
                            <input type="text" class="form-control" name="name_en">
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label>ประเภทพนักงาน</label>
                                <select class="form-control" required name="type">
                                    <option value="permanent">ประจำ</option>
                                    <option value="temporary">ชั่วคราว</option>
                                </select>
                            </div>

                            <div class="form-group col-sm-8">
                                <label>เบอร์โทร</label>
                                <input type="text" class="form-control" name="tel">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label>ธนาคาร</label>
                                {{--<input type="text" class="form-control" name="bank_name">--}}
                                <select class="form-control" name="bank_name">
                                    <option>ไทยพาณิชย์</option>
                                    <option>กสิกรไทย</option>
                                    <option>กรุงเทพ</option>
                                    <option>ทหารไทย</option>
                                    <option>กรุงไทย</option>
                                    <option>กรุงศรีอยุธยา</option>
                                    <option>ออมสิน</option>
                                </select>
                            </div>

                            <div class="form-group col-sm-6">
                                <label>เลขบัญชีธนาคาร</label>
                                <input type="text" class="form-control" name="bank_account_number">
                            </div>
                        </div>
                        <!-- /.general form elements -->
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary button-submit">เพิ่ม</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-delete-emp">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ยืนยันลบพนักงาน</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ไม่ใช่</button>
                    <button type="submit" class="btn btn-danger button-delete" data-emp-id="">ใช่</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-emp-increase">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <input type="hidden" name="employee_id">

                    <div class="modal-header">
                        <h4 class="modal-title">สร้างรายการเพิ่ม</h4>
                    </div>

                    <div class="modal-body">
                        <!-- general form elements -->
                        <div class="form-group">
                            <p>รหัสพนักงาน: <span class="emp-code"></span></p>
                            <p>ชื่อพนักงาน: <span class="emp-name"></span></p>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label>ประเภทรายการเพิ่ม</label>
                            <select class="form-control" id="item_type" name="item_type">
                                <option value="revenue">เงินรายได้ต่อวัน</option>
                                <option value="ot">โอที</option>
                                <option value="other">ทั่วไป</option>
                            </select>
                        </div>

                        <div class="form-group d-none">
                            <label>ระบุรายการเพิ่ม</label>
                            <input type="text" id="item_name" class="form-control" name="item_name">
                        </div>

                        <div class="form-group">
                            <label>จำนวน</label>
                            <input type="text" class="form-control" name="item_value">
                        </div>

                        <div class="form-group">
                            <label>วันที่</label>
                            <input
                                type="text"
                                name="item_date"
                                class="form-control datetimepicker-input"
                                id="item_date"
                                data-toggle="datetimepicker"
                                data-target="#item_date"/>
                        </div>
                        <!-- /.general form elements -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary button-submit">สร้าง</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-emp-deduction">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <input type="hidden" name="employee_id">

                    <div class="modal-header">
                        <h4 class="modal-title">สร้างรายการหัก</h4>
                    </div>

                    <div class="modal-body">
                        <!-- general form elements -->
                        <div class="form-group">
                            <p>รหัสพนักงาน: <span class="emp-code"></span></p>
                            <p>ชื่อพนักงาน: <span class="emp-name"></span></p>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label>ระบุรายการหัก</label>
                            <input type="text" id="item_name" class="form-control" name="item_name">
                        </div>

                        <div class="form-group">
                            <label>จำนวน</label>
                            <input type="text" class="form-control" name="item_value">
                        </div>

                        <div class="form-group">
                            <label>วันที่</label>
                            <input
                                type="text"
                                name="item_date"
                                class="form-control datetimepicker-input"
                                id="item_date_deduction"
                                data-toggle="datetimepicker"
                                data-target="#item_date_deduction"/>
                        </div>
                        <!-- /.general form elements -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-primary button-submit">สร้าง</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push("js")
    <script>
        $(function () {
            var empTable = $("#emp-table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "pageLength": 10,
                "language": {
                  "search": "ค้นหา:"
                },
                "ajax": {
                    url: "/employees/all",
                    type: "POST",
                    dataSrc: 'data'
                },
                "columns": [
                    {
                        data: 'type',
                        render: function(dataField) {
                            if (dataField == "permanent") {
                                return '<span class="badge badge-info">ประจำ</span>';
                            } else if (dataField == 'temporary') {
                                return '<span class="badge badge-primary">ชั่วคราว</span>';
                            } else {
                                return '';
                            }
                        }
                    },
                    {data: 'code'},
                    {data: 'name_th'},
                    {data: 'bank_name'},
                    {data: 'tel'},
                    {
                        data: 'id',
                        render: function(dataField) {
                            return `
                                <a  href="javascript:void(0);"
                                    class="btn btn-sm btn-success emp-increase-btn"
                                    data-emp-id="${dataField}">
                                    <i class="fas fa-plus-circle"></i>
                                </a>

                                <a href="javascript:void(0);"
                                    class="btn btn-sm btn-danger emp-deduction-btn"
                                    data-emp-id="${dataField}">
                                    <i class="fas fa-minus-circle"></i>
                                </a>

                                <a href="javascript:void(0);"
                                    class="btn btn-sm btn-warning edit-emp-btn"
                                    data-emp-id="${dataField}">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="javascript:void(0);"
                                    class="btn btn-sm btn-danger del-emp-btn"
                                    data-emp-id="${dataField}">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `;
                        }
                    }
                ]
            });

            $("#item_date").datetimepicker({
                format: 'L',
                locale: 'th'
            });
            $("#item_date_deduction").datetimepicker({
                format: 'L',
                locale: 'th'
            });

            $("#item_type").change(function() {
                if ($(this).val() === 'revenue' || $(this).val() === 'ot') {
                    $("#item_name").parent().addClass("d-none");
                } else {
                    $("#item_name").parent().removeClass("d-none");
                }
            });

            // Add Emp
            $("#addNewEmpBtn").click(function (event) {
                event.preventDefault();
                // Clear
                $("#modal-add-emp form").trigger("reset");
                $("#modal-add-emp .is-invalid").removeClass("is-invalid");

                $("#modal-add-emp .modal-title").text("เพิ่มพนักงาน");
                $("#modal-add-emp .button-submit").removeClass("btn-warning").addClass("btn-primary").text("เพิ่ม");
                $("#modal-add-emp input[name='id']").val("");

                $("#modal-add-emp").modal("show");
            });

            // Delete Emp
            $(document).on("click", "#emp-table .del-emp-btn", function(e) {
                e.preventDefault();
                let empId = $(this).attr("data-emp-id");
                $("#modal-delete-emp .button-delete").attr("data-emp-id", empId);
                $("#modal-delete-emp").modal("show");
            });

            $("#modal-delete-emp .button-delete").click(function() {
                let empId = $(this).attr("data-emp-id");

                $.ajax({
                    url: "/employees/delete",
                    type: "POST",
                    data: {
                        "id": empId
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.success == 1) {
                            empTable.ajax.reload();
                            $("#modal-delete-emp").modal("hide");
                            toastr.success(res.messages);
                        } else {
                            toastr.error("เกิดข้อผิดพลาดบางอย่าง");
                        }
                    },
                    error: function(err) {
                        toastr.error("เกิดข้อผิดพลาดบางอย่าง");
                    }
                });
            });

            // Edit Emp
            $(document).on("click", "#emp-table .edit-emp-btn", function(e) {
                e.preventDefault();
                let empId = $(this).attr("data-emp-id");
                $("#modal-add-emp .modal-title").text("แก้ไขข้อมูลพนักงาน");
                $("#modal-add-emp .button-submit").removeClass("btn-primary").addClass("btn-warning").text("แก้ไข");
                $("#modal-add-emp input[name='id']").val(empId);
                let empData = empTable.row($(this).parent().parent()).data();
                $.each(empData, function(key, val) {
                    console.log(key, val)
                   $(`#modal-add-emp [name="${key}"]`).val(val);
                });
                $("#modal-add-emp").modal("show");
            });

            // Submit
            $("#modal-add-emp form").submit(function(event) {
                event.preventDefault();

                let data = $(this).serialize();
                let id = $(this).find("[name='id']").val();
                console.log(id);
                if (id == false || id == "" || id == null) {
                    $.ajax({
                        type: "POST",
                        url: "/employees/add",
                        data: data,
                        success: function (res) {
                            if (res.error == 0) {
                                $("#modal-add-emp").modal("hide");
                                empTable.ajax.reload(null, false);
                                toastr.success(res.messages);
                            } else {
                                $.each(res.errorMessages, function (key, val) {
                                    $(`[name="${key}"]`).addClass("is-invalid");
                                    toastr.error(val[0]);
                                });
                            }
                        },
                        error: function (err) {
                            console.log('err');
                            console.log(err);
                        }
                    });
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/employees/edit",
                        data: data,
                        success: function (res) {
                            if (res.error == 0) {
                                $("#modal-add-emp").modal("hide");
                                empTable.ajax.reload(null, false);
                                toastr.success(res.messages);
                            } else {
                                $.each(res.errorMessages, function (key, val) {
                                    $(`[name="${key}"]`).addClass("is-invalid");
                                    toastr.error(val[0]);
                                });
                            }
                        },
                        error: function (err) {
                            console.log('err');
                            console.log(err);
                        }
                    });
                }
            });

            // Increase, Deduction Handler
            $(document).on("click", "#emp-table .del-emp-btn", function(e) {
                e.preventDefault();
                let empId = $(this).attr("data-emp-id");
                $("#modal-delete-emp .button-delete").attr("data-emp-id", empId);
                $("#modal-delete-emp").modal("show");
            });

            // Increase item for each employee
            $(document).on("click", "#emp-table .emp-increase-btn", function(e) {
                e.preventDefault();

                $('#modal-emp-increase .is-invalid').removeClass('is-invalid');

                let empData = empTable.row($(this).parent().parent()).data();
                $("#modal-emp-increase .emp-code").text(empData.code);
                $("#modal-emp-increase .emp-name").text(empData.name_th);
                $("#modal-emp-increase input[name='employee_id']").val(empData.id);

                $("#modal-emp-increase").modal("show");
            });

            $('#modal-emp-increase form').submit(function(e) {
                e.preventDefault();

                let data = $(this).serialize()

                $.ajax({
                    url: "/items/increase/add",
                    type: "POST",
                    data: data,
                    success: function(res) {
                        $("#modal-emp-increase .is-invalid").removeClass("is-invalid");

                        if (res.error == 0) {
                            // empTable.ajax.reload(null, false);
                            $("#modal-emp-increase").modal("hide");
                            $("#modal-emp-increase form").trigger("reset");
                            toastr.success(res.message);
                        } else if (res.error == 1) {
                            $.each(res.messages, function (key, val) {
                                $(`[name="${key}"]`).addClass("is-invalid");
                                toastr.error(val[0]);
                            });
                        } else if (res.error == 2) {
                            toastr.error(res.message);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });

            // Deduct item for each employee
            $(document).on('click', '#emp-table .emp-deduction-btn', function(e) {
                e.preventDefault();

                $('#modal-emp-deduction .is-invalid').removeClass('is-invalid');

                let empData = empTable.row($(this).parent().parent()).data();
                $("#modal-emp-deduction .emp-code").text(empData.code);
                $("#modal-emp-deduction .emp-name").text(empData.name_th);
                $("#modal-emp-deduction input[name='employee_id']").val(empData.id);

                $('#modal-emp-deduction').modal('show');
            });

            $('#modal-emp-deduction form').submit(function(e) {
                e.preventDefault();

                let data = $(this).serialize()

                $.ajax({
                    url: "/items/deduction/add",
                    type: "POST",
                    data: data,
                    success: function(res) {
                        $("#modal-emp-deduction .is-invalid").removeClass("is-invalid");

                        if (res.error == 0) {
                            // empTable.ajax.reload(null, false);
                            $("#modal-emp-deduction").modal("hide");
                            $("#modal-emp-deduction form").trigger("reset");
                            toastr.success(res.message);
                        } else if (res.error == 1) {
                            $.each(res.messages, function (key, val) {
                                $(`[name="${key}"]`).addClass("is-invalid");
                                toastr.error(val[0]);
                            });
                        } else if (res.error == 2) {
                            toastr.error(res.message);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });
        });

    </script>
@endpush
