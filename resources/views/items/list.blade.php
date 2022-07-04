@extends('layouts.app')

@section("title", "รายการหัก/เพิ่ม")

@section('pageName', 'รายการหัก/เพิ่ม')

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
                        <a href="javascript:void(0);" class="text-success" id="add-item-lists">
                            <i class="nav-icon fas fa-plus-circle fa-2x"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="item-table" class="table table-bordered">
                    <thead>
                    <th width="80">ประเภท</th>
                    <th>รายการ</th>
                    <th>จำนวน</th>
                    <th width="80">ต่อเนื่อง</th>
                    <th width="120">จัดการ</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!-- /CONTENT -->
    </div>
@endsection

@section("modal")
    <div class="modal fade" id="modal-add-items">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">สร้างรายการหัก/เพื่ม</h4>
                    </div>

                    <div class="modal-body">
                        <!-- general form elements -->
                        <div class="form-group">
                            <label>ประเภทรายการ</label>
                            <select class="form-control" id="type" name="type" value="increase">
                                <option value="increase">เพิ่ม</option>
                                <option value="deduct">หัก</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>กำหนดรูปแบบเวลา</label>
                            <select class="form-control" id="is_continue" name="is_continue" value="1">
                                <option value="1">ต่อเนื่อง</option>
                                <option value="0">ไม่ต่อเนื่อง</option>
                            </select>
                        </div>

                        {{--<div class="form-group">
                            <label>ประเภทรายการเพิ่ม</label>
                            <select class="form-control" id="value_type" name="value_type">
                                <option value="revenue">เงินรายได้ต่อวัน</option>
                                <option value="ot">โอที</option>
                                <option value="other">ทั่วไป</option>
                            </select>
                        </div>--}}

                        <div class="form-group">
                            <label>ระบุชื่อรายการ</label>
                            <input type="text" class="form-control" name="name">
                        </div>

                        <div class="form-group">
                            <label>จำนวน</label>
                            <input type="text" class="form-control" name="value">
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

    <div class="modal fade" id="modal-delete-items">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">ยืนยันลบรายการหัก/เพิ่ม</h4>
                </div>
                {{--<div class="modal-body"></div>--}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ไม่ใช่</button>
                    <button type="submit" class="btn btn-danger button-delete" data-item-id="">ใช่</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit-items">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <input type="hidden" name="id">

                    <div class="modal-header">
                        <h4 class="modal-title">แก้ไขรายการหัก/เพิ่ม</h4>
                    </div>

                    <div class="modal-body">
                        {{--<div class="form-group">
                            <label>ประเภทรายการ</label>
                            <select class="form-control" id="type" name="type" value="increase">
                                <option value="increase">เพิ่ม</option>
                                <option value="deduct">หัก</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>เวลา/ความต่อเนื่อง</label>
                            <select class="form-control" id="is_continue" name="is_continue" value="1">
                                <option value="1">รายวัน (ต่อเนื่อง)</option>
                                <option value="0">รายเดือน (ไม่ต่อเนื่อง)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>ประเภทรายการเพิ่ม</label>
                            <select class="form-control" id="value_type" name="value_type">
                                <option value="revenue">เงินรายได้ต่อวัน</option>
                                <option value="ot">โอที</option>
                                <option value="other">ทั่วไป</option>
                            </select>
                        </div>--}}

                        <div class="form-group d-none">
                            <label>ระบุชื่อรายการ</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>

                        <div class="form-group">
                            <label>จำนวน</label>
                            <input type="text" class="form-control" id="value" name="value">
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
            var itemTable = $("#item-table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "pageLength": 10,
                "bSort": false,
                "language": {
                    "search": "ค้นหา:"
                },
                ajax: {
                    url: "/items/all",
                    type: "POST",
                    dataSrc: "data"
                },
                columns: [
                    {
                        data: "type",
                        className: 'text-center',
                        render: function (dataField) {
                            if (dataField === "increase") {
                                return `<span class="badge badge-success">เพิ่ม</span>`;
                            } else if (dataField === "deduct") {
                                return `<span class="badge badge-danger">หัก</span>`;
                            }
                        }
                    },
                    {data: "name"},
                    {
                        data: "value",
                        render: function (dataField) {
                            let bath = Intl.NumberFormat("th-TH");
                            return bath.format(dataField) + " บาท";
                        }
                    },
                    {
                        data: "is_continued",
                        className: "text-center",
                        render: function (dataField, type, row, meta) {
                            if (row.value_type === 'revenue' || row.value_type === 'ot') {
                                return '';
                            } else {
                                if (dataField == "1") {
                                    return `<span role="button" class="text-success" id="is-continue-btn"><i class="fas fa-redo"></i></span>`;
                                } else if (dataField == "0") {
                                    return `<span role="button" class="text-default" id="is-continue-btn"><i class="fas fa-redo"></i></span>`;
                                }
                            }
                        }
                    },
                    {
                        data: "id",
                        className: 'text-center',
                        render: function (dataField, data, row) {
                            if (row.value_type === 'revenue') {
                                return `
                                    <a  href="javascript:void(0);"
                                        class="btn btn-sm btn-warning edit-item-btn"
                                        data-item-id="${dataField}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                `;
                            } else if (row.value_type === 'ot') {
                                return `
                                    <a  href="javascript:void(0);"
                                        class="btn btn-sm btn-warning edit-item-btn"
                                        data-item-id="${dataField}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                `;
                            } else {
                                return `
                                    <a  href="javascript:void(0);"
                                        class="btn btn-sm btn-warning edit-item-btn"
                                        data-item-id="${dataField}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a  href="javascript:void(0);"
                                        class="btn btn-sm btn-danger del-item-btn"
                                        data-item-id="${dataField}">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                `;
                            }
                        }
                    }
                ],
                createdRow: function(row, data) {
                    if (data.value_type === 'revenue' || data.value_type === 'ot') {
                        $(row).addClass('bg-secondary')
                    }

                }
            });

            // Main Button for add items
            $("#add-item-lists").click(function (e) {
                e.preventDefault();
                $("#modal-add-items form").trigger("reset");
                $("#modal-add-items").modal("show");
            });

            // Create item
            $("#modal-add-items form").submit(function (e) {
                e.preventDefault();

                let data = $(this).serialize();

                $.ajax({
                    url: "/items/add",
                    type: "POST",
                    data: data,
                    success: function (res) {
                        if (res.error == 0) {
                            itemTable.ajax.reload(null, false);
                            $("#modal-add-items").modal("hide");
                            $("#modal-add-items form").trigger("reset");
                            toastr.success(res.message);
                            $("#modal-delete-items .is-invalid").removeClass("is-invalid");
                        } else if (res.error == 1) {
                            $.each(res.messages, function (key, val) {
                                $(`[name="${key}"]`).addClass("is-invalid");
                                toastr.error(val[0]);
                            });
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function (err) {
                        toastr.error("เซิฟเวอร์เกิดข้อผิดพลาดบางอย่าง");
                        console.log(err)
                    }
                });
            });

            // Edit item
            $(document).on("click", "#item-table .edit-item-btn", function (e) {
                e.preventDefault();

                let itemData = itemTable.row($(this).parent().parent()).data();
                if (itemData.type === 'increase') {
                    if (itemData.is_continued == '1') {
                        if (itemData.value_type === 'other') {
                            $("#name").val(itemData.name);
                            $("#name").parent().removeClass('d-none');
                        } else {
                            $("#name").parent().addClass('d-none');
                        }
                    } else {
                        $("#name").val(itemData.name);
                        $("#name").parent().removeClass('d-none');
                    }
                } else {
                    $("#name").val(itemData.name);
                    $("#name").parent().removeClass('d-none');
                }
                $("#value").val(itemData.value);
                $("#modal-edit-items [name='id']").val(itemData.id);

                $("#modal-edit-items").modal("show");
            });

            $("#modal-edit-items form").submit(function(e) {
                e.preventDefault();

                let data = $(this).serialize();

                $.ajax({
                   url: "/items/edit",
                   type: "POST",
                   data: data,
                   success: function(res) {
                       if (res.error == 0) {
                           itemTable.ajax.reload(null, false);
                           $("#modal-add-items").modal("hide");
                           $("#modal-add-items form").trigger("reset");
                           toastr.success(res.message);
                           $("#modal-edit-items").modal("hide");
                           $("#modal-delete-items .is-invalid").removeClass("is-invalid");
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
                        toastr.error("Something went wrong!");
                       console.log(err);
                    }
                });
            });

            // Delete item
            $(document).on('click', '#item-table .del-item-btn', function(e) {
               e.preventDefault();
               let id = $(this).attr('data-item-id');
               $('#modal-delete-items .button-delete').attr('data-item-id', id);
               $("#modal-delete-items").modal("show");
            });

            $("#modal-delete-items .button-delete").click(function(e) {
                e.preventDefault();

                let id = $(this).attr('data-item-id');

                $.ajax({
                    url: "/items/delete",
                    type: "POST",
                    data : {id: id},
                    success: function(res) {
                        console.log(res)
                        if (res.error == 0) {
                            itemTable.ajax.reload();
                            $("#modal-delete-items").modal("hide");
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

            $(document).on('click', '#item-table #is-continue-btn', function(e) {
                e.preventDefault();

                let itemData = itemTable.row($(this).parent().parent()).data();

                let data = {
                    id: itemData.id,
                    isContinued: itemData.is_continued
                }

                $.ajax({
                    url: "/items/update-timetype",
                    type: "POST",
                    data : data,
                    success: function(res) {
                        console.log(res)
                        if (res.error == 0) {
                            itemTable.ajax.reload(null, false);
                            $("#modal-delete-items").modal("hide");
                            toastr.success(res.message);
                        } else {
                            $.each(res.messages, function (key, val) {
                                $(`[name="${key}"]`).addClass("is-invalid");
                                toastr.error(val[0]);
                            });
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
