@extends("layouts.app")

@section("title", "โปรไฟล์")

@section("pageName", "โปรไฟล์")

@section("content")
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                 src="{{ asset("assets/admin/img/avatar.png") }}"
                                 alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>
                        <br>
                        <hr>
                        @if ($errors->has("newPassword"))
                            <div class="alert alert-default-danger" role="alert">
                                {{ $errors->first("newPassword") }}
                            </div>
                        @endif

                        @if (session("success"))
                            <div class="alert alert-default-success" role="alert">
                                {{ session("success") }}
                            </div>
                        @endif
                        <form action="{{ route("resetPassword") }}" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="password" name="newPassword"
                                       class="form-control @error('newPassword') is-invalid @enderror"
                                       placeholder="รหัสผ่านใหม่">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-key"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block">เปลี่ยนรหัสผ่าน</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>

                        {{--                    <a href="#" class="btn btn-primary btn-block"><b>เปลี่ยนรหัสผ่าน</b></a>--}}
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
