@extends('layouts.app')

@section("title", "รายการหัก")

@section('pageName', 'รายการหัก')

@section('content')
    <div class="container-fluid">
        <!-- CONTENT -->
        <div class="card">
            <div class="card-header">
                <div class="row my-3">
                    <div class="col-md-6">
                        {{--<input type="text" class="form-control" placeholder="ค้นหา">--}}
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="javascript:void(0);" class="text-danger" id="add-deduction-item"><i
                                class="nav-icon fas fa-minus-circle fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="deduction-table" class="table table-bordered">
                    <thead>
                    <th>รหัสพนักงาน</th>
                    <th>ชื่อ</th>
                    <th>รายการ</th>
                    <th>จำนวน</th>
                    <th>วันที่</th>
                    <th>จัดการ</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!-- /CONTENT -->
    </div><!-- /.container-fluid -->
@endsection

@section("modal")
    <div class="modal fade" id="modal-add-deduction">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                <div class="modal-header">
                    <h4 class="modal-title">สร้างรายการหัก</h4>
                </div>

                <div class="modal-body">
                    <!-- general form elements -->
                    <div class="form-group">
                        <label>เลือกพนักงาน</label>
                        <select
                            class="form-control select2"
                            id="employee_selector"
                            name="employee_id" style="width:100%;">
                        </select>
                    </div>

                    {{--<div class="form-group">
                        <label>ประเภทรายการหัก</label>
                        <select class="form-control" id="item_type" name="item_type">
                            <option value="revenue">เงินรายได้ต่อวัน</option>
                            <option value="ot">โอที</option>
                            <option value="other">ทั่วไป</option>
                        </select>
                    </div>--}}

                    <div class="form-group">
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

    <div class="modal fade" id="modal-delete-deduction">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ยืนยันลบรายการหัก</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ไม่ใช่</button>
                    <button type="submit" class="btn btn-danger button-delete" data-deduction-id="">ใช่</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit-deduction">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <input type="hidden" name="id">

                    <div class="modal-header">
                        <h4 class="modal-title">แก้ไขรายการหัก</h4>
                    </div>

                    <div class="modal-body">
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
                                id="item_date_edit"
                                data-toggle="datetimepicker"
                                data-target="#item_date_edit"/>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                        <button type="submit" class="btn btn-warning button-submit">แก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push("js")
    <script>
        $(function () {
            var deductionTable = $("#deduction-table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "pageLength": 10,
                "language": {
                    "search": "ค้นหา:"
                },
                ajax: {
                    url: "/items/deduction/all",
                    type: "POST",
                    dataSrc: "data"
                },
                columns: [
                    {data: "code"},
                    {data: "name_th"},
                    {data: "item_name"},
                    {
                        data: "item_value",
                        render: function(dataField) {
                            let bath = Intl.NumberFormat("th-TH");
                            return bath.format(dataField) + " บาท";
                        }
                    },
                    {
                        data: "item_date",
                        render: function (dataField) {
                            let dateSplit = dataField.split("-");
                            return dateSplit[2] + "/" + dateSplit[1] + "/" + dateSplit[0];
                        }
                    },
                    {
                        data: "id",
                        render: function (dataField) {
                            return `
                                <a  href="javascript:void(0);"
                                    class="btn btn-sm btn-warning edit-deduction-btn"
                                    data-deduction-id="${dataField}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a  href="javascript:void(0);"
                                    class="btn btn-sm btn-danger del-deduction-btn"
                                    data-deduction-id="${dataField}">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `;
                        }
                    }
                ]
            });

            $("#employee_selector").select2({
                ajax: {
                    url: "/items/emp-data",
                    dataType: "json"
                }
            });
            $("#item_date").datetimepicker({
                format: 'L',
                locale: 'th'
            });
            $("#item_date_edit").datetimepicker({
                format: 'L',
                locale: 'th'
            });

            $("#add-deduction-item").click(function (e) {
                e.preventDefault();

                $("#modal-add-deduction .is-invalid").removeClass("is-invalid");

                $("#modal-add-deduction").modal("show");
            });

            // Create Deduction Item
            $("#modal-add-deduction form").submit(function(e) {
                e.preventDefault();

                let data = $(this).serialize();

                $.ajax({
                    url: "/items/deduction/add",
                    type: "POST",
                    data: data,
                    success: function(res) {
                        console.log(res);
                        if (res.error == 0) {
                            deductionTable.ajax.reload(null, false);
                            $("#modal-add-deduction").modal("hide");
                            $("#modal-add-deduction form").trigger("reset");
                            toastr.success(res.message);
                        } else if (res.error == 1) {
                            $.each(res.messages, function (key, val) {
                                $(`[name="${key}"]`).addClass("is-invalid");
                                toastr.error(val[0]);
                            });
                        } else {
                            toastr.error(res.error);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });


            // Edit Deduction Item
            $(document).on("click", "#deduction-table .edit-deduction-btn", function(e) {
                e.preventDefault();

                $("#modal-edit-deduction .is-invalid").removeClass("is-invalid");

                // set add modal to edit modal
                let deductionData = deductionTable.row($(this).parent().parent()).data();

                // config modal form
                $("#modal-edit-deduction [name='id']").val(deductionData.id);
                $("#modal-edit-deduction [name='item_name']").val(deductionData.item_name);
                $("#modal-edit-deduction [name='item_value']").val(deductionData.item_value);
                let dateSplit = deductionData.item_date.split("-");
                let itemDate = dateSplit[2] + "/" + dateSplit[1] + "/" + dateSplit[0];
                $("#modal-edit-deduction [name='item_date']").val(itemDate);

                $("#modal-edit-deduction").modal("show");
            });

            $("#modal-edit-deduction form").submit(function(e) {
                e.preventDefault();

                let data = $(this).serialize();

                $.ajax({
                    url: "/items/deduction/edit",
                    type: "POST",
                    data: data,
                    success: function (res){
                        if (res.error == 0) {
                            deductionTable.ajax.reload(null, false);
                            $("#modal-edit-deduction").modal("hide");
                            $("#modal-edit-deduction form").trigger("reset");
                            toastr.success(res.message);
                        } else if (res.error == 1) {
                            $.each(res.messages, function (key, val) {
                                $(`[name="${key}"]`).addClass("is-invalid");
                                toastr.error(val[0]);
                            });
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            });


            // Delete Deduction Item
            $(document).on('click', '#deduction-table .del-deduction-btn', function(e) {
                e.preventDefault();

                let id = $(this).attr('data-deduction-id');
                $('#modal-delete-deduction .button-delete').attr('data-deduction-id', id);

                $('#modal-delete-deduction').modal('show');
            });

            $('#modal-delete-deduction .button-delete').click(function(e) {
                e.preventDefault();

                let id = $(this).attr('data-deduction-id');

                $.ajax({
                    url: "/items/deduction/delete",
                    type: "POST",
                    data : {id: id},
                    success: function(res) {
                        if (res.error == 0) {
                            deductionTable.ajax.reload();
                            $("#modal-delete-deduction").modal("hide");
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function(err) {
                        toastr.success("Something went wrong");
                        console.log(err);
                    }
                });
            });
        });
    </script>
@endpush
