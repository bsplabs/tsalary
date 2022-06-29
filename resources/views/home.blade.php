@extends('layouts.app')

@section("title", "หน้าเเรก")

@section("pageName", "หน้าเเรก")

@section('content')
    <div class="container-fluid">
        <!-- CONTENT -->
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $countEmployees }}</h3>
                            <p>พนักงาน</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person"></i>
                        </div>
                        <a href="/employees" class="small-box-footer">ดูรายละเอียด <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $countIncreaseLists }}</h3>

                            <p>รายการเพิ่ม</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-arrow-up-c"></i>
                        </div>
                        <a href="/items/increase" class="small-box-footer">ดูรายละเอียด <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $countDeductionLists }}</h3>

                            <p>รายการหัก</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-arrow-down-c"></i>
                        </div>
                        <a href="/items/deduction" class="small-box-footer">ดูรายละเอียด <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $countItemLists }}</h3>

                            <p>รายการหัก/เพิ่ม</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-arrow-swap"></i>
                        </div>
                        <a href="/items" class="small-box-footer">ดูรายละเอียด <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
        <!-- /CONTENT -->
    </div><!-- /.container-fluid -->
@endsection
