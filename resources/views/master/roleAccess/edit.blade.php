@extends('layouts.home_master')
<style>
    p {
        font-family: 'Nunito', sans-serif;
    }
</style>

@section('judul')
Edit Role
@endsection

@section('pathjudul')
<li class="breadcrumb-item"><a href="/home">Home</a></li>
<li class="breadcrumb-item">Master</li>
<li class="breadcrumb-item"><a href="{{route('roleAccess.index')}}">Role</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="card card-primary">
        <!-- form start -->
        <form action="{{route('roleAccess.update', [$roleAccess])}}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="title">Nama Role</label>
                    <input disabled type="text" name="nama" class="form-control" value="{{old('name',$roleAccess->name)}}">
                </div>

                <div class="custom-control custom-checkbox">
                    <h2><input type="checkbox" id="checkSemuaCheckBox">
                        <label for="title">Check All</label><br>
                    </h2>
                </div>

                <div class="custom-control custom-checkbox">

                    @foreach($dataMenu as $data)
                    <input class="checkBoxMenu" type="checkbox" id="menuIni{{$data->MenuID}}" class="form-check-input" name="menu[]" value="{{$data->MenuID}}" {{'$data->MenuID' == old('MenuID',$data->MenuID)? 'checked' :'' }}>
                    <label for="subtitle">{{$data->Name}}</label><br>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        function CekSemuaTextBox(chek) {

            var checkboxes = document.getElementsByClassName('cek');
            if (chek.checked) {
                for (let i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].type == 'checkbox') {
                        checkboxes[i].checked = true;

                    }
                }
            }
            else
            {
              for (let i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].type == 'checkbox') {
                        checkboxes[i].checked = false;

                    }
                }
            }

        }
        var dataAccess = <?php echo json_encode($dataAccess); ?>;
        $.each(dataAccess, function(key, value) {

            $("#menuIni" + value.idMenu).prop('checked', true)
        });



    });

    //lek mau dipakek
    $("#checkSemuaCheckBox").click(function() {
        //alert($("#checkSemuaCheckBox").prop("checked"));
        if ($("#checkSemuaCheckBox").prop("checked") == true) {
            $('.checkBoxMenu').prop('checked', true);
        } else {
            $('.checkBoxMenu').prop('checked', false);
        }
    });
    /*$("#uncheckSemuaCheckBox").click(function() {
        $('.checkBoxMenu').prop('checked', false);
    });*/
</script>
@endsection