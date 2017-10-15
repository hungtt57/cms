@extends('admin.base')


@section('content')
<!-- Main Content -->
<section id="main-content" class="no-transition">
    <section class="wrapper">

        <div class="row">
            <div class="col-md-8">
                <section class="panel">
                    <!-- Panel Header -->
                    <header class="panel-heading">
                        <a href="{{url('admin/tournaments')}}">Tournaments</a>&nbsp;/&nbsp;Arrange Tournaments
                    </header>
                    <!-- End of Panel Header -->
                    <!-- Panel body -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="sortable">
                                    @foreach($tournaments as $tournament)
                                    <li value="{{$tournament->id}}">{{$tournament->name}}</li>
                                   @endforeach
                                </ul>
                            </div>
                        </div>

                        <br>
                        <br>
                        <button type="button" class="btn btn-primary" onclick="set_order()" href="#saveModal" data-toggle="modal">Save Changes</button>
                        <button type="reset" class="btn btn-default" onclick="window.open('{{url('admin/tournaments')}}','_self')">Cancel</button>
                    </div>
                    <!-- End of Panel body -->
                </section>
            </div>
        </div>


    </section>
</section>
<!-- End of Main Content -->

<div class="modal fade" id="saveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Save</h4>
            </div>
            <div class="modal-body">
                Confirm to save arranged categories?
            </div>
            <div class="modal-footer">
                <form name="cate_order_form" action="{{url('admin/reorder_tournaments')}}" method="POST" >
                    {{ csrf_field() }}
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <input name="order" id="id_order" type="hidden" value="">
                    <input type='submit' value='Confirm' class="btn btn-warning">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{url('tournament-admin/plugins/jquery-sortable/source/js/jquery-sortable.js')}}"></script>

<script type="text/javascript">
    $(function () {
        $("ul.sortable").sortable({
            group: 'sortable'
        });
    });

    function set_order(){
        var order = $("ul.sortable").find('li').map(function(){
            return this.value;
        }).get().join();
        console.log(order);
        $("#id_order").val(order);
    }

</script>
@endsection