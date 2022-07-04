@extends('layouts.app')

@section("title", "เวลาทำงาน")

@section("pageName", "เวลาทำงาน")

@section("content")
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="row my-3 d-flex align-items-end">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>เลือกวันที่ เริ่ม/สิ้นสุด:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" class="form-control float-right" id="date-range-filter">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>กรองพนักงาน</label>
                            <select name="" id="emp-group-filter" class="form-control">
                                <option value="all">ทั้งหมด</option>
                                {{--<option value="normal">ปกติ</option>--}}
                                <option value="late">มาสาย</option>
                            </select>
                            <!-- /.input group -->
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <input type="text" class="form-control float-right" id="search-keyword-filter" placeholder="คำค้นหา">
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="text-center">
                            <button class="btn btn-primary" id="filtered-button">กรอง</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-bordered" id="emp-working-time-table">
                    <thead>
                        <th>รหัสพนักงาน</th>
                        <th>ชื่อ</th>
                        <th>วันที่</th>
                        <th>จำนวนเวลาเข้างาน</th>
                        <th>OT</th>
                        <th>เวลาเข้างาน</th>
                        <th>เวลาออกงาน</th>
                    </thead>
                    <tbody></tbody>
                </table>
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

    <div class="modal fade" id="modal-edit-working-timeline">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">แก้ไขเวลาทำงานพนักงาน</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">ไม่ใช่</button>
                    <button type="submit" class="btn btn-warning button-submit" data-id="">แก้ไข</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("js")
    <script>
        $(function () {
            $('#date-range-filter').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'ยกเลิก',
                    applyLabel: 'นำมาใช้',
                    daysOfWeek: [
                        "อา.",
                        "จ.",
                        "อ.",
                        "พ.",
                        "พฤ.",
                        "ศ.",
                        "ส.",
                    ],
                    monthNames: [
                        "มกราคม",
                        "กุมภาพันธ์",
                        "มีนาคม",
                        "เมษายน",
                        "พฤษภาคม",
                        "มิถุนายน",
                        "กรกฎาคม",
                        "สิงหาคม",
                        "กันยายน",
                        "ตุลาคม",
                        "พฤศจิกายน",
                        "ธันวาคม"
                    ],
                    monthNamesShort: [
                        "ม.ค.",
                        "ก.พ.",
                        "มี.ค.",
                        "เม.ย.",
                        "พ.ค.",
                        "มิ.ย.",
                        "ก.ค.",
                        "ส.ค.",
                        "ก.ย.",
                        "ต.ค.",
                        "พ.ย.",
                        "ธ.ค."
                    ]
                }
            });

            var empWorkingTimeTable = $("#emp-working-time-table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "pageLength": 10,
                "bSort": false,
                "searching": false,
                "language": {
                    "search": "ค้นหา:"
                },
                "ajax": {
                    url: "/works/working-timeline",
                    type: "POST",
                    data: function (d) {
                        d.filters = {
                            dateRange: $('#date-range-filter').val(),
                            empGroup: $('#emp-group-filter').val(),
                            searchKeyword: $('#search-keyword-filter').val()
                        }
                    },
                    dataSrc: 'data'
                },
                "columns": [
                    {data: 'code'},
                    {data: 'name_th'},
                    {data: 'working_date'},
                    {
                        data: 'work_time',
                        className: 'text-center',
                        render: function (dataField) {
                            return dataField + " ชม.";
                        }
                    },
                    {
                        data: 'ot_time',
                        className: 'text-center',
                        render: function (dataField) {
                            return dataField + " ชม.";
                        }
                    },
                    {
                        data: 'shift_start',
                        className: 'text-center'
                    },
                    {
                        data: 'shift_end',
                        className: 'text-center'
                    }
                ]
            });

            $(document).on('click', '#emp-working-time-table .edit-working-timeline-btn', function (e) {
                e.preventDefault();

                $('#modal-edit-working-timeline').modal('show');
            });

            $('#filtered-button').click(function (e) {
                empWorkingTimeTable.draw();
            });
        });
    </script>
@endpush
