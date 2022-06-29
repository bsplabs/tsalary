@extends('layouts.app')

@section("title", "นำออก")

@section('pageName', 'นำออก')

@section('content')
    <div class="container-fluid">
        <!-- CONTENT -->
        <div class="card">

            <div class="card-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>เลือกปี :</label>
                        <select class="form-control" id="year" name="year">
                            @foreach($yearLists as $year)
                                <option>{{ $year->work_year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-sm-6 d-none">
                        <label>เลือกเดือน :</label>
                        <select class="form-control" id="month" name="month"></select>
                    </div>
                </div>

                <div class="form-group">
                    <a href="{{ route("generalReport") }}" id="general-report" class="btn btn-lg btn-info mr-2 reports">รายงานทั่วไป <br>
                        <i class="fas fa-download fa-2x"></i>
                    </a>

                    <a href="{{ route("increaseReport") }}" id="increase-report" class="btn btn-lg btn-success mr-2 reports">รายการเพิ่ม <br>
                        <i class="fas fa-download fa-2x"></i>
                    </a>

                    <a href="{{ route("deductionReport") }}" id="deduction-report" class="btn btn-lg btn-danger mr-2 reports">รายการหัก <br>
                        <i class="fas fa-download fa-2x"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- /CONTENT -->
    </div><!-- /.container-fluid -->
@endsection

@push("js")
    <script>
        $(function () {
            loadMonth();

            $("#year").change(function () {
                loadMonth();
            });

            $("#month").change(function () {
                hrefHandler($('#year').val(), $(this).val());
            });
        });

        function loadMonth() {
            let year = $('#year').val();

            $.ajax({
                url: "/reports/month",
                type: "POST",
                data: {year: year},
                success: function (res) {
                    if (res.error == 0 && res.data != false) {
                        $('#month').find('option').remove().end();
                        $.each(res.data, function (k, v) {
                            console.log(k, v);
                            $('#month').append($('<option>',
                                {
                                    value: k,
                                    text: v
                                }
                            ))
                        })

                        let month = $('#month').val();
                        hrefHandler(year, month);

                        $('#month').parent().removeClass('d-none');
                    } else {
                        $('#month').parent().addClass('d-none');
                    }
                }
            });
        }

        function hrefHandler(year, month) {
            const reportEl = $('.reports');
            $.each(reportEl, function(k, v) {
                let href = $(v).attr("href");
                href = href.split(/[?#]/)[0];
                href = href + "?year=" + year + "&month=" + month;
                $(v).attr("href", href);
            });
        }
    </script>
@endpush
