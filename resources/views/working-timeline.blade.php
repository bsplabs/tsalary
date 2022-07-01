@extends('layouts.app')

@section("title", "รายชื่อพนักงาน")
@section("pageName", "รายชื่อพนักงาน")

@section("content")
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row my-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="ค้นหา">
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="javascript:void(0);" class="text-success" id="addNewEmpBtn"><i
                                class="nav-icon fas fa-plus-circle fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="emp-working-time-table">
                    <thead>
                        <th>รหัสพนักงาน</th>
                        <th>ชื่อ</th>
                        <th>วันที่</th>
                        <th>จำนวนวันเข้างาน</th>
                        <th>OT</th>
                        <th>เวลาเข้างาน</th>
                        <th>เวลาออกงาน</th>
                        <th width="80">จัดการ</th>
                    </thead>
                    <tbody></tbody>
                </table>

                <div class="row">

                </div>
            </div>
        </div>
    </div>
@endsection

@section("modal")
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
@endsection

@push("js")
    <script>
        $(function () {
            var empWorkingTimeTable = $("#emp-working-time-table").DataTable({
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
                    url: "/works/working-timeline",
                    type: "POST",
                    dataSrc: 'data'
                },
                "columns": [
                    {data: 'code'},
                    {data: 'name_th'},
                    {data: 'working_date'},
                    {data: 'work_time'},
                    {data: 'ot_time'},
                    {data: 'shift_start'},
                    {data: 'shift_end'},
                    {data: 'id'}
                ]
            });
        });
    </script>
@endpush
