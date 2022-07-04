@extends('layouts.app')

@section("title", "รายการเพิ่ม")

@section('pageName', 'รายการเพิ่ม')

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
                        <a href="javascript:void(0);" class="text-success" id="create-increase-item-btn"><i class="nav-icon fas fa-plus-circle fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="increase-table" class="table table-bordered">
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
    <div class="modal fade" id="modal-add-increase">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="increase-item-form">
                    <div class="modal-header">
                        <h4 class="modal-title">สร้างรายการเพิ่ม</h4>
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
                            <label>ประเภทรายการเพิ่ม</label>
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

    <div class="modal fade" id="modal-delete-increase">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ยืนยันลบรายการเพิ่ม</h4>
                </div>
                {{--<div class="modal-body"></div>--}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ไม่ใช่</button>
                    <button type="submit" class="btn btn-danger button-delete" data-increase-id="">ใช่</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit-increase">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">สร้างรายการเพิ่ม</h4>
                    </div>

                    <div class="modal-body">
                        <!-- general form elements -->
                        <input type="hidden" name="id">

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
                                id="item_date_edit"
                                data-toggle="datetimepicker"
                                data-target="#item_date_edit"/>
                        </div>
                        <!-- /.general form elements -->
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
            var increaseTable = $("#increase-table").DataTable({
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
                    url: "/items/increase/all",
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
                        render: function(dataField) {
                            let dateSplit = dataField.split("-");
                            return dateSplit[2] + "/" + dateSplit[1] + "/" + dateSplit[0];
                        }
                    },
                    {
                        data: "id",
                        className: 'text-center',
                        render: function(dataField) {
                            return `
                                <a  href="javascript:void(0);"
                                    class="btn btn-sm btn-warning edit-increase-btn"
                                    data-increase-id="${dataField}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a  href="javascript:void(0);"
                                    class="btn btn-sm btn-danger del-increase-btn"
                                    data-increase-id="${dataField}">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `;
                        }
                    }
                ]
            });

            $("#employee_selector").select2({
                ajax: {
                    url: "/items/increase/emp-data",
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

            /*$("#item_type").change(function() {
                if ($(this).val() === 'revenue' || $(this).val() === 'ot') {
                    $("#item_name").parent().addClass("d-none");
                } else {
                    $("#item_name").parent().removeClass("d-none");
                }
            });*/

            // Create increase item
            $("#create-increase-item-btn").click(function(e) {
                $('#modal-add-increase .is-invalid').removeClass('is-invalid');
                $("#modal-add-increase").modal("show");
            });

            $("#increase-item-form").submit(function(e) {
                e.preventDefault();

                const data = $(this).serialize();

                $.ajax({
                    url: "/items/increase/add",
                    type: "POST",
                    data: data,
                    success: function(res) {
                        $("#modal-add-increase .is-invalid").removeClass("is-invalid");

                        if (res.error == 0) {
                            increaseTable.ajax.reload(null, false);
                            $("#modal-add-increase").modal("hide");
                            $("#modal-add-increase form").trigger("reset");
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

            // Edit increase item
            $(document).on("click", "#increase-table .edit-increase-btn", function(e) {
                e.preventDefault();

                $('#modal-edit-increase .is-invalid').removeClass('is-invalid');

                let increaseData = increaseTable.row($(this).parent().parent()).data();
                $("#modal-edit-increase #item_name").val(increaseData.item_name)
                $("#modal-edit-increase [name='item_value']").val(increaseData.item_value);
                let dateSplit = increaseData.item_date.split("-");
                let itemDate = dateSplit[2] + "/" + dateSplit[1] + "/" + dateSplit[0];
                $("#modal-edit-increase [name='item_date']").val(itemDate);
                $("#modal-edit-increase [name='id']").val(increaseData.id);

                $("#modal-edit-increase").modal("show");
            })

            $("#modal-edit-increase form").submit(function(e) {
                e.preventDefault();

                let data = $(this).serialize();

                $.ajax({
                    url: "/items/increase/edit",
                    type: "POST",
                    data: data,
                    success: function(res) {
                        if (res.error == 0) {
                            increaseTable.ajax.reload(null, false);
                            $("#modal-edit-increase").modal("hide");
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
                        console.log(err)
                    }
                });
            });

            // Delete increase item
            $(document).on("click", "#increase-table .del-increase-btn", function(e) {
                let id = $(this).attr("data-increase-id");
                $("#modal-delete-increase .button-delete").attr("data-increase-id", id);
                $("#modal-delete-increase").modal("show");
            });

            $("#modal-delete-increase .button-delete").click(function(e) {
                e.preventDefault();

                let id = $(this).attr("data-increase-id");

                $.ajax({
                    url: "/items/increase/delete",
                    type: "POST",
                    data: {id: id},
                    success: function(res) {
                        if (res.error == 0) {
                            increaseTable.ajax.reload();
                            $("#modal-delete-increase").modal("hide");
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            });
        });
    </script>
@endpush
