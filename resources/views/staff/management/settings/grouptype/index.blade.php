@extends('_layouts/default')

@section('content')
    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h2>
                    <a href="" class="btn btn-link">
                        <i class="icon-arrow-left8"></i>
                    </a>
                    Danh sách kiểu nhóm
                </h2>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- Page container -->
    <div class="page-container">
        <!-- Page content -->
        <div class="page-content">
            <!-- Main content -->
            <div class="content-wrapper">
                <div class="panel panel-flat">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Icon</th>
                                            <th>Type</th>
                                            <th>Name</th>
                                            <th>Categories Refer</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($types as $type)
                                            <tr>
                                                <td>
                                                    <img src="{{ get_image_url($type->icon, 'thumb_small') }}" alt="Image" class="img-rounded" width="64" height="64">
                                                </td>
                                                <td>{{ $type->type }}</td>
                                                <td>{{ $type->name }}</td>
                                                <td>{{ is_array($type->categories_refer) ? implode(', ', $type->categories_refer) : '' }}</td>
                                                <td>
                                                    <a href="{{ route('Staff::Management::settings@grouptype.edit', $type->id) }}">
                                                        <button type="submit">Edit</button>
                                                    </a>
                                                    <form action="{{ route('Staff::Management::settings@grouptype.delete', $type->id) }}" method="post" style="display: inline-block">
                                                        {!! method_field('DELETE') !!}
                                                        {{ csrf_field() }}
                                                        <input type="submit" value="Delete">
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
            </div>
            <!-- /main content -->
        </div>
        <!-- /page content -->
    </div>
    <!-- /page container -->
@endsection