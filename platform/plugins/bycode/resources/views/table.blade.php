@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Danh sách ứng dụng | Bấm để lấy số</h5>
                    <h5 class="card-title">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nhamang" class="mb-2">Nhà mạng</label>
                                    <input type="search" class="form-control" id="nhamang"
                                           placeholder="Chọn nhà mạng lấy số">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="dauso" class="mb-2">Đầu số</label>
                                    <input type="search" class="form-control"  id="dauso"
                                           placeholder="Ex: 0399,0925,097">
                                </div>
                            </div>
                        </div>
                    </h5>
                    <div class="card-title">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <input type="search" class="form-control border border-top-0 border-start-0 rounded-1" id="nhamang"
                                           placeholder="Tìm ứng dụng">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-text mb-3">
                        <div class="d-flex flex-row border pt-3">
                            <div class="mb-2 me-1"><button class="btn btn-outline-primary getphone" value="28">Zalo</button></div>
                            <div class="mb-2 me-1"><button class="btn btn-outline-primary getphone" value="90">Toss</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-xs-12">
            <div class="card">
                <div class="card-header">
                    Tin nhắn sẽ tự động xuất hiện, bạn không cần tải lại trang
                </div>
{{--                <div class="card-title">--}}
{{--                    --}}
{{--                </div>--}}
                @include('core/table::base-table')
            </div>
        </div>
    </div>

@stop
@section('javascript')
    <script>
        jQuery.support.cors = true;
        $('.getphone').on('click', function(e) {
            const value = $(this).val();
            $.ajax({
                url: `{{ url('api/getphone')}}/${value}`,
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function(response) {
                $('.buttons-reload').click()
            });
        })
        const autoRefresh = ()=>{
            $('.buttons-reload').click()
        }
        setInterval(function() {
            autoRefresh()
        }, 10000);
        function copyToClipboard(text) {
            var sampleTextarea = document.createElement("textarea");
            document.body.appendChild(sampleTextarea);
            sampleTextarea.value = text; //save main text in it
            sampleTextarea.select(); //select textarea contenrs
            document.execCommand("copy");
            document.body.removeChild(sampleTextarea);
        }
        $('.column-key-phone_number').on('click', function(e) {
            copyToClipboard($('.column-key-phone_number').text())
        })
    </script>
@endsection
