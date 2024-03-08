@extends('app')
@section('content')
<div id="loading-overlay">
    <div id="loading-spinner"></div>
</div>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">Update Tindakan</h3>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <form action="{{ route('tindakan-aktif') }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="search_poli"
                                        placeholder="Cari Poli..." value="{{ request()->search_poli ?? "" }}">
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group custom-search-form">
                                        <input type="text" class="form-control" name="search_tindakan"
                                            placeholder="Cari Tindakan..."
                                            value="{{ request()->search_tindakan ?? "" }}">
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Poli ID</th>
                                        <th>Tindakan ID</th>

                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_tindakan as $index => $item_tindakan)

                                    <tr class="odd gradeX">
                                        <td>{{ $item_tindakan['poli_name'] }}</td>
                                        <td>{{ $item_tindakan['tindakan_desc'] }}</td>
                                        <td id="td_{{ $index }}">{{ ($item_tindakan['aktif'] == 'Y') ? 'Aktif' :'Tidak
                                            Aktif' }}</td>
                                        <td>
                                            <label class=" switch">
                                                <input type="checkbox" name="check_aktif" id="check_aktif_{{ $index }}"
                                                    onChange="return ubahStatus('{{ $item_tindakan['poli_id'] }}','{{ $item_tindakan['tindakan_id'] }}','{{ $index }}')"
                                                    {{ $item_tindakan['aktif']=='Y' ? 'checked' :'' }}>
                                                <span class="slider round"></span>
                                            </label>
                                            {{-- @if($item_tindakan['aktif'] == 'Y')
                                            <div class='btn btn-sm btn-success'
                                                onClick="return ubahStatus('{{ $item_tindakan['poli_id'] }}','{{ $item_tindakan['tindakan_id'] }}','N')"
                                                data-toggle="modal" data-target="#modal">
                                                Aktif</div>
                                            @else
                                            <div class='btn btn-sm btn-danger'
                                                onClick="return ubahStatus('{{ $item_tindakan['poli_id'] }}','{{ $item_tindakan['tindakan_id'] }}','Y')"
                                                data-toggle="modal" data-target="#modal">
                                                Tidak Aktif</div>
                                            @endif --}}
                                        </td>
                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>

@endsection
@push('script')
<script>
    $(document).ready(function(){
    // Show loading overlay
    $('#loading-overlay').css('visibility', 'visible');

    // Hide loading overlay when page is fully loaded
    $(window).on('load', function(){
        $('#loading-overlay').css('visibility', 'hidden');
        $('#content').fadeIn(); // Fade in content
    });
});

</script>

<script>
    function ubahStatus(param_poli, param_tindakan, param_index)
    {

        var aktif = $('#check_aktif_'+param_index).prop('checked').toString();
        if(aktif == 'true')
                {
                    $('#td_'+param_index).text('Aktif');
                }else{
                    $('#td_'+param_index).text('Tidak Aktif');
                }
        var payload_tindakan = {
            poli_id  : param_poli,
            tindakan_id : param_tindakan,
            isChecked : aktif,
            _token: "{{ csrf_token() }}"
        }


        $.ajax({
            url: "{{ route('tindakan-poli-update') }}",
            data : payload_tindakan,
            type : 'POST',
            success: function(response){
                alert(response);
            }
            })
    }
</script>

@endpush